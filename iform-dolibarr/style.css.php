<?php
/* Copyright (C) 2004-2017	Laurent Destailleur		<eldy@users.sourceforge.net>
 * Copyright (C) 2006		Rodolphe Quiedeville	<rodolphe@quiedeville.org>
 * Copyright (C) 2007-2017	Regis Houssin			<regis.houssin@inodbox.com>
 * Copyright (C) 2011		Philippe Grand			<philippe.grand@atoo-net.com>
 * Copyright (C) 2012		Juanjo Menent			<jmenent@2byte.es>
 * Copyright (C) 2018       Ferran Marcet           <fmarcet@2byte.es>
 * Copyright (C) 2021-2023  Anthony Berton          <anthony.berton@bb2a.fr>
 * Copyright (C) 2024		MDW							<mdeweerd@users.noreply.github.com>
 * Copyright (C) 2024       Frédéric France         <frederic.france@free.fr>
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

/**
 *		\file       htdocs/theme/iform-dolibarr/style.css.php
 *		\brief      File for CSS style sheet Eldy
 */

//if (! defined('NOREQUIREUSER')) define('NOREQUIREUSER','1');	// Not disabled because need to load personalized language
//if (! defined('NOREQUIREDB'))   define('NOREQUIREDB','1');	// Not disabled to increase speed. Language code is found on url.
if (!defined('NOREQUIRESOC')) {
	define('NOREQUIRESOC', '1');
}
//if (! defined('NOREQUIRETRAN')) define('NOREQUIRETRAN','1');	// Not disabled because need to do translations
if (!defined('NOCSRFCHECK')) {
	define('NOCSRFCHECK', 1);
}
if (!defined('NOTOKENRENEWAL')) {
	define('NOTOKENRENEWAL', 1);
}
if (!defined('NOLOGIN')) {
	define('NOLOGIN', 1); // File must be accessed by logon page so without login.
}
//if (!defined('NOREQUIREMENU'))   define('NOREQUIREMENU',1);  	// We load menu manager class (note that object loaded may have wrong content because NOLOGIN is set and some values depends on login)
if (!defined('NOREQUIREHTML')) {
	define('NOREQUIREHTML', 1);
}
if (!defined('NOREQUIREAJAX')) {
	define('NOREQUIREAJAX', '1');
}


define('ISLOADEDBYSTEELSHEET', '1');


require __DIR__.'/theme_vars.inc.php';
if (defined('THEME_ONLY_CONSTANT')) {
	return;
}
// Types from theme_vars
'
@phan-var-force string $badgeWarning
@phan-var-force string $butactionbg
@phan-var-force string $colorbackbody
@phan-var-force string $colorbackhmenu1
@phan-var-force string $colorbacklinebreak
@phan-var-force string $colorbacklineimpair1
@phan-var-force string $colorbacklineimpair2
@phan-var-force string $colorbacklinepair1
@phan-var-force string $colorbacklinepair2
@phan-var-force string $colorbacklinepairchecked
@phan-var-force string $colorbacklinepairhover
@phan-var-force string $colorbacktabactive
@phan-var-force string $colorbacktabcard1
@phan-var-force string $colorbacktitle1
@phan-var-force string $colorbackvmenu1
@phan-var-force string $colorblind_deuteranopes_textSuccess
@phan-var-force string $colorblind_deuteranopes_textWarning
@phan-var-force string $colortext
@phan-var-force string $colortextlink
@phan-var-force string $colortexttitle
@phan-var-force string $colortexttitlelink
@phan-var-force string $colortexttitlenotab
@phan-var-force string $colortexttitlenotab2
@phan-var-force string $colortopbordertitle1
@phan-var-force string $fontsize
@phan-var-force string $textDanger
@phan-var-force string $textSuccess
@phan-var-force string $textWarning
@phan-var-force string $textbutaction
@phan-var-force string $toolTipBgColor
@phan-var-force string $toolTipFontColor
@phan-var-force string $topMenuFontSize
';


session_cache_limiter('public');

require_once __DIR__.'/../../main.inc.php'; // __DIR__ allow this script to be included in custom themes
require_once DOL_DOCUMENT_ROOT.'/core/lib/functions2.lib.php';
/**
 * @var Conf $conf
 * @var DoliDB $db
 * @var Translate $langs
 * @var User $user
 *
 * @var string $badgeWarning
 * @var string $butactionbg
 * @var string $colorbackbody
 * @var string $colorbackhmenu1
 * @var string $colorbacklinebreak
 * @var string $colorbacklineimpair1
 * @var string $colorbacklineimpair2
 * @var string $colorbacklinepair1
 * @var string $colorbacklinepair2
 * @var string $colorbacklinepairchecked
 * @var string $colorbacklinepairhover
 * @var string $colorbacktabactive
 * @var string $colorbacktabcard1
 * @var string $colorbacktitle1
 * @var string $colorbackvmenu1
 * @var string $colorblind_deuteranopes_textSuccess
 * @var string $colorblind_deuteranopes_textWarning
 * @var string $colortext
 * @var string $colortextlink
 * @var string $colortexttitle
 * @var string $colortexttitlelink
 * @var string $colortexttitlenotab
 * @var string $colortexttitlenotab2
 * @var string $colortopbordertitle1
 * @var string $fontsize
 * @var string $textDanger
 * @var string $textSuccess
 * @var string $textWarning
 * @var string $textbutaction
 * @var string $toolTipBgColor
 * @var string $toolTipFontColor
 * @var string $topMenuFontSize
 */
// Load user to have $user->conf loaded (not done into main because of NOLOGIN constant defined)
// and permission, so we can later calculate number of top menu ($nbtopmenuentries) according to user profile.
if (empty($user->id) && !empty($_SESSION['dol_login'])) {
	$user->fetch(0, $_SESSION['dol_login'], '', 1);
	$user->loadRights();

	// Reload menu now we have the good user (and we need the good menu to have ->showmenu('topnb') correct.
	// @phan-suppress-next-line PhanRedefinedClassReference
	$menumanager = new MenuManager($db, empty($user->socid) ? 0 : 1);
	// @phan-suppress-next-line PhanRedefinedClassReference
	$menumanager->loadMenu();
}


// Define css type
top_httphead('text/css');
// Important: Following code is to avoid page request by browser and PHP CPU at each Dolibarr page access.
if (empty($dolibarr_nocache)) {
	header('Cache-Control: max-age=10800, public, must-revalidate');
} else {
	header('Cache-Control: no-cache');
}

if (GETPOST('theme', 'aZ09')) {
	$conf->theme = GETPOST('theme', 'aZ09'); // If theme was forced on URL
}
if (GETPOST('lang', 'aZ09')) {
	$langs->setDefaultLang(GETPOST('lang', 'aZ09')); // If language was forced on URL
}
if (GETPOSTISSET('THEME_DARKMODEENABLED')) {
	$conf->global->THEME_DARKMODEENABLED = GETPOSTINT('THEME_DARKMODEENABLED'); // If darkmode was forced on URL
}

$langs->load("main", 0, 1);
$right = ($langs->trans("DIRECTION") == 'rtl' ? 'left' : 'right');
$left = ($langs->trans("DIRECTION") == 'rtl' ? 'right' : 'left');

$path = ''; // This value may be used in future for external module to overwrite theme
$theme = 'iform-dolibarr'; // Value of theme
if (getDolGlobalString('MAIN_OVERWRITE_THEME_RES')) {
	$path = '/' . getDolGlobalString('MAIN_OVERWRITE_THEME_RES');
	$theme = getDolGlobalString('MAIN_OVERWRITE_THEME_RES');
}

