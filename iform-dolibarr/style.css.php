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
	--iform-surface-alt: #f6f8fa;
	--iform-text: #1f2328;
	--iform-muted: #636e7b;
	--iform-danger: #cf222e;
	--iform-danger-bg: #ffebe9;
	--iform-danger-hover-bg: #ffd8d3;
	--iform-neutral-bg: #ffffff;
	--iform-neutral-hover-bg: #f6f8fa;
	--iform-refused-bg: #f6f8fa;
	--iform-focus-ring: rgba(31, 35, 40, 0.12);
	--iform-status-success-bg: #1a7f37;
	--iform-status-success-border: rgba(255, 255, 255, 0);
	--iform-status-success-text: #ffffff;
	--iform-icon-default: #57606a;
	--iform-icon-muted: #6e7781;
	--iform-icon-accent: #0969da;
	--iform-icon-danger: #cf222e;
	--iform-icon-warning: #9a6700;
	--iform-icon-success: #1a7f37;
	--iform-switch-knob: #ffffff;
	--iform-module-enabled-glow: rgba(26, 127, 55, 0.16);
	--iform-module-enabled-glow-soft: rgba(26, 127, 55, 0.05);
	--infoboxmoduleenabledbgcolor: transparent;
	--iform-symbol-search: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M8.7 14.2a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11Zm4-1.5 4.1 4.1' fill='none' stroke='black' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-refresh: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M16.2 7.2A6.4 6.4 0 0 0 4.1 5.4M3.6 3.1v3.9h3.9M3.8 12.8a6.4 6.4 0 0 0 12.1 1.8m.5 2.3v-3.9h-3.9' fill='none' stroke='black' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-list: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M5.5 5.2h11M5.5 10h11M5.5 14.8h11M2.8 5.2h.1M2.8 10h.1M2.8 14.8h.1' fill='none' stroke='black' stroke-width='2' stroke-linecap='round'/%3E%3C/svg%3E");
	--iform-symbol-grid: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M3.5 3.5h4.2v4.2H3.5zM12.3 3.5h4.2v4.2h-4.2zM3.5 12.3h4.2v4.2H3.5zM12.3 12.3h4.2v4.2h-4.2z' fill='none' stroke='black' stroke-width='1.7' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-plus: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M10 3.4v13.2M3.4 10h13.2' fill='none' stroke='black' stroke-width='2' stroke-linecap='round'/%3E%3C/svg%3E");
	--iform-symbol-edit: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='m4.1 13.8-.7 2.8 2.8-.7 9-9a2 2 0 0 0-2.8-2.8l-9 9Zm7.2-8.5 3.4 3.4' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-trash: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M4 6h12M8 6V4.5h4V6m-6.8 0 .7 10.2h8.2L14.8 6M8.3 9v4.5M11.7 9v4.5' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-close: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='m5.2 5.2 9.6 9.6M14.8 5.2l-9.6 9.6' fill='none' stroke='black' stroke-width='2' stroke-linecap='round'/%3E%3C/svg%3E");
	--iform-symbol-download: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M10 3.4v8.4m0 0 3.2-3.2M10 11.8 6.8 8.6M4.2 15.8h11.6' fill='none' stroke='black' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-preview: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M2.8 10s2.6-5 7.2-5 7.2 5 7.2 5-2.6 5-7.2 5-7.2-5-7.2-5Zm7.2 2.3a2.3 2.3 0 1 0 0-4.6 2.3 2.3 0 0 0 0 4.6Z' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-calendar: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M5.5 3v3M14.5 3v3M3.5 7.3h13M5.2 4.5h9.6a1.9 1.9 0 0 1 1.9 1.9v8.4a1.9 1.9 0 0 1-1.9 1.9H5.2a1.9 1.9 0 0 1-1.9-1.9V6.4a1.9 1.9 0 0 1 1.9-1.9Z' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-bolt: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M11.2 2.8 5 10.5h4.6l-.9 6.7 6.4-8.8h-4.8l.9-5.6Z' fill='none' stroke='black' stroke-width='1.75' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-sort-up: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='m5.3 12.2 4.7-4.7 4.7 4.7' fill='none' stroke='black' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-sort-down: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='m5.3 7.8 4.7 4.7 4.7-4.7' fill='none' stroke='black' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-info: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M10 17a7 7 0 1 0 0-14 7 7 0 0 0 0 14Zm0-7.6v4.1M10 6.6h.1' fill='none' stroke='black' stroke-width='1.8' stroke-linecap='round'/%3E%3C/svg%3E");
	--iform-symbol-warning: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M10 3.2 17 16H3L10 3.2Zm0 4.9v3.8M10 14.5h.1' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-check: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='m4 10.4 4 4L16.2 6' fill='none' stroke='black' stroke-width='2.1' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-print: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M6 7V3.8h8V7M6 14.2H4.7a1.9 1.9 0 0 1-1.9-1.9V9.1A2.1 2.1 0 0 1 4.9 7h10.2a2.1 2.1 0 0 1 2.1 2.1v3.2a1.9 1.9 0 0 1-1.9 1.9H14M6 12h8v4.4H6z' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-expand: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M7.8 4H4v3.8M12.2 4H16v3.8M7.8 16H4v-3.8M12.2 16H16v-3.8M4.4 4.4l4.5 4.5M15.6 4.4l-4.5 4.5M4.4 15.6l4.5-4.5M15.6 15.6l-4.5-4.5' fill='none' stroke='black' stroke-width='1.65' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-keyboard: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M4.2 5h11.6a1.7 1.7 0 0 1 1.7 1.7v6.6a1.7 1.7 0 0 1-1.7 1.7H4.2a1.7 1.7 0 0 1-1.7-1.7V6.7A1.7 1.7 0 0 1 4.2 5Zm1.5 3.2h.1m3 0h.1m3 0h.1m3 0h.1M5.7 11h.1m3 0h5.5' fill='none' stroke='black' stroke-width='1.65' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-shield: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M10 3.2 15.8 5v4.4c0 3.7-2 6.2-5.8 7.4-3.8-1.2-5.8-3.7-5.8-7.4V5L10 3.2Zm-2.4 6.7 1.7 1.7 3.3-3.4' fill='none' stroke='black' stroke-width='1.75' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-clipboard: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M7.5 4.5h5M7 3.5h6v2.4H7V3.5ZM5 5.3H4v11.2h12V5.3h-1M7 9.5h6M7 12.5h4' fill='none' stroke='black' stroke-width='1.65' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-building: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M5 16.5V3.5h10v13M3.6 16.5h12.8M8 6.5h.1M12 6.5h.1M8 9.5h.1M12 9.5h.1M8 12.5h.1M12 12.5h.1' fill='none' stroke='black' stroke-width='1.9' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-address-book: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M5.2 3.8h9.2a1.8 1.8 0 0 1 1.8 1.8v8.8a1.8 1.8 0 0 1-1.8 1.8H5.2a1.8 1.8 0 0 1-1.8-1.8V5.6a1.8 1.8 0 0 1 1.8-1.8ZM3.4 7h-1M3.4 13h-1M10 10a1.7 1.7 0 1 0 0-3.4 1.7 1.7 0 0 0 0 3.4Zm-3 3.8c.7-1.4 1.7-2.1 3-2.1s2.3.7 3 2.1' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-user: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M10 9.5a3.2 3.2 0 1 0 0-6.4 3.2 3.2 0 0 0 0 6.4Zm-6 7.3c1.2-3.1 3.2-4.6 6-4.6s4.8 1.5 6 4.6' fill='none' stroke='black' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-users: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M8.1 9.2a2.6 2.6 0 1 0 0-5.2 2.6 2.6 0 0 0 0 5.2Zm-5.1 7c.9-2.7 2.6-4 5.1-4s4.2 1.3 5.1 4M14 10a2.2 2.2 0 1 0 0-4.4M13.8 12.3c1.7.2 2.9 1.3 3.6 3.4' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-box: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='m10 2.8 6.5 3.5v7.4L10 17.2l-6.5-3.5V6.3L10 2.8Zm-6.1 3.7L10 9.8l6.1-3.3M10 9.8v7' fill='none' stroke='black' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-service: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M10 5.2V3.5M4.5 13.5a5.5 5.5 0 0 1 11 0M3.2 16.2h13.6M7.2 5.8h5.6' fill='none' stroke='black' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-doc: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M5 3.2h6.4L15 6.8v10H5v-13.6Zm6.2.3v3.6h3.5M7.5 10h5M7.5 13h5' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-doc-money: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M5 3.2h6.4L15 6.8v10H5v-13.6Zm6.2.3v3.6h3.5M10 9.2v5M8.4 10.3c.4-.6 1-.9 1.8-.9 1.1 0 1.8.5 1.8 1.2 0 1.8-3.8.8-3.8 2.6 0 .8.8 1.3 1.9 1.3.9 0 1.6-.3 2-.9' fill='none' stroke='black' stroke-width='1.55' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-doc-signature: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M5 3.2h6.4L15 6.8v10H5v-13.6Zm6.2.3v3.6h3.5M7.2 13.4c1.1-2.5 2.3-2.4 2.1-.7-.1 1.3.9 1.2 1.6.2.7-.9 1.4-.9 2 .2M7.2 15.2h5.8' fill='none' stroke='black' stroke-width='1.55' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-note: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M4.8 3.4h7.6l2.8 2.8v10.4H4.8V3.4Zm7.4.3v3h3M7.4 10h5.2M7.4 13h4' fill='none' stroke='black' stroke-width='1.65' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-truck: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M3 6h9v7H3V6Zm9 2.5h2.7l2.3 2.6V13h-5V8.5ZM6 15.2a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Zm8 0a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-briefcase: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M7.2 6V4.5h5.6V6M3.4 6h13.2v9.5H3.4V6Zm0 4.2c2.1 1 4.3 1.5 6.6 1.5s4.5-.5 6.6-1.5' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-project: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M3.7 4.4h4.6v4.6H3.7V4.4Zm8 6.6h4.6v4.6h-4.6V11ZM8.3 6.8h3.2c2.4 0 4.2 1.6 4.2 4.2M6 9v2c0 2.4 1.8 4 4.2 4h1.5' fill='none' stroke='black' stroke-width='1.75' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-task: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M4 5.5h2.5M9 5.5h7M4 10h2.5M9 10h7M4 14.5h2.5M9 14.5h7' fill='none' stroke='black' stroke-width='1.8' stroke-linecap='round'/%3E%3C/svg%3E");
	--iform-symbol-bank: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M3 7h14L10 3.4 3 7Zm2 2v5M9 9v5M15 9v5M3.5 16.5h13' fill='none' stroke='black' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-payment: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M3.5 5.5h13v9h-13v-9Zm0 3h13M7 12h3M13 11.8l1 1 2-2' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-wallet: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M4 6h11a2 2 0 0 1 2 2v7H4a2 2 0 0 1-2-2V5.8A1.8 1.8 0 0 1 3.8 4H14v2M13.2 10.5h3.5v2.7h-3.5a1.35 1.35 0 1 1 0-2.7Z' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-phone: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M6.3 3.5 4.2 5.1c-.5.4-.7 1.1-.4 1.7 1.8 4 5.4 7.5 9.4 9.4.6.3 1.3.1 1.7-.4l1.6-2.1-3.6-2.4-1.4 1.5a10.6 10.6 0 0 1-4.3-4.3l1.5-1.4-2.4-3.6Z' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-mobile: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M7 3.2h6a1.4 1.4 0 0 1 1.4 1.4v10.8a1.4 1.4 0 0 1-1.4 1.4H7a1.4 1.4 0 0 1-1.4-1.4V4.6A1.4 1.4 0 0 1 7 3.2Zm2.8 11.3h.4' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-mail: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M3.5 5.3h13v9.4h-13V5.3Zm.5.6 6 5 6-5' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-chat: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M4.4 5.2h11.2a2 2 0 0 1 2 2v5.1a2 2 0 0 1-2 2H9.3l-4.1 2v-2H4.4a2 2 0 0 1-2-2V7.2a2 2 0 0 1 2-2Z' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-send: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M3.5 10.2 16.7 3.8l-3.1 12.6-3.2-4.3-4.4 2 1.7-3.9-4.2 0Zm4.2 0 8.8-6.1' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-link: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M8.2 6.5 9.4 5.3a4 4 0 0 1 5.6 5.6l-1.7 1.7M11.8 13.5l-1.2 1.2A4 4 0 1 1 5 9.1l1.7-1.7M7.7 12.3l4.6-4.6' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-external: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M8 5H4.8a1.8 1.8 0 0 0-1.8 1.8v8.4A1.8 1.8 0 0 0 4.8 17h8.4a1.8 1.8 0 0 0 1.8-1.8V12M11 3h6v6M9.5 10.5 16.5 3.5' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-globe: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M10 17a7 7 0 1 0 0-14 7 7 0 0 0 0 14Zm0-14c2 1.8 3 4.1 3 7s-1 5.2-3 7m0-14c-2 1.8-3 4.1-3 7s1 5.2 3 7M3.5 10h13M4.8 6.4h10.4M4.8 13.6h10.4' fill='none' stroke='black' stroke-width='1.35' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-map: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='m3.5 5.4 4-1.8 5 1.8 4-1.8v11l-4 1.8-5-1.8-4 1.8v-11Zm4-1.8v11m5-9.2v11' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-folder: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M3.2 6.2h5l1.5 1.8h7.1v6.8A1.8 1.8 0 0 1 15 16.6H5a1.8 1.8 0 0 1-1.8-1.8V6.2Z' fill='none' stroke='black' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-tag: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M3.5 10.3 10.3 3.5h5.2v5.2l-6.8 6.8-5.2-5.2Zm9.2-4.2h.1' fill='none' stroke='black' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-barcode: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M4 4.5v11M7 4.5v11M10.5 4.5v11M13 4.5v11M16 4.5v11' fill='none' stroke='black' stroke-width='1.9' stroke-linecap='round'/%3E%3C/svg%3E");
	--iform-symbol-clock: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M10 17a7 7 0 1 0 0-14 7 7 0 0 0 0 14Zm0-10v3.6l2.4 1.5' fill='none' stroke='black' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-lock: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M5 8.5h10v7.8H5V8.5Zm2.2 0V6.8a2.8 2.8 0 0 1 5.6 0v1.7M10 12v1.8' fill='none' stroke='black' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-gear: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath fill='black' fill-rule='evenodd' d='M8.45 2.4h3.1l.35 2.05c.43.16.84.38 1.22.65l1.95-.7 1.55 2.68-1.58 1.33c.04.26.06.52.06.79s-.02.53-.06.79l1.58 1.33-1.55 2.68-1.95-.7c-.38.27-.79.49-1.22.65L11.55 16h-3.1l-.35-2.05a5.64 5.64 0 0 1-1.22-.65l-1.95.7-1.55-2.68 1.58-1.33A5.25 5.25 0 0 1 4.9 9.2c0-.27.02-.53.06-.79L3.38 7.08 4.93 4.4l1.95.7c.38-.27.79-.49 1.22-.65L8.45 2.4ZM10 11.85a2.65 2.65 0 1 0 0-5.3 2.65 2.65 0 0 0 0 5.3Z' clip-rule='evenodd'/%3E%3C/svg%3E");
	--iform-symbol-tools: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='m4 16 5.5-5.5M6.2 3.6 8.5 6 6 8.5 3.6 6.2M11.4 4.1l4.5 4.5m-1.2-5.7 2.4 2.4-8.9 8.9-2.4-2.4 8.9-8.9Z' fill='none' stroke='black' stroke-width='1.55' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-chart: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M4 16V4M4 16h12.5M6.5 12.5l3-3 2.2 2.1 4-5' fill='none' stroke='black' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-ticket: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M3.5 7.2a2.2 2.2 0 0 0 0 5.6v2h13v-2a2.2 2.2 0 0 0 0-5.6v-2h-13v2Zm7-1.8v9.2' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-magic: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='m5 15 8.7-8.7M11.8 4.4l3.8 3.8M4 4.5h.1M7 2.8v1.4M15.5 13.5h.1M17.2 10.5h-1.4M8.2 17.2v-1.4' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-server: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M4.5 3.6h11a1.6 1.6 0 0 1 1.6 1.6v2.3a1.6 1.6 0 0 1-1.6 1.6h-11a1.6 1.6 0 0 1-1.6-1.6V5.2a1.6 1.6 0 0 1 1.6-1.6Zm0 7.3h11a1.6 1.6 0 0 1 1.6 1.6v2.3a1.6 1.6 0 0 1-1.6 1.6h-11a1.6 1.6 0 0 1-1.6-1.6v-2.3a1.6 1.6 0 0 1 1.6-1.6ZM6 6.4h.1M6 13.6h.1M9 6.4h5M9 13.6h5' fill='none' stroke='black' stroke-width='1.55' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-home: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='m3.2 9 6.8-5.8L16.8 9M5 8.5v8h10v-8M8.2 16.5V12h3.6v4.5' fill='none' stroke='black' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-chevron-left: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='m12.5 4.5-5.5 5.5 5.5 5.5' fill='none' stroke='black' stroke-width='2.1' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-chevron-right: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='m7.5 4.5 5.5 5.5-5.5 5.5' fill='none' stroke='black' stroke-width='2.1' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-question: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M10 17a7 7 0 1 0 0-14 7 7 0 0 0 0 14Zm-2.1-8a2.2 2.2 0 1 1 3.5 1.8c-.9.6-1.4 1.1-1.4 2M10 15h.1' fill='none' stroke='black' stroke-width='1.65' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-source: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M4 6.2h4.2M4 13.8h4.2M8.2 10h7.2m0 0-2.9-2.9m2.9 2.9-2.9 2.9' fill='none' stroke='black' stroke-width='1.85' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-filter: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M3.5 5h13L11.5 10v4.5l-3 1.5v-6L3.5 5Z' fill='none' stroke='black' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-star: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='m10 3.2 2 4.1 4.5.7-3.3 3.2.8 4.6-4-2.1-4 2.1.8-4.6L3.5 8l4.5-.7 2-4.1Z' fill='none' stroke='black' stroke-width='1.7' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-play: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M6.5 4.5v11l9-5.5-9-5.5Z' fill='none' stroke='black' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-stop-circle: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M10 17a7 7 0 1 0 0-14 7 7 0 0 0 0 14Zm-2.2-9.2h4.4v4.4H7.8V7.8Z' fill='none' stroke='black' stroke-width='1.7' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-toggle-on: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M7 6h6a4 4 0 0 1 0 8H7a4 4 0 0 1 0-8Zm6 5.8a1.8 1.8 0 1 0 0-3.6 1.8 1.8 0 0 0 0 3.6Z' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-toggle-off: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M7 6h6a4 4 0 0 1 0 8H7a4 4 0 0 1 0-8Zm0 5.8a1.8 1.8 0 1 0 0-3.6 1.8 1.8 0 0 0 0 3.6Z' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-crop: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M6 3v11h11M3 6h11v11M14 3v3M3 14h3' fill='none' stroke='black' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-rss: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M5 15.5h.1M4.5 10.5a5 5 0 0 1 5 5M4.5 5.5a10 10 0 0 1 10 10' fill='none' stroke='black' stroke-width='2' stroke-linecap='round'/%3E%3C/svg%3E");
	--iform-symbol-balance: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M10 3.5v13M5 7h10M6 7l-3 5h6L6 7Zm8 0-3 5h6l-3-5ZM7 17h6' fill='none' stroke='black' stroke-width='1.6' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-ruler: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M4 13.5 13.5 4l2.5 2.5L6.5 16 4 13.5Zm3.1-.4 1.2 1.2M9.2 11l1.2 1.2M11.3 8.9l1.2 1.2M13.4 6.8 14.6 8' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-bug: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M6.7 8.1h6.6v5.1a3.3 3.3 0 0 1-6.6 0V8.1Zm1.6 0V6.3a1.7 1.7 0 0 1 3.4 0v1.8M4 10h2.7M13.3 10H16M4.8 5.5l2.4 2M12.8 7.5l2.4-2M4.8 15l2.2-1.4M13 13.6l2.2 1.4' fill='none' stroke='black' stroke-width='1.65' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-vial: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M8 3.8h4M10 4v5.6l4.4 5.6a1.2 1.2 0 0 1-1 2H6.6a1.2 1.2 0 0 1-1-2L10 9.6M7.3 13.2h5.4' fill='none' stroke='black' stroke-width='1.75' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--iform-symbol-archive: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M4 5h12v3H4V5Zm1.2 3v8h9.6V8M8.2 11h3.6' fill='none' stroke='black' stroke-width='1.75' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--inputbordercolor: rgba(208, 215, 222, 0.95);
	--colorboxstatsborder: #d0d7de;
	--colorboxiconbg: #f6f8fa;
	--fieldrequiredcolor: #cf222e;
}

