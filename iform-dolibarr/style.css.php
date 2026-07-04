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

#mainbody .imgforviewmode,
#mainbody .fa-bars.imgforviewmode,
#mainbody .fa-bars.paddingleft,
#mainbody .fa-stream,
#mainbody .fa-grip-horizontal.imgforviewmode,
#mainbody .fa-comments.imgforviewmode,
#mainbody .fa-pencil-alt {
	color: #57606a !important;
}

#mainbody .fa-lock {
	color: #64748b !important;
}

#mainbody .fa-rss {
	color: #b45309 !important;
}

#mainbody img.ui-datepicker-trigger,
#mainbody img[src$="/object_calendarday.png"] {
	background: transparent !important;
	content: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Crect x='2.5' y='3.5' width='11' height='10' rx='1.5' fill='none' stroke='%230969da' stroke-width='1.5'/%3E%3Cpath d='M5 2v3M11 2v3M3 6.5h10' stroke='%230969da' stroke-width='1.5' stroke-linecap='round'/%3E%3Cpath d='M5 8.5h2v2H5zM9 8.5h2v2H9z' fill='%230969da' opacity='.86'/%3E%3C/svg%3E") !important;
	filter: none !important;
	height: 14px !important;
	object-fit: contain !important;
	opacity: 0.9;
	vertical-align: middle;
	width: 14px !important;
}

#mainbody .fa-globe-americas,
#mainbody .fa-map,
#mainbody .fa-map-marked,
#mainbody .fa-map-marked-alt,
#mainbody .fa-map-marker-alt {
	color: #64748b !important;
}

#mainbody .infobox-bank_account,
#mainbody .fa-money-check-alt,
#mainbody .fa-university,
#mainbody .fa-dollar-sign,
#mainbody .fa-clipboard-list {
	color: #7a6d10 !important;
}

#mainbody .infobox-contrat,
#mainbody .fa-suitcase,
#mainbody .fa-ambulance,
#mainbody .fa-ticket-alt {
	color: #238b7f !important;
}

#id-top a.tmenuimage,
#id-top a.tmenuimage .fa,
#id-top a.tmenuimage .fas,
#id-top a.tmenuimage .far,
#id-top a.tmenuimage .fab {
	color: inherit !important;
}

#id-top {
	border-bottom: 1px solid var(--iform-line);
	box-shadow: 0 1px 0 rgba(31, 35, 40, 0.04);
}

#id-top li.tmenusel::after,
#id-top li.tmenu:not(.nohover):hover::after {
	content: none !important;
	display: none !important;
	border: 0 !important;
}

#id-top li.tmenu div.tmenucenter,
#id-top li.tmenusel div.tmenucenter {
	border: 1px solid transparent;
	border-radius: 5px;
	box-sizing: border-box;
}

#id-top li.tmenusel div.tmenucenter,
#id-top li.tmenu:not(.nohover):hover div.tmenucenter {
	background: #eef6ff;
	border-color: #d8e9ff;
}

#id-top li.tmenusel a.tmenuimage,
#id-top li.tmenusel a.tmenulabel,
#id-top li.tmenu:not(.nohover):hover a.tmenuimage,
#id-top li.tmenu:not(.nohover):hover a.tmenulabel {
	background: transparent !important;
	border: 0 !important;
	box-shadow: none !important;
}

#id-top li.tmenusel a.tmenulabel,
#id-top li.tmenusel a.tmenulabel .mainmenuaspan {
	color: var(--iform-accent) !important;
}

#id-left {
	border-left: 0 !important;
	border-right: 0 !important;
	box-shadow: none;
}

#id-left .blockvmenu {
	border-bottom: 1px solid var(--iform-line-soft) !important;
}

#id-left hr {
	border-top-color: var(--iform-line-soft) !important;
}

.menu_titre,
.vmenu_titre {
	color: var(--iform-text);
}

.tab,
.tabactive,
.tabunactive,
a.tab#active,
a.tab#unactive {
	border: 0 !important;
	border-bottom: 3px solid transparent !important;
}

.tabactive,
a.tab#active {
	border-bottom-color: var(--colortextbackhmenu) !important;
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

#mainbody .dropdown-content a:is(.butAction,.butActionDelete,.butActionRefused),
#mainbody .dropdown-content .butAction,
#mainbody .dropdown-content .butActionDelete,
#mainbody .dropdown-content .butActionRefused {
	background: transparent !important;
	border: 0 !important;
	border-bottom: 1px solid var(--iform-line-soft) !important;
	border-radius: 0 !important;
	box-shadow: none !important;
	color: var(--iform-text) !important;
	margin-left: 0 !important;
	margin-right: 0 !important;
}

#mainbody .dropdown-content .butAction:hover,
#mainbody .dropdown-content .butAction:focus {
	background: var(--iform-neutral-hover-bg) !important;
	border-bottom-color: var(--iform-line) !important;
	color: var(--iform-accent) !important;
	text-decoration: none !important;
}

#mainbody .dropdown-content .butActionDelete,
#mainbody .dropdown-content .butActionDelete:link,
#mainbody .dropdown-content .butActionDelete:visited {
	color: var(--iform-danger) !important;
}

#mainbody .dropdown-content .butActionDelete:hover,
#mainbody .dropdown-content .butActionDelete:focus {
	background: var(--iform-danger-bg) !important;
	border-bottom-color: #ff8182 !important;
	color: #a40e26 !important;
	text-decoration: none !important;
}

#mainbody .dropdown-content .butActionRefused,
#mainbody .dropdown-content .butActionRefused:hover,
#mainbody .dropdown-content .butActionRefused:focus {
	background: transparent !important;
	border-bottom-color: var(--iform-line-soft) !important;
	color: var(--iform-muted) !important;
	cursor: not-allowed !important;
}