// Define image path files and other constants

//$fontlist='helvetica, verdana, arial, sans-serif';
//$fontlist='"open sans", "Helvetica Neue", Helvetica, Arial, sans-serif';

$fontlist = 'arial,tahoma,verdana,helvetica';
if (getDolGlobalString('THEME_FONT_FAMILY')) {
	$fontlist = getDolGlobalString('THEME_FONT_FAMILY').', '.$fontlist;
}

$img_head = '';
$img_button = dol_buildpath($path.'/theme/'.$theme.'/img/button_bg.png', 1);
$dol_hide_topmenu = $conf->dol_hide_topmenu;
$dol_hide_leftmenu = $conf->dol_hide_leftmenu;
$dol_optimize_smallscreen = $conf->dol_optimize_smallscreen;
$dol_no_mouse_hover = $conf->dol_no_mouse_hover;

//$conf->global->THEME_ELDY_ENABLE_PERSONALIZED=0;
//$user->conf->THEME_ELDY_ENABLE_PERSONALIZED=0;
//var_dump($user->conf->THEME_ELDY_RGB);

$useboldtitle = getDolGlobalInt('THEME_ELDY_USEBOLDTITLE');
$userborderontable = getDolGlobalInt('THEME_ELDY_USEBORDERONTABLE');
$borderwidth = 1;

// Case of option always editable
if (!isset($conf->global->THEME_ELDY_BACKBODY)) {
	$conf->global->THEME_ELDY_BACKBODY = $colorbackbody;
}
if (!isset($conf->global->THEME_ELDY_TOPMENU_BACK1)) {
	$conf->global->THEME_ELDY_TOPMENU_BACK1 = $colorbackhmenu1;
}
if (!isset($conf->global->THEME_ELDY_VERMENU_BACK1)) {
	$conf->global->THEME_ELDY_VERMENU_BACK1 = $colorbackvmenu1;
}
if (!isset($conf->global->THEME_ELDY_BACKTITLE1)) {
	$conf->global->THEME_ELDY_BACKTITLE1 = $colorbacktitle1;
}
if (!isset($conf->global->THEME_ELDY_USE_HOVER)) {
	$conf->global->THEME_ELDY_USE_HOVER = $colorbacklinepairhover;
}
if (!isset($conf->global->THEME_ELDY_USE_CHECKED)) {
	$conf->global->THEME_ELDY_USE_CHECKED = $colorbacklinepairchecked;
}
if (!isset($conf->global->THEME_ELDY_LINEBREAK)) {
	$conf->global->THEME_ELDY_LINEBREAK = $colorbacklinebreak;
}
if (!isset($conf->global->THEME_ELDY_TEXTTITLENOTAB)) {
	$conf->global->THEME_ELDY_TEXTTITLENOTAB = $colortexttitlenotab;
}
if (!isset($conf->global->THEME_ELDY_TEXTLINK)) {
	$conf->global->THEME_ELDY_TEXTLINK = $colortextlink;
}
if (!isset($conf->global->THEME_ELDY_BTNACTION)) {
	$conf->global->THEME_ELDY_BTNACTION = $butactionbg;
}
if (!isset($conf->global->THEME_ELDY_TEXTBTNACTION)) {
	$conf->global->THEME_ELDY_TEXTBTNACTION = $textbutaction;
}
// Case of option editable only if option THEME_ELDY_ENABLE_PERSONALIZED is on
if (!getDolGlobalString('THEME_ELDY_ENABLE_PERSONALIZED')) {
	$conf->global->THEME_ELDY_BACKTABCARD1 = '255,255,255'; // card
	$conf->global->THEME_ELDY_BACKTABACTIVE = '234,234,234';
	$conf->global->THEME_ELDY_TEXT = '0,0,0';
	$conf->global->THEME_ELDY_FONT_SIZE1 = $fontsize;
	$conf->global->THEME_ELDY_FONT_SIZE2 = '0.75em';
}

// This theme deliberately keeps Eldy's layout code but freezes the iForm color set.
$conf->global->THEME_ELDY_ENABLE_PERSONALIZED = 0;
if (is_object($user)) {
	$user->conf->THEME_ELDY_ENABLE_PERSONALIZED = 0;
}
$conf->global->THEME_ELDY_BACKBODY = $colorbackbody;
$conf->global->THEME_ELDY_TOPMENU_BACK1 = $colorbackhmenu1;
$conf->global->THEME_ELDY_VERMENU_BACK1 = $colorbackvmenu1;
$conf->global->THEME_ELDY_TOPBORDER_TITLE1 = $colortopbordertitle1;
$conf->global->THEME_ELDY_BACKTITLE1 = $colorbacktitle1;
$conf->global->THEME_ELDY_BACKTABCARD1 = $colorbacktabcard1;
$conf->global->THEME_ELDY_BACKTABACTIVE = $colorbacktabactive;
$conf->global->THEME_ELDY_LINEIMPAIR1 = $colorbacklineimpair1;
$conf->global->THEME_ELDY_LINEIMPAIR2 = $colorbacklineimpair2;
$conf->global->THEME_ELDY_LINEPAIR1 = $colorbacklinepair1;
$conf->global->THEME_ELDY_LINEPAIR2 = $colorbacklinepair2;
$conf->global->THEME_ELDY_USE_HOVER = $colorbacklinepairhover;
$conf->global->THEME_ELDY_USE_CHECKED = $colorbacklinepairchecked;
$conf->global->THEME_ELDY_LINEBREAK = $colorbacklinebreak;
$conf->global->THEME_ELDY_TEXTTITLENOTAB = $colortexttitlenotab;
$conf->global->THEME_ELDY_TEXTTITLE = $colortexttitle;
$conf->global->THEME_ELDY_TEXTTITLELINK = $colortexttitlelink;
$conf->global->THEME_ELDY_TEXT = $colortext;
$conf->global->THEME_ELDY_TEXTLINK = $colortextlink;
$conf->global->THEME_ELDY_BTNACTION = $butactionbg;
$conf->global->THEME_ELDY_TEXTBTNACTION = $textbutaction;
$conf->global->THEME_ELDY_FONT_SIZE1 = $fontsize;
$conf->global->THEME_ELDY_FONT_SIZE2 = $fontsizesmaller;