body {
	color: var(--iform-text);
	background: var(--iform-bg);
}

.bodylogin .butActionLogin,
.bodylogin .butActionLogin:link,
.bodylogin .butActionLogin:visited,
.bodylogin .butActionLogin:hover,
.bodylogin .butActionLogin:active {
	color: var(--iform-text) !important;
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

#mainbody img[src$="/search.png"] {
	content: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M8.7 14.2a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11Zm4-1.5 4.1 4.1' fill='none' stroke='%236e7781' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E") !important;
}

#mainbody img[src$="/searchclear.png"],
#mainbody img[src$="/close.png"],
#mainbody img[src$="/close_title.png"] {
	content: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='m5.2 5.2 9.6 9.6M14.8 5.2l-9.6 9.6' fill='none' stroke='%236e7781' stroke-width='2' stroke-linecap='round'/%3E%3C/svg%3E") !important;
}

#mainbody img[src$="/refresh.png"] {
	content: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M16.2 7.2A6.4 6.4 0 0 0 4.1 5.4M3.6 3.1v3.9h3.9M3.8 12.8a6.4 6.4 0 0 0 12.1 1.8m.5 2.3v-3.9h-3.9' fill='none' stroke='%230969da' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E") !important;
}

#mainbody img[src$="/edit.png"],
#mainbody img[src$="/button_edit.png"] {
	content: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='m4.1 13.8-.7 2.8 2.8-.7 9-9a2 2 0 0 0-2.8-2.8l-9 9Zm7.2-8.5 3.4 3.4' fill='none' stroke='%236e7781' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E") !important;
}