#mainbody dl.dropdown dd .multichoicedoc > ul.ulselectedfields.open {
	background: var(--iform-surface) !important;
	border: 1px solid var(--iform-line) !important;
	box-shadow: 0 8px 18px rgba(31, 35, 40, 0.12) !important;
	color: var(--iform-text) !important;
	max-height: none !important;
	overflow: visible !important;
	z-index: 1000 !important;
}

#mainbody dl.dropdown dd .multichoicedoc > ul.ulselectedfields.open > li {
	color: var(--iform-text) !important;
}

#mainbody dl.dropdown dd .multichoicedoc > ul.ulselectedfields.open > li:hover:not(.liinputsearch) {
	background: var(--iform-neutral-hover-bg) !important;
	color: var(--iform-accent) !important;
}

#mainbody dl.dropdown dd .multichoicedoc > ul.ulselectedfields.open a {
	color: var(--iform-accent) !important;
	text-decoration: none !important;
}

#mainbody .box:has(dl.dropdown dd .multichoicedoc > ul.ulselectedfields.open),
#mainbody .boxtable:has(dl.dropdown dd .multichoicedoc > ul.ulselectedfields.open),
#mainbody .info-box:has(dl.dropdown dd .multichoicedoc > ul.ulselectedfields.open),
#mainbody .div-table-responsive:has(dl.dropdown dd .multichoicedoc > ul.ulselectedfields.open),
#mainbody .div-table-responsive-no-min:has(dl.dropdown dd .multichoicedoc > ul.ulselectedfields.open) {
	overflow: visible !important;
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

.badge.badge-status,
.badge-status {
	border-width: 1px !important;
	border-style: solid !important;
	box-shadow: none !important;
	line-height: 1.15;
	padding: 3px 8px 2px !important;
}

/* Some embedded linked-object tables render the status badge in label-less (tooltip-only) mode; without any text a badge with pill padding collapses into a thin flat bar instead of reading as a status dot. */
.badge:empty,
.badge-status:empty,
.status:empty,
.statusref:empty,
.statusrefsupplier:empty,
.statusdoc:empty {
	display: inline-block;
	width: 0.7em;
	height: 0.7em;
	min-width: 0.7em;
	padding: 0 !important;
	border-radius: 50% !important;
	vertical-align: middle;
}

.jnotify-container {
	top: 60px !important;
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
		--infoboxmoduleenabledbgcolor: linear-gradient(0.4turn, #24282d 0%, #24282d 68%, rgba(61, 180, 111, 0.18) 100%);
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
.login_block,
.inline-block.login_block {
	background: var(--iform-surface) !important;
	border-color: var(--iform-line-soft) !important;
	color: var(--iform-text) !important;
}

.tmenu,
.tmenuimage {
	background: var(--iform-surface) !important;
	border-color: var(--iform-line-soft) !important;
}

#id-top {
	box-shadow: 0 1px 0 rgba(0, 0, 0, 0.3);
}

.login_block,
.inline-block.login_block {
	border-bottom: 1px solid var(--iform-line-soft) !important;
}

#id-top a.tmenulabel,
#id-top a.tmenulabel .mainmenuaspan,
.login_block,
.login_block > a {
	color: var(--iform-text) !important;
}

#id-top li.tmenu div.tmenucenter,
#id-top li.tmenusel div.tmenucenter {
	border: 1px solid transparent !important;
	border-radius: 6px !important;
	box-sizing: border-box;
}

#id-top li.tmenusel div.tmenucenter,
#id-top li.tmenu:not(.nohover):hover div.tmenucenter {
	background: rgba(78, 163, 255, 0.11) !important;
	border-color: rgba(78, 163, 255, 0.22) !important;
	box-shadow: none !important;
}

#id-top li.tmenusel a.tmenuimage,
#id-top li.tmenusel a.tmenulabel,
#id-top li.tmenu:not(.nohover):hover a.tmenuimage,
#id-top li.tmenu:not(.nohover):hover a.tmenulabel {
	background: transparent !important;
	border: 0 !important;
	box-shadow: none !important;
}

#id-top li.tmenusel a.tmenulabel,
#id-top li.tmenusel a.tmenulabel .mainmenuaspan,
#id-top li.tmenu:not(.nohover):hover a.tmenulabel,
#id-top li.tmenu:not(.nohover):hover a.tmenulabel .mainmenuaspan {
	color: var(--iform-accent-hover) !important;
}

#id-left,
#id-right,
#id-container,
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

#id-container {
	min-height: calc(100vh - 51px);
}

#id-left .blockvmenu,
#id-left .blockvmenupair,
#id-left .blockvmenuimpair,
#id-left .blockvmenufirst,
#id-left .blockvmenulast {
	border-bottom: 1px solid rgba(80, 90, 102, 0.5) !important;
	border-top: 0 !important;
}

#id-left hr {
	border: 0 !important;
	border-top: 1px solid rgba(80, 90, 102, 0.5) !important;
}

#id-left .menu_titre,
#id-left .menu_contenu,
#id-left .vmenu_titre {
	border-color: rgba(80, 90, 102, 0.5) !important;
}