// Case of option availables only if THEME_ELDY_ENABLE_PERSONALIZED is on
$colorbackbody        = empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED) ? (!getDolGlobalString('THEME_ELDY_BACKBODY') ? $colorbackbody : $conf->global->THEME_ELDY_BACKBODY) : (empty($user->conf->THEME_ELDY_BACKBODY) ? $colorbackbody : $user->conf->THEME_ELDY_BACKBODY);
$colorbackhmenu1      = empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED) ? (!getDolGlobalString('THEME_ELDY_TOPMENU_BACK1') ? $colorbackhmenu1 : $conf->global->THEME_ELDY_TOPMENU_BACK1) : (empty($user->conf->THEME_ELDY_TOPMENU_BACK1) ? $colorbackhmenu1 : $user->conf->THEME_ELDY_TOPMENU_BACK1);
$colorbackvmenu1      = empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED) ? (!getDolGlobalString('THEME_ELDY_VERMENU_BACK1') ? $colorbackvmenu1 : $conf->global->THEME_ELDY_VERMENU_BACK1) : (empty($user->conf->THEME_ELDY_VERMENU_BACK1) ? $colorbackvmenu1 : $user->conf->THEME_ELDY_VERMENU_BACK1);
$colortopbordertitle1 = empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED) ? (!getDolGlobalString('THEME_ELDY_TOPBORDER_TITLE1') ? $colortopbordertitle1 : $conf->global->THEME_ELDY_TOPBORDER_TITLE1) : (empty($user->conf->THEME_ELDY_TOPBORDER_TITLE1) ? $colortopbordertitle1 : $user->conf->THEME_ELDY_TOPBORDER_TITLE1);
$colorbacktitle1      = empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED) ? (!getDolGlobalString('THEME_ELDY_BACKTITLE1') ? $colorbacktitle1 : $conf->global->THEME_ELDY_BACKTITLE1) : (empty($user->conf->THEME_ELDY_BACKTITLE1) ? $colorbacktitle1 : $user->conf->THEME_ELDY_BACKTITLE1);
$colorbacktabcard1    = empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED) ? (!getDolGlobalString('THEME_ELDY_BACKTABCARD1') ? $colorbacktabcard1 : $conf->global->THEME_ELDY_BACKTABCARD1) : (empty($user->conf->THEME_ELDY_BACKTABCARD1) ? $colorbacktabcard1 : $user->conf->THEME_ELDY_BACKTABCARD1);
$colorbacktabactive   = empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED) ? (!getDolGlobalString('THEME_ELDY_BACKTABACTIVE') ? $colorbacktabactive : $conf->global->THEME_ELDY_BACKTABACTIVE) : (empty($user->conf->THEME_ELDY_BACKTABACTIVE) ? $colorbacktabactive : $user->conf->THEME_ELDY_BACKTABACTIVE);
$colorbacklineimpair1 = empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED) ? (!getDolGlobalString('THEME_ELDY_LINEIMPAIR1') ? $colorbacklineimpair1 : $conf->global->THEME_ELDY_LINEIMPAIR1) : (empty($user->conf->THEME_ELDY_LINEIMPAIR1) ? $colorbacklineimpair1 : $user->conf->THEME_ELDY_LINEIMPAIR1);
$colorbacklineimpair2 = empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED) ? (!getDolGlobalString('THEME_ELDY_LINEIMPAIR2') ? $colorbacklineimpair2 : $conf->global->THEME_ELDY_LINEIMPAIR2) : (empty($user->conf->THEME_ELDY_LINEIMPAIR2) ? $colorbacklineimpair2 : $user->conf->THEME_ELDY_LINEIMPAIR2);
$colorbacklinepair1   = empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED) ? (!getDolGlobalString('THEME_ELDY_LINEPAIR1') ? $colorbacklinepair1 : $conf->global->THEME_ELDY_LINEPAIR1) : (empty($user->conf->THEME_ELDY_LINEPAIR1) ? $colorbacklinepair1 : $user->conf->THEME_ELDY_LINEPAIR1);
$colorbacklinepair2   = empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED) ? (!getDolGlobalString('THEME_ELDY_LINEPAIR2') ? $colorbacklinepair2 : $conf->global->THEME_ELDY_LINEPAIR2) : (empty($user->conf->THEME_ELDY_LINEPAIR2) ? $colorbacklinepair2 : $user->conf->THEME_ELDY_LINEPAIR2);
$colorbacklinebreak   = empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED) ? (!getDolGlobalString('THEME_ELDY_LINEBREAK') ? $colorbacklinebreak : $conf->global->THEME_ELDY_LINEBREAK) : (empty($user->conf->THEME_ELDY_LINEBREAK) ? $colorbacklinebreak : $user->conf->THEME_ELDY_LINEBREAK);
$colortexttitlenotab  = empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED) ? (!getDolGlobalString('THEME_ELDY_TEXTTITLENOTAB') ? $colortexttitlenotab : getDolGlobalString('THEME_ELDY_TEXTTITLENOTAB')) : (empty($user->conf->THEME_ELDY_TEXTTITLENOTAB) ? $colortexttitlenotab : $user->conf->THEME_ELDY_TEXTTITLENOTAB);
$colortexttitle       = empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED) ? (!getDolGlobalString('THEME_ELDY_TEXTTITLE') ? $colortexttitle : getDolGlobalString('THEME_ELDY_TEXTTITLE')) : (empty($user->conf->THEME_ELDY_TEXTTITLE) ? $colortexttitle : $user->conf->THEME_ELDY_TEXTTITLE);
$colortexttitlelink   = empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED) ? (!getDolGlobalString('THEME_ELDY_TEXTTITLELINK') ? $colortexttitlelink : getDolGlobalString('THEME_ELDY_TEXTTITLELINK')) : (empty($user->conf->THEME_ELDY_TEXTTITLELINK) ? $colortexttitlelink : $user->conf->THEME_ELDY_TEXTTITLELINK);
$colortext            = empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED) ? (!getDolGlobalString('THEME_ELDY_TEXT') ? $colortext : getDolGlobalString('THEME_ELDY_TEXT')) : (empty($user->conf->THEME_ELDY_TEXT) ? $colortext : $user->conf->THEME_ELDY_TEXT);
$colortextlink        = empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED) ? (!getDolGlobalString('THEME_ELDY_TEXTLINK') ? $colortextlink : getDolGlobalString('THEME_ELDY_TEXTLINK')) : (empty($user->conf->THEME_ELDY_TEXTLINK) ? $colortextlink : $user->conf->THEME_ELDY_TEXTLINK);
$colortextlinkHsla    = colorHexToHsl($colortextlink, false, true);

$butactionbg       	  = empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED) ? (!getDolGlobalString('THEME_ELDY_BTNACTION') ? $butactionbg : getDolGlobalString('THEME_ELDY_BTNACTION')) : (empty($user->conf->THEME_ELDY_BTNACTION) ? $butactionbg : $user->conf->THEME_ELDY_BTNACTION);
$textbutaction        = empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED) ? (!getDolGlobalString('THEME_ELDY_TEXTBTNACTION') ? $textbutaction : getDolGlobalString('THEME_ELDY_TEXTBTNACTION')) : (empty($user->conf->THEME_ELDY_TEXTBTNACTION) ? $textbutaction : $user->conf->THEME_ELDY_TEXTBTNACTION);
$fontsize             = empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED) ? (!getDolGlobalString('THEME_ELDY_FONT_SIZE1') ? $fontsize : getDolGlobalString('THEME_ELDY_FONT_SIZE1')) : (empty($user->conf->THEME_ELDY_FONT_SIZE1) ? $fontsize : $user->conf->THEME_ELDY_FONT_SIZE1);
$fontsizesmaller      = empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED) ? (!getDolGlobalString('THEME_ELDY_FONT_SIZE2') ? $fontsize : getDolGlobalString('THEME_ELDY_FONT_SIZE2')) : (empty($user->conf->THEME_ELDY_FONT_SIZE2) ? $fontsize : $user->conf->THEME_ELDY_FONT_SIZE2);
$heightrow			  = empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED) ? (!getDolGlobalString('THEME_ELDY_USECOMOACTROW') ? '155%' : '300%') : (empty($user->conf->THEME_ELDY_USECOMOACTROW) ? '155%' : '300%');
// Hover color
$colorbacklinepairhover = ((!isset($conf->global->THEME_ELDY_USE_HOVER) || getDolGlobalString('THEME_ELDY_USE_HOVER') === '255,255,255') ? '' : (getDolGlobalString('THEME_ELDY_USE_HOVER') === '1' ? 'e6edf0' : getDolGlobalString('THEME_ELDY_USE_HOVER')));
$colorbacklinepairchecked = ((!isset($conf->global->THEME_ELDY_USE_CHECKED) || getDolGlobalString('THEME_ELDY_USE_CHECKED') === '255,255,255') ? '' : (getDolGlobalString('THEME_ELDY_USE_CHECKED') === '1' ? 'e6edf0' : getDolGlobalString('THEME_ELDY_USE_CHECKED')));
if (!empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED)) {
	$colorbacklinepairhover = ((!isset($user->conf->THEME_ELDY_USE_HOVER) || $user->conf->THEME_ELDY_USE_HOVER === '0') ? '' : ($user->conf->THEME_ELDY_USE_HOVER === '1' ? 'e6edf0' : $user->conf->THEME_ELDY_USE_HOVER));
	$colorbacklinepairchecked = ((!isset($user->conf->THEME_ELDY_USE_CHECKED) || $user->conf->THEME_ELDY_USE_CHECKED === '0') ? '' : ($user->conf->THEME_ELDY_USE_CHECKED === '1' ? 'e6edf0' : $user->conf->THEME_ELDY_USE_CHECKED));
}


