<?php
/* Copyright (C) 2026       Codex
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 */

/**
 * \file       htdocs/theme/formic/formic_settings.inc.php
 * \brief      Helpers to map Dolibarr skin settings to Formic CSS variables.
 */

if (!function_exists('formic_color_hex')) {
	/**
	 * Normalize a Dolibarr color value to a CSS hex color.
	 *
	 * @param string|array<int,int>|null $value       Color value, either hex or "r,g,b"
	 * @param string                     $fallbackHex Fallback hex color
	 * @return string
	 */
	function formic_color_hex($value, $fallbackHex)
	{
		$fallbackHex = '#'.strtolower(ltrim($fallbackHex, '#'));
		if ($value === null || $value === '') {
			return $fallbackHex;
		}

		$array = colorStringToArray($value, array());
		if (count($array) < 3) {
			return $fallbackHex;
		}

		return '#'.strtolower(colorArrayToHex($array, ltrim($fallbackHex, '#')));
	}
}

if (!function_exists('formic_global_color_hex')) {
	/**
	 * Read an optional Formic-specific color constant.
	 *
	 * @param string $const       Constant name
	 * @param string $fallbackHex Fallback hex color
	 * @return string
	 */
	function formic_global_color_hex($const, $fallbackHex)
	{
		$value = getDolGlobalString($const);
		return formic_color_hex($value, $fallbackHex);
	}
}

if (!function_exists('formic_color_rgba')) {
	/**
	 * Convert a hex color to CSS rgba().
	 *
	 * @param string $hex   Hex color
	 * @param float  $alpha Alpha channel
	 * @return string
	 */
	function formic_color_rgba($hex, $alpha)
	{
		return colorHexToRgb($hex, $alpha);
	}
}

if (!function_exists('formic_color_lighten')) {
	/**
	 * Lighten a CSS hex color.
	 *
	 * @param string $hex     Hex color
	 * @param int    $percent Percent
	 * @return string
	 */
	function formic_color_lighten($hex, $percent)
	{
		return strtolower(colorLighten($hex, $percent));
	}
}

if (!function_exists('formic_color_darker')) {
	/**
	 * Darken a CSS hex color.
	 *
	 * @param string $hex     Hex color
	 * @param int    $percent Percent
	 * @return string
	 */
	function formic_color_darker($hex, $percent)
	{
		return strtolower(colorDarker($hex, $percent));
	}
}

if (!function_exists('formic_svg_color')) {
	/**
	 * Encode a color for inline SVG data URLs.
	 *
	 * @param string $hex Hex color
	 * @return string
	 */
	function formic_svg_color($hex)
	{
		return '%23'.ltrim(strtolower($hex), '#');
	}
}

if (!function_exists('formic_radius_scale')) {
	/**
	 * Derive Formic radius scale from Dolibarr table radius.
	 *
	 * @param int $radius Radius from system settings
	 * @return array{sm:int,base:int,lg:int,table:int}
	 */
	function formic_radius_scale($radius)
	{
		$radius = max(0, $radius);
		if ($radius === 0) {
			return array('sm' => 0, 'base' => 0, 'lg' => 0, 'table' => 0);
		}

		return array(
			'sm' => max(4, min($radius, 8)),
			'base' => max(6, min($radius + 2, 12)),
			'lg' => max(8, min($radius + 6, 16)),
			'table' => $radius,
		);
	}
}

