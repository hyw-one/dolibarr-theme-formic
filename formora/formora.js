(function () {
	'use strict';

	var aceRefreshTimer = null;
	var chartRefreshTimer = null;

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

	function configureChartDefaults(colors) {
		if (!window.Chart || !colors.dark) {
			return;
		}

		if (!window.Chart.defaults) {
			return;
		}

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

	function applyPieConfig(config, colors) {
		if (!config || !colors.dark) {
			return;
		}

		var type = config.type;
		if (type !== 'doughnut' && type !== 'pie' && type !== 'polarArea') {
			return;
		}

		config.options = config.options || {};
		config.options.elements = config.options.elements || {};
		config.options.elements.arc = config.options.elements.arc || {};
		var arcBorderColor = config.options.elements.arc.borderColor;
		promoteTransparentFills(config.options.elements.arc.backgroundColor, arcBorderColor);
		config.options.elements.arc.borderColor = colors.graphStroke;
		config.options.elements.arc.hoverBorderColor = colors.graphStroke;
		config.options.elements.arc.borderWidth = 2;

		config.options.plugins = config.options.plugins || {};
		config.options.plugins.legend = config.options.plugins.legend || {};
		config.options.plugins.legend.labels = config.options.plugins.legend.labels || {};
		config.options.plugins.legend.labels.color = colors.text;

		if (config.options.legend && config.options.legend.labels) {
			config.options.legend.labels.fontColor = colors.text;
		}

		if (config.data && config.data.datasets) {
			config.data.datasets.forEach(function (dataset) {
				promoteTransparentFills(dataset.backgroundColor, dataset.borderColor || arcBorderColor);
				dataset.borderColor = colors.graphStroke;
				dataset.hoverBorderColor = colors.graphStroke;
				dataset.borderWidth = 2;
			});
		}
	}

	function patchChartConstructor() {
		var colors = currentColors();
		configureChartDefaults(colors);

		if (!window.Chart || window.Chart.__formoraDarkPatch) {
			return;
		}

		var OriginalChart = window.Chart;
		var PatchedChart = new Proxy(OriginalChart, {
			construct: function (target, args) {
				// applyPieConfig only touches the plain pre-construction config
				// object here, so it shouldn't be able to hit Chart.js's own
				// live-instance resolver recursion, but the actual
				// construction must never be skipped because of a theming
				// failure -- guard it anyway so a chart still gets created
				// even if precoloring its config throws.
				try {
					applyPieConfig(args[1], currentColors());
				} catch (e) {
					// Retried via applyChartInstance() below / the next scheduled pass.
				}
				var chart = Reflect.construct(target, args);
				applyChartInstance(chart);
				return chart;
			},
			apply: function (target, thisArg, args) {
				try {
					applyPieConfig(args[1], currentColors());
				} catch (e) {
					// Retried via applyChartInstance() below / the next scheduled pass.
				}
				var chart = Reflect.apply(target, thisArg, args);
				applyChartInstance(chart);
				return chart;
			}
		});

		try {
			PatchedChart.__formoraDarkPatch = true;
			PatchedChart.__formoraOriginal = OriginalChart;
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

		if (typeof chart.update === 'function') {
			try {
				// This is the single most crash-prone line: Chart.js resolves
				// scriptable/indexable options lazily, and forcing an update
				// while the chart's own initial-render animation is still
				// resolving the same option tree can recurse into "Maximum
				// call stack size exceeded". The color values above are
				// already written to the chart's options either way, so a
				// caught failure here just skips this one forced repaint --
				// Chart.js's own next real redraw (or our own retry once the
				// animation has settled) still picks up the new colors.
				chart.update();
			} catch (e) {
				// Retried on the next scheduled pass.
			}
		}
	}

	function applyChartTheme() {
		var colors = currentColors();
		patchChartConstructor();
		configureChartDefaults(colors);

		if (!colors.dark) {
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
		if (!colors.dark || !editors.length || !window.ace || typeof window.ace.edit !== 'function') {
			return;
		}

		editors.forEach(function (element) {
			if (!element.id) {
				return;
			}

			try {
				var editor = window.ace.edit(element.id);
				if (editor && typeof editor.setTheme === 'function' && editor.getTheme && editor.getTheme() !== 'ace/theme/one_dark') {
					editor.setTheme('ace/theme/one_dark');
				}
				element.classList.add('formora-ace-dark');
			} catch (e) {
				// ACE may still be initializing; the scheduled retry will pick it up.
			}
		});
	}

	function scheduleAceTheme() {
		clearTimeout(aceRefreshTimer);
		aceRefreshTimer = setTimeout(applyAceTheme, 0);
	}

	safeApplyChartTheme();
	applyAceTheme();

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', scheduleAceTheme);
		document.addEventListener('DOMContentLoaded', scheduleChartTheme);
	} else {
		scheduleAceTheme();
		scheduleChartTheme();
	}

	window.addEventListener('load', scheduleAceTheme);
	window.addEventListener('load', scheduleChartTheme);
	setTimeout(scheduleAceTheme, 300);
	setTimeout(scheduleAceTheme, 1000);

	if (window.MutationObserver) {
		new MutationObserver(function () {
			scheduleAceTheme();
			scheduleChartTheme();
		}).observe(document.documentElement, {
			childList: true,
			subtree: true
		});
	}
}());