.side-nav,
.side-nav-vert {
	border-color: var(--iform-line-soft) !important;
	box-shadow: none !important;
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
.mytooltip,
.dropdown-menu > .user-header,
.dropdown-menu > .user-footer,
.dropdown-menu > .bookmark-footer,
.dropdown-search,
.dropdown-bookmark,
.dropdown-quickadd,
.dropdown-content {
	background: var(--iform-surface) !important;
	border-color: var(--iform-line) !important;
	color: var(--iform-text) !important;
}

.ui-dialog {
	box-shadow: 0 18px 40px rgba(0, 0, 0, 0.55) !important;
}

.info-box-sm .info-box-module-enabled {
	background: var(--infoboxmoduleenabledbgcolor) !important;
}

.box-flex-item.info-box-module:not(.--disabled) {
	border-color: rgba(61, 180, 111, 0.28) !important;
}

.box-flex-item.info-box-module:not(.--disabled) .info-box-content {
	color: var(--iform-text) !important;
}

.box-flex-item.info-box-module:not(.--disabled) .info-box-title,
.box-flex-item.info-box-module:not(.--disabled) .info-box-text {
	color: var(--iform-text) !important;
}

.box-flex-item.info-box-module:not(.--disabled) .info-box-desc,
.box-flex-item.info-box-module:not(.--disabled) .info-box-desc-long {
	color: var(--iform-muted) !important;
}

div.ui-tooltip.mytooltip,
div.ui-tooltip.mytooltip-hover-tooltip {
	background: var(--iform-surface) !important;
	border: 1px solid var(--iform-line) !important;
	color: var(--iform-text) !important;
	box-shadow: 0 2px 10px rgba(0, 0, 0, 0.45) !important;
	filter: none !important;
}

.dropdown-search,
.dropdown-bookmark,
.dropdown-quickadd,
.dropdown-content,
.select2-dropdown,
.select2-drop,
.info-box,
.boxtable,
.boxstats {
	box-shadow: 0 2px 10px rgba(0, 0, 0, 0.45) !important;
}

#mainbody dl.dropdown dd .multichoicedoc > ul.ulselectedfields.open {
	background: var(--iform-surface) !important;
	border-color: var(--iform-line) !important;
	box-shadow: 0 14px 28px rgba(0, 0, 0, 0.56) !important;
	color: var(--iform-text) !important;
}

#mainbody dl.dropdown dd .multichoicedoc > ul.ulselectedfields.open > li:hover:not(.liinputsearch) {
	background: var(--iform-neutral-hover-bg) !important;
	color: var(--iform-accent-hover) !important;
}

#mainbody .dropdown-holder.open .dropdown-content::before {
	border-color: transparent transparent var(--iform-surface) transparent !important;
}

.box {
	box-shadow: none !important;
}

.dropdown-body::-webkit-scrollbar-thumb {
	background: #3a4048 !important;
}

.user-footer .button-top-menu-dropdown,
.dropdown-item {
	background: var(--iform-surface-alt) !important;
	border-color: var(--iform-line) !important;
	color: var(--iform-text) !important;
}

.dropdown-item.active,
.dropdown-item:hover,
.dropdown-item:focus {
	background: var(--iform-neutral-hover-bg) !important;
	color: var(--iform-accent-hover) !important;
}

#topmenu-quickadd-dropdown .dropdown-item.active,
#topmenu-quickadd-dropdown .dropdown-item:hover,
#topmenu-quickadd-dropdown .dropdown-item:focus,
#topmenu-global-search-dropdown .dropdown-item.active,
#topmenu-global-search-dropdown .dropdown-item:hover,
#topmenu-global-search-dropdown .dropdown-item:focus {
	background: var(--iform-neutral-hover-bg) !important;
	color: var(--iform-text) !important;
}

#topmenu-quickadd-dropdown .dropdown-item span,
#topmenu-global-search-dropdown .dropdown-item span {
	opacity: 1 !important;
	background: transparent !important;
	border: 0 !important;
	box-shadow: none !important;
}

#topmenu-quickadd-dropdown .dropdown-item span::before,
#topmenu-quickadd-dropdown .dropdown-item.active span::before,
#topmenu-quickadd-dropdown .dropdown-item:hover span::before,
#topmenu-quickadd-dropdown .dropdown-item:focus span::before,
#topmenu-global-search-dropdown .dropdown-item span::before,
#topmenu-global-search-dropdown .dropdown-item.active span::before,
#topmenu-global-search-dropdown .dropdown-item:hover span::before,
#topmenu-global-search-dropdown .dropdown-item:focus span::before {
	color: inherit !important;
	background: transparent !important;
	border: 0 !important;
	box-shadow: none !important;
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
.divsection,
.tabBar,
.tabBarWithBottom,
.tabBarNoBottom,
.tabBar .tabBar {
	background: transparent !important;
	border-color: var(--iform-line-soft) !important;
	color: var(--iform-text) !important;
}

.tabBar,
.tabBarWithBottom,
.tabBarNoBottom {
	box-shadow: none !important;
}

#mainbody .divsection,
#mainbody .divsection.wordbreak {
	background: var(--iform-surface) !important;
	border-color: var(--iform-line) !important;
	color: var(--iform-text) !important;
}

.tab,
.tabactive,
.tabunactive,
.tabBar .tabsElem a,
.tabs .tabsElem a {
	background: transparent !important;
	border: 0 !important;
	border-bottom: 3px solid transparent !important;
	color: var(--iform-muted) !important;
	box-shadow: none !important;
}

.tabactive,
.tabBar .tabsElem.tabsElemActive a,
.tabs .tabsElem.tabsElemActive a {
	color: var(--iform-accent-hover) !important;
}

.tabactive {
	border-bottom-color: var(--iform-accent) !important;
	box-shadow: none !important;
}

div.tabsElem a.tabactive::before,
div.tabsElem a.tabunactive::before {
	background: transparent !important;
	border: 0 !important;
	box-shadow: none !important;
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

#mainbody .fa.opacityhigh,
#mainbody .fas.opacityhigh,
#mainbody .far.opacityhigh,
#mainbody i.opacityhigh[class*="fa-"],
#mainbody span.opacityhigh[class*="fa-"] {
	opacity: 0.72 !important;
}

#mainbody .fa-toggle-on.opacitymedium,
#mainbody .fa-toggle-off.opacitymedium,
#mainbody .fa-toggle-on.opacitymediumonly,
#mainbody .fa-toggle-off.opacitymediumonly {
	opacity: 0.72 !important;
}

table.noborder,
table.liste,
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