#mainbody img[src$="/delete.png"],
#mainbody img[src$="/edit_remove.png"] {
	content: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M4 6h12M8 6V4.5h4V6m-6.8 0 .7 10.2h8.2L14.8 6M8.3 9v4.5M11.7 9v4.5' fill='none' stroke='%23cf222e' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E") !important;
}

#mainbody img[src$="/edit_add.png"],
#mainbody img[src$="/addfile.png"] {
	content: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M10 3.4v13.2M3.4 10h13.2' fill='none' stroke='%230969da' stroke-width='2' stroke-linecap='round'/%3E%3C/svg%3E") !important;
}

#mainbody img[src$="/sort_asc.png"],
#mainbody img[src$="/sort_asc_disabled.png"],
#mainbody img[src$="/uparrow.png"] {
	content: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='m5.3 12.2 4.7-4.7 4.7 4.7' fill='none' stroke='%236e7781' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E") !important;
}

#mainbody img[src$="/sort_desc.png"],
#mainbody img[src$="/sort_desc_disabled.png"] {
	content: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='m5.3 7.8 4.7 4.7 4.7-4.7' fill='none' stroke='%236e7781' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E") !important;
}

#mainbody img[src$="/filter.png"] {
	content: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M3.5 5h13L11.5 10v4.5l-3 1.5v-6L3.5 5Z' fill='none' stroke='%236e7781' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E") !important;
}

#mainbody img[src$="/rightarrow.png"] {
	content: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M4 5v4.5a3.5 3.5 0 0 0 3.5 3.5H16m0 0-3-3m3 3-3 3' fill='none' stroke='%230969da' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E") !important;
}

#mainbody img[src$="/search.png"],
#mainbody img[src$="/searchclear.png"],
#mainbody img[src$="/refresh.png"],
#mainbody img[src$="/edit.png"],
#mainbody img[src$="/button_edit.png"],
#mainbody img[src$="/delete.png"],
#mainbody img[src$="/edit_remove.png"],
#mainbody img[src$="/edit_add.png"],
#mainbody img[src$="/addfile.png"],
#mainbody img[src$="/sort_asc.png"],
#mainbody img[src$="/sort_asc_disabled.png"],
#mainbody img[src$="/sort_desc.png"],
#mainbody img[src$="/sort_desc_disabled.png"],
#mainbody img[src$="/filter.png"],
#mainbody img[src$="/uparrow.png"],
#mainbody img[src$="/rightarrow.png"],
#mainbody img[src$="/close.png"],
#mainbody img[src$="/close_title.png"] {
	background: transparent !important;
	filter: none !important;
	height: 16px !important;
	object-fit: contain !important;
	opacity: 0.95;
	vertical-align: middle;
	width: 16px !important;
}

#mainbody img[src$="/warning.png"],
#mainbody img[src$="/warning_white.png"],
#mainbody a.dashboardlineindicatorlate img,
#mainbody .imglatecoin img {
	background: transparent !important;
	content: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M10 3.2 17 16H3L10 3.2Zm0 4.9v3.8M10 14.5h.1' fill='none' stroke='%239a6700' stroke-width='1.85' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E") !important;
	filter: none !important;
	height: 16px !important;
	object-fit: contain !important;
	opacity: 0.95;
	vertical-align: middle;
	width: 16px !important;
}

body#mainbody :is(.fa-search, .fa-redo, .fa-sync, .fa-refresh, .fa-bars, .fa-stream, .fa-list, .fa-th-list, .fa-th, .fa-grip-horizontal, .fa-table, .fa-arrows-alt, .fa-expand-arrows-alt, .fa-plus-circle, .fa-plus, .fa-pencil-alt, .fa-edit, .fa-trash, .fa-times, .fa-remove, .fa-download, .fa-search-plus, .fa-eye, .fa-binoculars, .fa-calendar, .fa-calendar-alt, .fa-calendar-day, .fa-calendar-week, .fa-caret-up, .fa-caret-down, .fa-info-circle, .fa-question, .fa-exclamation-triangle, .fa-check, .fa-print)::before {
	background-color: currentColor;
	content: "" !important;
	display: inline-block;
	height: 1em;
	-webkit-mask: var(--iform-symbol-mask) center / contain no-repeat;
	mask: var(--iform-symbol-mask) center / contain no-repeat;
	vertical-align: -0.12em;
	width: 1em;
}

body#mainbody :is(.fa-search, .fa-bars, .fa-stream, .fa-list, .fa-th-list, .fa-th, .fa-grip-horizontal, .fa-table, .fa-arrows-alt, .fa-expand-arrows-alt, .fa-pencil-alt, .fa-edit, .fa-times, .fa-remove, .fa-caret-up, .fa-caret-down, .fa-info-circle, .fa-question, .fa-print) {
	color: var(--iform-icon-muted) !important;
}

body#mainbody :is(.fa-redo, .fa-sync, .fa-refresh, .fa-plus-circle, .fa-plus, .fa-download, .fa-search-plus, .fa-eye, .fa-binoculars, .fa-calendar, .fa-calendar-alt, .fa-calendar-day, .fa-calendar-week) {
	color: var(--iform-icon-accent) !important;
}

body#mainbody :is(.fa-trash) {
	color: var(--iform-icon-danger) !important;
}

body#mainbody :is(.fa-exclamation-triangle) {
	color: var(--iform-icon-warning) !important;
}

body#mainbody :is(.fa-check) {
	color: var(--iform-icon-success) !important;
}

#id-top :is(.fa-search, .fa-redo, .fa-sync, .fa-refresh, .fa-bars, .fa-stream, .fa-list, .fa-th-list, .fa-th, .fa-grip-horizontal, .fa-table, .fa-arrows-alt, .fa-expand-arrows-alt, .fa-plus-circle, .fa-plus, .fa-pencil-alt, .fa-edit, .fa-trash, .fa-times, .fa-remove, .fa-download, .fa-search-plus, .fa-eye, .fa-binoculars, .fa-calendar, .fa-calendar-alt, .fa-calendar-day, .fa-calendar-week, .fa-caret-up, .fa-caret-down, .fa-info-circle, .fa-exclamation-triangle, .fa-check, .fa-print),
.login_block :is(.fa-search, .fa-redo, .fa-sync, .fa-refresh, .fa-bars, .fa-stream, .fa-list, .fa-th-list, .fa-th, .fa-grip-horizontal, .fa-table, .fa-arrows-alt, .fa-expand-arrows-alt, .fa-plus-circle, .fa-plus, .fa-pencil-alt, .fa-edit, .fa-trash, .fa-times, .fa-remove, .fa-download, .fa-search-plus, .fa-eye, .fa-binoculars, .fa-calendar, .fa-calendar-alt, .fa-calendar-day, .fa-calendar-week, .fa-caret-up, .fa-caret-down, .fa-info-circle, .fa-exclamation-triangle, .fa-check, .fa-print) {
	color: inherit !important;
}

body#mainbody :is(.fa-search)::before {
	--iform-symbol-mask: var(--iform-symbol-search);
}

body#mainbody :is(.fa-redo, .fa-sync, .fa-refresh)::before {
	--iform-symbol-mask: var(--iform-symbol-refresh);
}

body#mainbody :is(.fa-bars, .fa-stream, .fa-list, .fa-th-list)::before {
	--iform-symbol-mask: var(--iform-symbol-list);
}

body#mainbody :is(.fa-th, .fa-grip-horizontal, .fa-table)::before {
	--iform-symbol-mask: var(--iform-symbol-grid);
}

body#mainbody :is(.fa-arrows-alt, .fa-expand-arrows-alt)::before {
	--iform-symbol-mask: var(--iform-symbol-expand);
}

body#mainbody :is(.fa-plus-circle, .fa-plus)::before {
	--iform-symbol-mask: var(--iform-symbol-plus);
}