// Set text color to black or white
$colorbackhmenu1 = implode(',', colorStringToArray($colorbackhmenu1)); // Normalize value to 'x,y,z'
$tmppart = explode(',', $colorbackhmenu1);
$tmpval = (!empty($tmppart[0]) ? $tmppart[0] : 0) + (!empty($tmppart[1]) ? $tmppart[1] : 0) + (!empty($tmppart[2]) ? $tmppart[2] : 0);
if ($tmpval <= 460) {
	$colortextbackhmenu = 'FFFFFF';
} else {
	$colortextbackhmenu = '000000';
}

$colorbackvmenu1 = implode(',', colorStringToArray($colorbackvmenu1)); // Normalize value to 'x,y,z'
$tmppart = explode(',', $colorbackvmenu1);
$tmpval = (!empty($tmppart[0]) ? $tmppart[0] : 0) + (!empty($tmppart[1]) ? $tmppart[1] : 0) + (!empty($tmppart[2]) ? $tmppart[2] : 0);
if ($tmpval <= 460) {
	$colortextbackvmenu = 'FFFFFF';
} else {
	$colortextbackvmenu = '222222';
}

$colortopbordertitle1 = implode(',', colorStringToArray($colortopbordertitle1)); // Normalize value to 'x,y,z'

$colorbacktitle1 = implode(',', colorStringToArray($colorbacktitle1)); // Normalize value to 'x,y,z'
$tmppart = explode(',', $colorbacktitle1);
if ($colortexttitle == '') {
	$tmpval = (!empty($tmppart[0]) ? $tmppart[0] : 0) + (!empty($tmppart[1]) ? $tmppart[1] : 0) + (!empty($tmppart[2]) ? $tmppart[2] : 0);
	if ($tmpval <= 460) {
		$colortexttitle = 'FFFFFF';
		$colorshadowtitle = '888888';
	} else {
		$colortexttitle = '000000';
		$colorshadowtitle = 'FFFFFF';
	}
} else {
	$colorshadowtitle = '888888';
}

$colorbacktabcard1 = implode(',', colorStringToArray($colorbacktabcard1)); // Normalize value to 'x,y,z'
$tmppart = explode(',', $colorbacktabcard1);
$tmpval = (!empty($tmppart[0]) ? $tmppart[0] : 0) + (!empty($tmppart[1]) ? $tmppart[1] : 0) + (!empty($tmppart[2]) ? $tmppart[2] : 0);
if ($tmpval <= 460) {
	$colortextbacktab = 'FFFFFF';
} else {
	$colortextbacktab = '000000';
}


// Format color value to match expected format (may be 'FFFFFF' or '255,255,255')
$colorbackhmenu1 = implode(',', colorStringToArray($colorbackhmenu1));
$colorbackvmenu1 = implode(',', colorStringToArray($colorbackvmenu1));
$colorbacktitle1 = implode(',', colorStringToArray($colorbacktitle1));
$colorbacktabcard1 = implode(',', colorStringToArray($colorbacktabcard1));
$colorbacktabactive = implode(',', colorStringToArray($colorbacktabactive));
$colorbacklineimpair1 = implode(',', colorStringToArray($colorbacklineimpair1));
$colorbacklineimpair2 = implode(',', colorStringToArray($colorbacklineimpair2));
$colorbacklinepair1 = implode(',', colorStringToArray($colorbacklinepair1));
$colorbacklinepair2 = implode(',', colorStringToArray($colorbacklinepair2));
if ($colorbacklinepairhover != '') {
	$colorbacklinepairhover = implode(',', colorStringToArray($colorbacklinepairhover));
}
if ($colorbacklinepairchecked != '') {
	$colorbacklinepairchecked = implode(',', colorStringToArray($colorbacklinepairchecked));
}
$colorbackbody = implode(',', colorStringToArray($colorbackbody));
$colortexttitlenotab = implode(',', colorStringToArray($colortexttitlenotab));
$colortexttitle = implode(',', colorStringToArray($colortexttitle));
$colortext = implode(',', colorStringToArray($colortext));
$colortextlink = implode(',', colorStringToArray($colortextlink));

// @phan-suppress-next-line PhanRedefinedClassReference
$nbtopmenuentries = $menumanager->showmenu('topnb');
$nbtopmenuentriesreal = $nbtopmenuentries;
if ($conf->browser->layout == 'phone') {
	$nbtopmenuentries = max($nbtopmenuentries, 10);
}

$minwidthtmenu = 66; /* minimum width for one top menu entry */
$heightmenu = 50; /* height of top menu, part with image */
$heightmenu2 = 49; /* height of top menu, part with login  */
$disableimages = 0;
$maxwidthloginblock = 180;
if (getDolGlobalInt('THEME_TOPMENU_DISABLE_IMAGE') == 1 || !empty($user->conf->MAIN_OPTIMIZEFORTEXTBROWSER)) {
	$disableimages = 1;
	$maxwidthloginblock += 50;
	$minwidthtmenu = 0;
}

if (getDolGlobalString('MAIN_USE_TOP_MENU_QUICKADD_DROPDOWN')) {
	$maxwidthloginblock += 55;
}
if (getDolGlobalString('MAIN_USE_TOP_MENU_SEARCH_DROPDOWN')) {
	$maxwidthloginblock += 55;
}
if (isModEnabled('bookmark')) {
	$maxwidthloginblock += 55;
}