if (!function_exists('formic_build_light_settings')) {
	/**
	 * Build Formic light-mode variable values from Dolibarr system settings.
	 *
	 * @param array<string,string|int> $input Input colors and radius
	 * @return array<string,string>
	 */
	function formic_build_light_settings($input)
	{
		$accent = formic_global_color_hex('THEME_FORMIC_ACCENT', formic_color_hex($input['textlink'], '#1677ff'));
		$action = formic_global_color_hex('THEME_FORMIC_ACTION', formic_color_hex($input['button'], $accent));
		$actionText = formic_global_color_hex('THEME_FORMIC_ACTION_TEXT', formic_color_hex($input['button_text'], '#ffffff'));
		$text = formic_global_color_hex('THEME_FORMIC_TEXT', formic_color_hex($input['text'], '#1f1f1f'));
		$titleText = formic_global_color_hex('THEME_FORMIC_TITLE_TEXT', formic_color_hex($input['title_text'], $text));
		$titleLink = formic_global_color_hex('THEME_FORMIC_TITLE_LINK', formic_color_hex($input['title_link'], $accent));
		$titleNoTab = formic_global_color_hex('THEME_FORMIC_PAGE_TITLE', formic_color_hex($input['title_notab'], $accent));
		$workspace = formic_global_color_hex('THEME_FORMIC_WORKSPACE', formic_color_hex($input['body'], '#ffffff'));
		$shell = formic_global_color_hex('THEME_FORMIC_SHELL', formic_color_hex($input['vmenu'], '#f5f5f5'));
		$topmenu = formic_global_color_hex('THEME_FORMIC_TOPMENU', formic_color_hex($input['hmenu'], '#ffffff'));
		$surface = formic_global_color_hex('THEME_FORMIC_SURFACE', $workspace);
		$surfaceAlt = formic_global_color_hex('THEME_FORMIC_SURFACE_ALT', formic_color_hex($input['title_bg'], '#fafafa'));
		$line = formic_global_color_hex('THEME_FORMIC_LINE', formic_color_hex($input['title_border'], '#d9d9d9'));
		$lineSoft = formic_global_color_hex('THEME_FORMIC_LINE_SOFT', formic_color_lighten($line, 10));
		$tabCard = formic_global_color_hex('THEME_FORMIC_TAB_CARD', formic_color_hex($input['tab_card'], $surface));
		$tabActive = formic_global_color_hex('THEME_FORMIC_TAB_ACTIVE', formic_color_hex($input['tab_active'], $surfaceAlt));
		$lineBreak = formic_global_color_hex('THEME_FORMIC_LINE_BREAK', formic_color_hex($input['line_break'], $lineSoft));
		$rowOdd = formic_global_color_hex('THEME_FORMIC_ROW_ODD', formic_color_hex($input['row_odd'], $surface));
		$rowEven = formic_global_color_hex('THEME_FORMIC_ROW_EVEN', formic_color_hex($input['row_even'], '#fafafa'));
		$themeHover = getDolGlobalString('THEME_FORMIC_ROW_HOVER');
		$themeChecked = getDolGlobalString('THEME_FORMIC_ROW_CHECKED');
		$hover = ($themeHover !== '') ? formic_color_hex($themeHover, '#fafafa') : (((string) $input['row_hover'] === '') ? 'transparent' : formic_color_hex($input['row_hover'], '#fafafa'));
		$checked = ($themeChecked !== '') ? formic_color_hex($themeChecked, '#e6f4ff') : (((string) $input['row_checked'] === '') ? $hover : formic_color_hex($input['row_checked'], '#e6f4ff'));
		$danger = formic_global_color_hex('THEME_FORMIC_DANGER', '#ff4d4f');
		$success = formic_global_color_hex('THEME_FORMIC_SUCCESS', '#52c41a');
		$warning = formic_global_color_hex('THEME_FORMIC_WARNING', '#faad14');
		$muted = formic_global_color_hex('THEME_FORMIC_MUTED', '#595959');
		$radius = formic_radius_scale((int) $input['radius']);

		return array(
			'accent' => $accent,
			'accent_hover' => formic_global_color_hex('THEME_FORMIC_ACCENT_HOVER', formic_color_darker($accent, 14)),
				'accent_soft' => formic_color_rgba($accent, 0.10),
				'accent_soft_hover' => formic_color_rgba($accent, 0.16),
				'action' => $action,
				'action_hover' => formic_global_color_hex('THEME_FORMIC_ACTION_HOVER', formic_color_lighten($action, 12)),
				'action_text' => $actionText,
			'avatar_ring' => formic_color_rgba($text, 0.56),
			'bg' => $shell,
			'checked' => $checked,
			'danger' => $danger,
			'danger_bg' => formic_global_color_hex('THEME_FORMIC_DANGER_BG', '#fff1f0'),
			'danger_hover' => formic_global_color_hex('THEME_FORMIC_DANGER_HOVER', formic_color_darker($danger, 12)),
			'danger_hover_bg' => formic_global_color_hex('THEME_FORMIC_DANGER_HOVER_BG', '#ffccc7'),
			'elevated' => formic_global_color_hex('THEME_FORMIC_ELEVATED', $surface),
			'focus_outline' => formic_global_color_hex('THEME_FORMIC_FOCUS_OUTLINE', formic_color_darker($accent, 14)),
			'focus_ring' => formic_color_rgba($accent, 0.18),
			'hover' => $hover,
			'icon_accent' => formic_global_color_hex('THEME_FORMIC_ICON_ACCENT', $accent),
			'icon_danger' => formic_global_color_hex('THEME_FORMIC_ICON_DANGER', $danger),
			'icon_date' => formic_global_color_hex('THEME_FORMIC_ICON_DATE', '#8c8c8c'),
			'icon_default' => formic_global_color_hex('THEME_FORMIC_ICON_DEFAULT', '#595959'),
			'icon_muted' => formic_global_color_hex('THEME_FORMIC_ICON_MUTED', '#bfbfbf'),
			'icon_success' => formic_global_color_hex('THEME_FORMIC_ICON_SUCCESS', $success),
			'icon_warning' => formic_global_color_hex('THEME_FORMIC_ICON_WARNING', $warning),
			'input_background' => formic_global_color_hex('THEME_FORMIC_INPUT_BACKGROUND', $surface),
			'input_background_disabled' => formic_global_color_hex('THEME_FORMIC_INPUT_BACKGROUND_DISABLED', '#f5f5f5'),
			'input_border' => getDolGlobalString('THEME_FORMIC_INPUT_BORDER', formic_color_rgba($line, 0.96)),
			'input_color_disabled' => formic_global_color_hex('THEME_FORMIC_INPUT_COLOR_DISABLED', '#bfbfbf'),
			'line' => $line,
			'line_break' => $lineBreak,
			'line_soft' => $lineSoft,
			'list_total' => formic_global_color_hex('THEME_FORMIC_LIST_TOTAL', '#8c8c8c'),
			'muted' => $muted,
			'neutral_bg' => formic_global_color_hex('THEME_FORMIC_NEUTRAL_BG', '#fafafa'),
			'neutral_hover_bg' => formic_global_color_hex('THEME_FORMIC_NEUTRAL_HOVER_BG', '#f0f0f0'),
			'radius_sm' => (string) $radius['sm'].'px',
			'radius' => (string) $radius['base'].'px',
			'radius_lg' => (string) $radius['lg'].'px',
			'radius_full' => $radius['base'] > 0 ? '9999px' : '0',
			'refused_bg' => formic_global_color_hex('THEME_FORMIC_REFUSED_BG', $shell),
			'refid_no_color' => formic_global_color_hex('THEME_FORMIC_REFID_NO_COLOR', '#8c8c8c'),
			'row_even' => $rowEven,
			'row_odd' => $rowOdd,
			'shadow_elevated' => getDolGlobalString('THEME_FORMIC_SHADOW_ELEVATED', '0 6px 16px 0 rgba(0, 0, 0, 0.08), 0 9px 28px 8px rgba(0, 0, 0, 0.05), 0 12px 48px 16px rgba(0, 0, 0, 0.06)'),
			'shadow_menu' => getDolGlobalString('THEME_FORMIC_SHADOW_MENU', '0 6px 16px 0 rgba(0, 0, 0, 0.08), 0 3px 6px -4px rgba(0, 0, 0, 0.12), 0 9px 28px 8px rgba(0, 0, 0, 0.05)'),
			'shadow_soft' => getDolGlobalString('THEME_FORMIC_SHADOW_SOFT', '0 1px 2px 0 rgba(0, 0, 0, 0.03), 0 1px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px 0 rgba(0, 0, 0, 0.02)'),
			'shell' => $shell,
			'status_success_bg' => $success,
			'status_success_border' => formic_color_rgba($success, 0.16),
			'status_success_text' => '#ffffff',
			'surface' => $surface,
			'surface_alt' => $surfaceAlt,
			'switch_knob' => '#ffffff',
			'tab_active' => $tabActive,
			'tab_card' => $tabCard,
			'table_header' => $surfaceAlt,
			'table_radius' => (string) $radius['table'].'px',
			'tableforfield' => formic_global_color_hex('THEME_FORMIC_TABLEFORFIELD', '#8c8c8c'),
			'text' => $text,
			'title_bg' => $surfaceAlt,
			'title_link' => $titleLink,
			'title_notab' => $titleNoTab,
			'title_text' => $titleText,
			'toast_error_bg' => '#fff1f0',
			'toast_error_border' => formic_color_rgba($danger, 0.32),
			'toast_error_text' => $danger,
			'toast_success_bg' => '#f6ffed',
			'toast_success_border' => formic_color_rgba($success, 0.28),
			'toast_success_text' => $success,
			'toast_warning_bg' => '#fffbe6',
			'toast_warning_border' => formic_color_rgba($warning, 0.32),
			'toast_warning_text' => formic_global_color_hex('THEME_FORMIC_WARNING_TEXT', '#d48806'),
			'topmenu' => $topmenu,
			'warning' => $warning,
			'workspace' => $workspace,
			'workspace_grey' => formic_global_color_hex('THEME_FORMIC_WORKSPACE_GREY', '#f0f0f0'),
		);
	}
}