body#mainbody :is(.fa-pencil-alt, .fa-edit)::before {
	--iform-symbol-mask: var(--iform-symbol-edit);
}

body#mainbody :is(.fa-trash)::before {
	--iform-symbol-mask: var(--iform-symbol-trash);
}

body#mainbody :is(.fa-times, .fa-remove)::before {
	--iform-symbol-mask: var(--iform-symbol-close);
}

body#mainbody :is(.fa-download)::before {
	--iform-symbol-mask: var(--iform-symbol-download);
}

body#mainbody :is(.fa-search-plus, .fa-eye, .fa-binoculars)::before {
	--iform-symbol-mask: var(--iform-symbol-preview);
}

body#mainbody :is(.fa-calendar, .fa-calendar-alt, .fa-calendar-day, .fa-calendar-week)::before {
	--iform-symbol-mask: var(--iform-symbol-calendar);
}

body#mainbody :is(.fa-caret-up)::before {
	--iform-symbol-mask: var(--iform-symbol-sort-up);
}

body#mainbody :is(.fa-caret-down)::before {
	--iform-symbol-mask: var(--iform-symbol-sort-down);
}

body#mainbody :is(.fa-info-circle)::before {
	--iform-symbol-mask: var(--iform-symbol-info);
}

body#mainbody :is(.fa-question)::before {
	--iform-symbol-mask: var(--iform-symbol-source);
}

body#mainbody :is(.fa-exclamation-triangle)::before {
	--iform-symbol-mask: var(--iform-symbol-warning);
}

#mainbody .fa-exclamation-triangle.hideonsmartphone::before,
#mainbody .fa-exclamation-triangle.pictowarning::before {
	height: 14px !important;
	vertical-align: -0.16em;
	width: 14px !important;
}

body#mainbody :is(.fa-check)::before {
	--iform-symbol-mask: var(--iform-symbol-check);
}

body#mainbody :is(.fa-print)::before {
	--iform-symbol-mask: var(--iform-symbol-print);
}

body#mainbody :is(.fa-building, .fa-address-book, .fa-address-card, .fa-user, .fa-user-alt, .fa-user-tie, .fa-user-friends, .fa-users, .fa-cube, .fa-cubes, .fa-box, .fa-box-open, .fa-boxes, .fa-barcode, .fa-concierge-bell, .fa-file, .fa-file-alt, .fa-file-invoice, .fa-file-invoice-dollar, .fa-file-signature, .fa-file-pdf, .fa-file-pdf-o, .fa-file-o, .fa-file-text-o, .fa-file-image-o, .fa-file-code-o, .fa-file-excel-o, .fa-file-word-o, .fa-file-powerpoint-o, .fa-file-video-o, .fa-file-audio-o, .fa-file-archive, .fa-file-archive-o, .fa-archive, .fa-paragraph, .fa-clipboard, .fa-clipboard-list, .fa-keyboard, .fa-shield-alt, .fa-sticky-note, .fa-note-sticky, .fa-bug, .fa-vial, .fa-flask, .fa-dolly, .fa-truck, .fa-truck-loading, .fa-ambulance, .fa-suitcase, .fa-project-diagram, .fa-tasks, .fa-university, .fa-money-check-alt, .fa-dollar-sign, .fa-coins, .fa-wallet, .fa-stripe-s, .fa-phone, .fa-mobile-alt, .fa-fax, .fa-at, .fa-envelope, .fa-comments, .fa-comment, .fa-comment-dots, .fa-paper-plane, .fa-link, .fa-unlink, .fa-external-link-alt, .fa-share-alt, .fa-globe, .fa-globe-americas, .fa-globe-europe, .fa-map, .fa-map-marked, .fa-map-marked-alt, .fa-map-marker-alt, .fa-folder, .fa-folder-open, .fa-tag, .fa-tags, .fa-clock, .fa-business-time, .fa-language, .fa-lock, .fa-key, .fa-cog, .fa-cogs, .fa-tools, .fa-wrench, .fa-chart-line, .fa-chart-bar, .fa-ticket-alt, .fa-magic, .fa-gift, .fa-handshake, .fa-umbrella-beach, .fa-cash-register, .fa-door-open, .fa-check-double, .fa-receipt, .fa-calculator, .fa-hand-holding-usd, .fa-money-bill-alt, .fa-search-dollar, .fa-layer-group, .fa-columns, .fa-history, .fa-bullseye, .fa-id-card-alt, .fa-id-badge, .fa-people-arrows, .fa-laptop-house, .fa-pallet, .fa-shapes, .fa-chalkboard-teacher, .fa-server, .fa-weather-level4, .fa-dol-action, .fa-dol-commande, .fa-dol-contrat, .fa-dol-cubes, .fa-dol-expensereport, .fa-dol-facture, .fa-dol-holiday, .fa-dol-member, .fa-dol-project, .fa-dol-propal, .fa-dol-ticket, .fa-dol-bank_account, .fa-dol-invoice_supplier, .fa-dol-order_supplier, .fa-dol-supplier_proposal)::before {
	background-color: currentColor;
	content: "" !important;
	display: inline-block;
	height: 1em;
	-webkit-mask: var(--iform-symbol-mask) center / contain no-repeat;
	mask: var(--iform-symbol-mask) center / contain no-repeat;
	vertical-align: -0.12em;
	width: 1em;
}

body#mainbody :is(.fa-building)::before {
	--iform-symbol-mask: var(--iform-symbol-building);
}

body#mainbody :is(.fa-address-book, .fa-address-card)::before {
	--iform-symbol-mask: var(--iform-symbol-address-book);
}

body#mainbody :is(.fa-user, .fa-user-alt, .fa-user-tie, .fa-dol-member)::before {
	--iform-symbol-mask: var(--iform-symbol-user);
}

body#mainbody :is(.fa-user-friends, .fa-users, .fa-people-arrows)::before {
	--iform-symbol-mask: var(--iform-symbol-users);
}

body#mainbody :is(.fa-cube, .fa-cubes, .fa-box, .fa-box-open, .fa-boxes, .fa-pallet, .fa-shapes, .fa-dol-cubes)::before {
	--iform-symbol-mask: var(--iform-symbol-box);
}

body#mainbody :is(.fa-concierge-bell)::before {
	--iform-symbol-mask: var(--iform-symbol-service);
}

body#mainbody :is(.fa-file, .fa-file-alt, .fa-file-o, .fa-file-text-o)::before {
	--iform-symbol-mask: var(--iform-symbol-doc);
}

body#mainbody :is(.fa-file-invoice, .fa-dol-commande, .fa-dol-order_supplier)::before {
	--iform-symbol-mask: var(--iform-symbol-doc);
}

body#mainbody :is(.fa-file-invoice-dollar, .fa-receipt, .fa-dol-facture, .fa-dol-invoice_supplier)::before {
	--iform-symbol-mask: var(--iform-symbol-doc-money);
}

body#mainbody :is(.fa-file-signature, .fa-dol-propal, .fa-dol-supplier_proposal)::before {
	--iform-symbol-mask: var(--iform-symbol-doc-signature);
}

body#mainbody :is(.fa-paragraph, .fa-sticky-note, .fa-note-sticky)::before {
	--iform-symbol-mask: var(--iform-symbol-note);
}

body#mainbody :is(.fa-file-pdf, .fa-file-pdf-o, .fa-file-image-o, .fa-file-code-o, .fa-file-excel-o, .fa-file-word-o, .fa-file-powerpoint-o, .fa-file-video-o, .fa-file-audio-o)::before {
	--iform-symbol-mask: var(--iform-symbol-doc);
}

body#mainbody :is(.fa-file-archive, .fa-file-archive-o, .fa-archive)::before {
	--iform-symbol-mask: var(--iform-symbol-archive);
}

body#mainbody :is(.fa-clipboard, .fa-clipboard-list)::before {
	--iform-symbol-mask: var(--iform-symbol-clipboard);
}

body#mainbody :is(.fa-keyboard)::before {
	--iform-symbol-mask: var(--iform-symbol-keyboard);
}

body#mainbody :is(.fa-shield-alt)::before {
	--iform-symbol-mask: var(--iform-symbol-shield);
}

body#mainbody :is(.fa-bug)::before {
	--iform-symbol-mask: var(--iform-symbol-bug);
}

body#mainbody :is(.fa-vial, .fa-flask)::before {
	--iform-symbol-mask: var(--iform-symbol-vial);
}

body#mainbody :is(.fa-dolly, .fa-truck, .fa-truck-loading, .fa-ambulance)::before {
	--iform-symbol-mask: var(--iform-symbol-truck);
}

body#mainbody :is(.fa-suitcase, .fa-dol-contrat)::before {
	--iform-symbol-mask: var(--iform-symbol-briefcase);
}

body#mainbody :is(.fa-project-diagram, .fa-dol-project)::before {
	--iform-symbol-mask: var(--iform-symbol-project);
}

body#mainbody :is(.fa-tasks)::before {
	--iform-symbol-mask: var(--iform-symbol-task);
}

body#mainbody :is(.fa-university, .fa-dol-bank_account)::before {
	--iform-symbol-mask: var(--iform-symbol-bank);
}

body#mainbody :is(.fa-money-check-alt, .fa-dollar-sign, .fa-coins, .fa-calculator, .fa-hand-holding-usd, .fa-search-dollar, .fa-stripe-s)::before {
	--iform-symbol-mask: var(--iform-symbol-payment);
}

body#mainbody :is(.fa-wallet, .fa-money-bill-alt, .fa-dol-expensereport)::before {
	--iform-symbol-mask: var(--iform-symbol-wallet);
}

body#mainbody :is(.fa-phone)::before {
	--iform-symbol-mask: var(--iform-symbol-phone);
}

body#mainbody :is(.fa-mobile-alt)::before {
	--iform-symbol-mask: var(--iform-symbol-mobile);
}

body#mainbody :is(.fa-fax)::before {
	--iform-symbol-mask: var(--iform-symbol-print);
}

body#mainbody :is(.fa-at, .fa-envelope)::before {
	--iform-symbol-mask: var(--iform-symbol-mail);
}