table.border,
.border {
	background: transparent !important;
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

div.liste_titre_bydiv,
div.liste_titre_bydiv_nothingafter {
	border-color: var(--iform-line-soft) !important;
}

#mainbody .fichehalfleft table.noborder.centpercent,
#mainbody .fichehalfright table.noborder.centpercent,
#mainbody .boxhalfleft table.noborder.centpercent,
#mainbody .boxhalfright table.noborder.centpercent {
	border-color: var(--iform-line) !important;
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

.trforbreak td,
div.tableforcontact form.tagtr:last-of-type div.tagtd,
.ui-mobile fieldset {
	border-color: var(--iform-line-soft) !important;
}

.paginate_button,
.paginate_active,
table.jPicker {
	border-color: var(--iform-line-soft) !important;
}

#mainbody .navmode .btnTitle .pictoactionview {
	color: var(--iform-muted) !important;
}

#mainbody .navmode .btnTitle:hover .pictoactionview,
#mainbody .navmode .btnTitle:focus .pictoactionview,
#mainbody .navmode .btnTitleSelected .pictoactionview {
	color: var(--iform-accent-hover) !important;
}

#mainbody .sectioncalendarbymonth,
#mainbody table.cal_month {
	background: var(--iform-surface) !important;
	color: var(--iform-text) !important;
}

#mainbody table.cal_month tr,
#mainbody table.noborder.cal_month tr {
	background: var(--iform-surface) !important;
	border-color: var(--iform-line-soft) !important;
	border-top-color: var(--iform-line-soft) !important;
}

#mainbody table.cal_month td,
#mainbody table.cal_month th,
#mainbody table.cal_month .tagtd,
#mainbody table.cal_month .tdfordaytitle {
	border-color: var(--iform-line-soft) !important;
	color: var(--iform-text) !important;
}

#mainbody table.cal_month .tdfordaytitle {
	background: var(--iform-surface-alt) !important;
}

#mainbody table.cal_month .cal_current_month,
#mainbody table.cal_month .cal_current_month_right,
#mainbody table.cal_month .cal_current_month_oneday,
#mainbody table.cal_month .cal_current_month_peruserleft,
#mainbody table.cal_month .cal_current_month_peruserleft.cal_impair {
	background: var(--iform-surface) !important;
	border-color: var(--iform-line-soft) !important;
	color: var(--iform-text) !important;
}

#mainbody table.cal_month .cal_other_month,
#mainbody table.cal_month .cal_other_month_right,
#mainbody table.cal_month .cal_other_month_peruserleft,
#mainbody table.cal_month .cal_past_month,
#mainbody table.cal_month td.cal_other_month {
	background: var(--iform-neutral-bg) !important;
	border-color: var(--iform-line-soft) !important;
	color: var(--iform-muted) !important;
	opacity: 1 !important;
}

#mainbody table.cal_month .cal_today,
#mainbody table.cal_month .cal_today_peruser,
#mainbody table.cal_month .cal_today_peruser_peruserleft,
#mainbody table.cal_month .cal_today_peruser_impair {
	background: rgba(78, 163, 255, 0.12) !important;
	border-color: rgba(78, 163, 255, 0.38) !important;
	color: var(--iform-text) !important;
}

#mainbody table.cal_month .dayevent,
#mainbody table.cal_month .agendacell,
#mainbody table.cal_month .tagtr,
#mainbody table.cal_month .tagtd {
	background: transparent !important;
}

#mainbody div.event {
	box-shadow: none !important;
}

#mainbody table.cal_event {
	background: var(--iform-surface-alt) !important;
	border: 1px solid var(--iform-line) !important;
	box-shadow: none !important;
	filter: none !important;
}

#mainbody table.cal_event td,
#mainbody table.cal_event .cal_event,
#mainbody table.cal_event a.cal_event_title,
#mainbody table.cal_event a:link,
#mainbody table.cal_event a:visited,
#mainbody table.cal_event a:active {
	color: var(--iform-text) !important;
}

#mainbody table.cal_event a.cal_event_title:hover {
	color: var(--iform-accent-hover) !important;
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
.select2-container .select2-choice > .select2-chosen {
	color: var(--iform-text) !important;
	background: transparent !important;
}

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
	background: var(--iform-accent-soft) !important;
	border-color: rgba(78, 163, 255, 0.4) !important;
	color: var(--iform-accent-hover) !important;
}

#mainbody .butAction:not(.butActionDelete):not(.butActionRefused):hover,
#mainbody input.button:not(.buttonDelete):not(.bordertransp):not(.buttonpayment):not(:disabled):hover,
#mainbody button.button:not(.buttonDelete):not(.bordertransp):not(.buttonpayment):not(:disabled):hover,
#mainbody a.button:not(.buttonDelete):not(.bordertransp):not(.buttonpayment):hover,
#mainbody input.button.buttongen:not(:disabled):hover,
#mainbody button.button.buttongen:not(:disabled):hover,
#mainbody input.buttongen:not(:disabled):hover,
#mainbody button.buttongen:not(:disabled):hover {
	background: rgba(78, 163, 255, 0.24) !important;
	border-color: rgba(78, 163, 255, 0.55) !important;
	color: var(--iform-accent-hover) !important;
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

#mainbody .dropdown-content a:is(.butAction,.butActionDelete,.butActionRefused),
#mainbody .dropdown-content .butAction,
#mainbody .dropdown-content .butActionDelete,
#mainbody .dropdown-content .butActionRefused {
	background: transparent !important;
	border: 0 !important;
	border-bottom: 1px solid var(--iform-line-soft) !important;
	border-radius: 0 !important;
	box-shadow: none !important;
	color: var(--iform-text) !important;
	margin-left: 0 !important;
	margin-right: 0 !important;
}

#mainbody .dropdown-content .butAction:hover,
#mainbody .dropdown-content .butAction:focus {
	background: var(--iform-neutral-hover-bg) !important;
	border-bottom-color: var(--iform-line) !important;
	color: var(--iform-accent-hover) !important;
	text-decoration: none !important;
}

#mainbody .dropdown-content .butActionDelete,
#mainbody .dropdown-content .butActionDelete:link,
#mainbody .dropdown-content .butActionDelete:visited {
	color: var(--iform-danger) !important;
}