if (!function_exists('formic_build_dark_settings')) {
	/**
	 * Build Formic dark-mode variable values.
	 *
		 * @param array<string,string> $light      Light settings
		 * @param array<string,bool>   $customized Dolibarr settings explicitly customized by admin/user
		 * @return array<string,string>
		 */
		function formic_build_dark_settings($light, $customized = array())
		{
			$isCustomized = function ($name) use ($customized) {
				return !empty($customized[$name]);
			};

			$accent = formic_global_color_hex('THEME_FORMIC_DARK_ACCENT', $isCustomized('THEME_ELDY_TEXTLINK') ? $light['accent'] : '#1668dc');
			$danger = formic_global_color_hex('THEME_FORMIC_DARK_DANGER', '#d32029');
			$success = formic_global_color_hex('THEME_FORMIC_DARK_SUCCESS', '#49aa19');
			$warning = formic_global_color_hex('THEME_FORMIC_DARK_WARNING', '#d89614');
			$text = formic_global_color_hex('THEME_FORMIC_DARK_TEXT', '#e6e6e6');
			$surface = formic_global_color_hex('THEME_FORMIC_DARK_SURFACE', $isCustomized('THEME_ELDY_BACKBODY') ? $light['workspace'] : '#141414');
			$surfaceAlt = formic_global_color_hex('THEME_FORMIC_DARK_SURFACE_ALT', $isCustomized('THEME_ELDY_BACKTITLE1') ? $light['surface_alt'] : '#1f1f1f');
			$shell = formic_global_color_hex('THEME_FORMIC_DARK_SHELL', $isCustomized('THEME_ELDY_VERMENU_BACK1') ? $light['shell'] : '#1f1f1f');
			$line = formic_global_color_hex('THEME_FORMIC_DARK_LINE', '#424242');
			$lineSoft = formic_global_color_hex('THEME_FORMIC_DARK_LINE_SOFT', '#303030');
			$action = formic_global_color_hex('THEME_FORMIC_DARK_ACTION', $isCustomized('THEME_ELDY_BTNACTION') ? $light['action'] : $light['action']);
			$actionText = formic_global_color_hex('THEME_FORMIC_DARK_ACTION_TEXT', $isCustomized('THEME_ELDY_TEXTBTNACTION') ? $light['action_text'] : $light['action_text']);
			$rowOdd = ($isCustomized('THEME_ELDY_LINEIMPAIR1') || $isCustomized('THEME_ELDY_LINEIMPAIR2')) ? $light['row_odd'] : $surface;
			$rowEven = ($isCustomized('THEME_ELDY_LINEPAIR1') || $isCustomized('THEME_ELDY_LINEPAIR2')) ? $light['row_even'] : $surface;
			$titleLink = formic_global_color_hex('THEME_FORMIC_DARK_TITLE_LINK', $isCustomized('THEME_ELDY_TEXTTITLELINK') ? $light['title_link'] : '#4096ff');
			$titleNoTab = formic_global_color_hex('THEME_FORMIC_DARK_PAGE_TITLE', $isCustomized('THEME_ELDY_TEXTTITLENOTAB') ? $light['title_notab'] : '#4096ff');
			$titleText = $isCustomized('THEME_ELDY_TEXTTITLE') ? $light['title_text'] : $text;
			$topmenu = formic_global_color_hex('THEME_FORMIC_DARK_TOPMENU', $isCustomized('THEME_ELDY_TOPMENU_BACK1') ? $light['topmenu'] : $surface);

			return array_merge($light, array(
				'accent' => $accent,
				'accent_hover' => formic_global_color_hex('THEME_FORMIC_DARK_ACCENT_HOVER', '#4096ff'),
				'accent_soft' => formic_color_rgba($accent, 0.18),
				'accent_soft_hover' => formic_color_rgba($accent, 0.28),
				'action' => $action,
				'action_hover' => formic_global_color_hex('THEME_FORMIC_DARK_ACTION_HOVER', formic_color_lighten($action, 8)),
				'action_text' => $actionText,
			'avatar_ring' => formic_color_rgba($text, 0.42),
			'bg' => $shell,
				'checked' => formic_global_color_hex('THEME_FORMIC_DARK_ROW_CHECKED', $isCustomized('THEME_ELDY_USE_CHECKED') ? $light['checked'] : '#111a2c'),
			'danger' => $danger,
			'danger_bg' => formic_global_color_hex('THEME_FORMIC_DARK_DANGER_BG', '#2c1618'),
			'danger_hover' => formic_global_color_hex('THEME_FORMIC_DARK_DANGER_HOVER', formic_color_lighten($danger, 10)),
			'danger_hover_bg' => formic_global_color_hex('THEME_FORMIC_DARK_DANGER_HOVER_BG', '#431419'),
			'elevated' => $surfaceAlt,
			'focus_outline' => formic_global_color_hex('THEME_FORMIC_DARK_FOCUS_OUTLINE', '#69b1ff'),
			'focus_ring' => formic_color_rgba($accent, 0.32),
				'hover' => formic_global_color_hex('THEME_FORMIC_DARK_ROW_HOVER', $isCustomized('THEME_ELDY_USE_HOVER') ? $light['hover'] : '#262626'),
			'icon_accent' => formic_global_color_hex('THEME_FORMIC_DARK_ICON_ACCENT', '#4096ff'),
			'icon_danger' => $danger,
			'icon_date' => formic_global_color_hex('THEME_FORMIC_DARK_ICON_DATE', '#a6a6a6'),
			'icon_default' => formic_global_color_hex('THEME_FORMIC_DARK_ICON_DEFAULT', '#a6a6a6'),
			'icon_muted' => formic_global_color_hex('THEME_FORMIC_DARK_ICON_MUTED', '#737373'),
			'icon_success' => $success,
			'icon_warning' => $warning,
			'input_background' => formic_global_color_hex('THEME_FORMIC_DARK_INPUT_BACKGROUND', '#1f1f1f'),
			'input_background_disabled' => formic_global_color_hex('THEME_FORMIC_DARK_INPUT_BACKGROUND_DISABLED', '#1a1a1a'),
			'input_border' => formic_global_color_hex('THEME_FORMIC_DARK_INPUT_BORDER', $line),
			'input_color_disabled' => formic_global_color_hex('THEME_FORMIC_DARK_INPUT_COLOR_DISABLED', '#5c5c5c'),
			'line' => $line,
			'line_break' => $lineSoft,
			'line_soft' => $lineSoft,
			'list_total' => formic_global_color_hex('THEME_FORMIC_DARK_LIST_TOTAL', '#a6a6a6'),
			'muted' => formic_global_color_hex('THEME_FORMIC_DARK_MUTED', '#a6a6a6'),
			'neutral_bg' => '#1f1f1f',
			'neutral_hover_bg' => '#262626',
			'refused_bg' => '#1f1f1f',
			'refid_no_color' => $text,
				'row_even' => $rowEven,
				'row_odd' => $rowOdd,
			'shadow_elevated' => getDolGlobalString('THEME_FORMIC_DARK_SHADOW_ELEVATED', '0 6px 16px 0 rgba(0, 0, 0, 0.36), 0 9px 28px 8px rgba(0, 0, 0, 0.3), 0 12px 48px 16px rgba(0, 0, 0, 0.32)'),
			'shadow_menu' => getDolGlobalString('THEME_FORMIC_DARK_SHADOW_MENU', '0 6px 16px 0 rgba(0, 0, 0, 0.32), 0 3px 6px -4px rgba(0, 0, 0, 0.36), 0 9px 28px 8px rgba(0, 0, 0, 0.24)'),
			'shadow_soft' => getDolGlobalString('THEME_FORMIC_DARK_SHADOW_SOFT', '0 1px 2px 0 rgba(0, 0, 0, 0.3), 0 1px 6px -1px rgba(0, 0, 0, 0.24), 0 2px 4px 0 rgba(0, 0, 0, 0.2)'),
			'shell' => $shell,
			'status_success_bg' => formic_color_rgba($success, 0.16),
			'status_success_border' => formic_color_rgba($success, 0.32),
			'status_success_text' => $success,
			'surface' => $surface,
			'surface_alt' => $surfaceAlt,
			'switch_knob' => '#ffffff',
			'tab_active' => $surfaceAlt,
			'tab_card' => $surface,
			'table_header' => $surfaceAlt,
			'tableforfield' => formic_global_color_hex('THEME_FORMIC_DARK_TABLEFORFIELD', '#a6a6a6'),
			'text' => $text,
			'title_bg' => $surfaceAlt,
				'title_link' => $titleLink,
				'title_notab' => $titleNoTab,
				'title_text' => $titleText,
			'toast_error_bg' => '#2c1618',
			'toast_error_border' => formic_color_rgba($danger, 0.42),
			'toast_error_text' => '#ff7875',
			'toast_success_bg' => '#162312',
			'toast_success_border' => formic_color_rgba($success, 0.36),
			'toast_success_text' => '#95de64',
			'toast_warning_bg' => '#2b2111',
			'toast_warning_border' => formic_color_rgba($warning, 0.36),
			'toast_warning_text' => $warning,
				'topmenu' => $topmenu,
			'warning' => $warning,
			'workspace' => $surface,
			'workspace_grey' => '#1f1f1f',
		));
	}
}