body#mainbody :is(.fa-comments, .fa-comment, .fa-comment-dots)::before {
	--iform-symbol-mask: var(--iform-symbol-chat);
}

body#mainbody :is(.fa-paper-plane)::before {
	--iform-symbol-mask: var(--iform-symbol-send);
}

body#mainbody :is(.fa-link, .fa-unlink, .fa-share-alt)::before {
	--iform-symbol-mask: var(--iform-symbol-link);
}

body#mainbody :is(.fa-external-link-alt, .fa-door-open)::before {
	--iform-symbol-mask: var(--iform-symbol-external);
}

body#mainbody :is(.fa-globe, .fa-globe-americas, .fa-globe-europe)::before {
	--iform-symbol-mask: var(--iform-symbol-globe);
}

body#mainbody :is(.fa-map, .fa-map-marked, .fa-map-marked-alt, .fa-map-marker-alt)::before {
	--iform-symbol-mask: var(--iform-symbol-map);
}

body#mainbody :is(.fa-folder, .fa-folder-open)::before {
	--iform-symbol-mask: var(--iform-symbol-folder);
}

body#mainbody :is(.fa-tag, .fa-tags, .fa-layer-group)::before {
	--iform-symbol-mask: var(--iform-symbol-tag);
}

body#mainbody :is(.fa-barcode)::before {
	--iform-symbol-mask: var(--iform-symbol-barcode);
}

body#mainbody :is(.fa-clock, .fa-business-time, .fa-history)::before {
	--iform-symbol-mask: var(--iform-symbol-clock);
}

body#mainbody :is(.fa-language)::before {
	--iform-symbol-mask: var(--iform-symbol-globe);
}

body#mainbody :is(.fa-lock, .fa-key)::before {
	--iform-symbol-mask: var(--iform-symbol-lock);
}

body#mainbody :is(.fa-cog, .fa-cogs)::before {
	--iform-symbol-mask: var(--iform-symbol-gear);
}

body#mainbody :is(.fa-tools, .fa-wrench)::before {
	--iform-symbol-mask: var(--iform-symbol-tools);
}

body#mainbody :is(.fa-chart-line, .fa-chart-bar)::before {
	--iform-symbol-mask: var(--iform-symbol-chart);
}

body#mainbody :is(.fa-ticket-alt, .fa-dol-ticket)::before {
	--iform-symbol-mask: var(--iform-symbol-ticket);
}

body#mainbody :is(.fa-magic)::before {
	--iform-symbol-mask: var(--iform-symbol-magic);
}

body#mainbody :is(.fa-server)::before {
	--iform-symbol-mask: var(--iform-symbol-server);
}

body#mainbody :is(.fa-weather-level4)::before {
	--iform-symbol-mask: var(--iform-symbol-bolt);
}

body#mainbody :is(.fa-dol-action)::before {
	--iform-symbol-mask: var(--iform-symbol-calendar);
}

body#mainbody :is(.fa-gift, .fa-handshake, .fa-umbrella-beach, .fa-cash-register, .fa-check-double, .fa-id-card-alt, .fa-id-badge, .fa-laptop-house, .fa-chalkboard-teacher, .fa-bullseye, .fa-columns)::before {
	--iform-symbol-mask: var(--iform-symbol-service);
}

body#mainbody :is(.fa-dol-holiday)::before {
	--iform-symbol-mask: var(--iform-symbol-service);
}

body#mainbody :is(.fa-home, .fa-chevron-left, .fa-chevron-right, .fa-angle-left, .fa-angle-right, .fa-angle-double-left, .fa-angle-double-right, .fa-arrow-left, .fa-arrow-right, .fa-arrow-up, .fa-arrow-down, .fa-long-arrow-alt-left, .fa-long-arrow-alt-right, .fa-long-arrow-alt-up, .fa-long-arrow-alt-down, .fa-arrow-alt-circle-left, .fa-arrow-alt-circle-right, .fa-arrow-alt-circle-down, .fa-question-circle, .fa-filter, .fa-star, .fa-play, .fa-stop-circle, .fa-toggle-on, .fa-toggle-off, .fa-crop, .fa-rss, .fa-balance-scale, .fa-ruler, .fa-pencil-ruler, .fa-fill, .fa-exchange-alt, .fa-code-branch, .fa-square, .fa-check-square, .fa-plus-square, .fa-indent, .fa-list-alt, .fa-mars, .fa-venus, .fa-transgender, .fa-genderless, .fa-calendar-check, .fa-eye-slash, .fa-file-export)::before {
	background-color: currentColor;
	content: "" !important;
	display: inline-block;
	height: 1em;
	-webkit-mask: var(--iform-symbol-mask) center / contain no-repeat;
	mask: var(--iform-symbol-mask) center / contain no-repeat;
	vertical-align: -0.12em;
	width: 1em;
}

body#mainbody :is(.fa-home)::before {
	--iform-symbol-mask: var(--iform-symbol-home);
}

body#mainbody :is(.fa-chevron-left, .fa-angle-left, .fa-angle-double-left, .fa-arrow-left, .fa-long-arrow-alt-left, .fa-arrow-alt-circle-left)::before {
	--iform-symbol-mask: var(--iform-symbol-chevron-left);
}

body#mainbody :is(.fa-chevron-right, .fa-angle-right, .fa-angle-double-right, .fa-arrow-right, .fa-long-arrow-alt-right, .fa-arrow-alt-circle-right, .fa-arrow-alt-circle-down)::before {
	--iform-symbol-mask: var(--iform-symbol-chevron-right);
}

body#mainbody :is(.fa-arrow-up, .fa-long-arrow-alt-up)::before {
	--iform-symbol-mask: var(--iform-symbol-sort-up);
}

body#mainbody :is(.fa-arrow-down, .fa-long-arrow-alt-down)::before {
	--iform-symbol-mask: var(--iform-symbol-sort-down);
}

body#mainbody :is(.fa-question-circle)::before {
	--iform-symbol-mask: var(--iform-symbol-question);
}

body#mainbody :is(.fa-indent, .fa-list-alt)::before {
	--iform-symbol-mask: var(--iform-symbol-list);
}

body#mainbody :is(.fa-filter)::before {
	--iform-symbol-mask: var(--iform-symbol-filter);
}

body#mainbody :is(.fa-star)::before {
	--iform-symbol-mask: var(--iform-symbol-star);
}

body#mainbody :is(.fa-play)::before {
	--iform-symbol-mask: var(--iform-symbol-play);
}

body#mainbody :is(.fa-stop-circle)::before {
	--iform-symbol-mask: var(--iform-symbol-stop-circle);
}

body#mainbody :is(.fa-toggle-on)::before {
	--iform-symbol-mask: var(--iform-symbol-toggle-on);
}

body#mainbody :is(.fa-toggle-off)::before {
	--iform-symbol-mask: var(--iform-symbol-toggle-off);
}

body#mainbody :is(.fa-crop, .fa-fill)::before {
	--iform-symbol-mask: var(--iform-symbol-crop);
}

body#mainbody :is(.fa-rss)::before {
	--iform-symbol-mask: var(--iform-symbol-rss);
}

body#mainbody :is(.fa-balance-scale)::before {
	--iform-symbol-mask: var(--iform-symbol-balance);
}

body#mainbody :is(.fa-ruler)::before {
	--iform-symbol-mask: var(--iform-symbol-ruler);
}

body#mainbody :is(.fa-pencil-ruler)::before {
	--iform-symbol-mask: var(--iform-symbol-tools);
}

body#mainbody :is(.fa-exchange-alt)::before {
	--iform-symbol-mask: var(--iform-symbol-refresh);
}

body#mainbody :is(.fa-calendar-check)::before {
	--iform-symbol-mask: var(--iform-symbol-calendar);
}

body#mainbody :is(.fa-eye-slash)::before {
	--iform-symbol-mask: var(--iform-symbol-preview);
}

body#mainbody :is(.fa-file-export)::before {
	--iform-symbol-mask: var(--iform-symbol-download);
}

body#mainbody :is(.fa-mars, .fa-venus, .fa-transgender, .fa-genderless)::before {
	--iform-symbol-mask: var(--iform-symbol-user);
}

body#mainbody :is(.fa-code-branch)::before {
	--iform-symbol-mask: var(--iform-symbol-project);
}

body#mainbody :is(.fa-square, .fa-check-square, .fa-plus-square)::before {
	--iform-symbol-mask: var(--iform-symbol-check);
}

#mainbody :is(.pictotitle.widthpictotitle, .widthpictotitle.pictotitle)[class*="fa-"],
#mainbody .table-fiche-title :is(.pictotitle, .widthpictotitle)[class*="fa-"],
#mainbody .titre :is(.pictotitle, .widthpictotitle)[class*="fa-"] {
	align-items: center;
	display: inline-flex;
	font-size: 22.56px !important;
	justify-content: center;
	line-height: 1 !important;
	min-width: 34px;
	vertical-align: middle;
}

#mainbody :is(.pictotitle.widthpictotitle, .widthpictotitle.pictotitle)[class*="fa-"]::before,
#mainbody .table-fiche-title :is(.pictotitle, .widthpictotitle)[class*="fa-"]::before,
#mainbody .titre :is(.pictotitle, .widthpictotitle)[class*="fa-"]::before {
	height: 30px !important;
	width: 30px !important;
}

#id-left :is(.vmenu, .menu_titre, .vmenu_titre) :is(.pictofixedwidth[class*="fa-"], .fa[class*="fa-"])::before {
	height: 1.22em;
	vertical-align: -0.18em;
	width: 1.22em;
}

#id-left :is(.vmenu, .menu_titre, .vmenu_titre) :is(.em088, .em092).pictofixedwidth[class*="fa-"]::before {
	height: 1.38em;
	width: 1.38em;
}

#id-left :is(.pictofixedwidth[class*="fa-"], .fa[class*="fa-"], .fas[class*="fa-"], .far[class*="fa-"], .fab[class*="fa-"]) {
	align-items: center;
	display: inline-flex;
	justify-content: center;
	line-height: 1 !important;
	min-width: 24px;
	vertical-align: middle;
}