print '/*'."\n";
print 'colorbackbody='.$colorbackbody."\n";
print 'colorbackvmenu1='.$colorbackvmenu1."\n";
print 'colorbackhmenu1='.$colorbackhmenu1."\n";
print 'colorbacktitle1='.$colorbacktitle1."\n";
print 'colorbacklineimpair1='.$colorbacklineimpair1."\n";
print 'colorbacklineimpair2='.$colorbacklineimpair2."\n";
print 'colorbacklinepair1='.$colorbacklinepair1."\n";
print 'colorbacklinepair2='.$colorbacklinepair2."\n";
print 'colorbacklinepairhover='.$colorbacklinepairhover."\n";
print 'colorbacklinepairchecked='.$colorbacklinepairchecked."\n";
print '$colortexttitlenotab='.$colortexttitlenotab."\n";
print '$colortexttitle='.$colortexttitle."\n";
print '$colortext='.$colortext."\n";
print '$colortextlink='.$colortextlink."\n";
print '$colortextbackhmenu='.$colortextbackhmenu."\n";
print '$colortextbackvmenu='.$colortextbackvmenu."\n";
print 'dol_hide_topmenu='.$dol_hide_topmenu."\n";
print 'dol_hide_leftmenu='.$dol_hide_leftmenu."\n";
print 'dol_optimize_smallscreen='.$dol_optimize_smallscreen."\n";
print 'dol_no_mouse_hover='.$dol_no_mouse_hover."\n";
print 'dol_screenwidth='.(empty($_SESSION['dol_screenwidth']) ? '' : $_SESSION['dol_screenwidth'])."\n";
print 'dol_screenheight='.(empty($_SESSION['dol_screenheight']) ? '' : $_SESSION['dol_screenheight'])."\n";
print 'fontsize='.$fontsize."\n";
print 'nbtopmenuentries='.$nbtopmenuentries."\n";
print 'fontsizesmaller='.$fontsizesmaller."\n";
print 'topMenuFontSize='.$topMenuFontSize."\n";
print 'toolTipBgColor='.$toolTipBgColor."\n";
print 'toolTipFontColor='.$toolTipFontColor."\n";
print 'getDolGlobalString("THEME_SATURATE_RATIO")='.getDolGlobalString('THEME_SATURATE_RATIO')." (must be between 0 and 1)\n";
print '*/'."\n";


// Include the global.inc.php that include the badges, btn, info-box, dropdown, progress...
require __DIR__.'/global.inc.php';

?>

/* iForm skin layer: visual polish over Eldy's native layout. */
:root {
	--iform-accent: #0969da;
	--iform-accent-hover: #0757b8;
	--iform-line: #d0d7de;
	--iform-line-soft: #e5e7eb;
	--iform-bg: #f6f8fa;
	--iform-surface: #ffffff;
	--iform-text: #1f2328;
	--iform-muted: #636e7b;
	--iform-danger: #cf222e;
	--iform-danger-bg: #ffebe9;
	--iform-danger-hover-bg: #ffd8d3;
	--iform-neutral-bg: #ffffff;
	--iform-neutral-hover-bg: #f6f8fa;
	--iform-refused-bg: #f6f8fa;
	--iform-focus-ring: rgba(31, 35, 40, 0.12);
	--inputbordercolor: rgba(208, 215, 222, 0.95);
	--colorboxstatsborder: #d0d7de;
	--colorboxiconbg: #f6f8fa;
	--fieldrequiredcolor: #cf222e;
}

body {
	color: var(--iform-text);
	background: var(--iform-bg);
}

a:link,
a:visited,
a:hover,
a:active,
.classlink {
	color: var(--iform-accent);
}

#id-top {
	border-bottom: 1px solid var(--iform-line);
	box-shadow: 0 1px 0 rgba(31, 35, 40, 0.04);
}

#id-top li.tmenusel::after,
#id-top li.tmenu:hover::after {
	content: none !important;
	display: none !important;
	border: 0 !important;
}

#id-top li.tmenusel div.tmenucenter,
#id-top li.tmenu:hover div.tmenucenter {
	background: #eef6ff;
	border-radius: 5px;
}

#id-top li.tmenusel,
#id-top li.tmenusel a,
#id-top li.tmenusel span,
#id-top li.tmenusel .fas,
#id-top li.tmenusel .far,
#id-top li.tmenusel .fa {
	color: var(--iform-accent) !important;
}

#id-left {
	border-right: 1px solid var(--iform-line);
	box-shadow: none;
}

.menu_titre,
.vmenu_titre {
	color: var(--iform-text);
}

.liste_titre,
tr.liste_titre,
table.border tr.liste_titre,
table.noborder tr.liste_titre {
	background-color: #f6f8fa;
	color: var(--iform-text);
}

tr.oddeven:hover,
tr.pair:hover,
tr.impair:hover {
	background-color: #eef6ff;
}

input:not([type="image"]):not([type="checkbox"]):not([type="radio"]),
textarea,
select,
.select2-container--default .select2-selection--single,
.select2-container--default .select2-selection--multiple {
	border-color: var(--iform-line);
	border-radius: 5px;
}

input:focus:not(.noborderfocus):not(.inputsearch_dropdownselectedfields):not(.button):not(.buttonwebsite):not(.buttonreset):not(.select2-search__field):not(#top-bookmark-search-input):not(.search_component_input):not(.input-nobottom),
select:focus,
textarea:focus,
.select2-container--open [aria-expanded="false"].select2-selection--single,
.select2-container--focus span.selection span.select2-selection:not(.noborderfocus):not(.massactionselect) {
	border-color: var(--iform-accent) !important;
	box-shadow: 0 0 0 2px rgba(9, 105, 218, 0.14);
}

#mainbody .butAction:not(.butActionDelete):not(.butActionRefused),
#mainbody .butAction:not(.butActionDelete):not(.butActionRefused):link,
#mainbody .butAction:not(.butActionDelete):not(.butActionRefused):visited,
#mainbody input.button:not(.buttonDelete):not(.bordertransp):not(.buttonpayment):not(:disabled),
#mainbody button.button:not(.buttonDelete):not(.bordertransp):not(.buttonpayment):not(:disabled),
#mainbody a.button:not(.buttonDelete):not(.bordertransp):not(.buttonpayment) {
	background: var(--iform-accent) !important;
	background-image: none !important;
	border-color: var(--iform-accent) !important;
	color: #fff !important;
}

#mainbody .butAction:not(.butActionDelete):not(.butActionRefused):hover,
#mainbody input.button:not(.buttonDelete):not(.bordertransp):not(.buttonpayment):not(:disabled):hover,
#mainbody button.button:not(.buttonDelete):not(.bordertransp):not(.buttonpayment):not(:disabled):hover,
#mainbody a.button:not(.buttonDelete):not(.bordertransp):not(.buttonpayment):hover {
	background: var(--iform-accent-hover) !important;
	background-image: none !important;
	border-color: var(--iform-accent-hover) !important;
	box-shadow: none !important;
	color: #fff !important;
}

#mainbody input.button.buttongen:not(:disabled),
#mainbody button.button.buttongen:not(:disabled),
#mainbody input.buttongen:not(:disabled),
#mainbody button.buttongen:not(:disabled) {
	background: var(--iform-accent) !important;
	background-image: none !important;
	border: 1px solid var(--iform-accent) !important;
	color: #fff !important;
}

#mainbody input.button.buttongen:not(:disabled):hover,
#mainbody button.button.buttongen:not(:disabled):hover,
#mainbody input.buttongen:not(:disabled):hover,
#mainbody button.buttongen:not(:disabled):hover {
	background: var(--iform-accent-hover) !important;
	background-image: none !important;
	border-color: var(--iform-accent-hover) !important;
	box-shadow: none !important;
	color: #fff !important;
}

#mainbody .butActionDelete,
#mainbody .butActionDelete:link,
#mainbody .butActionDelete:visited,
#mainbody .butActionDelete:hover,
#mainbody .butActionDelete:active,
#mainbody .buttonDelete {
	background: var(--iform-danger-bg) !important;
	background-image: none !important;
	border: 1px solid #ff8182 !important;
	color: var(--iform-danger) !important;
	box-shadow: none !important;
}