if (!function_exists('formic_build_css_variables')) {
	/**
	 * Convert a Formic settings array to CSS custom properties.
	 *
	 * @param array<string,string> $settings Formic settings
	 * @param array<string,string> $legacy   Legacy values that are still computed by Eldy
	 * @return array<string,string>
	 */
	function formic_build_css_variables($settings, $legacy = array())
	{
		$linkHsla = colorHexToHsl($settings['accent'], false, true);

		return array(
			'--formic-accent' => $settings['accent'],
			'--formic-accent-hover' => $settings['accent_hover'],
			'--formic-accent-soft' => $settings['accent_soft'],
				'--formic-accent-soft-hover' => $settings['accent_soft_hover'],
				'--formic-action' => $settings['action'],
				'--formic-action-hover' => $settings['action_hover'],
				'--formic-action-text' => $settings['action_text'],
			'--formic-line' => $settings['line'],
			'--formic-line-soft' => $settings['line_soft'],
			'--colortopbordertitle1' => $settings['line'],
			'--formic-bg' => $settings['bg'],
			'--formic-surface' => $settings['surface'],
			'--formic-surface-alt' => $settings['surface_alt'],
			'--formic-shell' => $settings['shell'],
			'--formic-workspace' => $settings['workspace'],
			'--colorbackhmenu1' => $settings['topmenu'],
			'--colorbackvmenu1' => $settings['shell'],
			'--colorbackmobilemenu' => $settings['shell'],
			'--colorbackgrey' => $settings['workspace_grey'],
			'--formic-text' => $settings['text'],
			'--formic-muted' => $settings['muted'],
			'--formic-danger' => $settings['danger'],
			'--formic-danger-bg' => $settings['danger_bg'],
			'--formic-danger-hover' => $settings['danger_hover'],
			'--formic-danger-hover-bg' => $settings['danger_hover_bg'],
			'--formic-toast-success-bg' => $settings['toast_success_bg'],
			'--formic-toast-success-border' => $settings['toast_success_border'],
			'--formic-toast-success-text' => $settings['toast_success_text'],
			'--formic-toast-warning-bg' => $settings['toast_warning_bg'],
			'--formic-toast-warning-border' => $settings['toast_warning_border'],
			'--formic-toast-warning-text' => $settings['toast_warning_text'],
			'--formic-toast-error-bg' => $settings['toast_error_bg'],
			'--formic-toast-error-border' => $settings['toast_error_border'],
			'--formic-toast-error-text' => $settings['toast_error_text'],
			'--formic-neutral-bg' => $settings['neutral_bg'],
			'--formic-neutral-hover-bg' => $settings['neutral_hover_bg'],
			'--formic-refused-bg' => $settings['refused_bg'],
			'--formic-focus-ring' => $settings['focus_ring'],
			'--formic-focus-outline' => $settings['focus_outline'],
			'--formic-status-success-bg' => $settings['status_success_bg'],
			'--formic-status-success-border' => $settings['status_success_border'],
			'--formic-status-success-text' => $settings['status_success_text'],
			'--formic-icon-default' => $settings['icon_default'],
			'--formic-icon-muted' => $settings['icon_muted'],
			'--formic-icon-accent' => $settings['icon_accent'],
			'--formic-avatar-ring' => $settings['avatar_ring'],
			'--formic-icon-date' => $settings['icon_date'],
			'--formic-icon-danger' => $settings['icon_danger'],
			'--formic-icon-warning' => $settings['icon_warning'],
			'--formic-icon-success' => $settings['icon_success'],
			'--formic-infobox-action' => getDolGlobalString('THEME_FORMIC_INFOBOX_ACTION', isset($legacy['infobox_action']) ? $legacy['infobox_action'] : '#906080'),
			'--formic-infobox-bank' => getDolGlobalString('THEME_FORMIC_INFOBOX_BANK', isset($legacy['infobox_bank']) ? $legacy['infobox_bank'] : '#7a6d10'),
			'--formic-infobox-contract' => getDolGlobalString('THEME_FORMIC_INFOBOX_CONTRACT', isset($legacy['infobox_contract']) ? $legacy['infobox_contract'] : '#238b7f'),
			'--formic-infobox-customer' => getDolGlobalString('THEME_FORMIC_INFOBOX_CUSTOMER', isset($legacy['infobox_customer']) ? $legacy['infobox_customer'] : '#65953d'),
			'--formic-infobox-holiday' => getDolGlobalString('THEME_FORMIC_INFOBOX_HOLIDAY', isset($legacy['infobox_holiday']) ? $legacy['infobox_holiday'] : '#755114'),
			'--formic-infobox-member' => getDolGlobalString('THEME_FORMIC_INFOBOX_MEMBER', isset($legacy['infobox_member']) ? $legacy['infobox_member'] : '#79633f'),
			'--formic-infobox-project' => getDolGlobalString('THEME_FORMIC_INFOBOX_PROJECT', isset($legacy['infobox_project']) ? $legacy['infobox_project'] : '#6c6aa8'),
			'--formic-infobox-stock' => getDolGlobalString('THEME_FORMIC_INFOBOX_STOCK', isset($legacy['infobox_stock']) ? $legacy['infobox_stock'] : '#b0a53e'),
			'--formic-infobox-supplier' => getDolGlobalString('THEME_FORMIC_INFOBOX_SUPPLIER', isset($legacy['infobox_supplier']) ? $legacy['infobox_supplier'] : '#599caf'),
			'--formic-radius-sm' => $settings['radius_sm'],
			'--formic-radius' => $settings['radius'],
			'--formic-radius-lg' => $settings['radius_lg'],
			'--formic-radius-full' => $settings['radius_full'],
			'--formic-table-radius' => $settings['table_radius'],
			'--formic-control-height' => '32px',
			'--formic-shadow-soft' => $settings['shadow_soft'],
			'--formic-shadow-elevated' => $settings['shadow_elevated'],
			'--formic-shadow-menu' => $settings['shadow_menu'],
			'--formic-elevated' => $settings['elevated'],
			'--formic-table-header' => $settings['table_header'],
			'--colorbacktitle1' => $settings['title_bg'],
			'--colorbacktabcard1' => $settings['tab_card'],
			'--colorbacktabactive' => $settings['tab_active'],
			'--colorbacklineimpair1' => $settings['row_odd'],
			'--colorbacklineimpair2' => $settings['row_odd'],
			'--colorbacklinepair1' => $settings['row_even'],
			'--colorbacklinepair2' => $settings['row_even'],
			'--colorbacklinepairhover' => $settings['hover'],
			'--colorbacklinepairchecked' => $settings['checked'],
			'--colorbacklinebreak' => $settings['line_break'],
			'--colorbackbody' => $settings['workspace'],
			'--formic-switch-knob' => $settings['switch_knob'],
			'--infoboxmoduleenabledbgcolor' => 'transparent',
			'--colortexttitlenotab' => $settings['title_notab'],
			'--colortexttitlenotab2' => $settings['title_notab'],
			'--colortexttitle' => formic_color_rgba($settings['title_text'], 0.90),
			'--colortexttitlelink' => formic_color_rgba($settings['title_link'], 0.90),
			'--colortext' => $settings['text'],
			'--colortextlink' => $settings['accent'],
			'--colortextlink-h' => (string) $linkHsla['h'],
			'--colortextlink-l' => (string) $linkHsla['l'].'%',
			'--colortextlink-s' => (string) $linkHsla['s'].'%',
			'--colortextlink-a' => '1',
			'--butactionbg' => $settings['action'],
			'--textbutaction' => $settings['action_text'],
			'--colortextbackhmenu' => isset($legacy['text_back_hmenu']) ? $legacy['text_back_hmenu'] : $settings['text'],
			'--colortextbackvmenu' => isset($legacy['text_back_vmenu']) ? $legacy['text_back_vmenu'] : $settings['text'],
			'--colortextbacktab' => isset($legacy['text_back_tab']) ? $legacy['text_back_tab'] : $settings['text'],
			'--listetotal' => $settings['list_total'],
			'--inputbackgroundcolor' => $settings['input_background'],
			'--inputbackgroundcolordisabled' => $settings['input_background_disabled'],
			'--inputcolordisabled' => $settings['input_color_disabled'],
			'--inputbordercolor' => $settings['input_border'],
			'--tooltipbgcolor' => isset($legacy['tooltip_bg']) ? $legacy['tooltip_bg'] : $settings['surface'],
			'--tooltipfontcolor' => isset($legacy['tooltip_text']) ? $legacy['tooltip_text'] : $settings['text'],
			'--oddevencolor' => $settings['text'],
			'--colorboxstatsborder' => $settings['line'],
			'--colorboxiconbg' => $settings['neutral_bg'],
			'--fieldrequiredcolor' => $settings['danger'],
			'--refidnocolor' => $settings['refid_no_color'],
			'--tableforfieldcolor' => $settings['tableforfield'],
		);
	}
}