#id-left :is(.pictofixedwidth[class*="fa-"], .fa[class*="fa-"], .fas[class*="fa-"], .far[class*="fa-"], .fab[class*="fa-"])::before {
	height: 18px !important;
	width: 18px !important;
}

#mainbody .pictomodule[class*="fa-"] {
	align-items: center;
	display: inline-flex;
	justify-content: center;
	line-height: 1;
	min-width: 20px;
	vertical-align: middle;
}

#mainbody .pictomodule[class*="fa-"]::before {
	height: 20px;
	vertical-align: -0.24em;
	width: 20px;
}

#mainbody .pictomodule[class*="fa-"]:is(.em080, .em088, .em092)::before {
	height: 20px;
	width: 20px;
}

#mainbody :is(.em080, .em088, .em092).pictofixedwidth[class*="fa-"] {
	align-items: center;
	display: inline-flex;
	justify-content: center;
	line-height: 1;
	min-width: 22px;
	vertical-align: middle;
}

#mainbody :is(.em080, .em088, .em092).pictofixedwidth[class*="fa-"]::before {
	height: 1.14em !important;
	width: 1.14em !important;
}

#mainbody :is(table.border, .tableforfield, .tagtable, .fichecenter) :is(.em080, .em088, .em092).pictofixedwidth[class*="fa-"]::before {
	height: 20px !important;
	width: 20px !important;
}

#mainbody :is(table.border, .tableforfield, .tagtable, .fichecenter) .pictofixedwidth[class*="fa-"] {
	align-items: center;
	display: inline-flex;
	justify-content: center;
	line-height: 1 !important;
	min-width: 24px;
	text-align: center;
	vertical-align: middle;
	width: 24px;
}

#mainbody :is(table.border, .tableforfield, .tagtable, .fichecenter) .pictofixedwidth[class*="fa-"]::before {
	height: 20px !important;
	transform: none !important;
	width: 20px !important;
}

#id-left a.vmenu[title="设置"] :is(.pictofixedwidth[class*="fa-"], .fa[class*="fa-"], .fas[class*="fa-"], .far[class*="fa-"], .fab[class*="fa-"]) {
	align-items: center;
	color: inherit !important;
	display: inline-flex;
	font-size: 18px !important;
	justify-content: center;
	line-height: 1 !important;
	min-width: 28px;
	vertical-align: middle;
}

#id-left a.vmenu[title="设置"] :is(.pictofixedwidth[class*="fa-"], .fa[class*="fa-"], .fas[class*="fa-"], .far[class*="fa-"], .fab[class*="fa-"])::before {
	height: 18px !important;
	--iform-symbol-mask: var(--iform-symbol-gear) !important;
	width: 18px !important;
}

body#mainbody #id-left :is(.menu_titre, .vmenu_titre) :is(.pictofixedwidth[class*="fa-"], .fa[class*="fa-"], .fas[class*="fa-"], .far[class*="fa-"], .fab[class*="fa-"]) {
	align-items: center;
	display: inline-flex;
	font-size: 18px !important;
	justify-content: center;
	line-height: 1 !important;
	min-width: 28px;
	vertical-align: middle;
}

body#mainbody #id-left :is(.menu_titre, .vmenu_titre) :is(.pictofixedwidth[class*="fa-"], .fa[class*="fa-"], .fas[class*="fa-"], .far[class*="fa-"], .fab[class*="fa-"])::before {
	height: 18px !important;
	width: 18px !important;
}

#mainbody :is(table.border, .tableforfield, .tagtable, .fichecenter) .fa-history.paddingright {
	align-items: center;
	display: inline-flex;
	justify-content: center;
	line-height: 1;
	min-width: 20px;
	vertical-align: middle;
}

#mainbody :is(table.border, .tableforfield, .tagtable, .fichecenter) .fa-history.paddingright::before {
	height: 1.22em !important;
	width: 1.22em !important;
}

#mainbody :is(.fa-sticky-note, .fa-note-sticky) {
	align-items: center;
	display: inline-flex;
	justify-content: center;
	line-height: 1;
	vertical-align: middle;
}

#mainbody :is(.fa-sticky-note, .fa-note-sticky)::before {
	height: 1.05em !important;
	width: 1.05em !important;
}

#mainbody .boxstatsindicator :is(.fa, .fas, .far, .fab)[class*="fa-"] {
	align-items: center;
	display: inline-flex;
	font-size: 19.552px !important;
	justify-content: center;
	line-height: 1 !important;
	min-width: 20px;
	vertical-align: -0.18em;
}

#mainbody .boxstatsindicator :is(.fa, .fas, .far, .fab)[class*="fa-"]::before {
	height: 20px !important;
	width: 20px !important;
}

body#mainbody :is(.fa-toggle-on, .fa-toggle-off) {
	display: inline-block;
	height: 18px;
	line-height: 0;
	position: relative;
	text-align: left;
	vertical-align: -3px;
	width: 34px;
}

body#mainbody :is(.fa-toggle-on, .fa-toggle-off)::before {
	background-color: currentColor !important;
	border-radius: 999px;
	content: "" !important;
	display: block;
	height: 100%;
	left: 0;
	-webkit-mask: none !important;
	mask: none !important;
	position: absolute;
	top: 0;
	width: 100%;
}

body#mainbody :is(.fa-toggle-on, .fa-toggle-off)::after {
	background: var(--iform-switch-knob);
	border-radius: 999px;
	box-shadow: 0 1px 2px rgba(31, 35, 40, 0.22);
	content: "";
	display: block;
	height: 10px;
	position: absolute;
	top: 4px;
	width: 10px;
}

body#mainbody .fa-toggle-on::after {
	left: 20px;
}

body#mainbody .fa-toggle-off::after {
	left: 4px;
}

body#mainbody .fa-toggle-on {
	color: var(--iform-icon-success) !important;
}

body#mainbody .fa-toggle-off {
	color: var(--iform-icon-muted) !important;
}

#mainbody .fa-globe-americas,
#mainbody .fa-map,
#mainbody .fa-map-marked,
#mainbody .fa-map-marked-alt,
#mainbody .fa-map-marker-alt {
	color: #64748b !important;
}

#mainbody .infobox-bank_account,
#mainbody .fa-dol-bank_account,
#mainbody .fa-money-check-alt,
#mainbody .fa-university,
#mainbody .fa-dollar-sign,
#mainbody .fa-clipboard-list {
	color: #7a6d10 !important;
}

#mainbody .infobox-contrat,
#mainbody .infobox-ticket,
#mainbody .fa-dol-ticket,
#mainbody .fa-suitcase,
#mainbody .fa-ambulance,
#mainbody .fa-ticket-alt {
	color: #238b7f !important;
}

#mainbody .classfortooltip .fa-question-circle,
#mainbody .fa-question-circle.classfortooltip,
#mainbody .leaveuserbalance .fa-question-circle {
	color: #64748b !important;
}

#mainbody .divsearchfield .fa-square.pictofixedwidth {
	color: #8f99a7 !important;
}

body.bodytakepos .topnav a,
body.bodytakepos .topnav .fa,
body.bodytakepos .topnav .fas,
body.bodytakepos .topnav .far,
body.bodytakepos .topnav .fab {
	color: #4b5563 !important;
}

body.bodytakepos .topnav .fa,
body.bodytakepos .topnav .fas,
body.bodytakepos .topnav .far,
body.bodytakepos .topnav .fab {
	color: inherit !important;
}

body.bodytakepos .topnav {
	border-bottom: 1px solid #d0d7de !important;
}

body.bodytakepos .topnav-left a:hover:not(.nohover),
body.bodytakepos .topnav .login_block_other a:hover:not(.nohover) {
	background-color: #e6ebf0 !important;
	color: #1f2937 !important;
}

#id-top a.tmenuimage,
#id-top a.tmenuimage .fa,
#id-top a.tmenuimage .fas,
#id-top a.tmenuimage .far,
#id-top a.tmenuimage .fab {
	color: inherit !important;
}

#id-top a.tmenuimage {
	align-items: center;
	display: flex;
	height: 24px;
	justify-content: center;
	line-height: 24px;
	margin-bottom: 0;
}

#id-top a.tmenuimage[class*="fa-"]::before,
#id-top a.tmenuimage :is(.fa, .fas, .far, .fab)[class*="fa-"]::before {
	color: inherit !important;
	height: 20px !important;
	vertical-align: -0.16em;
	width: 20px !important;
}

#id-top a.tmenuimage :is(.fa, .fas, .far, .fab)[class*="fa-"] {
	align-items: center;
	display: inline-flex;
	justify-content: center;
	min-width: 24px;
}

#id-top div.mainmenu {
	align-items: center;
	display: flex;
	height: 24px;
	justify-content: center;
	line-height: 24px;
}

#id-top a.tmenulabel {
	display: inline-block;
	line-height: 18px;
	margin-top: 2px;
	transform: translateY(0);
}

#id-top li.tmenu,
#id-top li.tmenusel {
	margin-left: 12px;
}

#mainbody td.center > a > .fa-plus-circle {
	font-size: 18px !important;
	line-height: 1 !important;
	vertical-align: middle;
}

#mainbody td.center > a > .fa-plus-circle::before {
	height: 18px !important;
	width: 18px !important;
}

.info-box.info-box-sm .info-box-status {
	position: absolute;
	right: 8px;
	bottom: 6px;
}

.info-box .info-box-status .badge {
	align-items: center;
	box-sizing: border-box;
	display: inline-flex !important;
	justify-content: center;
	line-height: 14px !important;
	min-height: 18px;
	padding: 1px 7px !important;
	vertical-align: middle;
}

.info-box .info-box-status .badge + .badge {
	margin-left: 6px;
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
	height: calc(100% - 6px);
	margin-bottom: 3px;
	margin-top: 3px;
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
	background: var(--iform-danger) !important;
	background-image: none !important;
	border: 1px solid var(--iform-danger) !important;
	color: #fff !important;
	box-shadow: none !important;
}

#mainbody .butActionDelete:hover,
#mainbody .buttonDelete:hover:not(:disabled) {
	background: #a40e26 !important;
	background-image: none !important;
	border-color: #a40e26 !important;
	color: #fff !important;
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