#mainbody .butActionDelete:hover,
#mainbody .buttonDelete:hover:not(:disabled) {
	background: var(--iform-danger-hover-bg) !important;
	background-image: none !important;
	border-color: #ff8182 !important;
	color: #a40e26 !important;
	box-shadow: none !important;
}

#mainbody .butActionRefused,
#mainbody .butActionRefused:link,
#mainbody .butActionRefused:visited,
#mainbody .butActionRefused:hover,
#mainbody .butActionRefused:active,
#mainbody .butActionNewRefused,
#mainbody .buttonRefused,
#mainbody .button:disabled,
#mainbody .buttonDelete:disabled,
#mainbody .button.disabled,
#mainbody .buttonDelete.disabled {
	background: var(--iform-refused-bg) !important;
	background-image: none !important;
	border: 1px solid var(--iform-line) !important;
	color: var(--iform-muted) !important;
	opacity: 1 !important;
	box-shadow: none !important;
	cursor: not-allowed !important;
}

#mainbody .butAction:focus,
#mainbody .butActionDelete:focus,
#mainbody .butActionRefused:focus,
#mainbody .button:focus,
#mainbody .buttonDelete:focus {
	outline: none !important;
	box-shadow: 0 0 0 2px var(--iform-focus-ring) !important;
}

#mainbody .butActionRefused:focus,
#mainbody .button:disabled:focus,
#mainbody .buttonDelete:disabled:focus {
	box-shadow: none !important;
}

.button,
.buttonDelete,
.butAction,
.butActionRefused,
.butActionDelete,
.btnTitle,
.select2-selection,
.dropdown-toggle {
	border-radius: 5px;
}

table.border,
table.noborder,
table.liste,
.div-table-responsive,
.div-table-responsive-no-min {
	border-color: var(--iform-line-soft);
}

.badge-status,
.status,
.statusref,
.statusrefsupplier,
.statusdoc {
	border-radius: 999px;
}

.jnotify-container {
	right: 16px !important;
	left: auto !important;
	width: auto !important;
	min-width: min(320px, calc(100vw - 32px));
	max-width: min(520px, calc(100vw - 32px));
	padding: 10px 0 0 !important;
}

.jnotify-container .jnotify-notification {
	max-width: min(520px, calc(100vw - 32px));
	background: var(--iform-surface) !important;
	border: 1px solid var(--iform-line-soft);
	border-radius: 6px;
	box-shadow: 0 10px 24px rgba(31, 35, 40, 0.12);
	overflow: hidden;
}

.jnotify-background,
div.jnotify-background,
.jnotify-container .jnotify-background {
	background: var(--iform-surface) !important;
	border: 0 !important;
	border-radius: 0 !important;
	box-shadow: none !important;
	opacity: 1 !important;
}

.jnotify-container .jnotify-notification .jnotify-message {
	color: var(--iform-text) !important;
	padding: 12px 14px;
}

<?php
if (getDolGlobalInt('THEME_DARKMODEENABLED')) {
	print "/* iForm dark skin layer */\n";
	if (getDolGlobalInt('THEME_DARKMODEENABLED') != 2) {
		print "@media (prefers-color-scheme: dark) {";
	} else {
		print "@media not print {";
	}
?>
:root {
	color-scheme: dark;
	--iform-accent: #4ea3ff;
	--iform-accent-hover: #78b7ff;
	--iform-accent-soft: rgba(78, 163, 255, 0.16);
	--iform-line: #3a4048;
	--iform-line-soft: #2c3238;
	--iform-bg: #151719;
	--iform-surface: #1c1f22;
	--iform-surface-alt: #24282d;
	--iform-text: #edf2f7;
	--iform-muted: #a9b3c0;
	--iform-danger: #ff8b8b;
	--iform-danger-bg: #3a1f24;
	--iform-danger-hover-bg: #4a252b;
	--iform-neutral-bg: #202429;
	--iform-neutral-hover-bg: #2a3037;
	--iform-refused-bg: #202429;
	--iform-focus-ring: rgba(78, 163, 255, 0.28);
	--colorbackhmenu1: #1c1f22;
	--colorbackvmenu1: #181b1f;
	--colorbacktitle1: #24282d;
	--colorbacktabcard1: #1c1f22;
	--colorbacktabactive: #24282d;
	--colorbacklineimpair1: #1c1f22;
	--colorbacklineimpair2: #1c1f22;
	--colorbacklinepair1: #1c1f22;
	--colorbacklinepair2: #202429;
	--colorbacklinepairhover: #263141;
	--colorbacklinepairchecked: #183a5e;
	--colorbacklinebreak: #252a30;
	--colorbackbody: #151719;
	--colorbackmobilemenu: #181b1f;
	--colorbackgrey: #202429;
	--colortexttitlenotab: #edf2f7;
	--colortexttitlenotab2: #edf2f7;
	--colortexttitle: rgba(237, 242, 247, 0.95);
	--colortexttitlelink: rgba(78, 163, 255, 0.95);
	--colortext: #edf2f7;
	--colortextlink: #4ea3ff;
	--colortextbackhmenu: #edf2f7;
	--colortextbackvmenu: #edf2f7;
	--colortopbordertitle1: #3a4048;
	--inputbackgroundcolor: #202429;
	--inputbackgroundcolordisabled: #181b1f;
	--inputcolordisabled: #7f8a96;
	--inputbordercolor: #3a4048;
	--tooltipbgcolor: rgba(28, 31, 34, 0.98);
	--tooltipfontcolor: #edf2f7;
	--oddevencolor: #edf2f7;
	--colorboxstatsborder: #3a4048;
	--colorboxiconbg: #202429;
	--fieldrequiredcolor: #ff8b8b;
	--refidnocolor: #edf2f7;
	--tableforfieldcolor: #a9b3c0;
}

html,
body,
#mainbody {
	background: var(--iform-bg) !important;
	color: var(--iform-text);
}

a:link,
a:visited,
a:hover,
a:active,
.classlink,
.nowraponall a,
.linkobject,
.linkobject:visited {
	color: var(--iform-accent);
}

hr,
.opacitymedium {
	border-color: var(--iform-line-soft);
}

#id-top,
.tmenu,
.tmenuimage,
.login_block,
.inline-block.login_block {
	background: var(--iform-surface) !important;
	border-color: var(--iform-line-soft) !important;
	color: var(--iform-text) !important;
}

#id-top {
	box-shadow: 0 1px 0 rgba(0, 0, 0, 0.3);
}

#id-top a,
#id-top span,
#id-top .fas,
#id-top .far,
#id-top .fa,
.login_block,
.login_block a {
	color: var(--iform-text) !important;
}

#id-top li.tmenusel div.tmenucenter,
#id-top li.tmenu:hover div.tmenucenter {
	background: var(--iform-accent-soft) !important;
}

#id-top li.tmenusel,
#id-top li.tmenusel a,
#id-top li.tmenusel span,
#id-top li.tmenusel .fas,
#id-top li.tmenusel .far,
#id-top li.tmenusel .fa,
#id-top li.tmenu:hover a,
#id-top li.tmenu:hover span,
#id-top li.tmenu:hover .fas,
#id-top li.tmenu:hover .far,
#id-top li.tmenu:hover .fa {
	color: var(--iform-accent-hover) !important;
}

#id-left,
#id-right,
#id-left .vmenu,
#id-left .blockvmenusearch,
#id-left .blockvmenubookmarks,
#id-left .menu_contenu,
#id-left .menu_titre,
#id-left .vmenu_titre,
.vmenu_titre,
.menu_titre {
	background: var(--colorbackvmenu1) !important;
	border-color: var(--iform-line-soft) !important;
	color: var(--iform-text) !important;
}

