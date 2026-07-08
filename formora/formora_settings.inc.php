<?php
/* Copyright (C) 2026       Codex
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 */

/**
 * \file       htdocs/theme/formora/formora_settings.inc.php
 * \brief      Helpers to map Dolibarr skin settings to Formora CSS variables.
 */

if (!function_exists('formora_color_hex')) {
	/**
	 * Normalize a Dolibarr color value to a CSS hex color.
	 *
	 * @param string|array<int,int>|null $value       Color value, either hex or "r,g,b"
	 * @param string                     $fallbackHex Fallback hex color
	 * @return string
	 */
	function formora_color_hex($value, $fallbackHex)
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

if (!function_exists('formora_global_color_hex')) {
	/**
	 * Read an optional Formora-specific color constant.
	 *
	 * @param string $const       Constant name
	 * @param string $fallbackHex Fallback hex color
	 * @return string
	 */
	function formora_global_color_hex($const, $fallbackHex)
	{
		$value = getDolGlobalString($const);
		return formora_color_hex($value, $fallbackHex);
	}
}

if (!function_exists('formora_color_rgba')) {
	/**
	 * Convert a hex color to CSS rgba().
	 *
	 * @param string $hex   Hex color
	 * @param float  $alpha Alpha channel
	 * @return string
	 */
	function formora_color_rgba($hex, $alpha)
	{
		return colorHexToRgb($hex, $alpha);
	}
}

if (!function_exists('formora_color_lighten')) {
	/**
	 * Lighten a CSS hex color.
	 *
	 * @param string $hex     Hex color
	 * @param int    $percent Percent
	 * @return string
	 */
	function formora_color_lighten($hex, $percent)
	{
		return strtolower(colorLighten($hex, $percent));
	}
}

if (!function_exists('formora_color_darker')) {
	/**
	 * Darken a CSS hex color.
	 *
	 * @param string $hex     Hex color
	 * @param int    $percent Percent
	 * @return string
	 */
	function formora_color_darker($hex, $percent)
	{
		return strtolower(colorDarker($hex, $percent));
	}
}

if (!function_exists('formora_svg_color')) {
	/**
	 * Encode a color for inline SVG data URLs.
	 *
	 * @param string $hex Hex color
	 * @return string
	 */
	function formora_svg_color($hex)
	{
		return '%23'.ltrim(strtolower($hex), '#');
	}
}

if (!function_exists('formora_radius_scale')) {
	/**
	 * Derive Formora radius scale from Dolibarr table radius.
	 *
	 * @param int $radius Radius from system settings
	 * @return array{sm:int,base:int,lg:int,table:int}
	 */
	function formora_radius_scale($radius)
	{
		$radius = max(0, $radius);
		if ($radius === 0) {
			return array('sm' => 0, 'base' => 0, 'lg' => 0, 'table' => 0);
		}

		return array(
			'sm' => max(3, min($radius, 8)),
			'base' => max(5, min($radius + 2, 12)),
			'lg' => max(8, min($radius + 6, 20)),
			'table' => $radius,
		);
	}
}

if (!function_exists('formora_build_light_settings')) {
	/**
	 * Build Formora light-mode variable values from Dolibarr system settings.
	 *
	 * @param array<string,string|int> $input Input colors and radius
	 * @return array<string,string>
	 */
	function formora_build_light_settings($input)
	{
		$accent = formora_global_color_hex('THEME_FORMORA_ACCENT', formora_color_hex($input['textlink'], '#0071e3'));
		$action = formora_global_color_hex('THEME_FORMORA_ACTION', formora_color_hex($input['button'], $accent));
		$actionText = formora_global_color_hex('THEME_FORMORA_ACTION_TEXT', formora_color_hex($input['button_text'], '#ffffff'));
		$text = formora_global_color_hex('THEME_FORMORA_TEXT', formora_color_hex($input['text'], '#1d1d1f'));
		$titleText = formora_global_color_hex('THEME_FORMORA_TITLE_TEXT', formora_color_hex($input['title_text'], $text));
		$titleLink = formora_global_color_hex('THEME_FORMORA_TITLE_LINK', formora_color_hex($input['title_link'], $accent));
		$titleNoTab = formora_global_color_hex('THEME_FORMORA_PAGE_TITLE', formora_color_hex($input['title_notab'], $accent));
		$workspace = formora_global_color_hex('THEME_FORMORA_WORKSPACE', formora_color_hex($input['body'], '#ffffff'));
		$shell = formora_global_color_hex('THEME_FORMORA_SHELL', formora_color_hex($input['vmenu'], '#f5f5f7'));
		$topmenu = formora_global_color_hex('THEME_FORMORA_TOPMENU', formora_color_hex($input['hmenu'], '#ffffff'));
		$surface = formora_global_color_hex('THEME_FORMORA_SURFACE', $workspace);
		$surfaceAlt = formora_global_color_hex('THEME_FORMORA_SURFACE_ALT', formora_color_hex($input['title_bg'], '#eef1f5'));
		$line = formora_global_color_hex('THEME_FORMORA_LINE', formora_color_hex($input['title_border'], '#c9cdd3'));
		$lineSoft = formora_global_color_hex('THEME_FORMORA_LINE_SOFT', formora_color_lighten($line, 10));
		$tabCard = formora_global_color_hex('THEME_FORMORA_TAB_CARD', formora_color_hex($input['tab_card'], $surface));
		$tabActive = formora_global_color_hex('THEME_FORMORA_TAB_ACTIVE', formora_color_hex($input['tab_active'], $surfaceAlt));
		$lineBreak = formora_global_color_hex('THEME_FORMORA_LINE_BREAK', formora_color_hex($input['line_break'], $lineSoft));
		$rowOdd = formora_global_color_hex('THEME_FORMORA_ROW_ODD', formora_color_hex($input['row_odd'], $surface));
		$rowEven = formora_global_color_hex('THEME_FORMORA_ROW_EVEN', formora_color_hex($input['row_even'], '#fcfcfd'));
		$themeHover = getDolGlobalString('THEME_FORMORA_ROW_HOVER');
		$themeChecked = getDolGlobalString('THEME_FORMORA_ROW_CHECKED');
		$hover = ($themeHover !== '') ? formora_color_hex($themeHover, '#eef6ff') : (((string) $input['row_hover'] === '') ? 'transparent' : formora_color_hex($input['row_hover'], '#eef6ff'));
		$checked = ($themeChecked !== '') ? formora_color_hex($themeChecked, '#eef6ff') : (((string) $input['row_checked'] === '') ? $hover : formora_color_hex($input['row_checked'], '#eef6ff'));
		$danger = formora_global_color_hex('THEME_FORMORA_DANGER', '#d70015');
		$success = formora_global_color_hex('THEME_FORMORA_SUCCESS', '#248a3d');
		$warning = formora_global_color_hex('THEME_FORMORA_WARNING', '#b26a00');
		$muted = formora_global_color_hex('THEME_FORMORA_MUTED', '#6e6e73');
		$radius = formora_radius_scale((int) $input['radius']);

		return array(
			'accent' => $accent,
			'accent_hover' => formora_global_color_hex('THEME_FORMORA_ACCENT_HOVER', formora_color_darker($accent, 14)),
				'accent_soft' => formora_color_rgba($accent, 0.10),
				'accent_soft_hover' => formora_color_rgba($accent, 0.16),
				'action' => $action,
				'action_hover' => formora_global_color_hex('THEME_FORMORA_ACTION_HOVER', formora_color_darker($action, 10)),
				'action_text' => $actionText,
			'avatar_ring' => formora_color_rgba($text, 0.56),
			'bg' => $shell,
			'checked' => $checked,
			'danger' => $danger,
			'danger_bg' => formora_global_color_hex('THEME_FORMORA_DANGER_BG', '#fff1f1'),
			'danger_hover_bg' => formora_global_color_hex('THEME_FORMORA_DANGER_HOVER_BG', '#ffe1e4'),
			'elevated' => formora_global_color_hex('THEME_FORMORA_ELEVATED', $surface),
			'focus_outline' => formora_global_color_hex('THEME_FORMORA_FOCUS_OUTLINE', formora_color_darker($accent, 14)),
			'focus_ring' => formora_color_rgba($accent, 0.18),
			'hover' => $hover,
			'icon_accent' => formora_global_color_hex('THEME_FORMORA_ICON_ACCENT', $accent),
			'icon_danger' => formora_global_color_hex('THEME_FORMORA_ICON_DANGER', $danger),
			'icon_date' => formora_global_color_hex('THEME_FORMORA_ICON_DATE', '#57606a'),
			'icon_default' => formora_global_color_hex('THEME_FORMORA_ICON_DEFAULT', '#5f6368'),
			'icon_muted' => formora_global_color_hex('THEME_FORMORA_ICON_MUTED', '#86868b'),
			'icon_success' => formora_global_color_hex('THEME_FORMORA_ICON_SUCCESS', $success),
			'icon_warning' => formora_global_color_hex('THEME_FORMORA_ICON_WARNING', $warning),
			'input_background' => formora_global_color_hex('THEME_FORMORA_INPUT_BACKGROUND', $surface),
			'input_background_disabled' => formora_global_color_hex('THEME_FORMORA_INPUT_BACKGROUND_DISABLED', '#eeeeee'),
			'input_border' => getDolGlobalString('THEME_FORMORA_INPUT_BORDER', formora_color_rgba($line, 0.96)),
			'input_color_disabled' => formora_global_color_hex('THEME_FORMORA_INPUT_COLOR_DISABLED', '#505050'),
			'line' => $line,
			'line_break' => $lineBreak,
			'line_soft' => $lineSoft,
			'list_total' => formora_global_color_hex('THEME_FORMORA_LIST_TOTAL', '#888888'),
			'muted' => $muted,
			'neutral_bg' => formora_global_color_hex('THEME_FORMORA_NEUTRAL_BG', '#fbfbfd'),
			'neutral_hover_bg' => formora_global_color_hex('THEME_FORMORA_NEUTRAL_HOVER_BG', '#f2f2f4'),
			'radius_sm' => (string) $radius['sm'].'px',
			'radius' => (string) $radius['base'].'px',
			'radius_lg' => (string) $radius['lg'].'px',
			'refused_bg' => formora_global_color_hex('THEME_FORMORA_REFUSED_BG', $shell),
			'refid_no_color' => formora_global_color_hex('THEME_FORMORA_REFID_NO_COLOR', '#444444'),
			'row_even' => $rowEven,
			'row_odd' => $rowOdd,
			'shadow_elevated' => getDolGlobalString('THEME_FORMORA_SHADOW_ELEVATED', '0 16px 42px rgba(0, 0, 0, 0.16)'),
			'shadow_menu' => getDolGlobalString('THEME_FORMORA_SHADOW_MENU', '0 10px 28px rgba(0, 0, 0, 0.14)'),
			'shadow_soft' => getDolGlobalString('THEME_FORMORA_SHADOW_SOFT', '0 1px 2px rgba(0, 0, 0, 0.05), 0 8px 24px rgba(0, 0, 0, 0.06)'),
			'shell' => $shell,
			'status_success_bg' => $success,
			'status_success_border' => formora_color_rgba($success, 0.16),
			'status_success_text' => '#ffffff',
			'surface' => $surface,
			'surface_alt' => $surfaceAlt,
			'switch_knob' => '#ffffff',
			'tab_active' => $tabActive,
			'tab_card' => $tabCard,
			'table_header' => $surfaceAlt,
			'table_radius' => (string) $radius['table'].'px',
			'tableforfield' => formora_global_color_hex('THEME_FORMORA_TABLEFORFIELD', '#888888'),
			'text' => $text,
			'title_bg' => $surfaceAlt,
			'title_link' => $titleLink,
			'title_notab' => $titleNoTab,
			'title_text' => $titleText,
			'toast_error_bg' => '#fff1f1',
			'toast_error_border' => formora_color_rgba($danger, 0.32),
			'toast_error_text' => $danger,
			'toast_success_bg' => '#f0fbf3',
			'toast_success_border' => formora_color_rgba($success, 0.28),
			'toast_success_text' => $success,
			'toast_warning_bg' => '#fff8e6',
			'toast_warning_border' => formora_color_rgba($warning, 0.32),
			'toast_warning_text' => formora_global_color_hex('THEME_FORMORA_WARNING_TEXT', '#9a6700'),
			'topmenu' => $topmenu,
			'warning' => $warning,
			'workspace' => $workspace,
			'workspace_grey' => formora_global_color_hex('THEME_FORMORA_WORKSPACE_GREY', '#f0f0f0'),
		);
	}
}

if (!function_exists('formora_build_dark_settings')) {
	/**
	 * Build Formora dark-mode variable values.
	 *
		 * @param array<string,string> $light      Light settings
		 * @param array<string,bool>   $customized Dolibarr settings explicitly customized by admin/user
		 * @return array<string,string>
		 */
		function formora_build_dark_settings($light, $customized = array())
		{
			$isCustomized = function ($name) use ($customized) {
				return !empty($customized[$name]);
			};

			$accent = formora_global_color_hex('THEME_FORMORA_DARK_ACCENT', $isCustomized('THEME_ELDY_TEXTLINK') ? $light['accent'] : '#0a84ff');
			$danger = formora_global_color_hex('THEME_FORMORA_DARK_DANGER', '#ff6961');
			$success = formora_global_color_hex('THEME_FORMORA_DARK_SUCCESS', '#30d158');
			$warning = formora_global_color_hex('THEME_FORMORA_DARK_WARNING', '#ffd60a');
			$text = formora_global_color_hex('THEME_FORMORA_DARK_TEXT', '#f5f5f7');
			$surface = formora_global_color_hex('THEME_FORMORA_DARK_SURFACE', $isCustomized('THEME_ELDY_BACKBODY') ? $light['workspace'] : '#1c1c1e');
			$surfaceAlt = formora_global_color_hex('THEME_FORMORA_DARK_SURFACE_ALT', $isCustomized('THEME_ELDY_BACKTITLE1') ? $light['surface_alt'] : '#242426');
			$shell = formora_global_color_hex('THEME_FORMORA_DARK_SHELL', $isCustomized('THEME_ELDY_VERMENU_BACK1') ? $light['shell'] : '#0f1012');
			$line = formora_global_color_hex('THEME_FORMORA_DARK_LINE', '#3a3a3c');
			$lineSoft = formora_global_color_hex('THEME_FORMORA_DARK_LINE_SOFT', '#2c2c2e');
			$action = formora_global_color_hex('THEME_FORMORA_DARK_ACTION', $isCustomized('THEME_ELDY_BTNACTION') ? $light['action'] : $light['action']);
			$actionText = formora_global_color_hex('THEME_FORMORA_DARK_ACTION_TEXT', $isCustomized('THEME_ELDY_TEXTBTNACTION') ? $light['action_text'] : $light['action_text']);
			$rowOdd = ($isCustomized('THEME_ELDY_LINEIMPAIR1') || $isCustomized('THEME_ELDY_LINEIMPAIR2')) ? $light['row_odd'] : $surface;
			$rowEven = ($isCustomized('THEME_ELDY_LINEPAIR1') || $isCustomized('THEME_ELDY_LINEPAIR2')) ? $light['row_even'] : $surface;
			$titleLink = formora_global_color_hex('THEME_FORMORA_DARK_TITLE_LINK', $isCustomized('THEME_ELDY_TEXTTITLELINK') ? $light['title_link'] : '#64b5ff');
			$titleNoTab = formora_global_color_hex('THEME_FORMORA_DARK_PAGE_TITLE', $isCustomized('THEME_ELDY_TEXTTITLENOTAB') ? $light['title_notab'] : '#64b5ff');
			$titleText = $isCustomized('THEME_ELDY_TEXTTITLE') ? $light['title_text'] : $text;
			$topmenu = formora_global_color_hex('THEME_FORMORA_DARK_TOPMENU', $isCustomized('THEME_ELDY_TOPMENU_BACK1') ? $light['topmenu'] : $surface);

			return array_merge($light, array(
				'accent' => $accent,
				'accent_hover' => formora_global_color_hex('THEME_FORMORA_DARK_ACCENT_HOVER', '#64b5ff'),
				'accent_soft' => formora_color_rgba($accent, 0.18),
				'accent_soft_hover' => formora_color_rgba($accent, 0.28),
				'action' => $action,
				'action_hover' => formora_global_color_hex('THEME_FORMORA_DARK_ACTION_HOVER', formora_color_lighten($action, 8)),
				'action_text' => $actionText,
			'avatar_ring' => formora_color_rgba($text, 0.42),
			'bg' => $shell,
				'checked' => formora_global_color_hex('THEME_FORMORA_DARK_ROW_CHECKED', $isCustomized('THEME_ELDY_USE_CHECKED') ? $light['checked'] : '#173452'),
			'danger' => $danger,
			'danger_bg' => formora_global_color_hex('THEME_FORMORA_DARK_DANGER_BG', '#3a1f22'),
			'danger_hover_bg' => formora_global_color_hex('THEME_FORMORA_DARK_DANGER_HOVER_BG', '#4a2428'),
			'elevated' => $surfaceAlt,
			'focus_outline' => formora_global_color_hex('THEME_FORMORA_DARK_FOCUS_OUTLINE', '#78b7ff'),
			'focus_ring' => formora_color_rgba($accent, 0.32),
				'hover' => formora_global_color_hex('THEME_FORMORA_DARK_ROW_HOVER', $isCustomized('THEME_ELDY_USE_HOVER') ? $light['hover'] : '#242c36'),
			'icon_accent' => formora_global_color_hex('THEME_FORMORA_DARK_ICON_ACCENT', '#64b5ff'),
			'icon_danger' => $danger,
			'icon_date' => formora_global_color_hex('THEME_FORMORA_DARK_ICON_DATE', '#a1a1a6'),
			'icon_default' => formora_global_color_hex('THEME_FORMORA_DARK_ICON_DEFAULT', '#a1a1a6'),
			'icon_muted' => formora_global_color_hex('THEME_FORMORA_DARK_ICON_MUTED', '#8e8e93'),
			'icon_success' => $success,
			'icon_warning' => $warning,
			'input_background' => formora_global_color_hex('THEME_FORMORA_DARK_INPUT_BACKGROUND', '#202124'),
			'input_background_disabled' => formora_global_color_hex('THEME_FORMORA_DARK_INPUT_BACKGROUND_DISABLED', '#151517'),
			'input_border' => formora_global_color_hex('THEME_FORMORA_DARK_INPUT_BORDER', $line),
			'input_color_disabled' => formora_global_color_hex('THEME_FORMORA_DARK_INPUT_COLOR_DISABLED', '#77777d'),
			'line' => $line,
			'line_break' => $lineSoft,
			'line_soft' => $lineSoft,
			'list_total' => formora_global_color_hex('THEME_FORMORA_DARK_LIST_TOTAL', '#a1a1a6'),
			'muted' => formora_global_color_hex('THEME_FORMORA_DARK_MUTED', '#a1a1a6'),
			'neutral_bg' => '#202124',
			'neutral_hover_bg' => '#2c2c2e',
			'refused_bg' => '#202124',
			'refid_no_color' => $text,
				'row_even' => $rowEven,
				'row_odd' => $rowOdd,
			'shadow_elevated' => getDolGlobalString('THEME_FORMORA_DARK_SHADOW_ELEVATED', '0 18px 48px rgba(0, 0, 0, 0.48)'),
			'shadow_menu' => getDolGlobalString('THEME_FORMORA_DARK_SHADOW_MENU', '0 14px 34px rgba(0, 0, 0, 0.5)'),
			'shadow_soft' => getDolGlobalString('THEME_FORMORA_DARK_SHADOW_SOFT', '0 1px 2px rgba(0, 0, 0, 0.35), 0 8px 24px rgba(0, 0, 0, 0.28)'),
			'shell' => $shell,
			'status_success_bg' => formora_color_rgba($success, 0.16),
			'status_success_border' => formora_color_rgba($success, 0.32),
			'status_success_text' => $success,
			'surface' => $surface,
			'surface_alt' => $surfaceAlt,
			'switch_knob' => '#1c1f22',
			'tab_active' => $surfaceAlt,
			'tab_card' => $surface,
			'table_header' => $surfaceAlt,
			'tableforfield' => formora_global_color_hex('THEME_FORMORA_DARK_TABLEFORFIELD', '#a1a1a6'),
			'text' => $text,
			'title_bg' => $surfaceAlt,
				'title_link' => $titleLink,
				'title_notab' => $titleNoTab,
				'title_text' => $titleText,
			'toast_error_bg' => '#3a1f22',
			'toast_error_border' => formora_color_rgba($danger, 0.42),
			'toast_error_text' => '#ff8a82',
			'toast_success_bg' => '#173522',
			'toast_success_border' => formora_color_rgba($success, 0.36),
			'toast_success_text' => '#8ff0a4',
			'toast_warning_bg' => '#3a2f14',
			'toast_warning_border' => formora_color_rgba($warning, 0.36),
			'toast_warning_text' => $warning,
				'topmenu' => $topmenu,
			'warning' => $warning,
			'workspace' => $surface,
			'workspace_grey' => '#202124',
		));
	}
}

if (!function_exists('formora_build_css_variables')) {
	/**
	 * Convert a Formora settings array to CSS custom properties.
	 *
	 * @param array<string,string> $settings Formora settings
	 * @param array<string,string> $legacy   Legacy values that are still computed by Eldy
	 * @return array<string,string>
	 */
	function formora_build_css_variables($settings, $legacy = array())
	{
		$linkHsla = colorHexToHsl($settings['accent'], false, true);

		return array(
			'--formora-accent' => $settings['accent'],
			'--formora-accent-hover' => $settings['accent_hover'],
			'--formora-accent-soft' => $settings['accent_soft'],
				'--formora-accent-soft-hover' => $settings['accent_soft_hover'],
				'--formora-action' => $settings['action'],
				'--formora-action-hover' => $settings['action_hover'],
				'--formora-action-text' => $settings['action_text'],
			'--formora-line' => $settings['line'],
			'--formora-line-soft' => $settings['line_soft'],
			'--colortopbordertitle1' => $settings['line'],
			'--formora-bg' => $settings['bg'],
			'--formora-surface' => $settings['surface'],
			'--formora-surface-alt' => $settings['surface_alt'],
			'--formora-shell' => $settings['shell'],
			'--formora-workspace' => $settings['workspace'],
			'--colorbackhmenu1' => $settings['topmenu'],
			'--colorbackvmenu1' => $settings['shell'],
			'--colorbackmobilemenu' => $settings['shell'],
			'--colorbackgrey' => $settings['workspace_grey'],
			'--formora-text' => $settings['text'],
			'--formora-muted' => $settings['muted'],
			'--formora-danger' => $settings['danger'],
			'--formora-danger-bg' => $settings['danger_bg'],
			'--formora-danger-hover-bg' => $settings['danger_hover_bg'],
			'--formora-toast-success-bg' => $settings['toast_success_bg'],
			'--formora-toast-success-border' => $settings['toast_success_border'],
			'--formora-toast-success-text' => $settings['toast_success_text'],
			'--formora-toast-warning-bg' => $settings['toast_warning_bg'],
			'--formora-toast-warning-border' => $settings['toast_warning_border'],
			'--formora-toast-warning-text' => $settings['toast_warning_text'],
			'--formora-toast-error-bg' => $settings['toast_error_bg'],
			'--formora-toast-error-border' => $settings['toast_error_border'],
			'--formora-toast-error-text' => $settings['toast_error_text'],
			'--formora-neutral-bg' => $settings['neutral_bg'],
			'--formora-neutral-hover-bg' => $settings['neutral_hover_bg'],
			'--formora-refused-bg' => $settings['refused_bg'],
			'--formora-focus-ring' => $settings['focus_ring'],
			'--formora-focus-outline' => $settings['focus_outline'],
			'--formora-status-success-bg' => $settings['status_success_bg'],
			'--formora-status-success-border' => $settings['status_success_border'],
			'--formora-status-success-text' => $settings['status_success_text'],
			'--formora-icon-default' => $settings['icon_default'],
			'--formora-icon-muted' => $settings['icon_muted'],
			'--formora-icon-accent' => $settings['icon_accent'],
			'--formora-avatar-ring' => $settings['avatar_ring'],
			'--formora-icon-date' => $settings['icon_date'],
			'--formora-icon-danger' => $settings['icon_danger'],
			'--formora-icon-warning' => $settings['icon_warning'],
			'--formora-icon-success' => $settings['icon_success'],
			'--formora-infobox-action' => getDolGlobalString('THEME_FORMORA_INFOBOX_ACTION', isset($legacy['infobox_action']) ? $legacy['infobox_action'] : '#906080'),
			'--formora-infobox-bank' => getDolGlobalString('THEME_FORMORA_INFOBOX_BANK', isset($legacy['infobox_bank']) ? $legacy['infobox_bank'] : '#7a6d10'),
			'--formora-infobox-contract' => getDolGlobalString('THEME_FORMORA_INFOBOX_CONTRACT', isset($legacy['infobox_contract']) ? $legacy['infobox_contract'] : '#238b7f'),
			'--formora-infobox-customer' => getDolGlobalString('THEME_FORMORA_INFOBOX_CUSTOMER', isset($legacy['infobox_customer']) ? $legacy['infobox_customer'] : '#65953d'),
			'--formora-infobox-holiday' => getDolGlobalString('THEME_FORMORA_INFOBOX_HOLIDAY', isset($legacy['infobox_holiday']) ? $legacy['infobox_holiday'] : '#755114'),
			'--formora-infobox-member' => getDolGlobalString('THEME_FORMORA_INFOBOX_MEMBER', isset($legacy['infobox_member']) ? $legacy['infobox_member'] : '#79633f'),
			'--formora-infobox-project' => getDolGlobalString('THEME_FORMORA_INFOBOX_PROJECT', isset($legacy['infobox_project']) ? $legacy['infobox_project'] : '#6c6aa8'),
			'--formora-infobox-stock' => getDolGlobalString('THEME_FORMORA_INFOBOX_STOCK', isset($legacy['infobox_stock']) ? $legacy['infobox_stock'] : '#b0a53e'),
			'--formora-infobox-supplier' => getDolGlobalString('THEME_FORMORA_INFOBOX_SUPPLIER', isset($legacy['infobox_supplier']) ? $legacy['infobox_supplier'] : '#599caf'),
			'--formora-radius-sm' => $settings['radius_sm'],
			'--formora-radius' => $settings['radius'],
			'--formora-radius-lg' => $settings['radius_lg'],
			'--formora-table-radius' => $settings['table_radius'],
			'--formora-control-height' => '34px',
			'--formora-shadow-soft' => $settings['shadow_soft'],
			'--formora-shadow-elevated' => $settings['shadow_elevated'],
			'--formora-shadow-menu' => $settings['shadow_menu'],
			'--formora-elevated' => $settings['elevated'],
			'--formora-table-header' => $settings['table_header'],
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
			'--formora-switch-knob' => $settings['switch_knob'],
			'--infoboxmoduleenabledbgcolor' => 'transparent',
			'--colortexttitlenotab' => $settings['title_notab'],
			'--colortexttitlenotab2' => $settings['title_notab'],
			'--colortexttitle' => formora_color_rgba($settings['title_text'], 0.90),
			'--colortexttitlelink' => formora_color_rgba($settings['title_link'], 0.90),
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