#mainbody a.btnTitle,
#mainbody .btnTitle {
	background: transparent !important;
	border-color: var(--iform-line) !important;
	box-shadow: none !important;
	color: var(--iform-muted) !important;
}

#mainbody .box-flex-container.kanban .box-flex-item.info-box-module,
#mainbody .box-flex-container.kanban .box-flex-item.info-box-module .info-box,
#mainbody .box-flex-container.kanban .box-flex-item.info-box-module .info-box-icon,
#mainbody .box-flex-container.kanban .box-flex-item.info-box-module .info-box-content {
	box-sizing: border-box;
}

#mainbody .box-flex-container.kanban .box-flex-item.info-box-module .info-box {
	background: var(--iform-surface) !important;
	background-image: none !important;
	border-color: var(--iform-line) !important;
	overflow: hidden;
	position: relative;
}

#mainbody .box-flex-container.kanban .box-flex-item.info-box-module .info-box-icon {
	background: var(--iform-surface-alt) !important;
	background-image: none !important;
	position: relative;
	z-index: 1;
}

#mainbody .box-flex-container.kanban .box-flex-item.info-box-module .info-box-content,
#mainbody .box-flex-container.kanban .box-flex-item.info-box-module .info-box-module-enabled {
	background: transparent !important;
	background-image: none !important;
	position: relative;
	z-index: 1;
}

#mainbody .box-flex-container.kanban .box-flex-item.info-box-module:not(.--disabled) .info-box::after {
	background: radial-gradient(circle at calc(100% - 42px) calc(100% - 18px), var(--iform-module-enabled-glow) 0%, var(--iform-module-enabled-glow-soft) 38%, transparent 74%);
	bottom: 0;
	content: "";
	pointer-events: none;
	position: absolute;
	right: 0;
	top: 0;
	width: 58%;
	z-index: 0;
}

table.border,
table.noborder,
table.liste,
.div-table-responsive,
.div-table-responsive-no-min {
	border-color: var(--iform-line);
}

#mainbody .fichehalfleft table.noborder.centpercent,
#mainbody .fichehalfright table.noborder.centpercent,
#mainbody .boxhalfleft table.noborder.centpercent,
#mainbody .boxhalfright table.noborder.centpercent {
	border: 1px solid var(--iform-line) !important;
	border-color: var(--iform-line) !important;
}

div.liste_titre_bydiv,
div.liste_titre_bydiv_nothingafter,
#mainbody .liste_titre.liste_titre_bydiv {
	border-color: var(--iform-line) !important;
}

#mainbody .div-table-responsive > table.liste:not(.listwithfilterbefore),
#mainbody .div-table-responsive-no-min > table.liste:not(.listwithfilterbefore) {
	border-collapse: separate !important;
	border-radius: 8px !important;
	border-spacing: 0 !important;
	overflow: hidden;
}

#mainbody .div-table-responsive > table.liste:not(.listwithfilterbefore) > :is(thead, tbody):first-child > tr:first-child > :is(td, th):first-child,
#mainbody .div-table-responsive-no-min > table.liste:not(.listwithfilterbefore) > :is(thead, tbody):first-child > tr:first-child > :is(td, th):first-child {
	border-top-left-radius: 8px !important;
}

#mainbody .div-table-responsive > table.liste:not(.listwithfilterbefore) > :is(thead, tbody):first-child > tr:first-child > :is(td, th):last-child,
#mainbody .div-table-responsive-no-min > table.liste:not(.listwithfilterbefore) > :is(thead, tbody):first-child > tr:first-child > :is(td, th):last-child {
	border-top-right-radius: 8px !important;
}

#mainbody .div-table-responsive > table.liste.listwithfilterbefore,
#mainbody .div-table-responsive-no-min > table.liste.listwithfilterbefore {
	border-top-left-radius: 0 !important;
	border-top-right-radius: 0 !important;
	overflow: visible;
}

#mainbody .div-table-responsive > table.liste.listwithfilterbefore > :is(thead, tbody):first-child > tr:first-child > :is(td, th),
#mainbody .div-table-responsive-no-min > table.liste.listwithfilterbefore > :is(thead, tbody):first-child > tr:first-child > :is(td, th) {
	border-top-left-radius: 0 !important;
	border-top-right-radius: 0 !important;
}

#mainbody table.liste,
#mainbody table.tagtable.liste,
#mainbody .div-table-responsive > table.liste,
#mainbody .div-table-responsive-no-min > table.liste {
	border-color: var(--iform-line) !important;
}

#mainbody table.liste :is(tr, td, th),
#mainbody table.tagtable.liste :is(tr, td, th),
#mainbody table.liste > :is(thead, tbody, tfoot) > tr > :is(td, th) {
	border-color: var(--iform-line) !important;
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

.badge-status.badge-status6[data-status-element="facture"][data-status="2"],
.badge-status.badge-status6[data-status-element="invoice_supplier"][data-status="2"],
.badge-status.badge-status6[data-status-element="commande"][data-status="3"] {
	background: var(--iform-status-success-bg) !important;
	border-color: var(--iform-status-success-border) !important;
	color: var(--iform-status-success-text) !important;
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

#mainbody .badge.badge-warning:has(.fa-exclamation-triangle) {
	align-items: center;
	box-sizing: border-box;
	display: inline-flex !important;
	gap: 5px;
	line-height: 14px !important;
	min-height: 18px;
	padding: 1px 7px !important;
	position: relative;
	top: 0;
	vertical-align: middle;
}

#mainbody .badge.badge-warning .fa-exclamation-triangle {
	align-items: center;
	border-color: transparent !important;
	box-shadow: none !important;
	color: inherit !important;
	display: inline-flex !important;
	height: 12px;
	justify-content: center;
	line-height: 1 !important;
	margin: 0;
	width: 12px;
}

#mainbody .badge.badge-warning .fa-exclamation-triangle.hideonsmartphone {
	display: inline-flex !important;
}

#mainbody .badge.badge-warning .fa-exclamation-triangle::before {
	height: 12px !important;
	vertical-align: 0;
	width: 12px !important;
}

#mainbody :is(.dpInvisibleButtons, .datenowlink) {
	border-color: var(--iform-line) !important;
	box-shadow: none !important;
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
	border: 1px solid var(--iform-line);
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
	--iform-status-success-bg: rgba(61, 180, 111, 0.14);
	--iform-status-success-border: rgba(61, 180, 111, 0.3);
	--iform-status-success-text: #83e5a1;
	--iform-icon-default: #a9b3c0;
	--iform-icon-muted: #8f99a7;
	--iform-icon-accent: #78b7ff;
	--iform-icon-danger: #ff8b8b;
	--iform-icon-warning: #ffd38a;
	--iform-icon-success: #83e5a1;
	--iform-switch-knob: #1c1f22;
	--iform-module-enabled-glow: rgba(61, 180, 111, 0.28);
	--iform-module-enabled-glow-soft: rgba(61, 180, 111, 0.08);
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
		--infoboxmoduleenabledbgcolor: transparent;
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

.bodylogin .login_table input#username,
.bodylogin .login_table input#password,
.bodylogin .login_table input#securitycode {
	background: transparent !important;
}

.bodylogin .login_table .tagtable {
	background: transparent !important;
	border-color: transparent !important;
	box-shadow: none !important;
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
	border-left-color: transparent !important;
	border-right-color: transparent !important;
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
	border-color: var(--iform-line) !important;
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
	border-color: var(--iform-line) !important;
}

#mainbody .fichehalfleft table.noborder.centpercent,
#mainbody .fichehalfright table.noborder.centpercent,
#mainbody .boxhalfleft table.noborder.centpercent,
#mainbody .boxhalfright table.noborder.centpercent {
	border: 1px solid var(--iform-line) !important;
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

#mainbody .div-table-responsive,
#mainbody .div-table-responsive-no-min,
#mainbody table.liste,
#mainbody table.tagtable.liste,
#mainbody .div-table-responsive > table.liste,
#mainbody .div-table-responsive-no-min > table.liste {
	border-color: var(--iform-line) !important;
}

#mainbody table.liste :is(tr, td, th),
#mainbody table.tagtable.liste :is(tr, td, th),
#mainbody table.liste > :is(thead, tbody, tfoot) > tr > :is(td, th) {
	border-color: var(--iform-line) !important;
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
.select2-container--default .select2-results__option--highlighted[aria-selected] span:not(.flag-sprite):not([class*="flag"]):not(.fi):not([class^="fi-"]):not([class*=" fi-"]),
.select2-results .select2-highlighted,
.select2-results .select2-highlighted span:not(.flag-sprite):not([class*="flag"]):not(.fi):not([class^="fi-"]):not([class*=" fi-"]) {
	background: var(--iform-accent-soft) !important;
	color: var(--iform-accent-hover) !important;
}

.select2-container--default .select2-results__option--highlighted[aria-selected] :is(.flag-sprite, img),
.select2-container--default .select2-results__option--highlighted[aria-selected] [class*="flag"],
.select2-container--default .select2-results__option--highlighted[aria-selected] :is(.fi, [class^="fi-"], [class*=" fi-"]),
.select2-results .select2-highlighted :is(.flag-sprite, img),
.select2-results .select2-highlighted [class*="flag"],
.select2-results .select2-highlighted :is(.fi, [class^="fi-"], [class*=" fi-"]) {
	background-color: transparent !important;
	color: inherit !important;
	filter: none !important;
	opacity: 1 !important;
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

#mainbody .pictofixedwidth.fa-question,
#mainbody .pictomodule.fa-bug,
#mainbody .pictomodule.fa-vial,
#mainbody .pictomodule.fa-flask,
#mainbody .pictomodule.fa-file-archive,
#mainbody .pictomodule.fa-file-archive-o,
#mainbody .pictomodule.fa-archive,
#mainbody .info-box-module .fa-bug,
#mainbody .info-box-module .fa-vial,
#mainbody .info-box-module .fa-flask,
#mainbody .info-box-module .fa-file-archive,
#mainbody .info-box-module .fa-file-archive-o,
#mainbody .info-box-module .fa-archive {
	color: var(--iform-icon-muted) !important;
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
#mainbody .pictomodule.fa-umbrella-beach,
#mainbody .fa-dol-holiday,
#mainbody .fa-dol-holiday::before {
	color: #d6a43e !important;
}