#mainbody .dropdown-content .butActionDelete:hover,
#mainbody .dropdown-content .butActionDelete:focus {
	background: var(--iform-danger-hover-bg) !important;
	border-bottom-color: #8f3e49 !important;
	color: #ffb4b4 !important;
	text-decoration: none !important;
}

#mainbody .dropdown-content .butActionRefused,
#mainbody .dropdown-content .butActionRefused:hover,
#mainbody .dropdown-content .butActionRefused:focus {
	background: transparent !important;
	border-bottom-color: var(--iform-line-soft) !important;
	color: var(--iform-muted) !important;
	cursor: not-allowed !important;
}

.btnTitle,
.button,
.buttonDelete,
.butAction,
.butActionRefused,
.butActionDelete {
	box-shadow: none !important;
}

#mainbody a.btnTitle:hover,
#mainbody a.btnTitle:focus,
#mainbody .btnTitle:hover,
#mainbody .btnTitle:focus {
	background: var(--iform-neutral-hover-bg) !important;
	border-color: var(--iform-line) !important;
	box-shadow: none !important;
	color: var(--iform-accent-hover) !important;
}

#mainbody a.btnTitle.btnTitleSelected,
#mainbody a.btnTitle.btnTitleSelected:hover,
#mainbody .btnTitle.btnTitleSelected,
#mainbody .btnTitle.btnTitleSelected:hover {
	background: var(--iform-surface-alt) !important;
	border-color: var(--iform-line) !important;
	color: var(--iform-muted) !important;
}

.spannature,
.nonature-back {
	background: var(--iform-surface-alt) !important;
	border: 1px solid var(--iform-line) !important;
	color: var(--iform-muted) !important;
	box-shadow: none !important;
}

.spannature label,
.spannature .spanlabel {
	color: inherit !important;
}

.spannature input[type="checkbox"],
.spannature input[type="radio"] {
	accent-color: var(--iform-accent);
	color-scheme: dark;
}

.prospect-back {
	background: rgba(61, 180, 111, 0.18) !important;
	border-color: rgba(61, 180, 111, 0.34) !important;
	color: #9df0b4 !important;
}

.customer-back {
	background: rgba(61, 180, 111, 0.28) !important;
	border-color: rgba(61, 180, 111, 0.42) !important;
	color: #b7f7c7 !important;
}

.vendor-back {
	background: rgba(78, 163, 255, 0.2) !important;
	border-color: rgba(78, 163, 255, 0.38) !important;
	color: var(--iform-accent-hover) !important;
}

.user-back {
	background: rgba(214, 164, 62, 0.18) !important;
	border-color: rgba(214, 164, 62, 0.34) !important;
	color: #ffd38a !important;
}

.member-company-back,
.member-individual-back {
	background: rgba(169, 179, 192, 0.14) !important;
	border-color: rgba(169, 179, 192, 0.28) !important;
	color: #c8d0d9 !important;
}

.spannature.prospect-back:has(input:not(:checked)),
.spannature.customer-back:has(input:not(:checked)),
.spannature.vendor-back:has(input:not(:checked)) {
	background: var(--iform-surface-alt) !important;
	border-color: var(--iform-line) !important;
	color: var(--iform-muted) !important;
}

#mainbody table.border td > .pictofixedwidth[class*="fa-"],
#mainbody table.border td > span.pictofixedwidth[class*="fa-"],
#mainbody table.border td > i.pictofixedwidth[class*="fa-"],
#mainbody .select2-result-repository__email .fa,
#mainbody .select2-result-repository__address .fa,
#mainbody .select2-result-repository__country .fa,
#mainbody .select2-result-repository__departement .fa,
#mainbody .fa-phone,
#mainbody .fa-mobile-alt,
#mainbody .fa-fax,
#mainbody .fa-at,
#mainbody .fa-external-link-alt,
#mainbody .fa-share-alt,
#mainbody .fa-map-marked-alt,
#mainbody .fa-map-marker-alt,
#mainbody .fa-map,
#mainbody .fa-globe-americas,
#mainbody .fa-info-circle {
	opacity: 0.92 !important;
	background: transparent !important;
	border: 0 !important;
	box-shadow: none !important;
}

#mainbody table.border td > img.pictofixedwidth {
	filter: grayscale(1) brightness(1.75) contrast(0.9);
	opacity: 0.78;
}

#mainbody table.border td > .fa-building,
#mainbody table.border td > .fa-address-book,
#mainbody .select2-result-repository__address .fa {
	color: #9b9cf6 !important;
}

#mainbody table.border td > .fa-globe-americas,
#mainbody table.border td > .fa-map-marked,
#mainbody table.border td > .fa-map-marked-alt,
#mainbody table.border td > .fa-map-marker-alt,
#mainbody table.border td > .fa-map,
#mainbody .select2-result-repository__country .fa,
#mainbody .select2-result-repository__departement .fa {
	color: #a9b3c0 !important;
}

#mainbody table.border td > .fa-phone,
#mainbody table.border td > .fa-mobile-alt,
#mainbody table.border td > .fa-fax {
	color: #d6a43e !important;
}

#mainbody .fa-phone,
#mainbody .fa-mobile-alt,
#mainbody .fa-fax {
	color: #d6a43e !important;
}

#mainbody table.border td > .fa-external-link-alt,
#mainbody table.border td > .fa-share-alt,
#mainbody table.border td > .fa-at,
#mainbody .select2-result-repository__email .fa {
	color: #b985f4 !important;
}

#mainbody .fa-at,
#mainbody .fa-external-link-alt,
#mainbody .fa-share-alt {
	color: #b985f4 !important;
}

#mainbody .fa-link {
	color: #8ecbff !important;
}

#mainbody .fa-clock,
#mainbody .fa-language {
	color: var(--iform-muted) !important;
}

#mainbody table.border td > .fa-barcode,
#mainbody table.border td > .fa-cube,
#mainbody table.border td > .fa-box-open,
#mainbody table.border td > .fa-boxes {
	color: #c8b75a !important;
}

#mainbody table.border td > .fa-user,
#mainbody table.border td > .fa-user-alt,
#mainbody table.border td > .fa-user-tie {
	color: #b99155 !important;
}