#id-left a,
.vmenu a,
.menu_contenu a {
	color: var(--iform-muted) !important;
}

#id-left a:hover,
.vmenu a:hover,
.menu_contenu a:hover {
	color: var(--iform-accent-hover) !important;
}

.fiche,
.fichecenter,
.fichehalfleft,
.fichehalfright,
.ficheaddleft,
.ficheaddright,
.fichethirdleft,
.fichetwothirdright,
.fichehalfright .ficheaddleft,
.tabBar,
.tabBarWithBottom,
.tabBarNoBottom,
.tabBar .tabBar,
.div-table-responsive,
.div-table-responsive-no-min,
.box,
.box-flex-item,
.info-box,
.boxstats,
.tableforfield,
.tableforfieldcreate,
.tableforfieldedit,
.ui-widget-content,
.ui-dialog,
.ui-dialog-content,
.ui-dialog-titlebar,
.dropdown-menu,
.dropdown-body,
.user-body,
.bookmark-body,
.quickadd-body,
.select2-dropdown,
.select2-drop,
.ui-tooltip.mytooltip,
.mytooltip {
	background: var(--iform-surface) !important;
	border-color: var(--iform-line) !important;
	color: var(--iform-text) !important;
}

.tabBar,
.tabBarWithBottom,
.tabBarNoBottom {
	box-shadow: none !important;
}

.tab,
.tabactive,
.tabunactive,
.tabBar .tabsElem a,
.tabs .tabsElem a {
	background: var(--iform-surface-alt) !important;
	border-color: var(--iform-line) !important;
	color: var(--iform-muted) !important;
}

.tabactive,
.tabBar .tabsElem.tabsElemActive a,
.tabs .tabsElem.tabsElemActive a {
	color: var(--iform-accent-hover) !important;
	border-bottom-color: var(--iform-accent) !important;
}

.titre,
.titre.inline-block,
.titre:first-letter,
.table-fiche-title .titre,
.table-fiche-title .col-title,
.refid,
.refidno,
.refidno a,
.titlefield,
.titlefieldcreate,
.titlefieldmiddle,
.titlefieldmiddle2,
.nobold,
.wordbreak,
.liste_total,
.amount,
.opacityhigh {
	color: var(--iform-text) !important;
}

.secondary,
.opacitymedium,
.opacitymediumonly,
.opacitymediumonlyonhover,
.opacitymediumcolor,
.notopnoleftnoright,
.small,
.smallpaddingimp,
.tableforfield td:first-child,
.tableforfield div.tagtd:first-child,
.trforfield td:first-child {
	color: var(--iform-muted) !important;
}

table.border,
table.noborder,
table.liste,
.border,
.noborder,
.liste,
.tableforfield,
.tagtable,
.paymenttable,
.margintable {
	background: var(--iform-surface) !important;
	border-color: var(--iform-line-soft) !important;
	color: var(--iform-text) !important;
}

.liste_titre,
tr.liste_titre,
tr.liste_titre_sel,
.liste_titre_sel,
table.border tr.liste_titre,
table.noborder tr.liste_titre,
table.liste tr.liste_titre,
div.liste_titre,
div.liste_titre_bydiv,
table.dataTable thead tr {
	background: var(--iform-surface-alt) !important;
	border-color: var(--iform-line) !important;
	color: var(--iform-text) !important;
}

.liste_titre a,
.liste_titre td a,
.liste_titre th a,
tr.liste_titre th a,
tr.liste_titre td a {
	color: var(--iform-accent-hover) !important;
}

tr.oddeven,
tr.pair,
tr.impair,
.oddeven,
.pair,
.impair,
.tagtr.oddeven,
.tagtr.pair,
.tagtr.impair,
.noborder > tbody > tr:nth-child(even):not(.liste_titre):not(.nooddeven):not(.liste_total),
.liste > tbody > tr:nth-child(even):not(.liste_titre):not(.nooddeven):not(.liste_total),
.noborder > tbody > tr:nth-child(odd):not(.liste_titre):not(.nooddeven):not(.liste_total),
.liste > tbody > tr:nth-child(odd):not(.liste_titre):not(.nooddeven):not(.liste_total) {
	background: var(--iform-surface) !important;
	color: var(--iform-text) !important;
}

tr.oddeven:hover,
tr.pair:hover,
tr.impair:hover,
.oddeven:hover,
.pair:hover,
.impair:hover,
.tagtr.oddeven:hover,
.tagtr.pair:hover,
.tagtr.impair:hover {
	background: var(--iform-neutral-hover-bg) !important;
}

td,
th,
div.tagtd,
.liste td,
.liste th,
.noborder td,
.noborder th,
.border td,
.border th {
	border-color: var(--iform-line-soft) !important;
	color: inherit;
}

input:not([type="image"]):not([type="checkbox"]):not([type="radio"]),
textarea,
select,
.select2-container--default .select2-selection--single,
.select2-container--default .select2-selection--multiple,
.select2-choice,
.select2-choices,
.select2-search input,
.select2-search__field {
	background: var(--inputbackgroundcolor) !important;
	border-color: var(--inputbordercolor) !important;
	color: var(--iform-text) !important;
}

input::placeholder,
textarea::placeholder,
.select2-container--default .select2-selection--single .select2-selection__placeholder,
.select2-default {
	color: var(--iform-muted) !important;
	opacity: 1;
}

input:disabled,
textarea:disabled,
select:disabled,
.select2-container--default.select2-container--disabled .select2-selection--single,
.select2-container--default.select2-container--disabled .select2-selection--multiple {
	background: var(--inputbackgroundcolordisabled) !important;
	color: var(--inputcolordisabled) !important;
}

input:focus:not(.noborderfocus):not(.inputsearch_dropdownselectedfields):not(.button):not(.buttonwebsite):not(.buttonreset):not(.select2-search__field):not(#top-bookmark-search-input):not(.search_component_input):not(.input-nobottom),
select:focus,
textarea:focus,
.select2-container--open [aria-expanded="false"].select2-selection--single,
.select2-container--focus span.selection span.select2-selection:not(.noborderfocus):not(.massactionselect) {
	border-color: var(--iform-accent) !important;
	box-shadow: 0 0 0 2px var(--iform-focus-ring) !important;
}

.select2-container--default .select2-selection--single .select2-selection__rendered,
.select2-container .select2-selection--single .select2-selection__rendered,
.select2-container .select2-choice > .select2-chosen,
.select2-results__option,
.select2-results,
.select2-results .select2-no-results,
.select2-results .select2-searching,
.select2-results .select2-ajax-error,
.select2-results .select2-selection-limit {
	color: var(--iform-text) !important;
	background: var(--iform-surface) !important;
}

.select2-container--default .select2-results__option--highlighted[aria-selected],
.select2-container--default .select2-results__option--highlighted[aria-selected] span,
.select2-results .select2-highlighted,
.select2-results .select2-highlighted span {
	background: var(--iform-accent-soft) !important;
	color: var(--iform-accent-hover) !important;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice,
li.select2-selection__choice,
.select2-container-multi .select2-choices .select2-search-choice {
	background: var(--iform-neutral-bg) !important;
	border-color: var(--iform-line) !important;
	color: var(--iform-text) !important;
}

#mainbody .butAction:not(.butActionDelete):not(.butActionRefused),
#mainbody .butAction:not(.butActionDelete):not(.butActionRefused):link,
#mainbody .butAction:not(.butActionDelete):not(.butActionRefused):visited,
#mainbody input.button:not(.buttonDelete):not(.bordertransp):not(.buttonpayment):not(:disabled),
#mainbody button.button:not(.buttonDelete):not(.bordertransp):not(.buttonpayment):not(:disabled),
#mainbody a.button:not(.buttonDelete):not(.bordertransp):not(.buttonpayment),
#mainbody input.button.buttongen:not(:disabled),
#mainbody button.button.buttongen:not(:disabled),
#mainbody input.buttongen:not(:disabled),
#mainbody button.buttongen:not(:disabled) {
	background: var(--iform-accent) !important;
	border-color: var(--iform-accent) !important;
	color: #08111b !important;
}

#mainbody .butAction:not(.butActionDelete):not(.butActionRefused):hover,
#mainbody input.button:not(.buttonDelete):not(.bordertransp):not(.buttonpayment):not(:disabled):hover,
#mainbody button.button:not(.buttonDelete):not(.bordertransp):not(.buttonpayment):not(:disabled):hover,
#mainbody a.button:not(.buttonDelete):not(.bordertransp):not(.buttonpayment):hover,
#mainbody input.button.buttongen:not(:disabled):hover,
#mainbody button.button.buttongen:not(:disabled):hover,
#mainbody input.buttongen:not(:disabled):hover,
#mainbody button.buttongen:not(:disabled):hover {
	background: var(--iform-accent-hover) !important;
	border-color: var(--iform-accent-hover) !important;
	color: #08111b !important;
}

