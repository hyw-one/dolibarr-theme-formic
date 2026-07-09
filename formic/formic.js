(function () {
	'use strict';

	var aceRefreshTimer = null;
	var chartRefreshTimer = null;
	var chartDefaultsState = null;
	var chartInstanceState = typeof WeakMap === 'function' ? new WeakMap() : null;
	var aceThemeState = typeof WeakMap === 'function' ? new WeakMap() : null;

	function parseRgb(color) {
		var value = String(color || '').trim();
		var match = value.match(/rgba?\((\d+),\s*(\d+),\s*(\d+)/i);
		if (match) {
			return [parseInt(match[1], 10), parseInt(match[2], 10), parseInt(match[3], 10)];
		}

		match = value.match(/^#([0-9a-f]{3}|[0-9a-f]{6})$/i);
		if (!match) {
			return null;
		}

		var hex = match[1];
		if (hex.length === 3) {
			hex = hex.split('').map(function (part) {
				return part + part;
			}).join('');
		}

		return [
			parseInt(hex.slice(0, 2), 16),
			parseInt(hex.slice(2, 4), 16),
			parseInt(hex.slice(4, 6), 16)
		];
	}

	function luminance(rgb) {
		if (!rgb) {
			return 255;
		}
		return (rgb[0] * 0.299) + (rgb[1] * 0.587) + (rgb[2] * 0.114);
	}

	function cssVar(name, fallback) {
		var value = getComputedStyle(document.documentElement).getPropertyValue(name).trim();
		return value || fallback;
	}

	function isTransparentColor(color) {
		var value = String(color || '').replace(/\s+/g, '').toLowerCase();
		return value === 'transparent' || value === 'rgba(0,0,0,0)' || value === 'rgba(0,0,0,.0)' || value === 'rgba(0,0,0,0.0)';
	}

	function promoteTransparentFills(backgroundColor, borderColor) {
		if (!Array.isArray(backgroundColor) || !Array.isArray(borderColor)) {
			return;
		}

		backgroundColor.forEach(function (color, index) {
			var replacement = borderColor[index];
			if (isTransparentColor(color) && replacement && !isTransparentColor(replacement)) {
				backgroundColor[index] = replacement;
			}
		});
	}

	function cloneThemeValue(value) {
		if (Array.isArray(value)) {
			return value.map(cloneThemeValue);
		}
		return value;
	}

	function rememberProp(object, key) {
		return {
			exists: !!object && Object.prototype.hasOwnProperty.call(object, key),
			value: object ? cloneThemeValue(object[key]) : undefined
		};
	}

	function restoreProp(object, key, state) {
		if (!object || !state) {
			return;
		}
		if (state.exists) {
			object[key] = cloneThemeValue(state.value);
		} else {
			delete object[key];
		}
	}

	function requestChartUpdate(chart) {
		if (!chart || typeof chart.update !== 'function') {
			return;
		}
		try {
			chart.update('none');
		} catch (e) {
			try {
				chart.update();
			} catch (ignore) {
				// Retried on the next scheduled pass.
			}
		}
	}

	function currentColors() {
		var pageBackground = cssVar('--colorbackbody', '#1d1e20');
		var bodyBackground = document.body ? getComputedStyle(document.body).backgroundColor : pageBackground;
		var graphBackground = cssVar('--dolgraphbg', pageBackground);
		var textColor = cssVar('--colortext', '#dce0e5');
		var dark = luminance(parseRgb(bodyBackground)) < 128 || luminance(parseRgb(pageBackground)) < 128;

		return {
			dark: dark,
			graphStroke: graphBackground,
			text: textColor
		};
	}

	function eachChart(callback) {
		if (!window.Chart) {
			return;
		}

		// Chart.js's own scriptable-options resolver can throw (recursively,
		// "Maximum call stack size exceeded") if a chart is still mid-way
		// through its own initial-render animation when we touch its options;
		// isolating each chart keeps one bad instance from aborting the rest,
		// and a later retry (see scheduleChartTheme) picks it up once that
		// chart's animation has settled.
		function safeCallback(chart) {
			try {
				callback(chart);
			} catch (e) {
				// Swallow and retry on the next scheduled pass.
			}
		}

		if (typeof window.Chart.getChart === 'function') {
			document.querySelectorAll('canvas').forEach(function (canvas) {
				var chart = window.Chart.getChart(canvas);
				if (chart) {
					safeCallback(chart);
				}
			});
			return;
		}

		if (window.Chart.instances) {
			Object.keys(window.Chart.instances).forEach(function (key) {
				var chart = window.Chart.instances[key];
				if (chart) {
					safeCallback(chart);
				}
			});
		}
	}

	function isPieLike(chart) {
		var type = chart && chart.config && chart.config.type;
		return type === 'doughnut' || type === 'pie' || type === 'polarArea';
	}

	function captureChartDefaults() {
		if (chartDefaultsState || !window.Chart || !window.Chart.defaults) {
			return;
		}

		var defaults = window.Chart.defaults;
		chartDefaultsState = {
			color: rememberProp(defaults, 'color'),
			globalDefaultFontColor: defaults.global ? rememberProp(defaults.global, 'defaultFontColor') : null,
			arc: null,
			datasets: {}
		};

		if (defaults.elements && defaults.elements.arc) {
			chartDefaultsState.arc = {
				borderColor: rememberProp(defaults.elements.arc, 'borderColor'),
				hoverBorderColor: rememberProp(defaults.elements.arc, 'hoverBorderColor'),
				borderWidth: rememberProp(defaults.elements.arc, 'borderWidth')
			};
		}

		if (defaults.datasets) {
			['doughnut', 'pie', 'polarArea'].forEach(function (type) {
				var datasetDefaults = defaults.datasets[type];
				chartDefaultsState.datasets[type] = {
					exists: Object.prototype.hasOwnProperty.call(defaults.datasets, type),
					borderColor: datasetDefaults ? rememberProp(datasetDefaults, 'borderColor') : null,
					hoverBorderColor: datasetDefaults ? rememberProp(datasetDefaults, 'hoverBorderColor') : null,
					borderWidth: datasetDefaults ? rememberProp(datasetDefaults, 'borderWidth') : null
				};
			});
		}
	}

	function restoreChartDefaults() {
		if (!chartDefaultsState || !window.Chart || !window.Chart.defaults) {
			return;
		}

		var defaults = window.Chart.defaults;
		restoreProp(defaults, 'color', chartDefaultsState.color);
		if (defaults.global && chartDefaultsState.globalDefaultFontColor) {
			restoreProp(defaults.global, 'defaultFontColor', chartDefaultsState.globalDefaultFontColor);
		}

		if (defaults.elements && defaults.elements.arc && chartDefaultsState.arc) {
			restoreProp(defaults.elements.arc, 'borderColor', chartDefaultsState.arc.borderColor);
			restoreProp(defaults.elements.arc, 'hoverBorderColor', chartDefaultsState.arc.hoverBorderColor);
			restoreProp(defaults.elements.arc, 'borderWidth', chartDefaultsState.arc.borderWidth);
		}

		if (defaults.datasets) {
			['doughnut', 'pie', 'polarArea'].forEach(function (type) {
				var state = chartDefaultsState.datasets[type];
				if (!state) {
					return;
				}
				if (!state.exists) {
					delete defaults.datasets[type];
					return;
				}
				defaults.datasets[type] = defaults.datasets[type] || {};
				restoreProp(defaults.datasets[type], 'borderColor', state.borderColor);
				restoreProp(defaults.datasets[type], 'hoverBorderColor', state.hoverBorderColor);
				restoreProp(defaults.datasets[type], 'borderWidth', state.borderWidth);
			});
		}

		chartDefaultsState = null;
	}

	function captureChartInstance(chart) {
		if (!chartInstanceState || !chart || chartInstanceState.has(chart)) {
			return;
		}

		var state = {
			options: null,
			datasets: []
		};
		var options = chart.options;
		if (options) {
			var elements = options.elements;
			var arc = elements && elements.arc;
			state.options = {
				hadElements: Object.prototype.hasOwnProperty.call(options, 'elements'),
				hadArc: !!elements && Object.prototype.hasOwnProperty.call(elements, 'arc'),
				arc: arc ? {
					backgroundColor: rememberProp(arc, 'backgroundColor'),
					borderColor: rememberProp(arc, 'borderColor'),
					hoverBorderColor: rememberProp(arc, 'hoverBorderColor'),
					borderWidth: rememberProp(arc, 'borderWidth')
				} : null,
				pluginLegendLabelsColor: options.plugins && options.plugins.legend && options.plugins.legend.labels ? rememberProp(options.plugins.legend.labels, 'color') : null,
				legacyLegendLabelsFontColor: options.legend && options.legend.labels ? rememberProp(options.legend.labels, 'fontColor') : null
			};
		}

		if (chart.data && Array.isArray(chart.data.datasets)) {
			state.datasets = chart.data.datasets.map(function (dataset) {
				return {
					backgroundColor: rememberProp(dataset, 'backgroundColor'),
					borderColor: rememberProp(dataset, 'borderColor'),
					hoverBorderColor: rememberProp(dataset, 'hoverBorderColor'),
					borderWidth: rememberProp(dataset, 'borderWidth')
				};
			});
		}

		chartInstanceState.set(chart, state);
	}

	function restoreChartInstance(chart) {
		if (!chartInstanceState || !chart || !chartInstanceState.has(chart)) {
			return;
		}

		var state = chartInstanceState.get(chart);
		var options = chart.options;
		if (options && state.options) {
			if (options.elements && options.elements.arc && state.options.arc) {
				restoreProp(options.elements.arc, 'backgroundColor', state.options.arc.backgroundColor);
				restoreProp(options.elements.arc, 'borderColor', state.options.arc.borderColor);
				restoreProp(options.elements.arc, 'hoverBorderColor', state.options.arc.hoverBorderColor);
				restoreProp(options.elements.arc, 'borderWidth', state.options.arc.borderWidth);
			}
			if (options.elements && !state.options.hadArc) {
				delete options.elements.arc;
			}
			if (!state.options.hadElements) {
				delete options.elements;
			}
			if (options.plugins && options.plugins.legend && options.plugins.legend.labels && state.options.pluginLegendLabelsColor) {
				restoreProp(options.plugins.legend.labels, 'color', state.options.pluginLegendLabelsColor);
			}
			if (options.legend && options.legend.labels && state.options.legacyLegendLabelsFontColor) {
				restoreProp(options.legend.labels, 'fontColor', state.options.legacyLegendLabelsFontColor);
			}
		}

		if (chart.data && Array.isArray(chart.data.datasets)) {
			chart.data.datasets.forEach(function (dataset, index) {
				var datasetState = state.datasets[index];
				if (!datasetState) {
					return;
				}
				restoreProp(dataset, 'backgroundColor', datasetState.backgroundColor);
				restoreProp(dataset, 'borderColor', datasetState.borderColor);
				restoreProp(dataset, 'hoverBorderColor', datasetState.hoverBorderColor);
				restoreProp(dataset, 'borderWidth', datasetState.borderWidth);
			});
		}

		chartInstanceState.delete(chart);
		requestChartUpdate(chart);
	}

	function configureChartDefaults(colors) {
		if (!window.Chart || !colors.dark) {
			return;
		}

		if (!window.Chart.defaults) {
			return;
		}

		captureChartDefaults();

		// Each block below touches a separate part of Chart.js's defaults and
		// can independently hit the same recursive resolver bug eachChart()
		// guards against (see its comment). Isolate them so one failing
		// assignment doesn't skip the others.
		try {
			if (window.Chart.defaults.elements && window.Chart.defaults.elements.arc) {
				window.Chart.defaults.elements.arc.borderColor = colors.graphStroke;
				window.Chart.defaults.elements.arc.hoverBorderColor = colors.graphStroke;
				window.Chart.defaults.elements.arc.borderWidth = 2;
			}
		} catch (e) {
			// Retried on the next scheduled pass.
		}

		try {
			if (window.Chart.defaults.datasets) {
				['doughnut', 'pie', 'polarArea'].forEach(function (type) {
					window.Chart.defaults.datasets[type] = window.Chart.defaults.datasets[type] || {};
					window.Chart.defaults.datasets[type].borderColor = colors.graphStroke;
					window.Chart.defaults.datasets[type].hoverBorderColor = colors.graphStroke;
					window.Chart.defaults.datasets[type].borderWidth = 2;
				});
			}
		} catch (e) {
			// Retried on the next scheduled pass.
		}

		try {
			if ('color' in window.Chart.defaults) {
				window.Chart.defaults.color = colors.text;
			}

			if (window.Chart.defaults.global) {
				window.Chart.defaults.global.defaultFontColor = colors.text;
			}
		} catch (e) {
			// Retried on the next scheduled pass.
		}
	}

	function patchChartConstructor() {
		var colors = currentColors();
		configureChartDefaults(colors);

		if (!window.Chart || window.Chart.__formicDarkPatch) {
			return;
		}

		var OriginalChart = window.Chart;
		var PatchedChart = new Proxy(OriginalChart, {
			construct: function (target, args) {
				var chart = Reflect.construct(target, args);
				applyChartInstance(chart);
				return chart;
			},
			apply: function (target, thisArg, args) {
				var chart = Reflect.apply(target, thisArg, args);
				applyChartInstance(chart);
				return chart;
			}
		});

		try {
			PatchedChart.__formicDarkPatch = true;
			PatchedChart.__formicOriginal = OriginalChart;
		} catch (e) {
			// Some Chart.js builds expose read-only statics; the proxy still works.
		}

		window.Chart = PatchedChart;
	}

	function applyChartInstance(chart) {
		var colors = currentColors();
		if (!colors.dark || !isPieLike(chart)) {
			return;
		}

		captureChartInstance(chart);

		// See the comment on the chart.update() call below: any of these
		// property writes can hit the same Chart.js recursive-resolver bug
		// if the chart is still animating in, so each independent chunk of
		// work is isolated rather than letting one failure skip the rest.
		var arcBorderColor;
		try {
			if (chart.options) {
				chart.options.elements = chart.options.elements || {};
				chart.options.elements.arc = chart.options.elements.arc || {};
				arcBorderColor = chart.options.elements.arc.borderColor;
				promoteTransparentFills(chart.options.elements.arc.backgroundColor, arcBorderColor);
				chart.options.elements.arc.borderColor = colors.graphStroke;
				chart.options.elements.arc.hoverBorderColor = colors.graphStroke;
				chart.options.elements.arc.borderWidth = 2;

				if (chart.options.plugins && chart.options.plugins.legend && chart.options.plugins.legend.labels) {
					chart.options.plugins.legend.labels.color = colors.text;
				}

				if (chart.options.legend && chart.options.legend.labels) {
					chart.options.legend.labels.fontColor = colors.text;
				}
			}
		} catch (e) {
			// Retried on the next scheduled pass.
		}

		try {
			if (chart.data && chart.data.datasets) {
				chart.data.datasets.forEach(function (dataset) {
					promoteTransparentFills(dataset.backgroundColor, dataset.borderColor || arcBorderColor);
					dataset.borderColor = colors.graphStroke;
					dataset.hoverBorderColor = colors.graphStroke;
					dataset.borderWidth = 2;
				});
			}
		} catch (e) {
			// Retried on the next scheduled pass.
		}

		// Chart.js resolves scriptable/indexable options lazily, and forcing an
		// update while the chart's own initial-render animation is still resolving
		// the same option tree can recurse into "Maximum call stack size exceeded".
		// The color values above are already written either way; a caught failure
		// just skips this one repaint and the scheduled retry picks it up later.
		requestChartUpdate(chart);
	}

	function applyChartTheme() {
		var colors = currentColors();
		patchChartConstructor();
		configureChartDefaults(colors);

		if (!colors.dark) {
			restoreChartDefaults();
			eachChart(function (chart) {
				restoreChartInstance(chart);
			});
			return;
		}

		eachChart(function (chart) {
			applyChartInstance(chart);
		});
	}

	function safeApplyChartTheme() {
		try {
			applyChartTheme();
		} catch (e) {
			// Chart.js defaults can be proxied by older Dolibarr builds; do not block the rest of the theme script.
		}
	}

	function scheduleChartTheme() {
		clearTimeout(chartRefreshTimer);
		chartRefreshTimer = setTimeout(safeApplyChartTheme, 0);
	}

	function applyAceTheme() {
		var colors = currentColors();
		var editors = document.querySelectorAll('.ace_editor');
		if (!editors.length) {
			return;
		}

		if (!window.ace || typeof window.ace.edit !== 'function') {
			if (!colors.dark) {
				editors.forEach(function (element) {
					element.classList.remove('formic-ace-dark');
				});
			}
			return;
		}

		if (!colors.dark) {
			editors.forEach(function (element) {
				if (!element.id) {
					element.classList.remove('formic-ace-dark');
					return;
				}

				try {
					var editor = window.ace.edit(element.id);
					if (aceThemeState && aceThemeState.has(element) && editor && typeof editor.setTheme === 'function') {
						editor.setTheme(aceThemeState.get(element));
						aceThemeState.delete(element);
					}
					element.classList.remove('formic-ace-dark');
				} catch (e) {
					// ACE may already be detached; removing the helper class is still safe.
					element.classList.remove('formic-ace-dark');
				}
			});
			return;
		}

		editors.forEach(function (element) {
			if (!element.id) {
				return;
			}

			try {
				var editor = window.ace.edit(element.id);
				if (aceThemeState && !aceThemeState.has(element) && editor && editor.getTheme) {
					aceThemeState.set(element, editor.getTheme());
				}
				if (editor && typeof editor.setTheme === 'function' && editor.getTheme && editor.getTheme() !== 'ace/theme/one_dark') {
					editor.setTheme('ace/theme/one_dark');
				}
				element.classList.add('formic-ace-dark');
			} catch (e) {
				// ACE may still be initializing; the scheduled retry will pick it up.
			}
		});
	}

	function scheduleAceTheme() {
		clearTimeout(aceRefreshTimer);
		aceRefreshTimer = setTimeout(applyAceTheme, 0);
	}

	function scheduleThemeRefresh() {
		scheduleAceTheme();
		scheduleChartTheme();
	}

	function centerActiveTopMenu() {
		var container = document.getElementById('tmenu_tooltip');
		var active = container && container.querySelector('li.tmenusel');
		if (!container || !active || container.scrollWidth <= container.clientWidth) {
			return;
		}
		active.scrollIntoView({ block: 'nearest', inline: 'center' });
	}

	safeApplyChartTheme();
	applyAceTheme();

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', scheduleThemeRefresh);
		document.addEventListener('DOMContentLoaded', centerActiveTopMenu);
	} else {
		scheduleThemeRefresh();
		centerActiveTopMenu();
	}

	window.addEventListener('load', scheduleThemeRefresh);
	setTimeout(scheduleAceTheme, 300);
	setTimeout(scheduleAceTheme, 1000);

	if (window.matchMedia) {
		try {
			var colorSchemeQuery = window.matchMedia('(prefers-color-scheme: dark)');
			var colorSchemeListener = function () {
				scheduleThemeRefresh();
			};
			if (typeof colorSchemeQuery.addEventListener === 'function') {
				colorSchemeQuery.addEventListener('change', colorSchemeListener);
			} else if (typeof colorSchemeQuery.addListener === 'function') {
				colorSchemeQuery.addListener(colorSchemeListener);
			}
		} catch (e) {
			// matchMedia can be disabled by embedded browsers.
		}
	}

	if (window.MutationObserver) {
		new MutationObserver(function () {
			if (document.querySelector('.ace_editor, canvas')) {
				scheduleThemeRefresh();
			}
		}).observe(document.documentElement, {
			childList: true,
			subtree: true
		});
	}
}());
