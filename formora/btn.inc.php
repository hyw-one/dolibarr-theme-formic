<?php
/* Copyright (C) 2024-2025  Frédéric France         <frederic.france@free.fr>
 * Copyright (C) 2025       MDW                     <mdeweerd@users.noreply.github.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */
if (!defined('ISLOADEDBYSTEELSHEET')) {
	die('Must be call by steelsheet');
}

/**
 * Formora deliberately lets Eldy own button structure and spacing.
 * This file only layers Formora color variables over Eldy's btn.inc.php.
 *
 * @var string $butactionbg
 * @var string $colortextlink
 * @var string $textbutaction
 */

$formoraEldyBtnFile = DOL_DOCUMENT_ROOT.'/theme/eldy/btn.inc.php';
if (is_readable($formoraEldyBtnFile)) {
	require $formoraEldyBtnFile;
}
?>

/* IDE Hack <style type="text/css"> */

/* Formora button color layer. Eldy keeps btnTitle sizing, border, background and hover structure. */
:root {
	--btncolortext: rgb(<?php print $colortextlink; ?>);
	--btncolorbg: #fbfbfb;
	--btncolorborderhover: none;
	--btncolorborder: #FFF;
	--butactiondeletebg: rgb(234,228,225);
	--butactionbg: rgb(<?php print $butactionbg; ?>);
	--textbutaction: rgb(<?php print $textbutaction; ?>);
}

<?php
if (getDolGlobalString('THEME_DARKMODEENABLED')) {
	print "/* Formora dark-mode button colors. */\n";
	if (getDolGlobalInt('THEME_DARKMODEENABLED') != 2) {
		print "@media (prefers-color-scheme: dark) {";
	} else {
		print "@media not print {";
	}
?>
	:root {
		--btncolortext: var(--formora-text, #edf2f7);
		--btncolorbg: var(--formora-surface, #1c1f22);
		--btncolorborderhover: var(--formora-accent, #4ea3ff);
		--btncolorborder: var(--formora-line, #3a4048);
		--butactiondeletebg: var(--formora-danger-bg, #3a1f24);
		--butactionbg: var(--formora-accent-soft, rgba(78, 163, 255, 0.16));
		--textbutaction: var(--formora-accent-hover, #78b7ff);
	}

	.btnTitle.refused,
	a.btnTitle.refused,
	.btnTitle.refused:hover,
	a.btnTitle.refused:hover {
		color: var(--formora-muted, #a9b3c0) !important;
	}

	a.btnTitle.btnTitleSelected,
	.btnTitle:hover,
	a.btnTitle:hover {
		border-color: var(--formora-line, #3a4048) !important;
	}

	.butActionDelete,
	.butActionDelete:link,
	.butActionDelete:visited,
	.butActionDelete:hover,
	.butActionDelete:active,
	.buttonDelete {
		color: var(--formora-danger, #ff8b8b) !important;
	}
<?php
	print "}";
}
?>