#mainbody .fa-weather-level0::before {
	color: #f0d56a !important;
}

#mainbody .fa-weather-level1::before,
#mainbody .fa-weather-level2::before {
	color: #ffd38a !important;
}

#mainbody .fa-weather-level3::before {
	color: #ffb86b !important;
}

#mainbody .fa-weather-level4::before {
	color: #ff8b8b !important;
}

#mainbody .infobox-portal,
#mainbody .infobox-portal::before,
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

#mainbody .info .fa-info-circle,
#mainbody .messageinfo .fa-info-circle,
#mainbody .messagedebug .fa-info-circle,
#mainbody .wordbreak.info .fa-info-circle {
	color: var(--iform-accent-hover) !important;
}

#mainbody .a-filter,
#mainbody .a-mesure {
	background: var(--iform-accent-soft) !important;
	border: 1px solid rgba(78, 163, 255, 0.35) !important;
	box-shadow: none !important;
	color: var(--iform-accent-hover) !important;
}

#mainbody .a-filter-disabled,
#mainbody .a-mesure-disabled {
	background: var(--iform-surface-alt) !important;
	border: 1px solid var(--iform-line) !important;
	box-shadow: none !important;
	color: var(--iform-muted) !important;
	opacity: 1 !important;
}

#mainbody .websitebar {
	background: var(--iform-surface-alt) !important;
	border-bottom-color: var(--iform-line) !important;
	color: var(--iform-text) !important;
}

#mainbody .websitebar .button,
#mainbody .websitebar .buttonDelete {
	background: var(--iform-surface) !important;
	border-color: var(--iform-line) !important;
	color: var(--iform-text) !important;
	box-shadow: none !important;
}

#mainbody .websitebar .button:hover,
#mainbody .websitebar .button:focus,
#mainbody .websitebar .button.bordertransp:hover,
#mainbody .websitebar .button.bordertransp:focus {
	background: var(--iform-neutral-hover-bg) !important;
	border-color: rgba(78, 163, 255, 0.45) !important;
	color: var(--iform-accent-hover) !important;
}

#mainbody .websitebar input#previewpageurl,
#mainbody .websitebar input.bordertransp {
	background: var(--inputbackgroundcolor) !important;
	border-color: var(--inputbordercolor) !important;
	color: var(--iform-text) !important;
}

#mainbody .websitebar .fa-plus-circle.btnTitle-icon {
	color: var(--iform-accent-hover) !important;
}

#mainbody img[src$="/opensurvey/img/calendar-32.png"],
#mainbody img[src$="/opensurvey/img/chart-32.png"],
#mainbody img[src$="/img/calendar-32.png"],
#mainbody img[src$="/img/chart-32.png"] {
	filter: brightness(0) saturate(100%) invert(69%) sepia(93%) saturate(705%) hue-rotate(180deg) brightness(101%) contrast(101%) !important;
	opacity: 0.96;
}

#mainbody div.corps:has(.index_date, .index_sondage),
#mainbody div.corps:has(.opensurveydescription),
#mainbody .survey_intro {
	background: var(--iform-surface) !important;
	border-color: var(--iform-line) !important;
	box-shadow: none !important;
	color: var(--iform-text) !important;
}

#mainbody .index_date,
#mainbody .index_sondage {
	color: var(--iform-text) !important;
}

body.bodytakepos {
	background: var(--iform-bg) !important;
	color: var(--iform-text) !important;
}

body.bodytakepos .topnav {
	background: var(--iform-surface) !important;
	border-bottom: 1px solid var(--iform-line) !important;
}

body.bodytakepos .topnav a,
body.bodytakepos .topnav .fa,
body.bodytakepos .topnav .fas,
body.bodytakepos .topnav .far,
body.bodytakepos .topnav .fab {
	color: var(--iform-text) !important;
}

body.bodytakepos .topnav .fa,
body.bodytakepos .topnav .fas,
body.bodytakepos .topnav .far,
body.bodytakepos .topnav .fab {
	color: inherit !important;
}

body.bodytakepos .topnav-left a:hover:not(.nohover),
body.bodytakepos .topnav .login_block_other a:hover:not(.nohover) {
	background: var(--iform-neutral-hover-bg) !important;
	color: var(--iform-accent-hover) !important;
}

body.bodytakepos .modal-content {
	background: var(--iform-surface) !important;
	border: 1px solid var(--iform-line) !important;
	box-shadow: 0 12px 30px rgba(0, 0, 0, 0.45) !important;
	color: var(--iform-text) !important;
}

body.bodytakepos .wrapper,
body.bodytakepos .wrapper2 {
	background: var(--iform-surface) !important;
	border: 1px solid var(--iform-line) !important;
	box-shadow: none !important;
	color: var(--iform-text) !important;
}

body.bodytakepos .wrapper.divempty,
body.bodytakepos .wrapper2.divempty {
	background: var(--iform-surface-alt) !important;
	border-color: var(--iform-line-soft) !important;
}

body.bodytakepos .description,
body.bodytakepos .catwatermark {
	background: rgba(21, 23, 25, 0.72) !important;
	color: var(--iform-text) !important;
}

body.bodytakepos input,
body.bodytakepos select,
body.bodytakepos textarea {
	background: var(--inputbackgroundcolor) !important;
	border-color: var(--inputbordercolor) !important;
	color: var(--iform-text) !important;
}

#mainbody img[src$="/warning.png"],
#mainbody img[src$="/warning_white.png"],
#mainbody a.dashboardlineindicatorlate img,
#mainbody .imglatecoin img {
	content: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M10 3.2 17 16H3L10 3.2Zm0 4.9v3.8M10 14.5h.1' fill='none' stroke='%23ffd38a' stroke-width='1.85' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E") !important;
	opacity: 1;
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

#mainbody img[src$="/search.png"] {
	content: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M8.7 14.2a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11Zm4-1.5 4.1 4.1' fill='none' stroke='%238f99a7' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E") !important;
}

#mainbody img[src$="/searchclear.png"],
#mainbody img[src$="/close.png"],
#mainbody img[src$="/close_title.png"] {
	content: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='m5.2 5.2 9.6 9.6M14.8 5.2l-9.6 9.6' fill='none' stroke='%238f99a7' stroke-width='2' stroke-linecap='round'/%3E%3C/svg%3E") !important;
}

#mainbody img[src$="/refresh.png"] {
	content: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M16.2 7.2A6.4 6.4 0 0 0 4.1 5.4M3.6 3.1v3.9h3.9M3.8 12.8a6.4 6.4 0 0 0 12.1 1.8m.5 2.3v-3.9h-3.9' fill='none' stroke='%2378b7ff' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E") !important;
}

#mainbody img[src$="/edit.png"],
#mainbody img[src$="/button_edit.png"] {
	content: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='m4.1 13.8-.7 2.8 2.8-.7 9-9a2 2 0 0 0-2.8-2.8l-9 9Zm7.2-8.5 3.4 3.4' fill='none' stroke='%238f99a7' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E") !important;
}

#mainbody img[src$="/delete.png"],
#mainbody img[src$="/edit_remove.png"] {
	content: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M4 6h12M8 6V4.5h4V6m-6.8 0 .7 10.2h8.2L14.8 6M8.3 9v4.5M11.7 9v4.5' fill='none' stroke='%23ff8b8b' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E") !important;
}

#mainbody img[src$="/edit_add.png"],
#mainbody img[src$="/addfile.png"] {
	content: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M10 3.4v13.2M3.4 10h13.2' fill='none' stroke='%2378b7ff' stroke-width='2' stroke-linecap='round'/%3E%3C/svg%3E") !important;
}

#mainbody img[src$="/sort_asc.png"],
#mainbody img[src$="/sort_asc_disabled.png"],
#mainbody img[src$="/uparrow.png"] {
	content: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='m5.3 12.2 4.7-4.7 4.7 4.7' fill='none' stroke='%238f99a7' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E") !important;
}

#mainbody img[src$="/sort_desc.png"],
#mainbody img[src$="/sort_desc_disabled.png"] {
	content: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='m5.3 7.8 4.7 4.7 4.7-4.7' fill='none' stroke='%238f99a7' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E") !important;
}

#mainbody img[src$="/filter.png"] {
	content: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M3.5 5h13L11.5 10v4.5l-3 1.5v-6L3.5 5Z' fill='none' stroke='%238f99a7' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E") !important;
}

#mainbody img[src$="/rightarrow.png"],
#mainbody .AutoFillAmount img {
	content: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M4 5v4.5a3.5 3.5 0 0 0 3.5 3.5H16m0 0-3-3m3 3-3 3' fill='none' stroke='%2378b7ff' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E") !important;
}

#id-top a.tmenuimage,
#id-top a.tmenuimage .fa,
#id-top a.tmenuimage .fas,
#id-top a.tmenuimage .far,
#id-top a.tmenuimage .fab {
	color: inherit !important;
}

#id-top a.tmenuimage[class*="fa-"]::before,
#id-top a.tmenuimage :is(.fa, .fas, .far, .fab)[class*="fa-"]::before {
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

.badgeneutral,
span.badgeneutral {
	background: rgba(169, 179, 192, 0.14) !important;
	border: 1px solid rgba(169, 179, 192, 0.28) !important;
	box-shadow: none !important;
	color: #c8d0d9 !important;
	line-height: 1.15;
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

.badge-status.badge-status6[data-status-element="facture"][data-status="2"],
.badge-status.badge-status6[data-status-element="invoice_supplier"][data-status="2"],
.badge-status.badge-status6[data-status-element="commande"][data-status="3"] {
	background: var(--iform-status-success-bg) !important;
	border-color: var(--iform-status-success-border) !important;
	color: var(--iform-status-success-text) !important;
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

.ui-widget-overlay.ui-front,
.ui-widget-overlay {
	background: rgba(0, 0, 0, 0.58) !important;
	opacity: 1 !important;
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
