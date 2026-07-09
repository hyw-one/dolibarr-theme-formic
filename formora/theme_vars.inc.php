<?php
/* Copyright (C) 2004-2017	Laurent Destailleur		<eldy@users.sourceforge.net>
 * Copyright (C) 2006		Rodolphe Quiedeville	<rodolphe@quiedeville.org>
 * Copyright (C) 2007-2017	Regis Houssin			<regis.houssin@inodbox.com>
 * Copyright (C) 2011		Philippe Grand			<philippe.grand@atoo-net.com>
 * Copyright (C) 2012		Juanjo Menent			<jmenent@2byte.es>
 * Copyright (C) 2018       Ferran Marcet           <fmarcet@2byte.es>
 * Copyright (C) 2021-2023  Anthony Berton          <anthony.berton@bb2a.fr>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FI8TNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

/**
 *	\file       htdocs/theme/formora/theme_vars.inc.php
 *	\brief      File to declare variables of CSS style sheet
 *  \ingroup    core
 *
 *  To include file, do this:
 *              $var_file = DOL_DOCUMENT_ROOT.'/theme/'.$conf->theme.'/theme_vars.inc.php';
 *              if (is_readable($var_file)) include $var_file;
 */

global $theme_bordercolor, $theme_datacolor, $theme_bgcolor, $theme_bgcoloronglet;
$theme_bordercolor = array(208, 215, 222);
$theme_datacolor = array(array(9, 105, 218), array(26, 127, 55), array(154, 103, 0), array(207, 34, 46), array(130, 80, 223), array(87, 96, 106), array(64, 120, 192), array(110, 118, 129), array(36, 41, 47), array(82, 146, 87), array(140, 92, 45), array(55, 125, 150), array(150, 135, 80), array(150, 80, 150));
if (!defined('ISLOADEDBYSTEELSHEET')) {	// File is run after an include of a php page, not by the style sheet, if the constant is not defined.
	if (getDolGlobalString('MAIN_OPTIMIZEFORCOLORBLIND')) { // user is loaded by dolgraph.class.php
		if (getDolGlobalString('MAIN_OPTIMIZEFORCOLORBLIND') == 'flashy') {
			$theme_datacolor = array(array(157, 56, 191), array(0, 147, 183), array(250, 190, 30), array(221, 75, 57), array(0, 166, 90), array(140, 140, 220), array(190, 120, 120), array(190, 190, 100), array(115, 125, 150), array(100, 170, 20), array(150, 135, 125), array(85, 135, 150), array(150, 135, 80), array(150, 80, 150));
		} else {
			// for now we use the same configuration for all types of color blind
			$theme_datacolor = array(array(248, 220, 1), array(9, 85, 187), array(42, 208, 255), array(0, 0, 0), array(169, 169, 169), array(253, 102, 136), array(120, 154, 190), array(146, 146, 55), array(0, 52, 251), array(196, 226, 161), array(222, 160, 41), array(85, 135, 150), array(150, 135, 80), array(150, 80, 150));
		}
	}
}

$theme_bgcolor = array(255, 255, 255);
$theme_bgcoloronglet = array(hexdec('EE'), hexdec('F1'), hexdec('F5'));

// Colors
$colorbackbody = '255,255,255';
$colorbackhmenu1 = '255,255,255'; // topmenu
//$colorbackvmenu1 = '250,250,250'; // vmenu
$colorbackvmenu1 = '245,245,247'; // vmenu
$colortopbordertitle1 = '201,205,211'; // top border of title
$colorbacktitle1 = '238,241,245'; // title of tables,list
$colorbacktabcard1 = '255,255,255'; // card
$colorbacktabactive = '238,241,245';
$colorbacklineimpair1 = '255,255,255'; // line impair
$colorbacklineimpair2 = '255,255,255'; // line impair
$colorbacklinepair1 = '252,252,253'; // line pair
$colorbacklinepair2 = '252,252,253'; // line pair
$colorbacklinepairhover = '238,246,255'; // line hover
$colorbacklinepairchecked = '238,246,255'; // line checked
$colorbacklinebreak = '230,232,236'; // line break
$colortexttitlenotab = '22,119,255';
$colortexttitlenotab2 = '22,119,255';
$colortexttitle = '31,31,31';
$colortexttitlelink = '22,119,255';
$colortext = '31,31,31';
$colortextlink = '22,119,255';
$fontsize = '0.94em';
$fontsizesmaller = '0.75em';
$topMenuFontSize = '1.1em';
$toolTipBgColor = 'rgba(255, 255, 255, 0.96)';
$toolTipFontColor = '#333';
$butactionbg = '22,119,255';
$textbutaction = '255, 255, 255';

// text color
$textSuccess   = '#52c41a';
$colorblind_deuteranopes_textSuccess = '#37de5d';
$textWarning   = '#faad14'; // See $badgeWarning
$textDanger    = '#ff4d4f'; // See $badgeDanger
$colorblind_deuteranopes_textWarning = $textWarning; // currently not tested with a color blind people so use default color


// Badges colors
$badgePrimary   = '#1677ff';
$badgeSecondary = '#595959';
$badgeInfo      = '#1677ff';
$badgeSuccess   = '#52c41a';
$badgeWarning   = '#faad14'; // See $textWarning
$badgeDanger    = '#ff4d4f'; // See $textDanger
$badgeDark      = '#1f1f1f';
$badgeLight     = '#fafafa';

// badge color adjustment for color blind
$colorblind_deuteranopes_badgeSuccess   = '#37de5d'; //! text color black
$colorblind_deuteranopes_badgeSuccess_textColor7 = '#000';
$colorblind_deuteranopes_badgeWarning   = '#e4e411';
$colorblind_deuteranopes_badgeDanger    = $badgeDanger; // currently not tested with a color blind people so use default color

/* default color for status : After a quick check, somme status can have opposite function according to objects
*  So this badges status uses default value according to theme eldy status img
*  TODO: use color definition vars above for define badges color status X -> example $badgeStatusValidate, $badgeStatusClosed, $badgeStatusActive ....
*/
$badgeStatus0 = '#d9d9d9'; // draft
$badgeStatus1 = '#faad14'; // validated
$badgeStatus1b = '#faad14'; // validated
$badgeStatus2 = '#595959'; // approved
$badgeStatus3 = '#faad14';
$badgeStatus4 = '#52c41a'; // Color ok
$badgeStatus4b = '#52c41a'; // Color ok
$badgeStatus5 = '#d9d9d9';
$badgeStatus6 = '#d9d9d9';
$badgeStatus7 = '#52c41a';
$badgeStatus8 = '#ff4d4f';
$badgeStatus9 = '#f5f5f5';
$badgeStatus10 = '#ff4d4f';
$badgeStatus11 = '#52c41a';

// status color adjustment for color blind
$colorblind_deuteranopes_badgeStatus4 = $colorblind_deuteranopes_badgeStatus7 = $colorblind_deuteranopes_badgeSuccess; //! text color black
$colorblind_deuteranopes_badgeStatus_textColor4 = $colorblind_deuteranopes_badgeStatus_textColor7 = '#000';
$colorblind_deuteranopes_badgeStatus1 = $colorblind_deuteranopes_badgeWarning;
$colorblind_deuteranopes_badgeStatus_textColor1 = '#000';