#mainbody table.border td > .fa-info-circle {
	color: #778392 !important;
}

#mainbody .div-table-responsive .fa-phone,
#mainbody .div-table-responsive .fa-mobile-alt,
#mainbody .div-table-responsive .fa-fax,
#mainbody table.liste .fa-phone,
#mainbody table.liste .fa-mobile-alt,
#mainbody table.liste .fa-fax,
#mainbody table.noborder .fa-phone,
#mainbody table.noborder .fa-mobile-alt,
#mainbody table.noborder .fa-fax,
#mainbody .tagtable .fa-phone,
#mainbody .tagtable .fa-mobile-alt,
#mainbody .tagtable .fa-fax,
#mainbody .info-box-module .fa-phone,
#mainbody .info-box-module .fa-mobile-alt,
#mainbody .info-box-module .fa-fax,
#mainbody .pictomodule.fa-phone,
#mainbody .pictomodule.fa-mobile-alt,
#mainbody .pictomodule.fa-fax {
	color: #d6a43e !important;
}

#mainbody .infobox-adherent,
#mainbody .infobox-member,
#mainbody .infobox-expensereport {
	color: #b99155 !important;
}

#mainbody .infobox-holiday,
#mainbody .info-box-module .infobox-holiday,
#mainbody .pictomodule.infobox-holiday,
#mainbody .pictomodule.fa-umbrella-beach {
	color: #d6a43e !important;
}

#mainbody .info-box-module .infobox-portal,
#mainbody .info-box-module .fa-at,
#mainbody .info-box-module .fa-share-alt,
#mainbody .info-box-module .fa-globe-americas,
#mainbody .info-box-module .fa-check-double,
#mainbody .info-box-module .fa-cash-register,
#mainbody .info-box-module .fa-door-open,
#mainbody .pictomodule.infobox-portal,
#mainbody .pictomodule.fa-at,
#mainbody .pictomodule.fa-share-alt,
#mainbody .pictomodule.fa-globe-americas,
#mainbody .pictomodule.fa-check-double,
#mainbody .pictomodule.fa-cash-register,
#mainbody .pictomodule.fa-door-open {
	color: #b985f4 !important;
}

#mainbody .info-box-module .fa-print,
#mainbody .info-box-module .fa-business-time,
#mainbody .info-box-module .fa-cogs,
#mainbody .pictomodule.fa-print,
#mainbody .pictomodule.fa-business-time,
#mainbody .pictomodule.fa-cogs {
	color: #a9b3c0 !important;
}

#mainbody .pictowarning,
#mainbody .fa-exclamation-triangle.pictowarning {
	color: #ffd38a !important;
}

#mainbody img.ui-datepicker-trigger,
#mainbody img[src$="/object_calendarday.png"] {
	background: transparent !important;
	content: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Crect x='2.5' y='3.5' width='11' height='10' rx='1.5' fill='none' stroke='%2378b7ff' stroke-width='1.5'/%3E%3Cpath d='M5 2v3M11 2v3M3 6.5h10' stroke='%2378b7ff' stroke-width='1.5' stroke-linecap='round'/%3E%3Cpath d='M5 8.5h2v2H5zM9 8.5h2v2H9z' fill='%2378b7ff' opacity='.9'/%3E%3C/svg%3E") !important;
	filter: none !important;
	height: 14px !important;
	object-fit: contain !important;
	opacity: 0.95;
	vertical-align: middle;
	width: 14px !important;
}

#mainbody img[src$="/edit_add.png"] {
	filter: brightness(0) saturate(100%) invert(68%) sepia(62%) saturate(492%) hue-rotate(83deg) brightness(94%) contrast(92%) !important;
	opacity: 0.95;
}

#mainbody .AutoFillAmount img,
#mainbody img[src$="/rightarrow.png"] {
	filter: brightness(0) saturate(100%) invert(57%) sepia(93%) saturate(1816%) hue-rotate(190deg) brightness(101%) contrast(101%) !important;
	opacity: 0.95;
}

#mainbody .AutoFillAmount:hover img,
#mainbody .AutoFillAmount:focus img {
	filter: brightness(0) saturate(100%) invert(75%) sepia(77%) saturate(1183%) hue-rotate(181deg) brightness(102%) contrast(103%) !important;
	opacity: 1;
}

#id-top a.tmenuimage,
#id-top a.tmenuimage .fa,
#id-top a.tmenuimage .fas,
#id-top a.tmenuimage .far,
#id-top a.tmenuimage .fab {
	color: inherit !important;
}

#mainbody a:hover .pictofixedwidth[class*="fa-"],
#mainbody a:hover .fa-phone,
#mainbody a:hover .fa-mobile-alt,
#mainbody a:hover .fa-fax,
#mainbody a:hover .fa-at,
#mainbody a:hover .fa-external-link-alt,
#mainbody a:hover .fa-share-alt,
#mainbody a:hover .fa-map-marked-alt,
#mainbody a:hover .fa-map-marker-alt,
#mainbody a:hover .fa-map,
#mainbody a:hover .fa-globe-americas,
#mainbody a:hover .fa-info-circle {
	opacity: 1 !important;
}

.badge-status,
.badge,
.status,
.statusref,
.statusrefsupplier,
.statusdoc,
.badge.badge-status {
	border-color: rgba(78, 163, 255, 0.35) !important;
	border-width: 1px !important;
	box-shadow: none !important;
}

.statusref,
.statusrefsupplier,
.statusdoc {
	background: rgba(78, 163, 255, 0.12) !important;
	color: var(--iform-accent-hover) !important;
}

.badge-status.badge-status0,
.badge-status.badge-status2,
.badge-status.badge-status5,
.badge-status.badge-status6,
.badge-status.badge-status9,
.badge-status2,
.badge-status5,
.badge-status6,
.badge-status9,
.badge-status0 {
	background: rgba(169, 179, 192, 0.14) !important;
	border-color: rgba(169, 179, 192, 0.28) !important;
	color: #c8d0d9 !important;
}