#mainbody .butActionDelete,
#mainbody .butActionDelete:link,
#mainbody .butActionDelete:visited,
#mainbody .butActionDelete:hover,
#mainbody .butActionDelete:active,
#mainbody .buttonDelete {
	background: var(--iform-danger-bg) !important;
	border-color: #6e3038 !important;
	color: var(--iform-danger) !important;
}

#mainbody .butActionDelete:hover,
#mainbody .buttonDelete:hover:not(:disabled) {
	background: var(--iform-danger-hover-bg) !important;
	border-color: #8f3e49 !important;
	color: #ffb4b4 !important;
}

#mainbody .butActionRefused,
#mainbody .butActionRefused:link,
#mainbody .butActionRefused:visited,
#mainbody .butActionRefused:hover,
#mainbody .butActionRefused:active,
#mainbody .butActionNewRefused,
#mainbody .buttonRefused,
#mainbody .button:disabled,
#mainbody .buttonDelete:disabled,
#mainbody .button.disabled,
#mainbody .buttonDelete.disabled {
	background: var(--iform-refused-bg) !important;
	border-color: var(--iform-line) !important;
	color: var(--iform-muted) !important;
}

.btnTitle,
.button,
.buttonDelete,
.butAction,
.butActionRefused,
.butActionDelete {
	box-shadow: none !important;
}

.badge-status,
.badge,
.status,
.statusref,
.statusrefsupplier,
.statusdoc,
.badge.badge-status {
	border-color: rgba(78, 163, 255, 0.35) !important;
}

.statusref,
.statusrefsupplier,
.statusdoc,
.badge-status.badge-status0,
.badge-status.badge-status1,
.badge-status.badge-status2,
.badge-status.badge-status3,
.badge-status.badge-status4,
.badge-status.badge-status5,
.badge-status.badge-status6,
.badge-status.badge-status7,
.badge-status.badge-status8,
.badge-status.badge-status9 {
	background: rgba(78, 163, 255, 0.12) !important;
	color: var(--iform-accent-hover) !important;
}

.badge-status.badge-status0,
.badge-status0 {
	background: rgba(169, 179, 192, 0.14) !important;
	border-color: rgba(169, 179, 192, 0.35) !important;
	color: #c8d0d9 !important;
}

.badge-status.badge-status1,
.badge-status.badge-status2,
.badge-status.badge-status4,
.badge-status1,
.badge-status2,
.badge-status4 {
	background: rgba(61, 180, 111, 0.14) !important;
	border-color: rgba(61, 180, 111, 0.38) !important;
	color: #83e5a1 !important;
}

.badge-status.badge-status3,
.badge-status.badge-status5,
.badge-status.badge-status6,
.badge-status3,
.badge-status5,
.badge-status6 {
	background: rgba(214, 164, 62, 0.16) !important;
	border-color: rgba(214, 164, 62, 0.38) !important;
	color: #ffd38a !important;
}

.badge-status.badge-status7,
.badge-status.badge-status8,
.badge-status.badge-status9,
.badge-status.badge-status10,
.badge-status7,
.badge-status8,
.badge-status9,
.badge-status10 {
	background: rgba(255, 139, 139, 0.14) !important;
	border-color: rgba(255, 139, 139, 0.38) !important;
	color: #ffb4b4 !important;
}

.jnotify-container .jnotify-notification {
	background: var(--iform-surface) !important;
	border-color: var(--iform-line) !important;
	box-shadow: 0 14px 34px rgba(0, 0, 0, 0.42);
}

.jnotify-background,
div.jnotify-background,
.jnotify-container .jnotify-background {
	background: var(--iform-surface) !important;
}

.jnotify-container .jnotify-notification .jnotify-message,
.jnotify-container .jnotify-close {
	color: var(--iform-text) !important;
}

.ui-widget-header,
.ui-state-default,
.ui-state-active,
.ui-datepicker,
.ui-datepicker-header,
.ui-menu,
.ui-autocomplete {
	background: var(--iform-surface-alt) !important;
	border-color: var(--iform-line) !important;
	color: var(--iform-text) !important;
}

.ui-state-hover,
.ui-menu .ui-menu-item-wrapper.ui-state-active,
.ui-autocomplete .ui-menu-item-wrapper.ui-state-active {
	background: var(--iform-neutral-hover-bg) !important;
	color: var(--iform-accent-hover) !important;
}

.warning,
.error,
.ok,
.info,
.messagedebug,
.messagewarning,
.messageerr,
.messageok {
	border-color: var(--iform-line) !important;
	color: var(--iform-text) !important;
}

.messageerr,
.error {
	background: var(--iform-danger-bg) !important;
	color: #ffb4b4 !important;
}

.messageok,
.ok {
	background: rgba(61, 180, 111, 0.14) !important;
	color: #83e5a1 !important;
}

.warning,
.messagewarning {
	background: rgba(214, 164, 62, 0.16) !important;
	color: #ffd38a !important;
}

.info {
	background: var(--iform-accent-soft) !important;
	color: var(--iform-accent-hover) !important;
}

img,
.photowithmargin,
.photo,
.photowithmargin {
	border-color: var(--iform-line) !important;
}

.dropzone,
.attachedfiles,
.documentpreview,
.ecmfile,
.drag,
.dragHandle {
	background: var(--iform-surface-alt) !important;
	border-color: var(--iform-line) !important;
	color: var(--iform-text) !important;
}

::-webkit-scrollbar {
	background: var(--iform-bg);
}

::-webkit-scrollbar-thumb {
	background: #3a4048;
	border: 2px solid var(--iform-bg);
	border-radius: 999px;
}

::-webkit-scrollbar-thumb:hover {
	background: #4a525c;
}
<?php
	print "}\n";
}
?>

@media only screen and (max-width: 767px) {
	table.table-fiche-title .titre,
	table.table-fiche-title div.titre,
	table.table-fiche-title td.col-title {
		word-break: keep-all;
		overflow-wrap: normal;
	}
}

<?php

if (is_object($db)) {
	$db->close();
}
