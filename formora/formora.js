(function () {
	'use strict';

	var aceRefreshTimer = null;
	var chartRefreshTimer = null;
	var takeposUrlRefreshTimer = null;
	var topMenuScrollTimer = null;

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

		if (typeof window.Chart.getChart === 'function') {
			document.querySelectorAll('canvas').forEach(function (canvas) {
				var chart = window.Chart.getChart(canvas);
				if (chart) {
					callback(chart);
				}
			});
			return;
		}

		if (window.Chart.instances) {
			Object.keys(window.Chart.instances).forEach(function (key) {
				var chart = window.Chart.instances[key];
				if (chart) {
					callback(chart);
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

		if (window.Chart.defaults) {
			if (window.Chart.defaults.elements && window.Chart.defaults.elements.arc) {
				window.Chart.defaults.elements.arc.borderColor = colors.graphStroke;
				window.Chart.defaults.elements.arc.hoverBorderColor = colors.graphStroke;
				window.Chart.defaults.elements.arc.borderWidth = 2;
			}

			if (window.Chart.defaults.datasets) {
				['doughnut', 'pie', 'polarArea'].forEach(function (type) {
					window.Chart.defaults.datasets[type] = window.Chart.defaults.datasets[type] || {};
					window.Chart.defaults.datasets[type].borderColor = colors.graphStroke;
					window.Chart.defaults.datasets[type].hoverBorderColor = colors.graphStroke;
					window.Chart.defaults.datasets[type].borderWidth = 2;
				});
			}

			if ('color' in window.Chart.defaults) {
				window.Chart.defaults.color = colors.text;
			}

			if (window.Chart.defaults.global) {
				window.Chart.defaults.global.defaultFontColor = colors.text;
			}
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
				applyPieConfig(args[1], currentColors());
				var chart = Reflect.construct(target, args);
				applyChartInstance(chart);
				return chart;
			},
			apply: function (target, thisArg, args) {
				applyPieConfig(args[1], currentColors());
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

		if (chart.options) {
			chart.options.elements = chart.options.elements || {};
			chart.options.elements.arc = chart.options.elements.arc || {};
			var arcBorderColor = chart.options.elements.arc.borderColor;
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

		if (chart.data && chart.data.datasets) {
			chart.data.datasets.forEach(function (dataset) {
				promoteTransparentFills(dataset.backgroundColor, dataset.borderColor || arcBorderColor);
				dataset.borderColor = colors.graphStroke;
				dataset.hoverBorderColor = colors.graphStroke;
				dataset.borderWidth = 2;
			});
		}

		if (typeof chart.update === 'function') {
			chart.update();
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

	function scheduleChartTheme() {
		clearTimeout(chartRefreshTimer);
		chartRefreshTimer = setTimeout(applyChartTheme, 0);
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

	function isTakeposPage() {
		return !!(document.body && document.body.classList.contains('bodytakepos'));
	}

	function inheritedThemeParams() {
		var source = new URLSearchParams(window.location.search);
		var colors = currentColors();
		var params = {
			theme: source.get('theme') || 'formora'
		};

		if (source.has('THEME_DARKMODEENABLED')) {
			params.THEME_DARKMODEENABLED = source.get('THEME_DARKMODEENABLED');
		} else if (colors.dark) {
			params.THEME_DARKMODEENABLED = '2';
		}

		['dol_resetcache', 'dol_nocache'].forEach(function (name) {
			if (source.has(name)) {
				params[name] = source.get(name);
			}
		});

		return params;
	}

	function shouldPatchTakeposUrl(url) {
		return url.origin === window.location.origin && (
			url.pathname.indexOf('/takepos/') !== -1 ||
			url.pathname.indexOf('/societe/') !== -1 ||
			url.pathname.indexOf('/contact/') !== -1 ||
			url.pathname.indexOf('/compta/facture/') !== -1 ||
			url.pathname.indexOf('/compta/cashcontrol/') !== -1
		);
	}

	function appendTakeposThemeParams(href) {
		if (!href || typeof href !== 'string' || href.charAt(0) === '#') {
			return href;
		}

		try {
			var url = new URL(href, window.location.href);
			if (!shouldPatchTakeposUrl(url)) {
				return href;
			}

			var params = inheritedThemeParams();
			Object.keys(params).forEach(function (name) {
				if (!url.searchParams.has(name)) {
					url.searchParams.set(name, params[name]);
				}
			});

			return url.href;
		} catch (e) {
			return href;
		}
	}

	function patchTakeposColorbox() {
		if (!isTakeposPage() || !window.jQuery || !window.jQuery.colorbox || window.jQuery.colorbox.__formoraThemePatch) {
			return;
		}

		var $ = window.jQuery;
		var originalColorbox = $.colorbox;
		var patchedColorbox = function (settings) {
			if (settings && typeof settings === 'object' && settings.iframe && settings.href) {
				settings.href = appendTakeposThemeParams(settings.href);
			}
			return originalColorbox.apply(this, arguments);
		};

		Object.keys(originalColorbox).forEach(function (key) {
			patchedColorbox[key] = originalColorbox[key];
		});
		patchedColorbox.__formoraThemePatch = true;
		patchedColorbox.__formoraOriginal = originalColorbox;

		$.colorbox = patchedColorbox;
		if ($.fn && $.fn.colorbox === originalColorbox) {
			$.fn.colorbox = patchedColorbox;
		}
	}

	function refreshTakeposIframeUrls() {
		if (!isTakeposPage()) {
			return;
		}

		patchTakeposColorbox();
		document.querySelectorAll('iframe.cboxIframe, #cboxLoadedContent iframe').forEach(function (iframe) {
			var src = iframe.getAttribute('src');
			var patchedSrc = appendTakeposThemeParams(src);
			if (patchedSrc && patchedSrc !== src) {
				iframe.setAttribute('src', patchedSrc);
			}
		});
	}

	function scheduleTakeposThemeUrls() {
		clearTimeout(takeposUrlRefreshTimer);
		takeposUrlRefreshTimer = setTimeout(refreshTakeposIframeUrls, 0);
	}

	function clearTopMenuScrollState() {
		if (!document.body) {
			return;
		}

		document.body.classList.remove(
			'formora-tmenu-is-scrollable',
			'formora-tmenu-can-scroll-left',
			'formora-tmenu-can-scroll-right'
		);
	}

	function isTopMenuMobileLayout() {
		return !window.matchMedia || window.matchMedia('(max-width: 1180px)').matches;
	}

	function updateTopMenuScrollState() {
		var body = document.body;
		var menu = document.getElementById('tmenu_tooltip');

		if (!body || !menu || body.classList.contains('bodytakepos') || body.classList.contains('bodylogin') || !isTopMenuMobileLayout()) {
			clearTopMenuScrollState();
			return;
		}

		if (!menu.__formoraTmenuScrollBound) {
			menu.addEventListener('scroll', scheduleTopMenuScrollState, { passive: true });
			menu.__formoraTmenuScrollBound = true;
		}

		var maxScroll = Math.max(0, menu.scrollWidth - menu.clientWidth);
		var isScrollable = maxScroll > 1;
		body.classList.toggle('formora-tmenu-is-scrollable', isScrollable);
		body.classList.toggle('formora-tmenu-can-scroll-left', isScrollable && menu.scrollLeft > 1);
		body.classList.toggle('formora-tmenu-can-scroll-right', isScrollable && menu.scrollLeft < maxScroll - 1);
	}

	function scheduleTopMenuScrollState() {
		clearTimeout(topMenuScrollTimer);
		topMenuScrollTimer = setTimeout(updateTopMenuScrollState, 0);
	}

	applyChartTheme();
	applyAceTheme();
	refreshTakeposIframeUrls();
	updateTopMenuScrollState();

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', scheduleAceTheme);
		document.addEventListener('DOMContentLoaded', scheduleChartTheme);
		document.addEventListener('DOMContentLoaded', scheduleTakeposThemeUrls);
		document.addEventListener('DOMContentLoaded', scheduleTopMenuScrollState);
	} else {
		scheduleAceTheme();
		scheduleChartTheme();
		scheduleTakeposThemeUrls();
		scheduleTopMenuScrollState();
	}

	window.addEventListener('load', scheduleAceTheme);
	window.addEventListener('load', scheduleChartTheme);
	window.addEventListener('load', scheduleTakeposThemeUrls);
	window.addEventListener('load', scheduleTopMenuScrollState);
	window.addEventListener('resize', scheduleTopMenuScrollState);
	window.addEventListener('orientationchange', scheduleTopMenuScrollState);
	setTimeout(scheduleAceTheme, 300);
	setTimeout(scheduleAceTheme, 1000);
	setTimeout(scheduleTakeposThemeUrls, 300);
	setTimeout(scheduleTopMenuScrollState, 300);
	setTimeout(scheduleTopMenuScrollState, 1000);

	if (window.MutationObserver) {
		new MutationObserver(function () {
			scheduleAceTheme();
			scheduleChartTheme();
			scheduleTakeposThemeUrls();
			scheduleTopMenuScrollState();
		}).observe(document.documentElement, {
			childList: true,
			subtree: true
		});
	}
}());