.badge-status.badge-status1,
.badge-status.badge-status1b,
.badge-status.badge-status3,
.badge-status1,
.badge-status1b,
.badge-status3,
span.badge-status1b {
	background: rgba(214, 164, 62, 0.16) !important;
	border-color: rgba(214, 164, 62, 0.3) !important;
	color: #ffd38a !important;
}

.badge-status.badge-status4,
.badge-status.badge-status4b,
.badge-status.badge-status7,
.badge-status.badge-status11,
.badge-status4,
.badge-status4b,
.badge-status7,
.badge-status11,
span.badge-status4b {
	background: rgba(61, 180, 111, 0.14) !important;
	border-color: rgba(61, 180, 111, 0.3) !important;
	color: #83e5a1 !important;
}

.badge-status.badge-status8,
.badge-status.badge-status10,
.badge-status8,
.badge-status10 {
	background: rgba(255, 139, 139, 0.14) !important;
	border-color: rgba(255, 139, 139, 0.3) !important;
	color: #ffb4b4 !important;
}

div.statusref:has(.badge-status),
div.statusdoc:has(.badge-status),
div.statusrefsupplier:has(.badge-status) {
	background: transparent !important;
	border: 0 !important;
	border-radius: 0 !important;
	padding-left: 0 !important;
	padding-right: 0 !important;
}

div.statusref.statusrefbis,
div.statusdoc.statusrefbis,
div.statusrefsupplier.statusrefbis {
	background: rgba(78, 163, 255, 0.08) !important;
	border: 1px solid rgba(78, 163, 255, 0.18) !important;
	border-radius: 999px !important;
	color: var(--iform-muted) !important;
	padding: 2px 9px !important;
}

div.statusref.statusrefbis .opacitymedium,
div.statusdoc.statusrefbis .opacitymedium,
div.statusrefsupplier.statusrefbis .opacitymedium {
	color: var(--iform-muted) !important;
}

.jnotify-container .jnotify-notification {
	background: var(--iform-surface) !important;
	border-color: var(--iform-line) !important;
	box-shadow: 0 4px 14px rgba(0, 0, 0, 0.4);
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

.jnotify-container .jnotify-notification-ok .jnotify-background {
	background: rgba(61, 180, 111, 0.16) !important;
}

.jnotify-container .jnotify-notification-ok .jnotify-message,
.jnotify-container .jnotify-notification-ok .jnotify-close {
	color: #83e5a1 !important;
}

.jnotify-container .jnotify-notification-warning .jnotify-background {
	background: rgba(214, 164, 62, 0.18) !important;
}

.jnotify-container .jnotify-notification-warning .jnotify-message,
.jnotify-container .jnotify-notification-warning .jnotify-close {
	color: #ffd38a !important;
}

.jnotify-container .jnotify-notification-error .jnotify-background {
	background: rgba(255, 139, 139, 0.16) !important;
}

.jnotify-container .jnotify-notification-error .jnotify-message,
.jnotify-container .jnotify-notification-error .jnotify-close {
	color: #ffb4b4 !important;
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

div#ui-datepicker-div,
.ui-datepicker.ui-widget.ui-widget-content {
	background: var(--iform-surface-alt) !important;
	border-color: var(--iform-line) !important;
	box-shadow: 0 2px 14px rgba(0, 0, 0, 0.55) !important;
}

.ui-state-hover,
.ui-menu .ui-menu-item-wrapper.ui-state-active,
.ui-autocomplete .ui-menu-item-wrapper.ui-state-active {
	background: var(--iform-neutral-hover-bg) !important;
	color: var(--iform-accent-hover) !important;
}

.ui-dialog .ui-dialog-titlebar-close {
	background: var(--iform-surface-alt) !important;
	border: 1px solid var(--iform-line) !important;
	color: var(--iform-muted) !important;
	box-shadow: none !important;
}

.ui-dialog .ui-dialog-titlebar-close:hover,
.ui-dialog .ui-dialog-titlebar-close:focus,
.ui-dialog .ui-dialog-titlebar-close.ui-state-hover,
.ui-dialog .ui-dialog-titlebar-close.ui-state-focus {
	background: var(--iform-neutral-hover-bg) !important;
	border-color: rgba(78, 163, 255, 0.45) !important;
	color: var(--iform-accent-hover) !important;
}

.ui-dialog .ui-dialog-titlebar-close:focus-visible {
	outline: 0;
	box-shadow: 0 0 0 2px var(--iform-focus-ring) !important;
}

.ui-dialog .ui-dialog-titlebar-close .ui-icon-closethick {
	filter: brightness(1.85);
	opacity: 0.95;
}

.ui-dialog .ui-dialog-buttonpane .ui-button {
	background: var(--iform-surface-alt) !important;
	border: 1px solid var(--iform-line) !important;
	color: var(--iform-text) !important;
	box-shadow: none !important;
}

.ui-dialog .ui-dialog-buttonpane .ui-button:hover,
.ui-dialog .ui-dialog-buttonpane .ui-button:focus,
.ui-dialog .ui-dialog-buttonpane .ui-button.ui-state-hover,
.ui-dialog .ui-dialog-buttonpane .ui-button.ui-state-focus {
	background: var(--iform-neutral-hover-bg) !important;
	border-color: rgba(78, 163, 255, 0.45) !important;
	color: var(--iform-accent-hover) !important;
}

.ui-dialog .ui-dialog-buttonpane .ui-button:focus-visible {
	outline: 0;
	box-shadow: 0 0 0 2px var(--iform-focus-ring) !important;
}

.divadvancedsearchfieldcompinput,
.integrated-radio,
.flex-item-uploadfile,
span.userimg div.userphoto,
img.userphotosmall,
img.userphoto[alt="Gravatar avatar"],
img.photouserphoto.dropdown-user-image[alt="Gravatar avatar"] {
	background: var(--iform-surface-alt) !important;
	border-color: var(--iform-line) !important;
	color: var(--iform-text) !important;
}

div.pagination .disabled span,
div.pagination .disabled a {
	background: var(--iform-surface-alt) !important;
	border-color: var(--iform-line) !important;
	color: var(--iform-muted) !important;
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
	border-color: rgba(255, 139, 139, 0.3) !important;
	border-left-color: #ff8b8b !important;
	color: #ffb4b4 !important;
}

.messageok,
.ok {
	background: rgba(61, 180, 111, 0.14) !important;
	border-color: rgba(61, 180, 111, 0.28) !important;
	border-left-color: #3db46f !important;
	color: #83e5a1 !important;
}

.warning,
.messagewarning {
	background: rgba(214, 164, 62, 0.16) !important;
	border-color: rgba(214, 164, 62, 0.3) !important;
	border-left-color: #d6a43e !important;
	color: #ffd38a !important;
}

.info {
	background: var(--iform-accent-soft) !important;
	border-color: rgba(78, 163, 255, 0.28) !important;
	border-left-color: var(--iform-accent) !important;
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
.ecmfile,
.drag,
.dragHandle {
	background: var(--iform-surface-alt) !important;
	border-color: var(--iform-line) !important;
	color: var(--iform-text) !important;
}

.documentpreview img,
img.documentpreview,
.documentpreview object,
img[src*="modulepart=apercu"] {
	background: #ffffff !important;
}

div.fiche div.info,
div.fiche div.warning,
div.fiche div.neutral,
.firstcolumn table.noborder,
.secondcolumn table.noborder,
.shadow,
.boxshadow,
table.jPicker {
	box-shadow: 0 2px 10px rgba(0, 0, 0, 0.45) !important;
}

* {
	scrollbar-color: #3a4048 var(--iform-bg);
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

.box, .boxtable {
	scrollbar-color: var(--iform-line) transparent;
}
<?php
	print "}\n";
	}
	?>

	/* Final action/menu dropdown override: menu rows must not inherit primary button fills. */
	body#mainbody .dropdown-holder.open > .dropdown-content,
	body#mainbody .tabsAction .dropdown.open > .dropdown-menu,
	body#mainbody .tabsActionNoBottom .dropdown.open > .dropdown-menu,
	body#mainbody .tabBar .dropdown.open > .dropdown-menu {
		background-color: var(--iform-surface) !important;
		background-image: none !important;
		border: 1px solid var(--iform-line) !important;
		box-shadow: 0 8px 18px rgba(31, 35, 40, 0.12) !important;
		color: var(--iform-text) !important;
	}

	body#mainbody .dropdown-holder.open > .dropdown-content::before {
		border-color: transparent transparent var(--iform-surface) transparent !important;
	}

	body#mainbody .dropdown-holder > .dropdown-content > a.butAction,
	body#mainbody .dropdown-holder > .dropdown-content > a.butAction:link,
	body#mainbody .dropdown-holder > .dropdown-content > a.butAction:visited,
	body#mainbody .dropdown-holder > .dropdown-content > span.butAction,
	body#mainbody .dropdown-holder > .dropdown-content > a.butActionRefused,
	body#mainbody .dropdown-holder > .dropdown-content > span.butActionRefused,
	body#mainbody .tabsAction .dropdown-menu > .dropdown-item,
	body#mainbody .tabsActionNoBottom .dropdown-menu > .dropdown-item,
	body#mainbody .tabBar .dropdown-menu > .dropdown-item {
		background-color: transparent !important;
		background-image: none !important;
		border: 0 !important;
		border-bottom: 1px solid var(--iform-line-soft) !important;
		border-radius: 0 !important;
		box-sizing: border-box !important;
		box-shadow: none !important;
		color: var(--iform-text) !important;
		display: block !important;
		margin: 0 !important;
		min-width: 0 !important;
		text-align: left !important;
		width: 100% !important;
	}

	body#mainbody .dropdown-holder > .dropdown-content > a.butAction:hover,
	body#mainbody .dropdown-holder > .dropdown-content > a.butAction:focus,
	body#mainbody .tabsAction .dropdown-menu > .dropdown-item:hover,
	body#mainbody .tabsAction .dropdown-menu > .dropdown-item:focus,
	body#mainbody .tabsActionNoBottom .dropdown-menu > .dropdown-item:hover,
	body#mainbody .tabsActionNoBottom .dropdown-menu > .dropdown-item:focus,
	body#mainbody .tabBar .dropdown-menu > .dropdown-item:hover,
	body#mainbody .tabBar .dropdown-menu > .dropdown-item:focus {
		background-color: var(--iform-neutral-hover-bg) !important;
		background-image: none !important;
		border-bottom-color: var(--iform-line) !important;
		box-shadow: none !important;
		color: var(--iform-accent) !important;
		text-decoration: none !important;
	}

	body#mainbody .dropdown-holder > .dropdown-content > a.butActionDelete,
	body#mainbody .dropdown-holder > .dropdown-content > a.butActionDelete:link,
	body#mainbody .dropdown-holder > .dropdown-content > a.butActionDelete:visited {
		color: var(--iform-danger) !important;
	}

	body#mainbody .dropdown-holder > .dropdown-content > a.butActionDelete:hover,
	body#mainbody .dropdown-holder > .dropdown-content > a.butActionDelete:focus {
		background-color: var(--iform-danger-bg) !important;
		background-image: none !important;
		border-bottom-color: #ff8182 !important;
		color: var(--iform-danger) !important;
	}

	body#mainbody .dropdown-holder > .dropdown-content > a.butActionRefused,
	body#mainbody .dropdown-holder > .dropdown-content > span.butActionRefused,
	body#mainbody .dropdown-holder > .dropdown-content > a.butActionRefused:hover,
	body#mainbody .dropdown-holder > .dropdown-content > span.butActionRefused:hover {
		color: var(--iform-muted) !important;
		cursor: not-allowed !important;
	}

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
