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
 *		\file       htdocs/theme/formora/style.css.php
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
$theme = 'formora'; // Value of theme
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

// This theme deliberately keeps Eldy's layout code but freezes the Formora color set.
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

/* Formora skin layer: visual polish over Eldy's native layout. */
:root {
	--formora-accent: #0071e3;
	--formora-accent-hover: #005bb5;
	--formora-accent-soft-hover: rgba(0, 113, 227, 0.16);
	--formora-line: #c9cdd3;
	--formora-line-soft: #e6e8ec;
	--formora-bg: #f5f5f7;
	--formora-surface: #ffffff;
	--formora-surface-alt: #f5f5f7;
	--formora-text: #1d1d1f;
	--formora-muted: #6e6e73;
	--formora-danger: #d70015;
	--formora-danger-bg: #fff1f1;
	--formora-danger-hover-bg: #ffe1e4;
	--formora-toast-success-bg: #f0fbf3;
	--formora-toast-success-border: rgba(36, 138, 61, 0.28);
	--formora-toast-success-text: #1a7f37;
	--formora-toast-warning-bg: #fff8e6;
	--formora-toast-warning-border: rgba(178, 106, 0, 0.32);
	--formora-toast-warning-text: #9a6700;
	--formora-toast-error-bg: #fff1f1;
	--formora-toast-error-border: rgba(215, 0, 21, 0.32);
	--formora-toast-error-text: #d70015;
	--formora-neutral-bg: #fbfbfd;
	--formora-neutral-hover-bg: #f2f2f4;
	--formora-refused-bg: #f5f5f7;
	--formora-focus-ring: rgba(0, 113, 227, 0.18);
	--formora-focus-outline: #005bb5;
	--formora-status-success-bg: #248a3d;
	--formora-status-success-border: rgba(36, 138, 61, 0.16);
	--formora-status-success-text: #ffffff;
	--formora-icon-default: #5f6368;
	--formora-icon-muted: #86868b;
	--formora-icon-accent: #0071e3;
	--formora-icon-date: #57606a;
	--formora-icon-danger: #d70015;
	--formora-icon-warning: #b26a00;
	--formora-icon-success: #248a3d;
	--formora-infobox-action: #906080;
	--formora-infobox-bank: #7a6d10;
	--formora-infobox-contract: #238b7f;
	--formora-infobox-customer: #65953d;
	--formora-infobox-holiday: #755114;
	--formora-infobox-member: #79633f;
	--formora-infobox-project: #6c6aa8;
	--formora-infobox-stock: #b0a53e;
	--formora-infobox-supplier: #599caf;
	--formora-radius-sm: 6px;
	--formora-radius: 8px;
	--formora-radius-lg: 12px;
	--formora-control-height: 34px;
	--formora-shadow-soft: 0 1px 2px rgba(0, 0, 0, 0.05), 0 8px 24px rgba(0, 0, 0, 0.06);
	--formora-shadow-elevated: 0 16px 42px rgba(0, 0, 0, 0.16);
	--formora-shadow-menu: 0 10px 28px rgba(0, 0, 0, 0.14);
	--formora-elevated: #ffffff;
	--formora-table-header: #f5f5f7;
	--formora-switch-knob: #ffffff;
	--formora-module-enabled-glow: rgba(26, 127, 55, 0.16);
	--formora-module-enabled-glow-soft: rgba(26, 127, 55, 0.05);
	--infoboxmoduleenabledbgcolor: transparent;
	--formora-symbol-search: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M8.7 14.2a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11Zm4-1.5 4.1 4.1' fill='none' stroke='black' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-refresh: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M16.2 7.2A6.4 6.4 0 0 0 4.1 5.4M3.6 3.1v3.9h3.9M3.8 12.8a6.4 6.4 0 0 0 12.1 1.8m.5 2.3v-3.9h-3.9' fill='none' stroke='black' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-list: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M5.5 5.2h11M5.5 10h11M5.5 14.8h11M2.8 5.2h.1M2.8 10h.1M2.8 14.8h.1' fill='none' stroke='black' stroke-width='2' stroke-linecap='round'/%3E%3C/svg%3E");
	--formora-symbol-grid: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M3.5 3.5h4.2v4.2H3.5zM12.3 3.5h4.2v4.2h-4.2zM3.5 12.3h4.2v4.2H3.5zM12.3 12.3h4.2v4.2h-4.2z' fill='none' stroke='black' stroke-width='1.7' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-plus: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M10 3.4v13.2M3.4 10h13.2' fill='none' stroke='black' stroke-width='2' stroke-linecap='round'/%3E%3C/svg%3E");
	--formora-symbol-edit: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='m4.1 13.8-.7 2.8 2.8-.7 9-9a2 2 0 0 0-2.8-2.8l-9 9Zm7.2-8.5 3.4 3.4' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-trash: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M4 6h12M8 6V4.5h4V6m-6.8 0 .7 10.2h8.2L14.8 6M8.3 9v4.5M11.7 9v4.5' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-close: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='m5.2 5.2 9.6 9.6M14.8 5.2l-9.6 9.6' fill='none' stroke='black' stroke-width='2' stroke-linecap='round'/%3E%3C/svg%3E");
	--formora-symbol-download: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M10 3.4v8.4m0 0 3.2-3.2M10 11.8 6.8 8.6M4.2 15.8h11.6' fill='none' stroke='black' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-preview: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M2.8 10s2.6-5 7.2-5 7.2 5 7.2 5-2.6 5-7.2 5-7.2-5-7.2-5Zm7.2 2.3a2.3 2.3 0 1 0 0-4.6 2.3 2.3 0 0 0 0 4.6Z' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-calendar: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M5.5 3v3M14.5 3v3M3.5 7.3h13M5.2 4.5h9.6a1.9 1.9 0 0 1 1.9 1.9v8.4a1.9 1.9 0 0 1-1.9 1.9H5.2a1.9 1.9 0 0 1-1.9-1.9V6.4a1.9 1.9 0 0 1 1.9-1.9Z' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-bolt: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M11.2 2.8 5 10.5h4.6l-.9 6.7 6.4-8.8h-4.8l.9-5.6Z' fill='none' stroke='black' stroke-width='1.75' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-sort-up: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='m5.3 12.2 4.7-4.7 4.7 4.7' fill='none' stroke='black' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-sort-down: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='m5.3 7.8 4.7 4.7 4.7-4.7' fill='none' stroke='black' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-info: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M10 17a7 7 0 1 0 0-14 7 7 0 0 0 0 14Zm0-7.6v4.1M10 6.6h.1' fill='none' stroke='black' stroke-width='1.8' stroke-linecap='round'/%3E%3C/svg%3E");
	--formora-symbol-warning: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M10 3.2 17 16H3L10 3.2Zm0 4.9v3.8M10 14.5h.1' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-check: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='m4 10.4 4 4L16.2 6' fill='none' stroke='black' stroke-width='2.1' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-print: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M6 7V3.8h8V7M6 14.2H4.7a1.9 1.9 0 0 1-1.9-1.9V9.1A2.1 2.1 0 0 1 4.9 7h10.2a2.1 2.1 0 0 1 2.1 2.1v3.2a1.9 1.9 0 0 1-1.9 1.9H14M6 12h8v4.4H6z' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-expand: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M7.8 4H4v3.8M12.2 4H16v3.8M7.8 16H4v-3.8M12.2 16H16v-3.8M4.4 4.4l4.5 4.5M15.6 4.4l-4.5 4.5M4.4 15.6l4.5-4.5M15.6 15.6l-4.5-4.5' fill='none' stroke='black' stroke-width='1.65' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-keyboard: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M4.2 5h11.6a1.7 1.7 0 0 1 1.7 1.7v6.6a1.7 1.7 0 0 1-1.7 1.7H4.2a1.7 1.7 0 0 1-1.7-1.7V6.7A1.7 1.7 0 0 1 4.2 5Zm1.5 3.2h.1m3 0h.1m3 0h.1m3 0h.1M5.7 11h.1m3 0h5.5' fill='none' stroke='black' stroke-width='1.65' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-shield: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M10 3.2 15.8 5v4.4c0 3.7-2 6.2-5.8 7.4-3.8-1.2-5.8-3.7-5.8-7.4V5L10 3.2Zm-2.4 6.7 1.7 1.7 3.3-3.4' fill='none' stroke='black' stroke-width='1.75' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-clipboard: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M7.5 4.5h5M7 3.5h6v2.4H7V3.5ZM5 5.3H4v11.2h12V5.3h-1M7 9.5h6M7 12.5h4' fill='none' stroke='black' stroke-width='1.65' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-building: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M5 16.5V3.5h10v13M3.6 16.5h12.8M8 6.5h.1M12 6.5h.1M8 9.5h.1M12 9.5h.1M8 12.5h.1M12 12.5h.1' fill='none' stroke='black' stroke-width='1.9' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-address-book: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M5.2 3.8h9.2a1.8 1.8 0 0 1 1.8 1.8v8.8a1.8 1.8 0 0 1-1.8 1.8H5.2a1.8 1.8 0 0 1-1.8-1.8V5.6a1.8 1.8 0 0 1 1.8-1.8ZM3.4 7h-1M3.4 13h-1M10 10a1.7 1.7 0 1 0 0-3.4 1.7 1.7 0 0 0 0 3.4Zm-3 3.8c.7-1.4 1.7-2.1 3-2.1s2.3.7 3 2.1' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-user: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M10 9.5a3.2 3.2 0 1 0 0-6.4 3.2 3.2 0 0 0 0 6.4Zm-6 7.3c1.2-3.1 3.2-4.6 6-4.6s4.8 1.5 6 4.6' fill='none' stroke='black' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-users: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M8.1 9.2a2.6 2.6 0 1 0 0-5.2 2.6 2.6 0 0 0 0 5.2Zm-5.1 7c.9-2.7 2.6-4 5.1-4s4.2 1.3 5.1 4M14 10a2.2 2.2 0 1 0 0-4.4M13.8 12.3c1.7.2 2.9 1.3 3.6 3.4' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-box: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='m10 2.8 6.5 3.5v7.4L10 17.2l-6.5-3.5V6.3L10 2.8Zm-6.1 3.7L10 9.8l6.1-3.3M10 9.8v7' fill='none' stroke='black' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-service: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M10 5.2V3.5M4.5 13.5a5.5 5.5 0 0 1 11 0M3.2 16.2h13.6M7.2 5.8h5.6' fill='none' stroke='black' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-doc: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M5 3.2h6.4L15 6.8v10H5v-13.6Zm6.2.3v3.6h3.5M7.5 10h5M7.5 13h5' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-doc-money: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M5 3.2h6.4L15 6.8v10H5v-13.6Zm6.2.3v3.6h3.5M10 9.2v5M8.4 10.3c.4-.6 1-.9 1.8-.9 1.1 0 1.8.5 1.8 1.2 0 1.8-3.8.8-3.8 2.6 0 .8.8 1.3 1.9 1.3.9 0 1.6-.3 2-.9' fill='none' stroke='black' stroke-width='1.55' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-doc-signature: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M5 3.2h6.4L15 6.8v10H5v-13.6Zm6.2.3v3.6h3.5M7.2 13.4c1.1-2.5 2.3-2.4 2.1-.7-.1 1.3.9 1.2 1.6.2.7-.9 1.4-.9 2 .2M7.2 15.2h5.8' fill='none' stroke='black' stroke-width='1.55' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-note: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M4.8 3.4h7.6l2.8 2.8v10.4H4.8V3.4Zm7.4.3v3h3M7.4 10h5.2M7.4 13h4' fill='none' stroke='black' stroke-width='1.65' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-truck: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M3 6h9v7H3V6Zm9 2.5h2.7l2.3 2.6V13h-5V8.5ZM6 15.2a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Zm8 0a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-briefcase: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M7.2 6V4.5h5.6V6M3.4 6h13.2v9.5H3.4V6Zm0 4.2c2.1 1 4.3 1.5 6.6 1.5s4.5-.5 6.6-1.5' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-project: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M3.7 4.4h4.6v4.6H3.7V4.4Zm8 6.6h4.6v4.6h-4.6V11ZM8.3 6.8h3.2c2.4 0 4.2 1.6 4.2 4.2M6 9v2c0 2.4 1.8 4 4.2 4h1.5' fill='none' stroke='black' stroke-width='1.75' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-task: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M4 5.5h2.5M9 5.5h7M4 10h2.5M9 10h7M4 14.5h2.5M9 14.5h7' fill='none' stroke='black' stroke-width='1.8' stroke-linecap='round'/%3E%3C/svg%3E");
	--formora-symbol-bank: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M3 7h14L10 3.4 3 7Zm2 2v5M9 9v5M15 9v5M3.5 16.5h13' fill='none' stroke='black' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-payment: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M3.5 5.5h13v9h-13v-9Zm0 3h13M7 12h3M13 11.8l1 1 2-2' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-wallet: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M4 6h11a2 2 0 0 1 2 2v7H4a2 2 0 0 1-2-2V5.8A1.8 1.8 0 0 1 3.8 4H14v2M13.2 10.5h3.5v2.7h-3.5a1.35 1.35 0 1 1 0-2.7Z' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-phone: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M6.3 3.5 4.2 5.1c-.5.4-.7 1.1-.4 1.7 1.8 4 5.4 7.5 9.4 9.4.6.3 1.3.1 1.7-.4l1.6-2.1-3.6-2.4-1.4 1.5a10.6 10.6 0 0 1-4.3-4.3l1.5-1.4-2.4-3.6Z' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-mobile: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M7 3.2h6a1.4 1.4 0 0 1 1.4 1.4v10.8a1.4 1.4 0 0 1-1.4 1.4H7a1.4 1.4 0 0 1-1.4-1.4V4.6A1.4 1.4 0 0 1 7 3.2Zm2.8 11.3h.4' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-mail: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M3.5 5.3h13v9.4h-13V5.3Zm.5.6 6 5 6-5' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-chat: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M4.4 5.2h11.2a2 2 0 0 1 2 2v5.1a2 2 0 0 1-2 2H9.3l-4.1 2v-2H4.4a2 2 0 0 1-2-2V7.2a2 2 0 0 1 2-2Z' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-send: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M3.5 10.2 16.7 3.8l-3.1 12.6-3.2-4.3-4.4 2 1.7-3.9-4.2 0Zm4.2 0 8.8-6.1' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-link: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M8.2 6.5 9.4 5.3a4 4 0 0 1 5.6 5.6l-1.7 1.7M11.8 13.5l-1.2 1.2A4 4 0 1 1 5 9.1l1.7-1.7M7.7 12.3l4.6-4.6' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-external: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M8 5H4.8a1.8 1.8 0 0 0-1.8 1.8v8.4A1.8 1.8 0 0 0 4.8 17h8.4a1.8 1.8 0 0 0 1.8-1.8V12M11 3h6v6M9.5 10.5 16.5 3.5' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-globe: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M10 17a7 7 0 1 0 0-14 7 7 0 0 0 0 14Zm0-14c2 1.8 3 4.1 3 7s-1 5.2-3 7m0-14c-2 1.8-3 4.1-3 7s1 5.2 3 7M3.5 10h13M4.8 6.4h10.4M4.8 13.6h10.4' fill='none' stroke='black' stroke-width='1.35' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-map: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='m3.5 5.4 4-1.8 5 1.8 4-1.8v11l-4 1.8-5-1.8-4 1.8v-11Zm4-1.8v11m5-9.2v11' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-folder: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M3.2 6.2h5l1.5 1.8h7.1v6.8A1.8 1.8 0 0 1 15 16.6H5a1.8 1.8 0 0 1-1.8-1.8V6.2Z' fill='none' stroke='black' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-tag: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M3.5 10.3 10.3 3.5h5.2v5.2l-6.8 6.8-5.2-5.2Zm9.2-4.2h.1' fill='none' stroke='black' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-barcode: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M4 4.5v11M7 4.5v11M10.5 4.5v11M13 4.5v11M16 4.5v11' fill='none' stroke='black' stroke-width='1.9' stroke-linecap='round'/%3E%3C/svg%3E");
	--formora-symbol-clock: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M10 17a7 7 0 1 0 0-14 7 7 0 0 0 0 14Zm0-10v3.6l2.4 1.5' fill='none' stroke='black' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-lock: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M5 8.5h10v7.8H5V8.5Zm2.2 0V6.8a2.8 2.8 0 0 1 5.6 0v1.7M10 12v1.8' fill='none' stroke='black' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-gear: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath fill='black' fill-rule='evenodd' d='M8.45 2.4h3.1l.35 2.05c.43.16.84.38 1.22.65l1.95-.7 1.55 2.68-1.58 1.33c.04.26.06.52.06.79s-.02.53-.06.79l1.58 1.33-1.55 2.68-1.95-.7c-.38.27-.79.49-1.22.65L11.55 16h-3.1l-.35-2.05a5.64 5.64 0 0 1-1.22-.65l-1.95.7-1.55-2.68 1.58-1.33A5.25 5.25 0 0 1 4.9 9.2c0-.27.02-.53.06-.79L3.38 7.08 4.93 4.4l1.95.7c.38-.27.79-.49 1.22-.65L8.45 2.4ZM10 11.85a2.65 2.65 0 1 0 0-5.3 2.65 2.65 0 0 0 0 5.3Z' clip-rule='evenodd'/%3E%3C/svg%3E");
	--formora-symbol-tools: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='m4 16 5.5-5.5M6.2 3.6 8.5 6 6 8.5 3.6 6.2M11.4 4.1l4.5 4.5m-1.2-5.7 2.4 2.4-8.9 8.9-2.4-2.4 8.9-8.9Z' fill='none' stroke='black' stroke-width='1.55' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-chart: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M4 16V4M4 16h12.5M6.5 12.5l3-3 2.2 2.1 4-5' fill='none' stroke='black' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-ticket: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M3.5 7.2a2.2 2.2 0 0 0 0 5.6v2h13v-2a2.2 2.2 0 0 0 0-5.6v-2h-13v2Zm7-1.8v9.2' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-magic: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='m5 15 8.7-8.7M11.8 4.4l3.8 3.8M4 4.5h.1M7 2.8v1.4M15.5 13.5h.1M17.2 10.5h-1.4M8.2 17.2v-1.4' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-server: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M4.5 3.6h11a1.6 1.6 0 0 1 1.6 1.6v2.3a1.6 1.6 0 0 1-1.6 1.6h-11a1.6 1.6 0 0 1-1.6-1.6V5.2a1.6 1.6 0 0 1 1.6-1.6Zm0 7.3h11a1.6 1.6 0 0 1 1.6 1.6v2.3a1.6 1.6 0 0 1-1.6 1.6h-11a1.6 1.6 0 0 1-1.6-1.6v-2.3a1.6 1.6 0 0 1 1.6-1.6ZM6 6.4h.1M6 13.6h.1M9 6.4h5M9 13.6h5' fill='none' stroke='black' stroke-width='1.55' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-home: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='m3.2 9 6.8-5.8L16.8 9M5 8.5v8h10v-8M8.2 16.5V12h3.6v4.5' fill='none' stroke='black' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-chevron-left: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='m12.5 4.5-5.5 5.5 5.5 5.5' fill='none' stroke='black' stroke-width='2.1' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-chevron-right: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='m7.5 4.5 5.5 5.5-5.5 5.5' fill='none' stroke='black' stroke-width='2.1' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-question: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M10 17a7 7 0 1 0 0-14 7 7 0 0 0 0 14Zm-2.1-8a2.2 2.2 0 1 1 3.5 1.8c-.9.6-1.4 1.1-1.4 2M10 15h.1' fill='none' stroke='black' stroke-width='1.65' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-source: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M4 6.2h4.2M4 13.8h4.2M8.2 10h7.2m0 0-2.9-2.9m2.9 2.9-2.9 2.9' fill='none' stroke='black' stroke-width='1.85' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-filter: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M3.5 5h13L11.5 10v4.5l-3 1.5v-6L3.5 5Z' fill='none' stroke='black' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-star: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='m10 3.2 2 4.1 4.5.7-3.3 3.2.8 4.6-4-2.1-4 2.1.8-4.6L3.5 8l4.5-.7 2-4.1Z' fill='none' stroke='black' stroke-width='1.7' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-play: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M6.5 4.5v11l9-5.5-9-5.5Z' fill='none' stroke='black' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-stop-circle: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M10 17a7 7 0 1 0 0-14 7 7 0 0 0 0 14Zm-2.2-9.2h4.4v4.4H7.8V7.8Z' fill='none' stroke='black' stroke-width='1.7' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-toggle-on: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M7 6h6a4 4 0 0 1 0 8H7a4 4 0 0 1 0-8Zm6 5.8a1.8 1.8 0 1 0 0-3.6 1.8 1.8 0 0 0 0 3.6Z' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-toggle-off: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M7 6h6a4 4 0 0 1 0 8H7a4 4 0 0 1 0-8Zm0 5.8a1.8 1.8 0 1 0 0-3.6 1.8 1.8 0 0 0 0 3.6Z' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-crop: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M6 3v11h11M3 6h11v11M14 3v3M3 14h3' fill='none' stroke='black' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-rss: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M5 15.5h.1M4.5 10.5a5 5 0 0 1 5 5M4.5 5.5a10 10 0 0 1 10 10' fill='none' stroke='black' stroke-width='2' stroke-linecap='round'/%3E%3C/svg%3E");
	--formora-symbol-balance: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M10 3.5v13M5 7h10M6 7l-3 5h6L6 7Zm8 0-3 5h6l-3-5ZM7 17h6' fill='none' stroke='black' stroke-width='1.6' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-ruler: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M4 13.5 13.5 4l2.5 2.5L6.5 16 4 13.5Zm3.1-.4 1.2 1.2M9.2 11l1.2 1.2M11.3 8.9l1.2 1.2M13.4 6.8 14.6 8' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-bug: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M6.7 8.1h6.6v5.1a3.3 3.3 0 0 1-6.6 0V8.1Zm1.6 0V6.3a1.7 1.7 0 0 1 3.4 0v1.8M4 10h2.7M13.3 10H16M4.8 5.5l2.4 2M12.8 7.5l2.4-2M4.8 15l2.2-1.4M13 13.6l2.2 1.4' fill='none' stroke='black' stroke-width='1.65' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-vial: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M8 3.8h4M10 4v5.6l4.4 5.6a1.2 1.2 0 0 1-1 2H6.6a1.2 1.2 0 0 1-1-2L10 9.6M7.3 13.2h5.4' fill='none' stroke='black' stroke-width='1.75' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--formora-symbol-archive: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M4 5h12v3H4V5Zm1.2 3v8h9.6V8M8.2 11h3.6' fill='none' stroke='black' stroke-width='1.75' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	--inputbordercolor: rgba(201, 205, 211, 0.96);
	--colorboxstatsborder: #c9cdd3;
	--colorboxiconbg: #f5f5f7;
	--fieldrequiredcolor: #d70015;
}

body {
	color: var(--formora-text);
	background: var(--formora-bg);
}

.bodylogin .butActionLogin,
.bodylogin .butActionLogin:link,
.bodylogin .butActionLogin:visited,
.bodylogin .butActionLogin:hover,
.bodylogin .butActionLogin:active {
	color: var(--formora-text) !important;
}

a:link,
a:visited,
a:hover,
a:active,
.classlink {
	color: var(--formora-accent);
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

body#mainbody :is(.fa-search, .fa-redo, .fa-sync, .fa-refresh, .fa-bars, .fa-stream, .fa-list, .fa-th-list, .fa-th, .fa-grip-horizontal, .fa-table, .fa-arrows-alt, .fa-expand-arrows-alt, .fa-plus-circle, .fa-plus, .fa-pencil-alt, .fa-edit, .fa-trash, .fa-times, .fa-remove, .fa-download, .fa-search-plus, .fa-eye, .fa-binoculars, .fa-calendar, .fa-calendar-alt, .fa-calendar-day, .fa-calendar-week, .fa-caret-up, .fa-caret-down, .fa-caret-left, .fa-caret-right, .fa-info-circle, .fa-question, .fa-exclamation-triangle, .fa-check, .fa-print)::before {
	background-color: currentColor;
	content: "" !important;
	display: inline-block;
	height: 1em;
	-webkit-mask: var(--formora-symbol-mask) center / contain no-repeat;
	mask: var(--formora-symbol-mask) center / contain no-repeat;
	vertical-align: -0.12em;
	width: 1em;
}

body#mainbody :is(.fa-search, .fa-bars, .fa-stream, .fa-list, .fa-th-list, .fa-th, .fa-grip-horizontal, .fa-table, .fa-arrows-alt, .fa-expand-arrows-alt, .fa-pencil-alt, .fa-edit, .fa-times, .fa-remove, .fa-caret-up, .fa-caret-down, .fa-info-circle, .fa-question, .fa-print) {
	color: var(--formora-icon-muted) !important;
}

body#mainbody :is(.fa-redo, .fa-sync, .fa-refresh, .fa-plus-circle, .fa-plus, .fa-download, .fa-search-plus, .fa-eye, .fa-binoculars, .fa-calendar, .fa-calendar-alt, .fa-calendar-day, .fa-calendar-week) {
	color: var(--formora-icon-accent) !important;
}

body#mainbody :is(.fa-calendar, .fa-calendar-alt, .fa-calendar-day, .fa-calendar-week) {
	color: var(--formora-icon-date) !important;
}

body#mainbody :is(.fa-trash) {
	color: var(--formora-icon-danger) !important;
}

body#mainbody :is(.fa-exclamation-triangle) {
	color: var(--formora-icon-warning) !important;
}

body#mainbody :is(.fa-check) {
	color: var(--formora-icon-success) !important;
}

#id-top :is(.fa-search, .fa-redo, .fa-sync, .fa-refresh, .fa-bars, .fa-stream, .fa-list, .fa-th-list, .fa-th, .fa-grip-horizontal, .fa-table, .fa-arrows-alt, .fa-expand-arrows-alt, .fa-plus-circle, .fa-plus, .fa-pencil-alt, .fa-edit, .fa-trash, .fa-times, .fa-remove, .fa-download, .fa-search-plus, .fa-eye, .fa-binoculars, .fa-calendar, .fa-calendar-alt, .fa-calendar-day, .fa-calendar-week, .fa-caret-up, .fa-caret-down, .fa-caret-left, .fa-caret-right, .fa-info-circle, .fa-exclamation-triangle, .fa-check, .fa-print),
.login_block :is(.fa-search, .fa-redo, .fa-sync, .fa-refresh, .fa-bars, .fa-stream, .fa-list, .fa-th-list, .fa-th, .fa-grip-horizontal, .fa-table, .fa-arrows-alt, .fa-expand-arrows-alt, .fa-plus-circle, .fa-plus, .fa-pencil-alt, .fa-edit, .fa-trash, .fa-times, .fa-remove, .fa-download, .fa-search-plus, .fa-eye, .fa-binoculars, .fa-calendar, .fa-calendar-alt, .fa-calendar-day, .fa-calendar-week, .fa-caret-up, .fa-caret-down, .fa-caret-left, .fa-caret-right, .fa-info-circle, .fa-exclamation-triangle, .fa-check, .fa-print) {
	color: inherit !important;
}

body#mainbody :is(.fa-search)::before {
	--formora-symbol-mask: var(--formora-symbol-search);
}

body#mainbody :is(.fa-redo, .fa-sync, .fa-refresh)::before {
	--formora-symbol-mask: var(--formora-symbol-refresh);
}

body#mainbody :is(.fa-bars, .fa-stream, .fa-list, .fa-th-list)::before {
	--formora-symbol-mask: var(--formora-symbol-list);
}

body#mainbody :is(.fa-th, .fa-grip-horizontal, .fa-table)::before {
	--formora-symbol-mask: var(--formora-symbol-grid);
}

body#mainbody :is(.fa-arrows-alt, .fa-expand-arrows-alt)::before {
	--formora-symbol-mask: var(--formora-symbol-expand);
}

body#mainbody :is(.fa-plus-circle, .fa-plus)::before {
	--formora-symbol-mask: var(--formora-symbol-plus);
}

body#mainbody :is(.fa-pencil-alt, .fa-edit)::before {
	--formora-symbol-mask: var(--formora-symbol-edit);
}

body#mainbody :is(.fa-trash)::before {
	--formora-symbol-mask: var(--formora-symbol-trash);
}

body#mainbody :is(.fa-times, .fa-remove)::before {
	--formora-symbol-mask: var(--formora-symbol-close);
}

body#mainbody :is(.fa-download)::before {
	--formora-symbol-mask: var(--formora-symbol-download);
}

body#mainbody :is(.fa-search-plus, .fa-eye, .fa-binoculars)::before {
	--formora-symbol-mask: var(--formora-symbol-preview);
}

body#mainbody :is(.fa-calendar, .fa-calendar-alt, .fa-calendar-day, .fa-calendar-week)::before {
	--formora-symbol-mask: var(--formora-symbol-calendar);
}

body#mainbody :is(.fa-caret-up)::before {
	--formora-symbol-mask: var(--formora-symbol-sort-up);
}

body#mainbody :is(.fa-caret-down)::before {
	--formora-symbol-mask: var(--formora-symbol-sort-down);
}

body#mainbody :is(.fa-caret-left)::before {
	--formora-symbol-mask: var(--formora-symbol-chevron-left);
}

body#mainbody :is(.fa-caret-right)::before {
	--formora-symbol-mask: var(--formora-symbol-chevron-right);
}

body#mainbody :is(.fa-info-circle)::before {
	--formora-symbol-mask: var(--formora-symbol-info);
}

body#mainbody :is(.fa-question)::before {
	--formora-symbol-mask: var(--formora-symbol-source);
}

body#mainbody :is(.fa-exclamation-triangle)::before {
	--formora-symbol-mask: var(--formora-symbol-warning);
}

#mainbody .fa-exclamation-triangle.hideonsmartphone::before,
#mainbody .fa-exclamation-triangle.pictowarning::before {
	height: 14px !important;
	vertical-align: -0.16em;
	width: 14px !important;
}

body#mainbody :is(.fa-check)::before {
	--formora-symbol-mask: var(--formora-symbol-check);
}

body#mainbody :is(.fa-print)::before {
	--formora-symbol-mask: var(--formora-symbol-print);
}

body#mainbody :is(.fa-building, .fa-address-book, .fa-address-card, .fa-user, .fa-user-alt, .fa-user-tie, .fa-user-friends, .fa-users, .fa-cube, .fa-cubes, .fa-box, .fa-box-open, .fa-boxes, .fa-barcode, .fa-concierge-bell, .fa-file, .fa-file-alt, .fa-file-invoice, .fa-file-invoice-dollar, .fa-file-signature, .fa-file-pdf, .fa-file-pdf-o, .fa-file-o, .fa-file-text-o, .fa-file-image-o, .fa-file-code-o, .fa-file-excel-o, .fa-file-word-o, .fa-file-powerpoint-o, .fa-file-video-o, .fa-file-audio-o, .fa-file-archive, .fa-file-archive-o, .fa-archive, .fa-paragraph, .fa-clipboard, .fa-clipboard-list, .fa-keyboard, .fa-shield-alt, .fa-sticky-note, .fa-note-sticky, .fa-bug, .fa-vial, .fa-flask, .fa-dolly, .fa-truck, .fa-truck-loading, .fa-ambulance, .fa-suitcase, .fa-project-diagram, .fa-tasks, .fa-university, .fa-money-check-alt, .fa-dollar-sign, .fa-coins, .fa-wallet, .fa-stripe-s, .fa-phone, .fa-mobile-alt, .fa-fax, .fa-at, .fa-envelope, .fa-comments, .fa-comment, .fa-comment-dots, .fa-paper-plane, .fa-link, .fa-unlink, .fa-external-link-alt, .fa-share-alt, .fa-globe, .fa-globe-americas, .fa-globe-europe, .fa-map, .fa-map-marked, .fa-map-marked-alt, .fa-map-marker-alt, .fa-folder, .fa-folder-open, .fa-tag, .fa-tags, .fa-clock, .fa-business-time, .fa-language, .fa-lock, .fa-key, .fa-cog, .fa-cogs, .fa-tools, .fa-wrench, .fa-chart-line, .fa-chart-bar, .fa-ticket-alt, .fa-magic, .fa-gift, .fa-handshake, .fa-umbrella-beach, .fa-cash-register, .fa-door-open, .fa-check-double, .fa-receipt, .fa-calculator, .fa-hand-holding-usd, .fa-money-bill-alt, .fa-search-dollar, .fa-layer-group, .fa-columns, .fa-history, .fa-bullseye, .fa-id-card-alt, .fa-id-badge, .fa-people-arrows, .fa-laptop-house, .fa-pallet, .fa-shapes, .fa-chalkboard-teacher, .fa-server, .fa-weather-level4, .fa-dol-action, .fa-dol-commande, .fa-dol-contrat, .fa-dol-cubes, .fa-dol-expensereport, .fa-dol-facture, .fa-dol-holiday, .fa-dol-member, .fa-dol-project, .fa-dol-propal, .fa-dol-ticket, .fa-dol-bank_account, .fa-dol-invoice_supplier, .fa-dol-order_supplier, .fa-dol-supplier_proposal)::before {
	background-color: currentColor;
	content: "" !important;
	display: inline-block;
	height: 1em;
	-webkit-mask: var(--formora-symbol-mask) center / contain no-repeat;
	mask: var(--formora-symbol-mask) center / contain no-repeat;
	vertical-align: -0.12em;
	width: 1em;
}

body#mainbody :is(.fa-building)::before {
	--formora-symbol-mask: var(--formora-symbol-building);
}

body#mainbody :is(.fa-address-book, .fa-address-card)::before {
	--formora-symbol-mask: var(--formora-symbol-address-book);
}

body#mainbody :is(.fa-user, .fa-user-alt, .fa-user-tie, .fa-dol-member)::before {
	--formora-symbol-mask: var(--formora-symbol-user);
}

body#mainbody :is(.fa-user-friends, .fa-users, .fa-people-arrows)::before {
	--formora-symbol-mask: var(--formora-symbol-users);
}

body#mainbody :is(.fa-cube, .fa-cubes, .fa-box, .fa-box-open, .fa-boxes, .fa-pallet, .fa-shapes, .fa-dol-cubes)::before {
	--formora-symbol-mask: var(--formora-symbol-box);
}

body#mainbody :is(.fa-concierge-bell)::before {
	--formora-symbol-mask: var(--formora-symbol-service);
}

body#mainbody :is(.fa-file, .fa-file-alt, .fa-file-o, .fa-file-text-o)::before {
	--formora-symbol-mask: var(--formora-symbol-doc);
}

body#mainbody :is(.fa-file-invoice, .fa-dol-commande, .fa-dol-order_supplier)::before {
	--formora-symbol-mask: var(--formora-symbol-doc);
}

body#mainbody :is(.fa-file-invoice-dollar, .fa-receipt, .fa-dol-facture, .fa-dol-invoice_supplier)::before {
	--formora-symbol-mask: var(--formora-symbol-doc-money);
}

body#mainbody :is(.fa-file-signature, .fa-dol-propal, .fa-dol-supplier_proposal)::before {
	--formora-symbol-mask: var(--formora-symbol-doc-signature);
}

body#mainbody :is(.fa-paragraph, .fa-sticky-note, .fa-note-sticky)::before {
	--formora-symbol-mask: var(--formora-symbol-note);
}

body#mainbody :is(.fa-file-pdf, .fa-file-pdf-o, .fa-file-image-o, .fa-file-code-o, .fa-file-excel-o, .fa-file-word-o, .fa-file-powerpoint-o, .fa-file-video-o, .fa-file-audio-o)::before {
	--formora-symbol-mask: var(--formora-symbol-doc);
}

body#mainbody :is(.fa-file-archive, .fa-file-archive-o, .fa-archive)::before {
	--formora-symbol-mask: var(--formora-symbol-archive);
}

body#mainbody :is(.fa-clipboard, .fa-clipboard-list)::before {
	--formora-symbol-mask: var(--formora-symbol-clipboard);
}

body#mainbody :is(.fa-keyboard)::before {
	--formora-symbol-mask: var(--formora-symbol-keyboard);
}

body#mainbody :is(.fa-shield-alt)::before {
	--formora-symbol-mask: var(--formora-symbol-shield);
}

body#mainbody :is(.fa-bug)::before {
	--formora-symbol-mask: var(--formora-symbol-bug);
}

body#mainbody :is(.fa-vial, .fa-flask)::before {
	--formora-symbol-mask: var(--formora-symbol-vial);
}

body#mainbody :is(.fa-dolly, .fa-truck, .fa-truck-loading, .fa-ambulance)::before {
	--formora-symbol-mask: var(--formora-symbol-truck);
}

body#mainbody :is(.fa-suitcase, .fa-dol-contrat)::before {
	--formora-symbol-mask: var(--formora-symbol-briefcase);
}

body#mainbody :is(.fa-project-diagram, .fa-dol-project)::before {
	--formora-symbol-mask: var(--formora-symbol-project);
}

body#mainbody :is(.fa-tasks)::before {
	--formora-symbol-mask: var(--formora-symbol-task);
}

body#mainbody :is(.fa-university, .fa-dol-bank_account)::before {
	--formora-symbol-mask: var(--formora-symbol-bank);
}

body#mainbody :is(.fa-money-check-alt, .fa-dollar-sign, .fa-coins, .fa-calculator, .fa-hand-holding-usd, .fa-search-dollar, .fa-stripe-s)::before {
	--formora-symbol-mask: var(--formora-symbol-payment);
}

body#mainbody :is(.fa-wallet, .fa-money-bill-alt, .fa-dol-expensereport)::before {
	--formora-symbol-mask: var(--formora-symbol-wallet);
}

body#mainbody :is(.fa-phone)::before {
	--formora-symbol-mask: var(--formora-symbol-phone);
}

body#mainbody :is(.fa-mobile-alt)::before {
	--formora-symbol-mask: var(--formora-symbol-mobile);
}

body#mainbody :is(.fa-fax)::before {
	--formora-symbol-mask: var(--formora-symbol-print);
}

body#mainbody :is(.fa-at, .fa-envelope)::before {
	--formora-symbol-mask: var(--formora-symbol-mail);
}

body#mainbody :is(.fa-comments, .fa-comment, .fa-comment-dots)::before {
	--formora-symbol-mask: var(--formora-symbol-chat);
}

body#mainbody :is(.fa-paper-plane)::before {
	--formora-symbol-mask: var(--formora-symbol-send);
}

body#mainbody :is(.fa-link, .fa-unlink, .fa-share-alt)::before {
	--formora-symbol-mask: var(--formora-symbol-link);
}

body#mainbody :is(.fa-external-link-alt, .fa-door-open)::before {
	--formora-symbol-mask: var(--formora-symbol-external);
}

body#mainbody :is(.fa-globe, .fa-globe-americas, .fa-globe-europe)::before {
	--formora-symbol-mask: var(--formora-symbol-globe);
}

body#mainbody :is(.fa-map, .fa-map-marked, .fa-map-marked-alt, .fa-map-marker-alt)::before {
	--formora-symbol-mask: var(--formora-symbol-map);
}

body#mainbody :is(.fa-folder, .fa-folder-open)::before {
	--formora-symbol-mask: var(--formora-symbol-folder);
}

body#mainbody :is(.fa-tag, .fa-tags, .fa-layer-group)::before {
	--formora-symbol-mask: var(--formora-symbol-tag);
}

body#mainbody :is(.fa-barcode)::before {
	--formora-symbol-mask: var(--formora-symbol-barcode);
}

body#mainbody :is(.fa-clock, .fa-business-time, .fa-history)::before {
	--formora-symbol-mask: var(--formora-symbol-clock);
}

body#mainbody :is(.fa-language)::before {
	--formora-symbol-mask: var(--formora-symbol-globe);
}

body#mainbody :is(.fa-lock, .fa-key)::before {
	--formora-symbol-mask: var(--formora-symbol-lock);
}

body#mainbody :is(.fa-cog, .fa-cogs)::before {
	--formora-symbol-mask: var(--formora-symbol-gear);
}

body#mainbody :is(.fa-tools, .fa-wrench)::before {
	--formora-symbol-mask: var(--formora-symbol-tools);
}

body#mainbody :is(.fa-chart-line, .fa-chart-bar)::before {
	--formora-symbol-mask: var(--formora-symbol-chart);
}

body#mainbody :is(.fa-ticket-alt, .fa-dol-ticket)::before {
	--formora-symbol-mask: var(--formora-symbol-ticket);
}

body#mainbody :is(.fa-magic)::before {
	--formora-symbol-mask: var(--formora-symbol-magic);
}

body#mainbody :is(.fa-server)::before {
	--formora-symbol-mask: var(--formora-symbol-server);
}

body#mainbody :is(.fa-weather-level4)::before {
	--formora-symbol-mask: var(--formora-symbol-bolt);
}

body#mainbody :is(.fa-dol-action)::before {
	--formora-symbol-mask: var(--formora-symbol-calendar);
}

body#mainbody :is(.fa-gift, .fa-handshake, .fa-umbrella-beach, .fa-cash-register, .fa-check-double, .fa-id-card-alt, .fa-id-badge, .fa-laptop-house, .fa-chalkboard-teacher, .fa-bullseye, .fa-columns)::before {
	--formora-symbol-mask: var(--formora-symbol-service);
}

body#mainbody :is(.fa-dol-holiday)::before {
	--formora-symbol-mask: var(--formora-symbol-service);
}

body#mainbody :is(.fa-home, .fa-chevron-left, .fa-chevron-right, .fa-angle-left, .fa-angle-right, .fa-angle-double-left, .fa-angle-double-right, .fa-arrow-left, .fa-arrow-right, .fa-arrow-up, .fa-arrow-down, .fa-long-arrow-alt-left, .fa-long-arrow-alt-right, .fa-long-arrow-alt-up, .fa-long-arrow-alt-down, .fa-arrow-alt-circle-left, .fa-arrow-alt-circle-right, .fa-arrow-alt-circle-down, .fa-question-circle, .fa-filter, .fa-star, .fa-play, .fa-stop-circle, .fa-toggle-on, .fa-toggle-off, .fa-crop, .fa-rss, .fa-balance-scale, .fa-ruler, .fa-pencil-ruler, .fa-fill, .fa-exchange-alt, .fa-code-branch, .fa-square, .fa-check-square, .fa-plus-square, .fa-indent, .fa-list-alt, .fa-mars, .fa-venus, .fa-transgender, .fa-genderless, .fa-calendar-check, .fa-eye-slash, .fa-file-export)::before {
	background-color: currentColor;
	content: "" !important;
	display: inline-block;
	height: 1em;
	-webkit-mask: var(--formora-symbol-mask) center / contain no-repeat;
	mask: var(--formora-symbol-mask) center / contain no-repeat;
	vertical-align: -0.12em;
	width: 1em;
}

body#mainbody :is(.fa-home)::before {
	--formora-symbol-mask: var(--formora-symbol-home);
}

body#mainbody :is(.fa-chevron-left, .fa-angle-left, .fa-angle-double-left, .fa-arrow-left, .fa-long-arrow-alt-left, .fa-arrow-alt-circle-left)::before {
	--formora-symbol-mask: var(--formora-symbol-chevron-left);
}

body#mainbody :is(.fa-chevron-right, .fa-angle-right, .fa-angle-double-right, .fa-arrow-right, .fa-long-arrow-alt-right, .fa-arrow-alt-circle-right, .fa-arrow-alt-circle-down)::before {
	--formora-symbol-mask: var(--formora-symbol-chevron-right);
}

body#mainbody :is(.fa-arrow-up, .fa-long-arrow-alt-up)::before {
	--formora-symbol-mask: var(--formora-symbol-sort-up);
}

body#mainbody :is(.fa-arrow-down, .fa-long-arrow-alt-down)::before {
	--formora-symbol-mask: var(--formora-symbol-sort-down);
}

body#mainbody :is(.fa-question-circle)::before {
	--formora-symbol-mask: var(--formora-symbol-question);
}

body#mainbody :is(.fa-indent, .fa-list-alt)::before {
	--formora-symbol-mask: var(--formora-symbol-list);
}

body#mainbody :is(.fa-filter)::before {
	--formora-symbol-mask: var(--formora-symbol-filter);
}

body#mainbody :is(.fa-star)::before {
	--formora-symbol-mask: var(--formora-symbol-star);
}

body#mainbody :is(.fa-play)::before {
	--formora-symbol-mask: var(--formora-symbol-play);
}

body#mainbody :is(.fa-stop-circle)::before {
	--formora-symbol-mask: var(--formora-symbol-stop-circle);
}

body#mainbody :is(.fa-toggle-on)::before {
	--formora-symbol-mask: var(--formora-symbol-toggle-on);
}

body#mainbody :is(.fa-toggle-off)::before {
	--formora-symbol-mask: var(--formora-symbol-toggle-off);
}

body#mainbody :is(.fa-crop, .fa-fill)::before {
	--formora-symbol-mask: var(--formora-symbol-crop);
}

body#mainbody :is(.fa-rss)::before {
	--formora-symbol-mask: var(--formora-symbol-rss);
}

body#mainbody :is(.fa-balance-scale)::before {
	--formora-symbol-mask: var(--formora-symbol-balance);
}

body#mainbody :is(.fa-ruler)::before {
	--formora-symbol-mask: var(--formora-symbol-ruler);
}

body#mainbody :is(.fa-pencil-ruler)::before {
	--formora-symbol-mask: var(--formora-symbol-tools);
}

body#mainbody :is(.fa-exchange-alt)::before {
	--formora-symbol-mask: var(--formora-symbol-refresh);
}

body#mainbody :is(.fa-calendar-check)::before {
	--formora-symbol-mask: var(--formora-symbol-calendar);
}

body#mainbody :is(.fa-eye-slash)::before {
	--formora-symbol-mask: var(--formora-symbol-preview);
}

body#mainbody :is(.fa-file-export)::before {
	--formora-symbol-mask: var(--formora-symbol-download);
}

body#mainbody :is(.fa-mars, .fa-venus, .fa-transgender, .fa-genderless)::before {
	--formora-symbol-mask: var(--formora-symbol-user);
}

body#mainbody :is(.fa-code-branch)::before {
	--formora-symbol-mask: var(--formora-symbol-project);
}

body#mainbody :is(.fa-square, .fa-check-square, .fa-plus-square)::before {
	--formora-symbol-mask: var(--formora-symbol-check);
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
	--formora-symbol-mask: var(--formora-symbol-gear) !important;
	width: 18px !important;
}

body#mainbody #id-left :is(.menu_titre, .vmenu_titre) :is(.pictofixedwidth[class*="fa-"], .fa[class*="fa-"], .fas[class*="fa-"], .far[class*="fa-"], .fab[class*="fa-"]) {
	align-items: center;
	display: inline-flex;
	font-size: 18px !important;
	justify-content: center;
	line-height: 1 !important;
	min-width: 24px !important;
	padding-left: 0 !important;
	padding-right: 0 !important;
	vertical-align: middle;
	width: 24px !important;
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
	background: var(--formora-switch-knob);
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
	color: var(--formora-icon-success) !important;
}

body#mainbody .fa-toggle-off {
	color: var(--formora-icon-muted) !important;
}

#mainbody .fa-globe-americas,
#mainbody .fa-map,
#mainbody .fa-map-marked,
#mainbody .fa-map-marked-alt,
#mainbody .fa-map-marker-alt {
	color: #64748b !important;
}

#mainbody .infobox-project,
#mainbody .fa-dol-project {
	color: var(--formora-infobox-project) !important;
}

#mainbody .infobox-cubes,
#mainbody .fa-dol-cubes {
	color: var(--formora-infobox-stock) !important;
}

#mainbody .infobox-action,
#mainbody .fa-dol-action {
	color: var(--formora-infobox-action) !important;
}

#mainbody .infobox-propal,
#mainbody .infobox-facture,
#mainbody .infobox-commande,
#mainbody .fa-dol-propal,
#mainbody .fa-dol-facture,
#mainbody .fa-dol-commande {
	color: var(--formora-infobox-customer) !important;
}

#mainbody .infobox-supplier_proposal,
#mainbody .infobox-invoice_supplier,
#mainbody .infobox-order_supplier,
#mainbody .fa-dol-supplier_proposal,
#mainbody .fa-dol-invoice_supplier,
#mainbody .fa-dol-order_supplier {
	color: var(--formora-infobox-supplier) !important;
}

#mainbody .infobox-adherent,
#mainbody .infobox-member,
#mainbody .infobox-expensereport,
#mainbody .fa-dol-member,
#mainbody .fa-dol-expensereport {
	color: var(--formora-infobox-member) !important;
}

#mainbody .infobox-holiday,
#mainbody .fa-dol-holiday {
	color: var(--formora-infobox-holiday) !important;
}

#mainbody .infobox-bank_account,
#mainbody .fa-dol-bank_account,
#mainbody .fa-money-check-alt,
#mainbody .fa-university,
#mainbody .fa-dollar-sign,
#mainbody .fa-clipboard-list {
	color: var(--formora-infobox-bank) !important;
}

#mainbody .infobox-contrat,
#mainbody .infobox-ticket,
#mainbody .fa-dol-ticket,
#mainbody .fa-suitcase,
#mainbody .fa-ambulance,
#mainbody .fa-ticket-alt {
	color: var(--formora-infobox-contract) !important;
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

#mainbody .cke_combo {
	align-items: center;
	display: inline-flex !important;
	height: 28px !important;
	margin-bottom: 5px !important;
	margin-right: 10px !important;
	vertical-align: top;
}

#mainbody .cke_combo::after {
	margin-top: 0 !important;
	right: -6px !important;
	top: 5px !important;
}

#mainbody a.cke_combo_button,
#mainbody .cke_combo_on a.cke_combo_button,
#mainbody .cke_combo_off a.cke_combo_button,
#mainbody .cke_combo_off a.cke_combo_button:hover,
#mainbody .cke_combo_off a.cke_combo_button:focus,
#mainbody .cke_combo_off a.cke_combo_button:active {
	background: transparent !important;
	border: 1px solid transparent !important;
	box-sizing: border-box !important;
	height: 28px !important;
	margin: 0 !important;
	padding: 0 !important;
}

#mainbody .cke_combo_on a.cke_combo_button,
#mainbody .cke_combo_off a.cke_combo_button:hover,
#mainbody .cke_combo_off a.cke_combo_button:focus,
#mainbody .cke_combo_off a.cke_combo_button:active {
	background: rgba(0, 113, 227, 0.08) !important;
	border-color: var(--formora-line) !important;
}

#mainbody .cke_combo_text {
	box-sizing: border-box !important;
	height: 26px !important;
	line-height: 26px !important;
	padding-left: 10px !important;
}

#mainbody .cke_combo_open {
	height: 26px !important;
	margin-bottom: 0 !important;
	margin-top: 0 !important;
}

#mainbody .cke_combo_arrow {
	margin-top: 11px !important;
}

body.bodytakepos .topnav {
	border-bottom: 1px solid #d0d7de !important;
}

body.bodytakepos .topnav-left a:hover:not(.nohover),
body.bodytakepos .topnav .login_block_other a:hover:not(.nohover) {
	background-color: #e6ebf0 !important;
	color: #1f2937 !important;
}

body#mainbody .select2-hidden-accessible {
	bottom: auto !important;
	height: 1px !important;
	left: -10000px !important;
	margin: 0 !important;
	max-height: 1px !important;
	max-width: 1px !important;
	min-height: 0 !important;
	min-width: 0 !important;
	overflow: hidden !important;
	position: absolute !important;
	right: auto !important;
	top: auto !important;
	width: 1px !important;
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

#mainbody .info-box .info-box-line:has(.badge) {
	align-items: center;
	display: flex;
	gap: 6px;
	line-height: 24px;
	min-height: 24px;
	overflow: visible !important;
}

#mainbody .info-box .info-box-line:has(.badge) > .inline-block {
	align-items: center;
	display: inline-flex;
	min-height: 24px;
	overflow: visible !important;
}

#mainbody .info-box .info-box-line:has(.badge) .badge {
	align-items: center;
	box-sizing: border-box;
	display: inline-flex !important;
	height: 22px;
	line-height: 14px !important;
	min-height: 22px;
	overflow: visible !important;
	padding: 2px 8px !important;
}

#id-top {
	border-bottom: 1px solid var(--formora-line);
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
	color: var(--formora-accent) !important;
}

#id-left {
	border-left: 0 !important;
	border-right: 0 !important;
	box-shadow: none;
}

#id-left .blockvmenu {
	border-bottom: 1px solid var(--formora-line-soft) !important;
}

#id-left hr {
	border-top-color: var(--formora-line-soft) !important;
}

.menu_titre,
.vmenu_titre {
	color: var(--formora-text);
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
	color: var(--formora-text);
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
	border-color: var(--formora-line);
	border-radius: 5px;
}

input:focus:not(.noborderfocus):not(.inputsearch_dropdownselectedfields):not(.button):not(.buttonwebsite):not(.buttonreset):not(.select2-search__field):not(#top-bookmark-search-input):not(.search_component_input):not(.input-nobottom),
select:focus,
textarea:focus,
.select2-container--open [aria-expanded="false"].select2-selection--single,
.select2-container--focus span.selection span.select2-selection:not(.noborderfocus):not(.massactionselect) {
	border-color: var(--formora-accent) !important;
	box-shadow: 0 0 0 2px rgba(9, 105, 218, 0.14);
}

#mainbody .butAction:not(.butActionDelete):not(.butActionRefused),
#mainbody .butAction:not(.butActionDelete):not(.butActionRefused):link,
#mainbody .butAction:not(.butActionDelete):not(.butActionRefused):visited,
#mainbody input.button:not(.buttonDelete):not(.bordertransp):not(.buttonpayment):not(:disabled),
#mainbody button.button:not(.buttonDelete):not(.bordertransp):not(.buttonpayment):not(:disabled),
#mainbody a.button:not(.buttonDelete):not(.bordertransp):not(.buttonpayment) {
	background: var(--formora-accent) !important;
	background-image: none !important;
	border-color: var(--formora-accent) !important;
	color: #fff !important;
}

#mainbody .butAction:not(.butActionDelete):not(.butActionRefused):hover,
#mainbody input.button:not(.buttonDelete):not(.bordertransp):not(.buttonpayment):not(:disabled):hover,
#mainbody button.button:not(.buttonDelete):not(.bordertransp):not(.buttonpayment):not(:disabled):hover,
#mainbody a.button:not(.buttonDelete):not(.bordertransp):not(.buttonpayment):hover {
	background: var(--formora-accent-hover) !important;
	background-image: none !important;
	border-color: var(--formora-accent-hover) !important;
	box-shadow: none !important;
	color: #fff !important;
}

#mainbody input.button.buttongen:not(:disabled),
#mainbody button.button.buttongen:not(:disabled),
#mainbody input.buttongen:not(:disabled),
#mainbody button.buttongen:not(:disabled) {
	background: var(--formora-accent) !important;
	background-image: none !important;
	border: 1px solid var(--formora-accent) !important;
	color: #fff !important;
}

#mainbody input.button.buttongen:not(:disabled):hover,
#mainbody button.button.buttongen:not(:disabled):hover,
#mainbody input.buttongen:not(:disabled):hover,
#mainbody button.buttongen:not(:disabled):hover {
	background: var(--formora-accent-hover) !important;
	background-image: none !important;
	border-color: var(--formora-accent-hover) !important;
	box-shadow: none !important;
	color: #fff !important;
}

#mainbody .butActionDelete,
#mainbody .butActionDelete:link,
#mainbody .butActionDelete:visited,
#mainbody .butActionDelete:hover,
#mainbody .butActionDelete:active,
#mainbody .buttonDelete {
	background: var(--formora-danger) !important;
	background-image: none !important;
	border: 1px solid var(--formora-danger) !important;
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
	background: var(--formora-refused-bg) !important;
	background-image: none !important;
	border: 1px solid var(--formora-line) !important;
	color: var(--formora-muted) !important;
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
	box-shadow: 0 0 0 2px var(--formora-focus-ring) !important;
}

#mainbody .butAction:focus-visible,
#mainbody .butActionDelete:focus-visible,
#mainbody .butActionRefused:focus-visible,
#mainbody .button:focus-visible,
#mainbody .buttonDelete:focus-visible,
#mainbody button:focus-visible,
#mainbody a.btnTitle:focus-visible,
#mainbody .btnTitle:focus-visible {
	outline: 2px solid var(--formora-focus-outline) !important;
	outline-offset: 3px !important;
	box-shadow: 0 0 0 4px var(--formora-focus-ring) !important;
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
	border-bottom: 1px solid var(--formora-line-soft) !important;
	border-radius: 0 !important;
	box-shadow: none !important;
	color: var(--formora-text) !important;
	margin-left: 0 !important;
	margin-right: 0 !important;
}

#mainbody .dropdown-content .butAction:hover,
#mainbody .dropdown-content .butAction:focus {
	background: var(--formora-neutral-hover-bg) !important;
	border-bottom-color: var(--formora-line) !important;
	color: var(--formora-accent) !important;
	text-decoration: none !important;
}

#mainbody .dropdown-content .butActionDelete,
#mainbody .dropdown-content .butActionDelete:link,
#mainbody .dropdown-content .butActionDelete:visited {
	color: var(--formora-danger) !important;
}

#mainbody .dropdown-content .butActionDelete:hover,
#mainbody .dropdown-content .butActionDelete:focus {
	background: var(--formora-danger-bg) !important;
	border-bottom-color: #ff8182 !important;
	color: #a40e26 !important;
	text-decoration: none !important;
}

#mainbody .dropdown-content .butActionRefused,
#mainbody .dropdown-content .butActionRefused:hover,
#mainbody .dropdown-content .butActionRefused:focus {
	background: transparent !important;
	border-bottom-color: var(--formora-line-soft) !important;
	color: var(--formora-muted) !important;
	cursor: not-allowed !important;
}

#mainbody dl.dropdown dd ul.open {
	background: var(--formora-surface) !important;
	border: 1px solid var(--formora-line) !important;
	box-shadow: var(--formora-shadow-menu) !important;
	color: var(--formora-text) !important;
	z-index: 1000 !important;
}

#mainbody dl.dropdown dd .multichoicedoc > ul.ulselectedfields.open {
	max-height: none !important;
	overflow: visible !important;
}

#mainbody dl.dropdown dd ul.open > li {
	background: transparent !important;
	color: var(--formora-text) !important;
}

#mainbody dl.dropdown dd ul.open > li:hover:not(.liinputsearch),
#mainbody dl.dropdown dd ul.open > li:focus-within:not(.liinputsearch) {
	background: var(--formora-neutral-hover-bg) !important;
	color: var(--formora-text) !important;
}

#mainbody dl.dropdown dd ul.open > li:has(input[type="checkbox"]:checked) {
	color: var(--formora-text) !important;
}

#mainbody dl.dropdown dd ul.open > li.liinputsearch {
	background: var(--formora-surface) !important;
}

#mainbody dl.dropdown dd ul.open input.inputsearch_dropdownselectedfields {
	background: var(--formora-surface-alt) !important;
	border-color: var(--formora-line) !important;
	color: var(--formora-text) !important;
}

#mainbody dl.dropdown dd ul.open input.inputsearch_dropdownselectedfields::placeholder {
	color: var(--formora-muted) !important;
}

#mainbody dl.dropdown dd ul.open a {
	color: var(--formora-accent) !important;
	text-decoration: none !important;
}

#mainbody .box:has(dl.dropdown dd ul.open),
#mainbody .boxtable:has(dl.dropdown dd ul.open),
#mainbody .info-box:has(dl.dropdown dd ul.open),
#mainbody table.liste:has(dl.dropdown dd ul.open),
#mainbody .div-table-responsive:has(dl.dropdown dd ul.open),
#mainbody .div-table-responsive-no-min:has(dl.dropdown dd ul.open) {
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
	border-color: var(--formora-line) !important;
	box-shadow: none !important;
	color: var(--formora-muted) !important;
}

#mainbody .box-flex-container.kanban .box-flex-item.info-box-module,
#mainbody .box-flex-container.kanban .box-flex-item.info-box-module .info-box,
#mainbody .box-flex-container.kanban .box-flex-item.info-box-module .info-box-icon,
#mainbody .box-flex-container.kanban .box-flex-item.info-box-module .info-box-content {
	box-sizing: border-box;
}

#mainbody .box-flex-container.kanban .box-flex-item.info-box-module .info-box {
	background: var(--formora-surface) !important;
	background-image: none !important;
	border-color: var(--formora-line) !important;
	overflow: hidden;
	position: relative;
}

#mainbody .box-flex-container.kanban .box-flex-item.info-box-module .info-box-icon {
	background: var(--formora-surface-alt) !important;
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
	background: radial-gradient(circle at calc(100% - 42px) calc(100% - 18px), var(--formora-module-enabled-glow) 0%, var(--formora-module-enabled-glow-soft) 38%, transparent 74%);
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
	border-color: var(--formora-line);
}

#mainbody .fichehalfleft table.noborder.centpercent,
#mainbody .fichehalfright table.noborder.centpercent,
#mainbody .boxhalfleft table.noborder.centpercent,
#mainbody .boxhalfright table.noborder.centpercent {
	border: 1px solid var(--formora-line) !important;
	border-color: var(--formora-line) !important;
}

div.liste_titre_bydiv,
div.liste_titre_bydiv_nothingafter,
#mainbody .liste_titre.liste_titre_bydiv {
	border-color: var(--formora-line) !important;
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

#mainbody .div-table-responsive > table.liste > :is(tbody, tfoot):last-child > tr:last-child > :is(td, th):first-child,
#mainbody .div-table-responsive-no-min > table.liste > :is(tbody, tfoot):last-child > tr:last-child > :is(td, th):first-child {
	border-bottom-left-radius: 8px !important;
}

#mainbody .div-table-responsive > table.liste > :is(tbody, tfoot):last-child > tr:last-child > :is(td, th):last-child,
#mainbody .div-table-responsive-no-min > table.liste > :is(tbody, tfoot):last-child > tr:last-child > :is(td, th):last-child {
	border-bottom-right-radius: 8px !important;
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
	border-color: var(--formora-line) !important;
}

#mainbody table.liste :is(tr, td, th),
#mainbody table.tagtable.liste :is(tr, td, th),
#mainbody table.liste > :is(thead, tbody, tfoot) > tr > :is(td, th) {
	border-color: var(--formora-line) !important;
}

#mainbody table.liste.formdoc > :is(tbody, tfoot):last-child > tr:last-child > :is(td, th),
#mainbody table.liste.formdoc > tbody > tr.oddeven:last-child > :is(td, th) {
	border-bottom: 0 !important;
}

#mainbody table.tableforfield > :is(thead, tbody):first-child > tr:first-child > :is(td, th),
#mainbody table.border.tableforfield > :is(thead, tbody):first-child > tr:first-child > :is(td, th) {
	border-top: 0 !important;
}

#mainbody :is(.fichehalfleft, .fichehalfright, .fichethirdleft, .fichetwothirdright):has(> table.tableforfield) > .underbanner.clearboth {
	border-bottom: 0 !important;
	height: 0 !important;
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
	background: var(--formora-status-success-bg) !important;
	border-color: var(--formora-status-success-border) !important;
	color: var(--formora-status-success-text) !important;
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
	border-color: var(--formora-line) !important;
	box-shadow: none !important;
}

.jnotify-container {
	top: 60px !important;
	right: 16px !important;
	left: auto !important;
	width: auto !important;
	min-width: min(320px, calc(100vw - 32px));
	max-width: min(520px, calc(100vw - 32px));
	overflow: visible !important;
	padding: 10px 0 0 !important;
}

.jnotify-container .jnotify-notification {
	max-width: min(520px, calc(100vw - 32px));
	background: var(--formora-surface) !important;
	border: 1px solid var(--formora-line);
	border-radius: 6px;
	box-shadow: 0 10px 24px rgba(31, 35, 40, 0.12);
	overflow: hidden;
}

.jnotify-background,
div.jnotify-background,
.jnotify-container .jnotify-background {
	background: transparent !important;
	border: 0 !important;
	border-radius: 0 !important;
	box-shadow: none !important;
	opacity: 1 !important;
	pointer-events: none !important;
}

.jnotify-container .jnotify-notification .jnotify-message {
	color: var(--formora-text) !important;
	padding: 12px 14px;
}

<?php
if (getDolGlobalInt('THEME_DARKMODEENABLED')) {
	print "/* Formora dark skin layer */\n";
	if (getDolGlobalInt('THEME_DARKMODEENABLED') != 2) {
		print "@media (prefers-color-scheme: dark) {";
	} else {
		print "@media not print {";
	}
?>
:root {
	color-scheme: dark;
	--formora-accent: #0a84ff;
	--formora-accent-hover: #64b5ff;
	--formora-accent-soft: rgba(10, 132, 255, 0.18);
	--formora-line: #3a3a3c;
	--formora-line-soft: #2c2c2e;
	--formora-bg: #0f1012;
	--formora-surface: #1c1c1e;
	--formora-surface-alt: #242426;
	--formora-text: #f5f5f7;
	--formora-muted: #a1a1a6;
	--formora-danger: #ff6961;
	--formora-danger-bg: #3a1f22;
	--formora-danger-hover-bg: #4a2428;
	--formora-toast-success-bg: #173522;
	--formora-toast-success-border: rgba(48, 209, 88, 0.36);
	--formora-toast-success-text: #8ff0a4;
	--formora-toast-warning-bg: #3a2f14;
	--formora-toast-warning-border: rgba(255, 214, 10, 0.36);
	--formora-toast-warning-text: #ffd60a;
	--formora-toast-error-bg: #3a1f22;
	--formora-toast-error-border: rgba(255, 105, 97, 0.42);
	--formora-toast-error-text: #ff8a82;
	--formora-neutral-bg: #202124;
	--formora-neutral-hover-bg: #2c2c2e;
	--formora-refused-bg: #202124;
	--formora-focus-ring: rgba(10, 132, 255, 0.32);
	--formora-focus-outline: #78b7ff;
	--formora-status-success-bg: rgba(48, 209, 88, 0.16);
	--formora-status-success-border: rgba(48, 209, 88, 0.32);
	--formora-status-success-text: #30d158;
	--formora-icon-default: #a1a1a6;
	--formora-icon-muted: #8e8e93;
	--formora-icon-accent: #64b5ff;
	--formora-icon-date: #a1a1a6;
	--formora-icon-danger: #ff6961;
	--formora-icon-warning: #ffd60a;
	--formora-icon-success: #30d158;
	--formora-accent-soft-hover: rgba(10, 132, 255, 0.28);
	--formora-infobox-action: #c98fb4;
	--formora-infobox-bank: #d8ca53;
	--formora-infobox-contract: #55d6c4;
	--formora-infobox-customer: #9bd56b;
	--formora-infobox-holiday: #e3b85a;
	--formora-infobox-member: #d3a66c;
	--formora-infobox-project: #aaa7ee;
	--formora-infobox-stock: #d7ce5e;
	--formora-infobox-supplier: #73c7df;
	--formora-switch-knob: #1c1f22;
	--formora-module-enabled-glow: rgba(48, 209, 88, 0.26);
	--formora-module-enabled-glow-soft: rgba(48, 209, 88, 0.08);
	--formora-shadow-soft: 0 1px 2px rgba(0, 0, 0, 0.35), 0 8px 24px rgba(0, 0, 0, 0.28);
	--formora-shadow-elevated: 0 18px 48px rgba(0, 0, 0, 0.48);
	--formora-shadow-menu: 0 14px 34px rgba(0, 0, 0, 0.5);
	--formora-elevated: #242426;
	--formora-table-header: #242426;
	--colorbackhmenu1: #1c1c1e;
	--colorbackvmenu1: #151517;
	--colorbacktitle1: #242426;
	--colorbacktabcard1: #1c1c1e;
	--colorbacktabactive: #242426;
	--colorbacklineimpair1: #1c1c1e;
	--colorbacklineimpair2: #1c1c1e;
	--colorbacklinepair1: #1c1c1e;
	--colorbacklinepair2: #202124;
	--colorbacklinepairhover: #2b3340;
	--colorbacklinepairchecked: #15395f;
	--colorbacklinebreak: #2c2c2e;
		--colorbackbody: #0f1012;
		--colorbackmobilemenu: #151517;
		--colorbackgrey: #202124;
		--infoboxmoduleenabledbgcolor: transparent;
		--colortexttitlenotab: #f5f5f7;
		--colortexttitlenotab2: #f5f5f7;
		--colortexttitle: rgba(245, 245, 247, 0.96);
	--colortexttitlelink: rgba(10, 132, 255, 0.96);
	--colortext: #f5f5f7;
	--colortextlink: #0a84ff;
	--colortextbackhmenu: #f5f5f7;
	--colortextbackvmenu: #f5f5f7;
	--colortopbordertitle1: #3a3a3c;
	--inputbackgroundcolor: #202124;
	--inputbackgroundcolordisabled: #151517;
	--inputcolordisabled: #77777d;
	--inputbordercolor: #3a3a3c;
	--tooltipbgcolor: rgba(28, 28, 30, 0.98);
	--tooltipfontcolor: #f5f5f7;
	--oddevencolor: #f5f5f7;
	--colorboxstatsborder: #3a3a3c;
	--colorboxiconbg: #202124;
	--fieldrequiredcolor: #ff6961;
	--refidnocolor: #f5f5f7;
	--tableforfieldcolor: #a1a1a6;
}

html,
body,
#mainbody {
	background: var(--formora-bg) !important;
	color: var(--formora-text);
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
	color: var(--formora-accent);
}

hr,
.opacitymedium {
	border-color: var(--formora-line-soft);
}

#id-top,
.login_block,
.inline-block.login_block {
	background: var(--formora-surface) !important;
	border-color: var(--formora-line-soft) !important;
	color: var(--formora-text) !important;
}

.tmenu,
.tmenuimage {
	background: var(--formora-surface) !important;
	border-color: var(--formora-line-soft) !important;
}

#id-top {
	box-shadow: 0 1px 0 rgba(0, 0, 0, 0.3);
}

.login_block,
.inline-block.login_block {
	border-bottom: 0 !important;
}

body#mainbody .login_block.usedropdown {
	align-items: center !important;
	display: flex !important;
	height: 60px !important;
	justify-content: flex-end !important;
	line-height: normal !important;
	top: 0 !important;
}

body#mainbody .login_block.usedropdown > :is(.login_block_tools, .login_block_other, .login_block_user) {
	align-items: center !important;
	display: flex !important;
	height: 100% !important;
	line-height: normal !important;
}

body#mainbody .login_block.usedropdown :is(.atoplogin, .login_block_elem, .login_block_other > .inline-block) {
	align-items: center !important;
	display: inline-flex !important;
	line-height: normal !important;
}

body#mainbody .login_block.usedropdown #topmenu-quickadd-dropdown,
body#mainbody .login_block.usedropdown #topmenu-quickadd-dropdown > a.dropdown-toggle {
	align-items: center !important;
	display: inline-flex !important;
	height: 60px !important;
	line-height: normal !important;
}

body#mainbody .login_block.usedropdown #topmenu-login-dropdown > a.dropdown-toggle {
	align-items: center !important;
	display: inline-flex !important;
	height: 60px !important;
	line-height: normal !important;
}

body#mainbody .login_block.usedropdown :is(.userimg.atoplogin span.userphoto, .userimgatoplogin span.userphoto, span.fa.atoplogin) {
	align-items: center !important;
	display: inline-flex !important;
	justify-content: center !important;
	line-height: 1 !important;
}

body#mainbody .login_block.usedropdown :is(.userimg.atoplogin span.userphoto, .userimgatoplogin span.userphoto, span.fa.atoplogin)::before {
	margin: 0 !important;
	vertical-align: 0 !important;
}

#id-top a.tmenulabel,
#id-top a.tmenulabel .mainmenuaspan,
.login_block,
.login_block > a {
	color: var(--formora-text) !important;
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
	color: var(--formora-accent-hover) !important;
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
	border-color: var(--formora-line-soft) !important;
	color: var(--formora-text) !important;
}

#id-container {
	min-height: calc(100vh - 51px);
}

@media only screen and (min-width: 768px) {
	body#mainbody:not(.bodylogin) #id-left {
		min-height: calc(100vh - 60px);
	}

	body#mainbody:not(.bodylogin) #id-left > .vmenu {
		min-height: calc(100vh - 80px);
	}
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
	border-color: var(--formora-line-soft) !important;
	box-shadow: none !important;
}

#id-left a,
.vmenu a,
.menu_contenu a {
	color: var(--formora-muted) !important;
}

#id-left a:hover,
.vmenu a:hover,
.menu_contenu a:hover {
	color: var(--formora-accent-hover) !important;
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
	background: var(--formora-surface) !important;
	border-color: var(--formora-line) !important;
	color: var(--formora-text) !important;
}

.ui-dialog {
	box-shadow: 0 18px 40px rgba(0, 0, 0, 0.55) !important;
}

.info-box-sm .info-box-module-enabled {
	background: var(--infoboxmoduleenabledbgcolor) !important;
}

.box-flex-item.info-box-module:not(.--disabled) {
	border-color: var(--formora-line) !important;
}

.box-flex-item.info-box-module:not(.--disabled) .info-box-content {
	color: var(--formora-text) !important;
}

.box-flex-item.info-box-module:not(.--disabled) .info-box-title,
.box-flex-item.info-box-module:not(.--disabled) .info-box-text {
	color: var(--formora-text) !important;
}

.box-flex-item.info-box-module:not(.--disabled) .info-box-desc,
.box-flex-item.info-box-module:not(.--disabled) .info-box-desc-long {
	color: var(--formora-muted) !important;
}

div.ui-tooltip.mytooltip,
div.ui-tooltip.mytooltip-hover-tooltip {
	background: var(--formora-surface) !important;
	border: 1px solid var(--formora-line) !important;
	color: var(--formora-text) !important;
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
	background: var(--formora-surface) !important;
	border-color: var(--formora-line) !important;
	box-shadow: 0 14px 28px rgba(0, 0, 0, 0.56) !important;
	color: var(--formora-text) !important;
}

#mainbody dl.dropdown dd .multichoicedoc > ul.ulselectedfields.open > li:hover:not(.liinputsearch) {
	background: var(--formora-neutral-hover-bg) !important;
	color: var(--formora-accent-hover) !important;
}

#mainbody .dropdown-holder.open .dropdown-content::before {
	border-color: transparent transparent var(--formora-surface) transparent !important;
}

.box {
	box-shadow: none !important;
}

.dropdown-body::-webkit-scrollbar-thumb {
	background: #3a4048 !important;
}

.user-footer .button-top-menu-dropdown,
.dropdown-item {
	background: var(--formora-surface-alt) !important;
	border-color: var(--formora-line) !important;
	color: var(--formora-text) !important;
}

.dropdown-item.active,
.dropdown-item:hover,
.dropdown-item:focus {
	background: var(--formora-neutral-hover-bg) !important;
	color: var(--formora-accent-hover) !important;
}

#topmenu-quickadd-dropdown .dropdown-item.active,
#topmenu-quickadd-dropdown .dropdown-item:hover,
#topmenu-quickadd-dropdown .dropdown-item:focus,
#topmenu-global-search-dropdown .dropdown-item.active,
#topmenu-global-search-dropdown .dropdown-item:hover,
#topmenu-global-search-dropdown .dropdown-item:focus {
	background: var(--formora-neutral-hover-bg) !important;
	color: var(--formora-text) !important;
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
	border-color: var(--formora-line-soft) !important;
	color: var(--formora-text) !important;
}

.tabBar,
.tabBarWithBottom,
.tabBarNoBottom {
	box-shadow: none !important;
}

#mainbody .divsection,
#mainbody .divsection.wordbreak {
	background: var(--formora-surface) !important;
	border-color: var(--formora-line) !important;
	color: var(--formora-text) !important;
}

.tab,
.tabactive,
.tabunactive,
.tabBar .tabsElem a,
.tabs .tabsElem a {
	background: transparent !important;
	border: 0 !important;
	border-bottom: 3px solid transparent !important;
	color: var(--formora-muted) !important;
	box-shadow: none !important;
}

.tabactive,
.tabBar .tabsElem.tabsElemActive a,
.tabs .tabsElem.tabsElemActive a {
	color: var(--formora-accent-hover) !important;
}

.tabactive {
	border-bottom-color: var(--formora-accent) !important;
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
	color: var(--formora-text) !important;
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
	color: var(--formora-muted) !important;
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
	background: var(--formora-surface) !important;
	border-color: var(--formora-line-soft) !important;
	color: var(--formora-text) !important;
}

table.border,
.border {
	background: transparent !important;
	border-color: var(--formora-line-soft) !important;
	color: var(--formora-text) !important;
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
	background: var(--formora-surface-alt) !important;
	border-color: var(--formora-line) !important;
	color: var(--formora-text) !important;
}

.liste_titre a,
.liste_titre td a,
.liste_titre th a,
tr.liste_titre th a,
tr.liste_titre td a {
	color: var(--formora-accent-hover) !important;
}

div.liste_titre_bydiv,
div.liste_titre_bydiv_nothingafter {
	border-color: var(--formora-line) !important;
}

#mainbody .fichehalfleft table.noborder.centpercent,
#mainbody .fichehalfright table.noborder.centpercent,
#mainbody .boxhalfleft table.noborder.centpercent,
#mainbody .boxhalfright table.noborder.centpercent {
	border: 1px solid var(--formora-line) !important;
	border-color: var(--formora-line) !important;
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
	background: var(--formora-surface) !important;
	color: var(--formora-text) !important;
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
	background: var(--formora-neutral-hover-bg) !important;
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
	border-color: var(--formora-line-soft) !important;
	color: inherit;
}

#mainbody .div-table-responsive,
#mainbody .div-table-responsive-no-min,
#mainbody table.liste,
#mainbody table.tagtable.liste,
#mainbody .div-table-responsive > table.liste,
#mainbody .div-table-responsive-no-min > table.liste {
	border-color: var(--formora-line) !important;
}

#mainbody table.liste :is(tr, td, th),
#mainbody table.tagtable.liste :is(tr, td, th),
#mainbody table.liste > :is(thead, tbody, tfoot) > tr > :is(td, th) {
	border-color: var(--formora-line) !important;
}

.trforbreak td,
div.tableforcontact form.tagtr:last-of-type div.tagtd,
.ui-mobile fieldset {
	border-color: var(--formora-line-soft) !important;
}

.paginate_button,
.paginate_active,
table.jPicker {
	border-color: var(--formora-line-soft) !important;
}

#mainbody .navmode .btnTitle .pictoactionview {
	color: var(--formora-muted) !important;
}

#mainbody .navmode .btnTitle:hover .pictoactionview,
#mainbody .navmode .btnTitle:focus .pictoactionview,
#mainbody .navmode .btnTitleSelected .pictoactionview {
	color: var(--formora-accent-hover) !important;
}

#mainbody .sectioncalendarbymonth,
#mainbody table.cal_month {
	background: var(--formora-surface) !important;
	color: var(--formora-text) !important;
}

#mainbody table.cal_month tr,
#mainbody table.noborder.cal_month tr {
	background: var(--formora-surface) !important;
	border-color: var(--formora-line-soft) !important;
	border-top-color: var(--formora-line-soft) !important;
}

#mainbody table.cal_month td,
#mainbody table.cal_month th,
#mainbody table.cal_month .tagtd,
#mainbody table.cal_month .tdfordaytitle {
	border-color: var(--formora-line-soft) !important;
	color: var(--formora-text) !important;
}

#mainbody table.cal_month .tdfordaytitle {
	background: var(--formora-surface-alt) !important;
}

#mainbody table.cal_month .cal_current_month,
#mainbody table.cal_month .cal_current_month_right,
#mainbody table.cal_month .cal_current_month_oneday,
#mainbody table.cal_month .cal_current_month_peruserleft,
#mainbody table.cal_month .cal_current_month_peruserleft.cal_impair {
	background: var(--formora-surface) !important;
	border-color: var(--formora-line-soft) !important;
	color: var(--formora-text) !important;
}

#mainbody table.cal_month .cal_other_month,
#mainbody table.cal_month .cal_other_month_right,
#mainbody table.cal_month .cal_other_month_peruserleft,
#mainbody table.cal_month .cal_past_month,
#mainbody table.cal_month td.cal_other_month {
	background: var(--formora-neutral-bg) !important;
	border-color: var(--formora-line-soft) !important;
	color: var(--formora-muted) !important;
	opacity: 1 !important;
}

#mainbody table.cal_month .cal_today,
#mainbody table.cal_month .cal_today_peruser,
#mainbody table.cal_month .cal_today_peruser_peruserleft,
#mainbody table.cal_month .cal_today_peruser_impair {
	background: rgba(78, 163, 255, 0.12) !important;
	border-color: rgba(78, 163, 255, 0.38) !important;
	color: var(--formora-text) !important;
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
	background: var(--formora-surface-alt) !important;
	border: 1px solid var(--formora-line) !important;
	box-shadow: none !important;
	filter: none !important;
}

#mainbody table.cal_event td,
#mainbody table.cal_event .cal_event,
#mainbody table.cal_event a.cal_event_title,
#mainbody table.cal_event a:link,
#mainbody table.cal_event a:visited,
#mainbody table.cal_event a:active {
	color: var(--formora-text) !important;
}

#mainbody table.cal_event a.cal_event_title:hover {
	color: var(--formora-accent-hover) !important;
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
	color: var(--formora-text) !important;
}

input::placeholder,
textarea::placeholder,
.select2-container--default .select2-selection--single .select2-selection__placeholder,
.select2-default {
	color: var(--formora-muted) !important;
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
	border-color: var(--formora-accent) !important;
	box-shadow: 0 0 0 2px var(--formora-focus-ring) !important;
}

.select2-container--default .select2-selection--single .select2-selection__rendered,
.select2-container .select2-selection--single .select2-selection__rendered,
.select2-container .select2-choice > .select2-chosen {
	color: var(--formora-text) !important;
	background: transparent !important;
}

.select2-results__option,
.select2-results,
.select2-results .select2-no-results,
.select2-results .select2-searching,
.select2-results .select2-ajax-error,
.select2-results .select2-selection-limit {
	color: var(--formora-text) !important;
	background: var(--formora-surface) !important;
}

.select2-container--default .select2-results__option--highlighted[aria-selected],
.select2-container--default .select2-results__option--highlighted[aria-selected] span:not(.flag-sprite):not([class*="flag"]):not(.fi):not([class^="fi-"]):not([class*=" fi-"]),
.select2-results .select2-highlighted,
.select2-results .select2-highlighted span:not(.flag-sprite):not([class*="flag"]):not(.fi):not([class^="fi-"]):not([class*=" fi-"]) {
	background: var(--formora-accent-soft) !important;
	color: var(--formora-accent-hover) !important;
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
	background: var(--formora-neutral-bg) !important;
	border-color: var(--formora-line) !important;
	color: var(--formora-text) !important;
}

body#mainbody .ace_editor,
body#mainbody .ace_editor.ace-chrome,
body#mainbody .ace_editor.ace-one-dark {
	background: var(--inputbackgroundcolor) !important;
	border: 1px solid var(--formora-line) !important;
	border-radius: 0 0 var(--formora-radius-sm) var(--formora-radius-sm) !important;
	box-shadow: none !important;
	color: var(--formora-text) !important;
	font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, "Liberation Mono", monospace !important;
}

body#mainbody .ace_scroller,
body#mainbody .ace_content,
body#mainbody .ace_layer,
body#mainbody .ace_text-layer {
	background: var(--inputbackgroundcolor) !important;
	color: var(--formora-text) !important;
}

body#mainbody .ace_gutter {
	background: var(--formora-surface-alt) !important;
	border-right: 1px solid var(--formora-line-soft) !important;
	color: var(--formora-muted) !important;
}

body#mainbody .ace_gutter-cell {
	background: transparent !important;
	color: var(--formora-muted) !important;
}

body#mainbody .ace_gutter-active-line {
	background: rgba(10, 132, 255, 0.14) !important;
	color: var(--formora-accent-hover) !important;
}

body#mainbody .ace_marker-layer .ace_active-line {
	background: rgba(10, 132, 255, 0.10) !important;
}

body#mainbody .ace_marker-layer .ace_selection {
	background: rgba(10, 132, 255, 0.34) !important;
}

body#mainbody .ace_multiselect .ace_selection.ace_start {
	box-shadow: 0 0 3px 0 var(--inputbackgroundcolor) !important;
}

body#mainbody .ace_marker-layer .ace_selected-word {
	background: rgba(10, 132, 255, 0.18) !important;
	border: 1px solid rgba(78, 163, 255, 0.42) !important;
}

body#mainbody .ace_marker-layer .ace_bracket {
	border: 1px solid var(--formora-accent-hover) !important;
}

body#mainbody .ace_marker-layer .ace_step {
	background: rgba(255, 214, 10, 0.20) !important;
}

body#mainbody .ace_marker-layer .ace_stack {
	background: rgba(48, 209, 88, 0.16) !important;
}

body#mainbody .ace_cursor {
	border-left-color: var(--formora-accent-hover) !important;
	color: var(--formora-accent-hover) !important;
	opacity: 1 !important;
}

body#mainbody .ace_print-margin {
	background: var(--formora-line-soft) !important;
	opacity: 0.38 !important;
}

body#mainbody .ace_invisible {
	color: rgba(245, 245, 247, 0.22) !important;
}

body#mainbody .ace_indent-guide,
body#mainbody .ace_indent-guide-active {
	background-image: linear-gradient(to bottom, rgba(245, 245, 247, 0.14) 50%, transparent 50%) !important;
	background-position: right top !important;
	background-repeat: repeat-y !important;
	background-size: 1px 4px !important;
}

body#mainbody .ace_keyword,
body#mainbody .ace_storage,
body#mainbody .ace_meta.ace_tag {
	color: #d7a5ff !important;
}

body#mainbody .ace_keyword.ace_operator {
	color: #c8d0d9 !important;
}

body#mainbody .ace_string,
body#mainbody .ace_string.ace_regex,
body#mainbody .ace_string.ace_regexp {
	color: #9df0b4 !important;
}

body#mainbody .ace_comment,
body#mainbody .ace_comment.ace_doc,
body#mainbody .ace_comment.ace_doc.ace_tag {
	color: #7f8a99 !important;
	font-style: italic;
}

body#mainbody .ace_constant,
body#mainbody .ace_constant.ace_numeric,
body#mainbody .ace_constant.ace_language,
body#mainbody .ace_constant.ace_library,
body#mainbody .ace_constant.ace_buildin {
	color: #ffd38a !important;
}

body#mainbody .ace_variable,
body#mainbody .ace_variable.ace_parameter {
	color: #8bdfff !important;
}

body#mainbody .ace_support.ace_function,
body#mainbody .ace_entity.ace_name.ace_function {
	color: #64b5ff !important;
}

body#mainbody .ace_support.ace_type,
body#mainbody .ace_support.ace_class,
body#mainbody .ace_support.ace_constant,
body#mainbody .ace_support.ace_other {
	color: #f4d47c !important;
}

body#mainbody .ace_entity.ace_name.ace_tag,
body#mainbody .ace_entity.ace_other.ace_attribute-name,
body#mainbody .ace_heading,
body#mainbody .ace_list {
	color: #ff9f8f !important;
}

body#mainbody .ace_invalid,
body#mainbody .ace_invalid.ace_deprecated {
	background: var(--formora-danger-bg) !important;
	color: var(--formora-danger) !important;
}

body#mainbody .ace_fold {
	background: var(--formora-accent) !important;
	border-color: var(--formora-text) !important;
}

body#mainbody .aceeditorstatusbar {
	background: var(--formora-surface-alt) !important;
	border: 1px solid var(--formora-line) !important;
	border-bottom: 0 !important;
	border-radius: var(--formora-radius-sm) var(--formora-radius-sm) 0 0 !important;
	box-sizing: border-box !important;
	color: var(--formora-text) !important;
}

body#mainbody .aceeditorstatusbar .classlink,
body#mainbody .aceeditorstatusbar a {
	color: var(--formora-accent-hover) !important;
}

body#mainbody .ace_status-indicator {
	border-color: var(--formora-line-soft) !important;
	color: var(--formora-muted) !important;
}

body#mainbody .ace_editor.ace_autocomplete,
body#mainbody .ace_editor.ace_autocomplete .ace_scroller,
body#mainbody .ace_editor.ace_autocomplete .ace_content {
	background: var(--formora-surface) !important;
	border-color: var(--formora-line) !important;
	box-shadow: var(--formora-shadow-menu) !important;
	color: var(--formora-text) !important;
}

body#mainbody .ace_editor.ace_autocomplete .ace_line-hover {
	background: var(--formora-neutral-hover-bg) !important;
	border-color: var(--formora-line) !important;
}

body#mainbody .ace_editor.ace_autocomplete .ace_completion-highlight {
	color: var(--formora-accent-hover) !important;
}

body#mainbody .ace_editor.ace_autocomplete .ace_rightAlignedText {
	color: var(--formora-muted) !important;
}

body#mainbody .cke_chrome,
body#mainbody .cke_inner,
body#mainbody .cke_top,
body#mainbody .cke_bottom,
body#mainbody .cke_contents,
body#mainbody .cke_panel,
body#mainbody .cke_panel_frame {
	background: var(--formora-surface) !important;
	border-color: var(--formora-line) !important;
	color: var(--formora-text) !important;
}

body#mainbody .cke_chrome {
	border: 1px solid var(--formora-line) !important;
	border-radius: var(--formora-radius-sm) !important;
	box-shadow: none !important;
	overflow: hidden;
}

body#mainbody .cke_top,
body#mainbody .cke_bottom {
	background: var(--formora-surface-alt) !important;
	border-color: var(--formora-line-soft) !important;
}

body#mainbody .cke_contents {
	border-top: 1px solid var(--formora-line-soft) !important;
}

body#mainbody iframe.cke_wysiwyg_frame {
	background: var(--formora-surface) !important;
	color-scheme: dark;
}

body#mainbody textarea.cke_source {
	background: var(--inputbackgroundcolor) !important;
	border: 0 !important;
	box-sizing: border-box !important;
	color: var(--formora-text) !important;
	font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, "Liberation Mono", monospace !important;
	line-height: 1.45 !important;
	margin: 0 !important;
	padding: 10px 12px !important;
}

body#mainbody .cke_reset_all,
body#mainbody .cke_reset_all *,
body#mainbody .cke_combo_text,
body#mainbody .cke_button_label,
body#mainbody .cke_path_item,
body#mainbody .cke_path_empty {
	color: var(--formora-text) !important;
}

body#mainbody .cke_combo_label,
body#mainbody .cke_button_disabled .cke_button_label,
body#mainbody .cke_button_disabled .cke_button_icon,
body#mainbody .cke_path_empty {
	color: var(--formora-muted) !important;
	opacity: 0.62 !important;
}

body#mainbody .cke_toolgroup,
body#mainbody .cke_combo {
	background: transparent !important;
	border: 0 !important;
	box-shadow: none !important;
}

body#mainbody .cke_toolbar_separator,
body#mainbody .cke_toolgroup a.cke_button:last-child::after,
body#mainbody .cke_combo::after {
	background-color: var(--formora-line) !important;
	border-color: var(--formora-line) !important;
}

body#mainbody a.cke_button,
body#mainbody a.cke_button:hover,
body#mainbody a.cke_button:focus,
body#mainbody a.cke_button:active,
body#mainbody a.cke_button_on,
body#mainbody a.cke_combo_button,
body#mainbody .cke_combo_on a.cke_combo_button,
body#mainbody .cke_combo_off a.cke_combo_button,
body#mainbody .cke_combo_off a.cke_combo_button:hover,
body#mainbody .cke_combo_off a.cke_combo_button:focus,
body#mainbody .cke_combo_off a.cke_combo_button:active {
	border: 1px solid transparent !important;
	border-radius: var(--formora-radius-sm) !important;
	box-shadow: none !important;
	color: var(--formora-text) !important;
	padding: 3px 5px !important;
}

body#mainbody a.cke_button_expandable,
body#mainbody a.cke_button_expandable:hover,
body#mainbody a.cke_button_expandable:focus,
body#mainbody a.cke_button_expandable:active,
body#mainbody a.cke_button_expandable.cke_button_on {
	padding: 3px 4px !important;
}

body#mainbody a.cke_combo_button,
body#mainbody .cke_combo_on a.cke_combo_button,
body#mainbody .cke_combo_off a.cke_combo_button,
body#mainbody .cke_combo_off a.cke_combo_button:hover,
body#mainbody .cke_combo_off a.cke_combo_button:focus,
body#mainbody .cke_combo_off a.cke_combo_button:active {
	padding: 0 !important;
}

body#mainbody a.cke_button:hover,
body#mainbody a.cke_button:focus,
body#mainbody a.cke_button:active,
body#mainbody a.cke_button_on,
body#mainbody .cke_combo_on a.cke_combo_button,
body#mainbody .cke_combo_off a.cke_combo_button:hover,
body#mainbody .cke_combo_off a.cke_combo_button:focus,
body#mainbody .cke_combo_off a.cke_combo_button:active {
	background: var(--formora-accent-soft) !important;
	border-color: rgba(78, 163, 255, 0.42) !important;
	color: var(--formora-accent-hover) !important;
}

body#mainbody a.cke_button_disabled,
body#mainbody a.cke_button_disabled:hover,
body#mainbody a.cke_button_disabled:focus,
body#mainbody a.cke_button_disabled:active {
	background: transparent !important;
	border-color: transparent !important;
	color: var(--formora-muted) !important;
	opacity: 0.62 !important;
}

body#mainbody .cke_button_icon {
	filter: invert(1) brightness(1.5) contrast(0.92);
	opacity: 0.92 !important;
}

body#mainbody .cke_button_disabled .cke_button_icon {
	filter: invert(1) brightness(1.08) contrast(0.85);
	opacity: 0.42 !important;
}

body#mainbody .cke_button_arrow,
body#mainbody .cke_combo_arrow {
	border-top-color: var(--formora-muted) !important;
}

body#mainbody a.cke_button:hover .cke_button_arrow,
body#mainbody a.cke_button:focus .cke_button_arrow,
body#mainbody .cke_combo_on .cke_combo_arrow,
body#mainbody .cke_combo_off a.cke_combo_button:hover .cke_combo_arrow,
body#mainbody .cke_combo_off a.cke_combo_button:focus .cke_combo_arrow {
	border-top-color: var(--formora-accent-hover) !important;
}

body#mainbody .cke_panel {
	background: var(--formora-surface) !important;
	border: 1px solid var(--formora-line) !important;
	box-shadow: var(--formora-shadow-menu) !important;
	color-scheme: dark;
}

body#mainbody .cke_panel_frame {
	background: var(--formora-surface) !important;
}

body#mainbody .cke_dialog_background_cover {
	background: rgba(0, 0, 0, 0.58) !important;
	opacity: 1 !important;
}

body#mainbody .cke_dialog {
	background: var(--formora-surface) !important;
	border: 1px solid var(--formora-line) !important;
	border-radius: var(--formora-radius) !important;
	box-shadow: var(--formora-shadow-elevated) !important;
	color: var(--formora-text) !important;
	overflow: hidden;
}

body#mainbody .cke_dialog_body,
body#mainbody .cke_dialog_contents,
body#mainbody .cke_dialog_contents_body,
body#mainbody .cke_dialog_page_contents,
body#mainbody .cke_dialog_ui_vbox_child,
body#mainbody .cke_dialog_ui_hbox_first,
body#mainbody .cke_dialog_ui_hbox_child,
body#mainbody .cke_dialog_ui_hbox_last {
	background: var(--formora-surface) !important;
	border-color: var(--formora-line-soft) !important;
	color: var(--formora-text) !important;
}

body#mainbody .cke_dialog_title,
body#mainbody .cke_dialog_footer {
	background: var(--formora-surface-alt) !important;
	background-image: none !important;
	border-color: var(--formora-line-soft) !important;
	color: var(--formora-text) !important;
	text-shadow: none !important;
}

body#mainbody .cke_dialog_title {
	border-bottom: 1px solid var(--formora-line-soft) !important;
}

body#mainbody .cke_dialog_footer {
	border-top: 1px solid var(--formora-line-soft) !important;
}

body#mainbody .cke_dialog_tabs {
	background: var(--formora-surface-alt) !important;
	border-bottom: 1px solid var(--formora-line-soft) !important;
}

body#mainbody .cke_dialog_tab,
body#mainbody .cke_dialog_tab:hover,
body#mainbody .cke_dialog_tab:focus {
	background: transparent !important;
	border: 0 !important;
	border-bottom: 3px solid transparent !important;
	box-shadow: none !important;
	color: var(--formora-muted) !important;
	text-shadow: none !important;
}

body#mainbody .cke_dialog_tab_selected,
body#mainbody .cke_dialog_tab_selected:hover,
body#mainbody .cke_dialog_tab_selected:focus {
	background: var(--formora-surface) !important;
	border-bottom-color: var(--formora-accent) !important;
	color: var(--formora-accent-hover) !important;
}

body#mainbody .cke_dialog_ui_labeled_label,
body#mainbody .cke_dialog_ui_html,
body#mainbody .cke_dialog_ui_text,
body#mainbody .cke_dialog_ui_checkbox label,
body#mainbody .cke_dialog .ImagePreviewBox,
body#mainbody .cke_dialog .FlashPreviewBox,
body#mainbody .cke_dialog table,
body#mainbody .cke_dialog td,
body#mainbody .cke_dialog th,
body#mainbody .cke_dialog label,
body#mainbody .cke_dialog legend {
	background: transparent !important;
	border-color: var(--formora-line-soft) !important;
	color: var(--formora-text) !important;
	text-shadow: none !important;
}

body#mainbody .cke_dialog .ImagePreviewBox,
body#mainbody .cke_dialog .FlashPreviewBox {
	background: var(--formora-neutral-bg) !important;
	border: 1px solid var(--formora-line) !important;
	border-radius: var(--formora-radius-sm) !important;
	color: var(--formora-muted) !important;
}

body#mainbody .cke_dialog .ImagePreviewBox img {
	filter: none !important;
	opacity: 1 !important;
}

body#mainbody .cke_dialog input,
body#mainbody .cke_dialog textarea,
body#mainbody .cke_dialog select {
	background: var(--inputbackgroundcolor) !important;
	border: 1px solid var(--inputbordercolor) !important;
	border-radius: var(--formora-radius-sm) !important;
	box-shadow: none !important;
	color: var(--formora-text) !important;
	color-scheme: dark;
}

body#mainbody .cke_dialog input:focus,
body#mainbody .cke_dialog textarea:focus,
body#mainbody .cke_dialog select:focus {
	border-color: var(--formora-accent) !important;
	box-shadow: 0 0 0 3px var(--formora-focus-ring) !important;
	outline: none !important;
}

body#mainbody .cke_dialog input[type="checkbox"],
body#mainbody .cke_dialog input[type="radio"] {
	accent-color: var(--formora-accent);
}

body#mainbody .cke_dialog a.cke_dialog_ui_button,
body#mainbody .cke_dialog a.cke_dialog_ui_button:hover,
body#mainbody .cke_dialog a.cke_dialog_ui_button:focus,
body#mainbody .cke_dialog a.cke_dialog_ui_button:active {
	background: var(--formora-neutral-bg) !important;
	background-image: none !important;
	border: 1px solid var(--formora-line) !important;
	border-radius: var(--formora-radius-sm) !important;
	box-shadow: none !important;
	color: var(--formora-text) !important;
	text-shadow: none !important;
}

body#mainbody .cke_dialog a.cke_dialog_ui_button:hover,
body#mainbody .cke_dialog a.cke_dialog_ui_button:focus {
	background: var(--formora-neutral-hover-bg) !important;
	border-color: var(--formora-accent) !important;
	color: var(--formora-accent-hover) !important;
}

body#mainbody .cke_dialog a.cke_dialog_ui_button_ok,
body#mainbody .cke_dialog a.cke_dialog_ui_button_ok:hover,
body#mainbody .cke_dialog a.cke_dialog_ui_button_ok:focus {
	background: var(--formora-accent-soft) !important;
	border-color: rgba(78, 163, 255, 0.42) !important;
	color: var(--formora-accent-hover) !important;
}

body#mainbody .cke_dialog a.cke_dialog_ui_button span,
body#mainbody .cke_dialog a.cke_dialog_ui_button_ok span {
	color: inherit !important;
	text-shadow: none !important;
}

body#mainbody .cke_dialog_close_button {
	filter: invert(1) brightness(1.7) contrast(0.9);
	opacity: 0.76 !important;
}

body#mainbody .cke_dialog_close_button:hover,
body#mainbody .cke_dialog_close_button:focus {
	opacity: 1 !important;
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
	background: var(--formora-accent-soft) !important;
	border-color: rgba(78, 163, 255, 0.4) !important;
	color: var(--formora-accent-hover) !important;
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
	color: var(--formora-accent-hover) !important;
}

#mainbody .butActionDelete,
#mainbody .butActionDelete:link,
#mainbody .butActionDelete:visited,
#mainbody .butActionDelete:hover,
#mainbody .butActionDelete:active,
#mainbody .buttonDelete {
	background: var(--formora-danger-bg) !important;
	border-color: #6e3038 !important;
	color: var(--formora-danger) !important;
}

#mainbody .butActionDelete:hover,
#mainbody .buttonDelete:hover:not(:disabled) {
	background: var(--formora-danger-hover-bg) !important;
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
	background: var(--formora-refused-bg) !important;
	border-color: var(--formora-line) !important;
	color: var(--formora-muted) !important;
}

#mainbody .dropdown-content a:is(.butAction,.butActionDelete,.butActionRefused),
#mainbody .dropdown-content .butAction,
#mainbody .dropdown-content .butActionDelete,
#mainbody .dropdown-content .butActionRefused {
	background: transparent !important;
	border: 0 !important;
	border-bottom: 1px solid var(--formora-line-soft) !important;
	border-radius: 0 !important;
	box-shadow: none !important;
	color: var(--formora-text) !important;
	margin-left: 0 !important;
	margin-right: 0 !important;
}

#mainbody .dropdown-content .butAction:hover,
#mainbody .dropdown-content .butAction:focus {
	background: var(--formora-neutral-hover-bg) !important;
	border-bottom-color: var(--formora-line) !important;
	color: var(--formora-accent-hover) !important;
	text-decoration: none !important;
}

#mainbody .dropdown-content .butActionDelete,
#mainbody .dropdown-content .butActionDelete:link,
#mainbody .dropdown-content .butActionDelete:visited {
	color: var(--formora-danger) !important;
}

#mainbody .dropdown-content .butActionDelete:hover,
#mainbody .dropdown-content .butActionDelete:focus {
	background: var(--formora-danger-hover-bg) !important;
	border-bottom-color: #8f3e49 !important;
	color: #ffb4b4 !important;
	text-decoration: none !important;
}

#mainbody .dropdown-content .butActionRefused,
#mainbody .dropdown-content .butActionRefused:hover,
#mainbody .dropdown-content .butActionRefused:focus {
	background: transparent !important;
	border-bottom-color: var(--formora-line-soft) !important;
	color: var(--formora-muted) !important;
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
	background: var(--formora-neutral-hover-bg) !important;
	border-color: var(--formora-line) !important;
	box-shadow: none !important;
	color: var(--formora-accent-hover) !important;
}

#mainbody a.btnTitle.btnTitleSelected,
#mainbody a.btnTitle.btnTitleSelected:hover,
#mainbody .btnTitle.btnTitleSelected,
#mainbody .btnTitle.btnTitleSelected:hover {
	background: var(--formora-surface-alt) !important;
	border-color: var(--formora-line) !important;
	color: var(--formora-muted) !important;
}

.spannature,
.nonature-back {
	background: var(--formora-surface-alt) !important;
	border: 1px solid var(--formora-line) !important;
	color: var(--formora-muted) !important;
	box-shadow: none !important;
}

.spannature label,
.spannature .spanlabel {
	color: inherit !important;
}

.spannature input[type="checkbox"],
.spannature input[type="radio"] {
	accent-color: var(--formora-accent);
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
	color: var(--formora-accent-hover) !important;
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
	background: var(--formora-surface-alt) !important;
	border-color: var(--formora-line) !important;
	color: var(--formora-muted) !important;
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
	color: var(--formora-muted) !important;
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
	color: var(--formora-icon-muted) !important;
}

#mainbody table.border td > .fa-barcode,
#mainbody table.border td > .fa-cube,
#mainbody table.border td > .fa-box-open,
#mainbody table.border td > .fa-boxes {
	color: var(--formora-infobox-stock) !important;
}

#mainbody table.border td > .fa-user,
#mainbody table.border td > .fa-user-alt,
#mainbody table.border td > .fa-user-tie {
	color: var(--formora-infobox-member) !important;
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
	color: var(--formora-infobox-member) !important;
}

#mainbody .infobox-holiday,
#mainbody .info-box-module .infobox-holiday,
#mainbody .pictomodule.infobox-holiday,
#mainbody .pictomodule.fa-umbrella-beach,
#mainbody .fa-dol-holiday,
#mainbody .fa-dol-holiday::before {
	color: var(--formora-infobox-holiday) !important;
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
	color: var(--formora-accent-hover) !important;
}

#mainbody .a-filter,
#mainbody .a-mesure {
	background: var(--formora-accent-soft) !important;
	border: 1px solid rgba(78, 163, 255, 0.35) !important;
	box-shadow: none !important;
	color: var(--formora-accent-hover) !important;
}

#mainbody .a-filter-disabled,
#mainbody .a-mesure-disabled {
	background: var(--formora-surface-alt) !important;
	border: 1px solid var(--formora-line) !important;
	box-shadow: none !important;
	color: var(--formora-muted) !important;
	opacity: 1 !important;
}

#mainbody .websitebar {
	background: var(--formora-surface-alt) !important;
	border-bottom-color: var(--formora-line) !important;
	color: var(--formora-text) !important;
}

#mainbody .websitebar .button,
#mainbody .websitebar .buttonDelete {
	background: var(--formora-surface) !important;
	border-color: var(--formora-line) !important;
	color: var(--formora-text) !important;
	box-shadow: none !important;
}

#mainbody .websitebar .button:hover,
#mainbody .websitebar .button:focus,
#mainbody .websitebar .button.bordertransp:hover,
#mainbody .websitebar .button.bordertransp:focus {
	background: var(--formora-neutral-hover-bg) !important;
	border-color: rgba(78, 163, 255, 0.45) !important;
	color: var(--formora-accent-hover) !important;
}

#mainbody .websitebar input#previewpageurl,
#mainbody .websitebar input.bordertransp {
	background: var(--inputbackgroundcolor) !important;
	border-color: var(--inputbordercolor) !important;
	color: var(--formora-text) !important;
}

#mainbody .websitebar .fa-plus-circle.btnTitle-icon {
	color: var(--formora-accent-hover) !important;
}

#mainbody img[src$="/opensurvey/img/calendar-32.png"],
#mainbody img[src$="/opensurvey/img/chart-32.png"],
#mainbody img[src$="/img/calendar-32.png"],
#mainbody img[src$="/img/chart-32.png"] {
	filter: none !important;
	opacity: 1;
}

#mainbody div.corps:has(.index_date, .index_sondage),
#mainbody div.corps:has(.opensurveydescription),
#mainbody .survey_intro {
	background: var(--formora-surface) !important;
	border-color: var(--formora-line) !important;
	box-shadow: none !important;
	color: var(--formora-text) !important;
}

#mainbody .index_date,
#mainbody .index_sondage {
	color: var(--formora-text) !important;
}

body.bodytakepos {
	background: var(--formora-bg) !important;
	color: var(--formora-text) !important;
}

body.bodytakepos .topnav {
	background: var(--formora-surface) !important;
	border-bottom: 1px solid var(--formora-line) !important;
}

body.bodytakepos .topnav a,
body.bodytakepos .topnav .fa,
body.bodytakepos .topnav .fas,
body.bodytakepos .topnav .far,
body.bodytakepos .topnav .fab {
	color: var(--formora-text) !important;
}

body.bodytakepos .topnav .fa,
body.bodytakepos .topnav .fas,
body.bodytakepos .topnav .far,
body.bodytakepos .topnav .fab {
	color: inherit !important;
}

body.bodytakepos .topnav-left a:hover:not(.nohover),
body.bodytakepos .topnav .login_block_other a:hover:not(.nohover) {
	background: var(--formora-neutral-hover-bg) !important;
	color: var(--formora-accent-hover) !important;
}

body.bodytakepos .modal-content {
	background: var(--formora-surface) !important;
	border: 1px solid var(--formora-line) !important;
	box-shadow: 0 12px 30px rgba(0, 0, 0, 0.45) !important;
	color: var(--formora-text) !important;
}

body.bodytakepos .wrapper,
body.bodytakepos .wrapper2 {
	background: var(--formora-surface) !important;
	border: 1px solid var(--formora-line) !important;
	box-shadow: none !important;
	color: var(--formora-text) !important;
}

body.bodytakepos .wrapper.divempty,
body.bodytakepos .wrapper2.divempty {
	background: var(--formora-surface-alt) !important;
	border-color: var(--formora-line-soft) !important;
}

body.bodytakepos .description,
body.bodytakepos .catwatermark {
	background: rgba(21, 23, 25, 0.72) !important;
	color: var(--formora-text) !important;
}

body.bodytakepos input,
body.bodytakepos select,
body.bodytakepos textarea {
	background: var(--inputbackgroundcolor) !important;
	border-color: var(--inputbordercolor) !important;
	color: var(--formora-text) !important;
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
	color: var(--formora-accent-hover) !important;
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
	background: var(--formora-status-success-bg) !important;
	border-color: var(--formora-status-success-border) !important;
	color: var(--formora-status-success-text) !important;
}

.badge-status.badge-status8,
.badge-status.badge-status10,
.badge-status8,
.badge-status10 {
	background: rgba(255, 139, 139, 0.14) !important;
	border-color: rgba(255, 139, 139, 0.3) !important;
	color: #ffb4b4 !important;
}

.badge-dot.badge-status0,
.badge-dot.badge-status2,
.badge-dot.badge-status5,
.badge-dot.badge-status6,
.badge-dot.badge-status9 {
	background: rgba(200, 208, 217, 0.28) !important;
	border-color: rgba(200, 208, 217, 0.38) !important;
}

.badge-dot.badge-status1,
.badge-dot.badge-status1b,
.badge-dot.badge-status3 {
	background: rgba(255, 211, 138, 0.32) !important;
	border-color: rgba(255, 211, 138, 0.46) !important;
}

.badge-dot.badge-status4,
.badge-dot.badge-status4b,
.badge-dot.badge-status7,
.badge-dot.badge-status11 {
	background: rgba(131, 229, 161, 0.3) !important;
	border-color: rgba(131, 229, 161, 0.44) !important;
}

.badge-dot.badge-status8,
.badge-dot.badge-status10 {
	background: rgba(255, 180, 180, 0.3) !important;
	border-color: rgba(255, 180, 180, 0.44) !important;
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
	color: var(--formora-muted) !important;
	padding: 2px 9px !important;
}

div.statusref.statusrefbis .opacitymedium,
div.statusdoc.statusrefbis .opacitymedium,
div.statusrefsupplier.statusrefbis .opacitymedium {
	color: var(--formora-muted) !important;
}

.jnotify-container .jnotify-notification {
	background: var(--formora-surface) !important;
	border-color: var(--formora-line) !important;
	box-shadow: 0 4px 14px rgba(0, 0, 0, 0.4);
}

.jnotify-background,
div.jnotify-background,
.jnotify-container .jnotify-background {
	background: transparent !important;
	box-shadow: none !important;
	pointer-events: none !important;
}

	.jnotify-container .jnotify-notification .jnotify-message,
	.jnotify-container .jnotify-close {
		color: var(--formora-text) !important;
	}

	.jnotify-container .jnotify-notification-ok {
		background: var(--formora-toast-success-bg) !important;
		border-color: var(--formora-toast-success-border) !important;
	}

	.jnotify-container .jnotify-notification-ok .jnotify-background {
		background: transparent !important;
	}

	.jnotify-container .jnotify-notification-ok .jnotify-message {
		color: var(--formora-text) !important;
	}

	.jnotify-container .jnotify-notification-ok .jnotify-close {
		color: #83e5a1 !important;
	}

	.jnotify-container .jnotify-notification-warning {
		background: var(--formora-toast-warning-bg) !important;
		border-color: var(--formora-toast-warning-border) !important;
	}

	.jnotify-container .jnotify-notification-warning .jnotify-background {
		background: transparent !important;
	}

	.jnotify-container .jnotify-notification-warning .jnotify-message {
		color: var(--formora-text) !important;
	}

	.jnotify-container .jnotify-notification-warning .jnotify-close {
		color: #ffd38a !important;
	}

	.jnotify-container .jnotify-notification-error {
		background: var(--formora-toast-error-bg) !important;
		border-color: var(--formora-toast-error-border) !important;
	}

	.jnotify-container .jnotify-notification-error .jnotify-background {
		background: transparent !important;
	}

	.jnotify-container .jnotify-notification-error .jnotify-message {
		color: var(--formora-text) !important;
	}

	.jnotify-container .jnotify-notification-error .jnotify-close {
		color: #ffb4b4 !important;
	}

	.jnotify-container .jnotify-notification .jnotify-message :is(b, strong) {
		color: inherit !important;
	}

.ui-widget-header,
.ui-state-default,
.ui-state-active,
.ui-datepicker,
.ui-datepicker-header,
.ui-menu,
.ui-autocomplete {
	background: var(--formora-surface-alt) !important;
	border-color: var(--formora-line) !important;
	color: var(--formora-text) !important;
}

div#ui-datepicker-div,
.ui-datepicker.ui-widget.ui-widget-content {
	background: var(--formora-surface-alt) !important;
	border-color: var(--formora-line) !important;
	box-shadow: 0 2px 14px rgba(0, 0, 0, 0.55) !important;
}

.ui-state-hover,
.ui-menu .ui-menu-item-wrapper.ui-state-active,
.ui-autocomplete .ui-menu-item-wrapper.ui-state-active {
	background: var(--formora-neutral-hover-bg) !important;
	color: var(--formora-accent-hover) !important;
}

.ui-dialog .ui-dialog-titlebar-close {
	background: var(--formora-surface-alt) !important;
	border: 1px solid var(--formora-line) !important;
	color: var(--formora-muted) !important;
	box-shadow: none !important;
}

.ui-dialog .ui-dialog-titlebar-close:hover,
.ui-dialog .ui-dialog-titlebar-close:focus,
.ui-dialog .ui-dialog-titlebar-close.ui-state-hover,
.ui-dialog .ui-dialog-titlebar-close.ui-state-focus {
	background: var(--formora-neutral-hover-bg) !important;
	border-color: rgba(78, 163, 255, 0.45) !important;
	color: var(--formora-accent-hover) !important;
}

.ui-dialog .ui-dialog-titlebar-close:focus-visible {
	outline: 0;
	box-shadow: 0 0 0 2px var(--formora-focus-ring) !important;
}

.ui-dialog .ui-dialog-titlebar-close .ui-icon-closethick {
	filter: brightness(1.85);
	opacity: 0.95;
}

.ui-dialog .ui-dialog-buttonpane .ui-button {
	background: var(--formora-surface-alt) !important;
	border: 1px solid var(--formora-line) !important;
	color: var(--formora-text) !important;
	box-shadow: none !important;
}

.ui-dialog .ui-dialog-buttonpane .ui-button:hover,
.ui-dialog .ui-dialog-buttonpane .ui-button:focus,
.ui-dialog .ui-dialog-buttonpane .ui-button.ui-state-hover,
.ui-dialog .ui-dialog-buttonpane .ui-button.ui-state-focus {
	background: var(--formora-neutral-hover-bg) !important;
	border-color: rgba(78, 163, 255, 0.45) !important;
	color: var(--formora-accent-hover) !important;
}

.ui-dialog .ui-dialog-buttonpane .ui-button:focus-visible {
	outline: 0;
	box-shadow: 0 0 0 2px var(--formora-focus-ring) !important;
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
	background: var(--formora-surface-alt) !important;
	border-color: var(--formora-line) !important;
	color: var(--formora-text) !important;
}

div.pagination .disabled span,
div.pagination .disabled a {
	background: var(--formora-surface-alt) !important;
	border-color: var(--formora-line) !important;
	color: var(--formora-muted) !important;
}

.warning,
.error,
.ok,
.info,
.messagedebug,
.messagewarning,
.messageerr,
.messageok {
	border-color: var(--formora-line) !important;
	color: var(--formora-text) !important;
}

.messageerr,
.error {
	background: var(--formora-danger-bg) !important;
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
	background: var(--formora-accent-soft) !important;
	border-color: rgba(78, 163, 255, 0.28) !important;
	border-left-color: var(--formora-accent) !important;
	color: var(--formora-accent-hover) !important;
}

img,
.photowithmargin,
.photo,
.photowithmargin {
	border-color: var(--formora-line) !important;
}

.dropzone,
.attachedfiles,
.ecmfile,
.drag,
.dragHandle {
	background: var(--formora-surface-alt) !important;
	border-color: var(--formora-line) !important;
	color: var(--formora-text) !important;
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
	scrollbar-color: #3a4048 var(--formora-bg);
}

::-webkit-scrollbar {
	background: var(--formora-bg);
}

::-webkit-scrollbar-thumb {
	background: #3a4048;
	border: 2px solid var(--formora-bg);
	border-radius: 999px;
}

::-webkit-scrollbar-thumb:hover {
	background: #4a525c;
}

.box, .boxtable {
	scrollbar-color: var(--formora-line) transparent;
}
<?php
	print "}\n";
	}
	?>

	/* Formora Apple-inspired refresh layer: shared component rules for light and dark palettes. */
	body#mainbody,
	body.bodylogin {
		background: var(--formora-bg) !important;
		color: var(--formora-text) !important;
		font-synthesis-weight: none;
		-webkit-font-smoothing: antialiased;
		text-rendering: optimizeLegibility;
	}

	body#mainbody table,
	body#mainbody input,
	body#mainbody textarea,
	body#mainbody select,
	body#mainbody button,
	body.bodylogin input,
	body.bodylogin button {
		letter-spacing: 0;
	}

	body#mainbody a,
	body#mainbody .classlink,
	body#mainbody .linkobject,
	body#mainbody .nowraponall a {
		color: var(--formora-accent);
		text-decoration-thickness: 1px;
		text-underline-offset: 2px;
	}

	body#mainbody a:hover,
	body#mainbody .classlink:hover,
	body#mainbody .linkobject:hover {
		color: var(--formora-accent-hover);
	}

	body#mainbody #id-container,
	body#mainbody #id-right,
	body#mainbody #id-right .fiche,
	body#mainbody #id-right div.fiche {
		background: var(--formora-bg) !important;
		color: var(--formora-text) !important;
	}

	body#mainbody #id-top {
		background: var(--formora-surface) !important;
		border-bottom: 1px solid var(--formora-line-soft) !important;
		box-shadow: 0 1px 0 rgba(0, 0, 0, 0.02) !important;
	}

	body#mainbody #id-top li.tmenu,
	body#mainbody #id-top li.tmenusel {
		background: transparent !important;
		background-image: none !important;
		border-radius: 0 !important;
		margin-left: 6px !important;
		min-width: 52px !important;
		width: auto !important;
	}

	body#mainbody #id-top li.tmenu div.tmenucenter,
	body#mainbody #id-top li.tmenusel div.tmenucenter {
		border: 1px solid transparent !important;
		border-radius: var(--formora-radius-sm) !important;
		box-sizing: border-box !important;
		box-shadow: none !important;
		display: flex !important;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		min-height: 46px;
		min-width: 52px;
		overflow: visible !important;
		padding: 3px 0 !important;
		position: relative;
		transition: background-color 0.14s ease, border-color 0.14s ease, color 0.14s ease;
		width: auto !important;
	}

	body#mainbody #id-top :is(li.tmenu, li.tmenusel) div.tmenucenter:has(> a.tmenulabel[href]) {
		cursor: pointer;
	}

	body#mainbody #id-top :is(li.tmenu, li.tmenusel) div.tmenucenter > a.tmenulabel[href] {
		align-items: flex-end;
		box-sizing: border-box !important;
		display: flex !important;
		justify-content: center;
		min-height: 18px;
		padding: 0 8px !important;
		position: relative;
		width: 100% !important;
	}

	body#mainbody #id-top :is(li.tmenu, li.tmenusel) div.tmenucenter > a.tmenuimage {
		align-items: center;
		box-sizing: border-box !important;
		display: flex !important;
		height: 25px !important;
		justify-content: center;
		min-height: 25px;
		position: relative;
		width: 100% !important;
	}

	body#mainbody #id-top :is(li.tmenu, li.tmenusel) div.tmenucenter > a.tmenuimage > .topmenuimage {
		left: auto !important;
		margin-left: auto !important;
		margin-right: auto !important;
		position: static !important;
		right: auto !important;
		transform: none !important;
	}

	body#mainbody #id-top li.tmenu :is(a, span, div.mainmenu, .mainmenuaspan, .tmenuimage, .tmenulabel),
	body#mainbody #id-top li.tmenusel :is(a, span, div.mainmenu, .mainmenuaspan, .tmenuimage, .tmenulabel),
	body#mainbody #id-top li.tmenu:hover :is(a, span, div.mainmenu, .mainmenuaspan, .tmenuimage, .tmenulabel),
	body#mainbody #id-top li.tmenusel:hover :is(a, span, div.mainmenu, .mainmenuaspan, .tmenuimage, .tmenulabel) {
		background: transparent !important;
		background-color: transparent !important;
		background-image: none !important;
		box-shadow: none !important;
	}

	body#mainbody #id-top li.tmenu:not(.nohover):hover div.tmenucenter,
	body#mainbody #id-top li.tmenusel div.tmenucenter {
		background: var(--formora-neutral-hover-bg) !important;
		border-color: var(--formora-line-soft) !important;
	}

	body#mainbody #id-top a.tmenuimage,
	body#mainbody #id-top a.tmenulabel,
	body#mainbody #id-top a.tmenulabel .mainmenuaspan {
		color: var(--formora-muted) !important;
		max-width: none !important;
		overflow: visible !important;
		text-overflow: clip !important;
		white-space: nowrap !important;
		width: auto !important;
	}

	body#mainbody #id-top li.tmenu:not(.nohover):hover a.tmenuimage,
	body#mainbody #id-top li.tmenu:not(.nohover):hover a.tmenulabel,
	body#mainbody #id-top li.tmenusel a.tmenuimage,
	body#mainbody #id-top li.tmenusel a.tmenulabel,
	body#mainbody #id-top li.tmenusel a.tmenulabel .mainmenuaspan {
		color: var(--formora-text) !important;
	}

	body#mainbody #id-top a.tmenuimage :is(.fa, .fas, .far, .fab)[class*="fa-"]::before,
	body#mainbody #id-top a.tmenuimage[class*="fa-"]::before {
		color: currentColor !important;
		font-size: 22px !important;
		height: 22px !important;
		line-height: 22px !important;
		width: 22px !important;
	}

	body#mainbody #id-left,
	body#mainbody #id-left .blockvmenu,
	body#mainbody #id-left .blockvmenupair,
	body#mainbody #id-left .blockvmenuimpair,
	body#mainbody #id-left .blockvmenufirst,
	body#mainbody #id-left .blockvmenulast {
		background: var(--colorbackvmenu1) !important;
		border-color: var(--formora-line-soft) !important;
	}

	@media only screen and (min-width: 768px) {
		body#mainbody:not(.bodylogin):not(.poslist) #id-container:has(#id-left) {
			min-height: calc(100vh - 51px);
			position: relative;
		}

		body#mainbody:not(.bodylogin):not(.poslist) #id-container:has(#id-left)::before {
			background: var(--colorbackvmenu1);
			border-<?php print $right; ?>: 1px solid var(--formora-line-soft);
			bottom: 0;
			box-sizing: border-box;
			content: "";
			<?php print $left; ?>: 0;
			pointer-events: none;
			position: absolute;
			top: 0;
			width: <?php echo $leftmenuwidth + 20; ?>px;
			z-index: 0;
		}

		body#mainbody:not(.bodylogin) #id-left,
		body#mainbody:not(.bodylogin) #id-right {
			position: relative;
			z-index: 1;
		}

		body#mainbody:not(.bodylogin) #id-left {
			min-height: calc(100vh - 60px);
		}

		body#mainbody:not(.bodylogin) #id-left > .vmenu {
			min-height: calc(100vh - 80px);
		}

		body#mainbody.bodyforlist:not(.poslist) #id-right .fiche {
			box-sizing: border-box !important;
			max-width: calc(100vw - <?php echo $leftmenuwidth + 103; ?>px) !important;
			min-width: 0 !important;
		}

		body#mainbody.bodyforlist:not(.poslist) #id-right .fiche :is(form#searchFormList, table.table-fiche-title, .liste_titre_bydiv, .div-table-responsive, .div-table-responsive-no-min) {
			box-sizing: border-box !important;
			max-width: 100% !important;
			min-width: 0 !important;
			width: 100% !important;
		}

		body#mainbody.bodyforlist:not(.poslist) #id-right .fiche :is(.div-table-responsive, .div-table-responsive-no-min) {
			-webkit-overflow-scrolling: touch;
			overflow-x: auto !important;
			scrollbar-color: var(--formora-line) transparent;
			scrollbar-width: thin;
		}

		body#mainbody.bodyforlist:not(.poslist) #id-right .fiche :is(.div-table-responsive, .div-table-responsive-no-min) > table.liste {
			max-width: none !important;
			min-width: 100% !important;
		}

		@media only screen and (max-width: 929px) {
			body#mainbody.bodyforlist.sidebar-collapse:not(.poslist) #id-right .fiche {
				max-width: calc(100vw - 27px) !important;
			}
		}

		body#mainbody.bodyforlist:not(.poslist) #id-right .fiche form#searchFormList:has(> .liste_titre_bydiv + .div-table-responsive) {
			-webkit-overflow-scrolling: touch;
			overflow-x: auto !important;
			scrollbar-color: var(--formora-line) transparent;
			scrollbar-width: thin;
		}

		body#mainbody.bodyforlist:not(.poslist) #id-right .fiche form#searchFormList:has(> .liste_titre_bydiv + .div-table-responsive) > table.table-fiche-title {
			left: 0;
			position: sticky;
			z-index: 2;
		}

		body#mainbody.bodyforlist:not(.poslist) #id-right .fiche form#searchFormList:has(> .liste_titre_bydiv + .div-table-responsive) > .liste_titre_bydiv,
		body#mainbody.bodyforlist:not(.poslist) #id-right .fiche form#searchFormList:has(> .liste_titre_bydiv + .div-table-responsive) > .div-table-responsive {
			max-width: none !important;
			min-width: 100% !important;
			width: max-content !important;
		}

		body#mainbody.bodyforlist:not(.poslist) #id-right .fiche form#searchFormList:has(> .liste_titre_bydiv + .div-table-responsive) > .div-table-responsive {
			overflow-x: visible !important;
		}

		body#mainbody:not(.bodyforlist):not(.bodytakepos):not(.bodylogin) #id-right .fiche :is(.boxhalfleft, .boxhalfright, .fichehalfleft, .fichehalfright) > .box.divboxtable.boxdraggable:has(> table.boxtable) {
			-webkit-overflow-scrolling: touch;
			box-sizing: border-box !important;
			max-width: 100% !important;
			overflow-x: auto !important;
			scrollbar-color: var(--formora-line) transparent;
			scrollbar-width: thin;
		}

		body#mainbody:not(.bodyforlist):not(.bodytakepos):not(.bodylogin) #id-right .fiche :is(.boxhalfleft, .boxhalfright, .fichehalfleft, .fichehalfright) > .box.divboxtable.boxdraggable > table.boxtable {
			max-width: none !important;
			min-width: 100% !important;
		}
	}

	body#mainbody #id-left .vmenu_titre,
	body#mainbody #id-left .menu_titre {
		background: transparent !important;
		border-color: transparent !important;
		color: var(--formora-muted) !important;
		font-weight: 600;
		padding: 2px 0 !important;
	}

	body#mainbody #id-left :is(.menu_titre, .vmenu_titre):not(:has(> a.vmenu)) {
		align-items: center;
		border-radius: var(--formora-radius-sm) !important;
		box-sizing: border-box !important;
		color: var(--formora-text) !important;
		display: flex !important;
		gap: 4px;
		line-height: 1.25 !important;
		margin: 4px 8px 0 !important;
		max-width: 100%;
		min-height: 28px;
		padding: 4px 10px 4px 4px !important;
		width: calc(100% - 16px) !important;
	}

	body#mainbody #id-left :is(.menu_titre, .vmenu_titre):not(:has(> a.vmenu)) > :is(span.vmenu, span.vmenudisabled) {
		align-items: center;
		background: transparent !important;
		border: 0 !important;
		box-sizing: border-box !important;
		color: inherit !important;
		display: flex !important;
		gap: 4px;
		line-height: 1.25 !important;
		margin: 0 !important;
		padding: 0 !important;
		width: auto !important;
	}

	body#mainbody #id-left :is(.menu_titre, .vmenu_titre):not(:has(> a.vmenu)) > :is(span.vmenu, span.vmenudisabled) :is(.paddingright, .paddingleft) {
		padding-left: 0 !important;
		padding-right: 0 !important;
	}

	body#mainbody #id-left :is(.menu_titre, .vmenu_titre):not(:has(> a.vmenu)) > span.vmenudisabled {
		color: var(--formora-muted) !important;
	}

	body#mainbody #id-left .menu_top {
		display: none !important;
	}

	body#mainbody #id-left .menu_contenu {
		line-height: 0 !important;
		padding: 1px 0 !important;
	}

	body#mainbody #id-left .menu_contenu > br {
		display: none !important;
	}

	body#mainbody #id-left .menu_contenu:not(:has(> a)) {
		background: transparent !important;
		border-color: transparent !important;
		box-sizing: border-box !important;
		color: var(--formora-muted) !important;
		font-size: 0.92em;
		font-weight: 600;
		line-height: 1.25 !important;
		margin: 6px 8px 2px !important;
		min-height: 24px;
		padding: 3px 10px !important;
		width: calc(100% - 16px) !important;
	}

	body#mainbody #id-left a.vmenu,
	body#mainbody #id-left .menu_titre > a.vmenu,
	body#mainbody #id-left .vmenu_titre > a.vmenu,
	body#mainbody #id-left .menu_contenu > a {
		border-radius: var(--formora-radius-sm) !important;
		box-sizing: border-box !important;
		color: var(--formora-text) !important;
		display: flex !important;
		align-items: center;
		gap: 4px;
		line-height: 1.25 !important;
		margin: 0 8px !important;
		max-width: 100%;
		min-height: 28px;
		padding: 4px 10px !important;
		transition: background-color 0.14s ease, color 0.14s ease;
		width: calc(100% - 16px) !important;
	}

	body#mainbody #id-left .menu_contenu > a {
		min-height: 28px;
		margin: 0 8px !important;
		padding: 4px 10px !important;
		width: calc(100% - 16px) !important;
	}

	body#mainbody #id-left :is(.menu_titre, .vmenu_titre) > a.vmenu {
		padding-left: 4px !important;
	}

	body#mainbody #id-left .menu_contenu > a[href*="search_status="],
	body#mainbody #id-left .menu_contenu > a[href*="search_fk_statut="],
	body#mainbody #id-left .menu_contenu > a[href*="status="],
	body#mainbody #id-left .menu_contenu > a[href*="statut="]:not([href*="statut=-1%2C1"]),
	body#mainbody #id-left .menu_contenu > a[href*="filter="],
	body#mainbody #id-left .menu_contenu > a[href*="filtert="],
	body#mainbody #id-left .menu_contenu > a[href*="list_det.php"],
	body#mainbody #id-left .menu_contenu_societe_list > a[href*="type="],
	body#mainbody #id-left .menu_contenu_societe_card > a[href*="type="],
	body#mainbody #id-left .menu_contenu_contact_list > a[href*="type="],
	body#mainbody #id-left .menu_contenu[class*="menu_contenu_accountancy_journal_"] > a {
		margin: 0 8px 0 24px !important;
		width: calc(100% - 32px) !important;
	}

	body#mainbody #id-left a.vmenu:hover,
	body#mainbody #id-left .menu_titre:hover > a.vmenu,
	body#mainbody #id-left .vmenu_titre:hover > a.vmenu,
	body#mainbody #id-left .menu_contenu > a:hover,
	body#mainbody #id-left .menu_contenu:hover > a,
	body#mainbody #id-left .menu_contenu > a:focus {
		background: var(--formora-neutral-hover-bg) !important;
		color: var(--formora-accent) !important;
		text-decoration: none !important;
	}

	body#mainbody #id-left :is(.pictofixedwidth[class*="fa-"], .fa[class*="fa-"], .fas[class*="fa-"], .far[class*="fa-"], .fab[class*="fa-"])::before {
		font-size: 16px !important;
		line-height: 18px !important;
	}

	body#mainbody table.table-fiche-title {
		border-bottom: 1px solid var(--formora-line-soft) !important;
		margin-bottom: 16px !important;
		padding-bottom: 8px !important;
	}

	body#mainbody table.table-fiche-title tr,
	body#mainbody table.table-fiche-title td,
	body#mainbody table.table-fiche-title th {
		border-color: transparent !important;
	}

	body#mainbody table.table-fiche-title .titre,
	body#mainbody table.table-fiche-title div.titre,
	body#mainbody .titre {
		color: var(--formora-text) !important;
		font-weight: 650 !important;
		letter-spacing: 0 !important;
	}

	body#mainbody .opacitymedium,
	body#mainbody .opacitymedium i,
	body#mainbody .opacitymedium span {
		color: var(--formora-muted) !important;
	}

	body#mainbody .box,
	body#mainbody .boxtable,
	body#mainbody .box-flex-item,
	body#mainbody div.fiche div.info,
	body#mainbody div.fiche div.warning,
	body#mainbody div.fiche div.error,
	body#mainbody div.fiche div.neutral,
	body#mainbody table.noborder,
	body#mainbody table.border,
	body#mainbody table.tableforfield,
	body#mainbody table.tagtable {
		background: var(--formora-surface) !important;
		border-color: var(--formora-line-soft) !important;
		box-shadow: none !important;
		color: var(--formora-text) !important;
	}

	body#mainbody .fichecenter,
	body#mainbody .fichethirdleft,
	body#mainbody .fichetwothirdright,
	body#mainbody .fichehalfleft,
	body#mainbody .fichehalfright {
		background: transparent !important;
		box-shadow: none !important;
	}

	body#mainbody :is(.fichehalfleft, .fichehalfright, .fichethirdleft, .fichetwothirdright) > :is(table.noborder, table.border, table.tableforfield, .div-table-responsive, .div-table-responsive-no-min) {
		margin-bottom: 12px !important;
	}

	body#mainbody .tabBar {
		background: transparent !important;
		border-color: transparent !important;
		box-shadow: none !important;
		color: var(--formora-text) !important;
	}

	body#mainbody .tabBar :is(.tagtable, .table-border),
	body#mainbody .tabBar :is(.tagtr, .tagtd) {
		background: transparent !important;
		border-color: transparent !important;
		box-shadow: none !important;
	}

	body#mainbody .paginationref,
	body#mainbody .paginationref :is(ul, li, .litext, .noborder) {
		background: transparent !important;
		border-color: transparent !important;
		box-shadow: none !important;
	}

	body#mainbody .fichecenter .box-flex-container > .box-flex-item,
	body#mainbody .fichecenter .box-flex-container > .box-flex-item:hover {
		background: transparent !important;
		border-color: transparent !important;
		box-shadow: none !important;
	}

	body#mainbody :is(.boxhalfleft, .boxhalfright, .fichehalfleft, .fichehalfright) > .box.divboxtable.boxdraggable,
	body#mainbody :is(.boxhalfleft, .boxhalfright, .fichehalfleft, .fichehalfright) > .box.divboxtable.boxdraggable:hover {
		background: transparent !important;
		border-color: transparent !important;
		box-shadow: none !important;
		overflow: visible !important;
	}

	body#mainbody .box,
	body#mainbody .boxtable,
	body#mainbody .box-flex-item,
	body#mainbody .info-box,
	body#mainbody table.noborder,
	body#mainbody table.border,
	body#mainbody table.tableforfield,
	body#mainbody table.tagtable {
		border-radius: var(--formora-radius) !important;
	}

	body#mainbody .info-box,
	body#mainbody .box-flex-item.info-box-module .info-box {
		background: var(--formora-surface) !important;
		border: 1px solid var(--formora-line-soft) !important;
		box-shadow: none !important;
		color: var(--formora-text) !important;
		box-sizing: border-box !important;
		overflow: hidden !important;
		position: relative;
	}

	body#mainbody .info-box > .info-box-icon {
		border-radius: 0 !important;
		box-sizing: border-box !important;
		display: flex !important;
		align-items: center;
		justify-content: center;
		height: 100% !important;
		min-height: inherit !important;
	}

	body#mainbody .info-box:hover,
	body#mainbody .box-flex-item.info-box-module:hover .info-box {
		border-color: var(--formora-line) !important;
		box-shadow: var(--formora-shadow-soft) !important;
	}

	body#mainbody .info-box:has(dl.dropdown dd .multichoicedoc > ul.ulselectedfields.open) {
		overflow: visible !important;
	}

	body#mainbody table.liste,
	body#mainbody table.noborder,
	body#mainbody table.border,
	body#mainbody table.boxtable,
	body#mainbody table.tagtable.liste {
		border-collapse: separate !important;
		border-spacing: 0 !important;
		overflow: hidden;
	}

	body#mainbody table.boxtable tr:first-child > :first-child {
		border-top-left-radius: calc(var(--formora-radius) - 1px) !important;
	}

	body#mainbody table.boxtable tr:first-child > :last-child {
		border-top-right-radius: calc(var(--formora-radius) - 1px) !important;
	}

	body#mainbody table.boxtable tr:last-child > :first-child {
		border-bottom-left-radius: calc(var(--formora-radius) - 1px) !important;
	}

	body#mainbody table.boxtable tr:last-child > :last-child {
		border-bottom-right-radius: calc(var(--formora-radius) - 1px) !important;
	}

	body#mainbody table.liste th,
	body#mainbody table.noborder th,
	body#mainbody table.border th,
	body#mainbody .liste_titre,
	body#mainbody .liste_titre_sel,
	body#mainbody .liste_titre_create {
		background: var(--formora-table-header) !important;
		border-color: var(--formora-line-soft) !important;
		color: var(--formora-muted) !important;
		font-weight: 650 !important;
	}

	body#mainbody table.liste td,
	body#mainbody table.noborder td,
	body#mainbody table.border td,
	body#mainbody table.tableforfield td,
	body#mainbody table.tagtable td,
	body#mainbody tr.oddeven td,
	body#mainbody tr.pair td,
		body#mainbody tr.impair td {
			border-color: var(--formora-line-soft) !important;
			color: var(--formora-text) !important;
		}

		body#mainbody table.border:not(.liste) > tbody > tr > td {
			padding-bottom: 7px !important;
			padding-top: 7px !important;
		}

		body#mainbody table.border:not(.liste) {
			box-sizing: border-box !important;
			padding-left: 10px !important;
			padding-right: 10px !important;
		}

		body#mainbody table.liste td.opacitylow,
		body#mainbody table.noborder td.opacitylow,
		body#mainbody table.border td.opacitylow,
	body#mainbody table.tagtable td.opacitylow,
	body#mainbody tr.oddeven td.opacitylow,
	body#mainbody tr.pair td.opacitylow,
	body#mainbody tr.impair td.opacitylow {
		opacity: 1 !important;
		color: var(--formora-muted) !important;
	}

	body#mainbody table.liste tr,
	body#mainbody table.noborder tr,
	body#mainbody table.border tr,
	body#mainbody table.tableforfield tr,
	body#mainbody table.tagtable tr,
	body#mainbody tr.oddeven,
	body#mainbody tr.pair,
	body#mainbody tr.impair {
		border-color: var(--formora-line-soft) !important;
	}

	body#mainbody table.liste.formdoc > :is(tbody, tfoot):last-child > tr:last-child > :is(td, th),
	body#mainbody table.liste.formdoc > tbody > tr.oddeven:last-child > :is(td, th) {
		border-bottom: 0 !important;
	}

	body#mainbody table.tableforfield > :is(thead, tbody):first-child > tr:first-child > :is(td, th),
	body#mainbody table.border.tableforfield > :is(thead, tbody):first-child > tr:first-child > :is(td, th) {
		border-top: 0 !important;
	}

	body#mainbody :is(.fichehalfleft, .fichehalfright, .fichethirdleft, .fichetwothirdright):has(> table.tableforfield) > .underbanner.clearboth {
		border-bottom: 0 !important;
		height: 0 !important;
	}

	body#mainbody :is(.boxhalfleft, .boxhalfright, .fichehalfleft, .fichehalfright) > .box.divboxtable.boxdraggable:empty,
	body#mainbody :is(.boxhalfleft, .boxhalfright, .fichehalfleft, .fichehalfright) > .box.divboxtable.boxdraggable[id="boxto_A"],
	body#mainbody :is(.boxhalfleft, .boxhalfright, .fichehalfleft, .fichehalfright) > .box.divboxtable.boxdraggable[id="boxto_B"] {
		background: transparent !important;
		border-color: transparent !important;
		box-shadow: none !important;
	}

	body#mainbody tr.oddeven:hover td,
	body#mainbody tr.pair:hover td,
	body#mainbody tr.impair:hover td,
	body#mainbody tr.highlight td {
		background: var(--formora-neutral-hover-bg) !important;
	}

	body#mainbody tr.rowselected td,
	body#mainbody tr.selected td,
	body#mainbody tr.oddeven.selected td {
		background: var(--formora-accent-soft, rgba(0, 113, 227, 0.12)) !important;
		border-color: var(--formora-accent) !important;
	}

	body#mainbody input:not(.select2-search__field):not([type="checkbox"]):not([type="radio"]):not([type="image"]):not([type="submit"]):not([type="button"]),
	body#mainbody textarea,
	body#mainbody select,
	body#mainbody .select2-container--default .select2-selection--single,
	body#mainbody .select2-container--default .select2-selection--multiple,
	body.bodylogin input:not([type="checkbox"]):not([type="radio"]):not([type="image"]):not([type="submit"]):not([type="button"]) {
		background: var(--inputbackgroundcolor, var(--formora-surface)) !important;
		border: 1px solid var(--inputbordercolor, var(--formora-line)) !important;
		border-radius: var(--formora-radius-sm) !important;
		box-shadow: none !important;
		color: var(--formora-text) !important;
		min-height: var(--formora-control-height);
		transition: border-color 0.14s ease, box-shadow 0.14s ease, background-color 0.14s ease;
	}

	body#mainbody .select2-container--default .select2-selection--multiple .select2-search__field {
		background: transparent !important;
		border: 0 !important;
		border-radius: 0 !important;
		box-shadow: none !important;
		min-height: 0;
	}

	body#mainbody .select2-container--default .select2-selection--single {
		align-items: center !important;
		display: flex !important;
	}

	body#mainbody .select2-container--default .select2-selection--single .select2-selection__arrow {
		height: 100% !important;
		top: 0 !important;
	}

	body#mainbody .select2-container--default .select2-selection--single .select2-selection__rendered {
		align-items: center !important;
		display: flex !important;
		flex: 1 1 auto !important;
		height: 100% !important;
		line-height: normal !important;
		min-width: 0 !important;
		padding-bottom: 0 !important;
		padding-top: 0 !important;
	}

	body#mainbody .select2-container--default .select2-selection--single .select2-selection__arrow b {
		background-color: var(--formora-icon-muted) !important;
		border: 0 !important;
		display: block !important;
		height: 14px !important;
		left: 50% !important;
		margin: 0 !important;
		position: absolute !important;
		top: 50% !important;
		transform: translate(-50%, -50%) !important;
		width: 14px !important;
		-webkit-mask: var(--formora-symbol-sort-down) center / contain no-repeat;
		mask: var(--formora-symbol-sort-down) center / contain no-repeat;
	}

	body#mainbody .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b,
	body#mainbody .select2-container--default .select2-selection--single[aria-expanded="true"] .select2-selection__arrow b {
		-webkit-mask-image: var(--formora-symbol-sort-up) !important;
		mask-image: var(--formora-symbol-sort-up) !important;
	}

	body#mainbody input:focus:not(.noborderfocus):not(.inputsearch_dropdownselectedfields):not(.button):not(.buttonwebsite):not(.buttonreset):not(.select2-search__field):not(#top-bookmark-search-input):not(.search_component_input):not(.input-nobottom),
	body#mainbody textarea:focus,
	body#mainbody select:focus,
	body#mainbody .select2-container--open [aria-expanded="false"].select2-selection--single,
	body#mainbody .select2-container--focus span.selection span.select2-selection:not(.noborderfocus):not(.massactionselect),
	body.bodylogin input:focus {
		border-color: var(--formora-accent) !important;
		box-shadow: 0 0 0 3px var(--formora-focus-ring) !important;
		outline: none !important;
	}

	body#mainbody input:disabled,
	body#mainbody textarea:disabled,
	body#mainbody select:disabled,
	body#mainbody input[readonly],
	body#mainbody textarea[readonly],
	body#mainbody .select2-container--default.select2-container--disabled .select2-selection--single,
	body#mainbody .select2-container--default.select2-container--disabled .select2-selection--multiple {
		background: var(--inputbackgroundcolordisabled, var(--formora-neutral-bg)) !important;
		border-color: var(--formora-line-soft) !important;
		color: var(--inputcolordisabled, var(--formora-muted)) !important;
		cursor: not-allowed;
		opacity: 1;
	}

	body#mainbody div.urllink {
		align-items: center;
		background: var(--formora-neutral-bg) !important;
		border: 1px solid var(--formora-line-soft) !important;
		border-radius: var(--formora-radius-sm) !important;
		box-shadow: none !important;
		color: var(--formora-text) !important;
		display: flex;
		gap: 8px;
		height: auto !important;
		min-height: 40px;
		opacity: 1 !important;
		padding: 4px 6px !important;
	}

	body#mainbody div.urllink a,
	body#mainbody div.urllink :is(.fa, .fas, .far, .fab)[class*="fa-"] {
		color: var(--formora-accent) !important;
	}

	body#mainbody div.urllink input {
		flex: 1 1 auto;
		min-width: 0 !important;
		opacity: 1 !important;
	}

	body#mainbody .select2-dropdown,
	body#mainbody .select2-drop,
	body#mainbody .ui-autocomplete,
	body#mainbody .dropdown-content,
	body#mainbody .dropdown-menu,
	body#mainbody .ui-datepicker {
		background: var(--formora-elevated) !important;
		border: 1px solid var(--formora-line) !important;
		border-radius: var(--formora-radius) !important;
		box-shadow: var(--formora-shadow-menu) !important;
		color: var(--formora-text) !important;
		overflow: hidden;
	}

	body#mainbody .select2-results__option,
	body#mainbody .ui-menu .ui-menu-item-wrapper,
	body#mainbody .dropdown-item,
	body#mainbody .dropdown-content a {
		color: var(--formora-text) !important;
	}

	body#mainbody .select2-dropdown .select2-results__option {
		background: var(--formora-elevated) !important;
	}

	body#mainbody .select2-container--default .select2-results__option--highlighted[aria-selected],
	body#mainbody .select2-container--default .select2-results__option:hover,
	body#mainbody .select2-dropdown .select2-results .select2-highlighted,
	body#mainbody .select2-dropdown .select2-results__option:hover {
		background: var(--formora-neutral-hover-bg) !important;
		color: var(--formora-accent) !important;
	}

	body#mainbody .select2-container--default .select2-results__option--highlighted[aria-selected] span:not(.flag-sprite):not([class*="flag"]):not(.fi):not([class^="fi-"]):not([class*=" fi-"]),
	body#mainbody .select2-container--default .select2-results__option:hover span:not(.flag-sprite):not([class*="flag"]):not(.fi):not([class^="fi-"]):not([class*=" fi-"]) {
		background: transparent !important;
		color: inherit !important;
	}

	body#mainbody .select2-results .select2-highlighted,
	body#mainbody .ui-menu .ui-menu-item-wrapper.ui-state-active,
	body#mainbody .ui-menu .ui-menu-item-wrapper:hover,
	body#mainbody .ui-datepicker td a:hover {
		background: var(--formora-neutral-hover-bg) !important;
		color: var(--formora-accent) !important;
	}

	body#mainbody .butAction:not(.butActionDelete):not(.butActionRefused),
	body#mainbody .butAction:not(.butActionDelete):not(.butActionRefused):link,
	body#mainbody .butAction:not(.butActionDelete):not(.butActionRefused):visited,
	body#mainbody .button:not(.buttonDelete):not(.buttonRefused):not(.button-delete):not(.buttonpayment):not(.bordertransp):not(:disabled),
	body#mainbody input[type="submit"]:not(.buttonDelete):not(.buttonRefused):not(.buttonpayment):not(.bordertransp):not(:disabled),
	body#mainbody input[type="button"]:not(.buttonDelete):not(.buttonRefused):not(.buttonpayment):not(.bordertransp):not(:disabled) {
		align-items: center !important;
		appearance: none;
		background: var(--formora-accent) !important;
		background-image: none !important;
		border: 1px solid var(--formora-accent) !important;
		border-radius: var(--formora-radius-sm) !important;
		box-shadow: none !important;
		box-sizing: border-box !important;
		color: #fff !important;
		display: inline-flex !important;
		font-weight: 600 !important;
		height: auto !important;
		justify-content: center !important;
		line-height: 1.15 !important;
		min-height: 34px !important;
		padding: 7px 12px !important;
		vertical-align: middle !important;
	}

	body#mainbody .butAction:not(.butActionDelete):not(.butActionRefused):hover,
	body#mainbody .button:not(.buttonDelete):not(.buttonRefused):not(.button-delete):not(.buttonpayment):not(.bordertransp):not(:disabled):hover,
	body#mainbody input[type="submit"]:not(.buttonDelete):not(.buttonRefused):not(.buttonpayment):not(.bordertransp):not(:disabled):hover,
	body#mainbody input[type="button"]:not(.buttonDelete):not(.buttonRefused):not(.buttonpayment):not(.bordertransp):not(:disabled):hover {
		background: var(--formora-accent-hover) !important;
		border-color: var(--formora-accent-hover) !important;
		box-shadow: none !important;
		color: #fff !important;
		text-decoration: none !important;
	}

	body#mainbody.mod-product.page-list a.btnTitle.btnTitlePlus[href*="/product/card.php?action=create"] {
		align-items: center !important;
		background: rgba(10, 132, 255, 0.12) !important;
		border: 1px solid rgba(10, 132, 255, 0.34) !important;
		border-radius: 6px !important;
		border-width: 1px !important;
		box-sizing: border-box !important;
		color: var(--formora-accent) !important;
		display: inline-flex !important;
		gap: 6px !important;
		height: 36px !important;
		justify-content: center !important;
		min-height: 36px !important;
		padding: 7px 10px !important;
		text-decoration: none !important;
		vertical-align: middle !important;
		white-space: nowrap !important;
	}

	body#mainbody.mod-product.page-list a.btnTitle.btnTitlePlus[href*="/product/card.php?action=create"] :is(.btnTitle-icon, .btnTitle-label) {
		color: inherit !important;
		line-height: 1 !important;
	}

	body#mainbody.mod-product.page-list a.btnTitle.btnTitlePlus[href*="/product/card.php?action=create"] .btnTitle-icon {
		font-size: 14px !important;
		margin: 0 !important;
	}

	body#mainbody.mod-product.page-list a.btnTitle.btnTitlePlus[href*="/product/card.php?action=create"] .btnTitle-label {
		font-weight: 600 !important;
	}

	body#mainbody.mod-product.page-list a.btnTitle.btnTitlePlus[href*="/product/card.php?action=create"]:hover,
	body#mainbody.mod-product.page-list a.btnTitle.btnTitlePlus[href*="/product/card.php?action=create"]:focus {
		background: rgba(10, 132, 255, 0.18) !important;
		border-color: rgba(10, 132, 255, 0.56) !important;
		border-width: 1px !important;
		box-shadow: none !important;
		color: var(--formora-accent-hover) !important;
		height: 36px !important;
		padding: 7px 10px !important;
	}

	<?php
	if (getDolGlobalInt('THEME_DARKMODEENABLED')) {
		print "/* Formora dark-mode action buttons keep outline/soft fill treatment. */\n";
		if (getDolGlobalInt('THEME_DARKMODEENABLED') != 2) {
			print "@media (prefers-color-scheme: dark) {";
		} else {
			print "@media not print {";
		}
	?>
	body#mainbody .butAction:not(.butActionDelete):not(.butActionRefused),
	body#mainbody .butAction:not(.butActionDelete):not(.butActionRefused):link,
	body#mainbody .butAction:not(.butActionDelete):not(.butActionRefused):visited,
	body#mainbody .button:not(.buttonDelete):not(.buttonRefused):not(.button-delete):not(.buttonpayment):not(.bordertransp):not(:disabled),
	body#mainbody input[type="submit"]:not(.buttonDelete):not(.buttonRefused):not(.buttonpayment):not(.bordertransp):not(:disabled),
	body#mainbody input[type="button"]:not(.buttonDelete):not(.buttonRefused):not(.buttonpayment):not(.bordertransp):not(:disabled) {
		background: var(--formora-accent-soft) !important;
		border-color: rgba(78, 163, 255, 0.4) !important;
		color: var(--formora-accent) !important;
	}

	body#mainbody .butAction:not(.butActionDelete):not(.butActionRefused):hover,
	body#mainbody .button:not(.buttonDelete):not(.buttonRefused):not(.button-delete):not(.buttonpayment):not(.bordertransp):not(:disabled):hover,
	body#mainbody input[type="submit"]:not(.buttonDelete):not(.buttonRefused):not(.buttonpayment):not(.bordertransp):not(:disabled):hover,
	body#mainbody input[type="button"]:not(.buttonDelete):not(.buttonRefused):not(.buttonpayment):not(.bordertransp):not(:disabled):hover {
		background: var(--formora-accent-soft-hover) !important;
		border-color: var(--formora-accent) !important;
		color: var(--formora-accent) !important;
	}
	<?php
		print "}\n";
	}
	?>

	body#mainbody .butActionDelete,
	body#mainbody .butActionDelete:link,
	body#mainbody .butActionDelete:visited {
		background: var(--formora-danger) !important;
		background-image: none !important;
		border: 1px solid var(--formora-danger) !important;
		border-radius: var(--formora-radius-sm) !important;
		box-shadow: none !important;
		color: #fff !important;
	}

	body#mainbody .butActionDelete:hover {
		background: #a40e26 !important;
		border-color: #a40e26 !important;
		color: #fff !important;
	}

	<?php
	if (getDolGlobalInt('THEME_DARKMODEENABLED')) {
		if (getDolGlobalInt('THEME_DARKMODEENABLED') != 2) {
			print "@media (prefers-color-scheme: dark) {";
		} else {
			print "@media not print {";
		}
	?>
	body#mainbody .butActionDelete,
	body#mainbody .butActionDelete:link,
	body#mainbody .butActionDelete:visited {
		background: var(--formora-danger-bg) !important;
		border-color: rgba(255, 69, 58, 0.4) !important;
		color: var(--formora-danger) !important;
	}

	body#mainbody .butActionDelete:hover {
		background: var(--formora-danger-hover-bg) !important;
		border-color: var(--formora-danger) !important;
		color: var(--formora-danger) !important;
	}
	<?php
		print "}\n";
	}
	?>

	body#mainbody .butActionRefused,
	body#mainbody .butActionRefused:link,
	body#mainbody .butActionRefused:visited,
	body#mainbody .butActionNewRefused {
		background: var(--formora-refused-bg) !important;
		background-image: none !important;
		border: 1px solid var(--formora-line-soft) !important;
		border-radius: var(--formora-radius-sm) !important;
		box-shadow: none !important;
		color: var(--formora-muted) !important;
		cursor: not-allowed !important;
		opacity: 1 !important;
	}

	body#mainbody .butAction,
	body#mainbody .butActionDelete,
	body#mainbody .butActionRefused,
	body#mainbody .butActionNewRefused,
	body#mainbody .button,
	body#mainbody .buttonDelete,
	body#mainbody .buttonRefused,
	body#mainbody input[type="submit"],
	body#mainbody input[type="button"],
	body#mainbody button.button {
		align-items: center !important;
		box-sizing: border-box !important;
		display: inline-flex !important;
		height: auto !important;
		justify-content: center !important;
		line-height: 1.15 !important;
		min-height: 34px !important;
		padding: 7px 12px !important;
		vertical-align: middle !important;
		white-space: nowrap;
	}

	body#mainbody div.tabsAction,
	body#mainbody div.tabsActionNoBottom {
		align-items: center;
		display: flex;
		flex-wrap: nowrap;
		gap: 10px;
		justify-content: flex-end;
		overflow: visible !important;
	}

	body#mainbody div.tabsAction > a.butAction,
	body#mainbody div.tabsAction > a.butActionRefused,
	body#mainbody div.tabsAction > a.butActionDelete,
	body#mainbody div.tabsAction > span.butAction,
	body#mainbody div.tabsAction > span.butActionRefused,
	body#mainbody div.tabsAction > span.butActionDelete,
	body#mainbody div.tabsAction > div.divButAction > span.butAction,
	body#mainbody div.tabsAction > div.divButAction > span.butActionDelete,
	body#mainbody div.tabsAction > div.divButAction > span.butActionRefused,
	body#mainbody div.tabsAction > div.divButAction > a.butAction,
	body#mainbody div.tabsAction > div.divButAction > a.butActionDelete,
	body#mainbody div.tabsAction > div.divButAction > a.butActionRefused,
	body#mainbody div.tabsAction > .dropdown > .dropdown-toggle {
		margin-bottom: 0 !important;
		margin-left: 0 !important;
		margin-right: 0 !important;
	}

	body#mainbody input[name="confirmmassactioninvisible"] {
		display: none !important;
	}

	body#mainbody .butAction:focus,
	body#mainbody .butActionDelete:focus,
	body#mainbody .butActionRefused:focus,
	body#mainbody .button:focus,
	body#mainbody button:focus {
		box-shadow: 0 0 0 3px var(--formora-focus-ring) !important;
		outline: none !important;
	}

	body#mainbody .butAction:focus-visible,
	body#mainbody .butActionDelete:focus-visible,
	body#mainbody .butActionRefused:focus-visible,
	body#mainbody .button:focus-visible,
	body#mainbody .buttonDelete:focus-visible,
	body#mainbody button:focus-visible,
	body#mainbody a.btnTitle:focus-visible,
	body#mainbody .btnTitle:focus-visible {
		outline: 2px solid var(--formora-focus-outline) !important;
		outline-offset: 3px !important;
		box-shadow: 0 0 0 4px var(--formora-focus-ring) !important;
	}

	body#mainbody .badge,
	body#mainbody .badge-status,
	body#mainbody .badgeneutral,
	body#mainbody span.badgeneutral {
		align-items: center;
		border-radius: 999px !important;
		display: inline-flex;
		font-weight: 650 !important;
		gap: 4px;
		min-height: 18px;
		padding: 2px 7px !important;
		vertical-align: middle;
	}

	body#mainbody .badge-status,
	body#mainbody .badgeneutral,
	body#mainbody span.badgeneutral {
		border: 1px solid var(--formora-line-soft) !important;
	}

	body#mainbody .badge-dot.badge-status {
		box-sizing: border-box !important;
		display: inline-block !important;
		height: 14px !important;
		min-height: 14px !important;
		min-width: 14px !important;
		padding: 0 !important;
		vertical-align: middle;
		width: 14px !important;
	}

	body#mainbody .badge-dot.badge-status0,
	body#mainbody .badge-dot.badge-status2,
	body#mainbody .badge-dot.badge-status5,
	body#mainbody .badge-dot.badge-status6,
	body#mainbody .badge-dot.badge-status9 {
		background: rgba(200, 208, 217, 0.28) !important;
		border: 1px solid rgba(200, 208, 217, 0.38) !important;
	}

	body#mainbody .badge-dot.badge-status1,
	body#mainbody .badge-dot.badge-status1b,
	body#mainbody .badge-dot.badge-status3 {
		background: rgba(255, 211, 138, 0.32) !important;
		border: 1px solid rgba(255, 211, 138, 0.46) !important;
	}

	body#mainbody .badge-dot.badge-status4,
	body#mainbody .badge-dot.badge-status4b,
	body#mainbody .badge-dot.badge-status7,
	body#mainbody .badge-dot.badge-status11,
	body#mainbody .badge-dot.badge-status6[data-status-element="facture"][data-status="2"],
	body#mainbody .badge-dot.badge-status6[data-status-element="invoice_supplier"][data-status="2"],
	body#mainbody .badge-dot.badge-status6[data-status-element="commande"][data-status="3"] {
		background: rgba(48, 209, 88, 0.32) !important;
		border: 1px solid rgba(48, 209, 88, 0.48) !important;
	}

	body#mainbody .badge-dot.badge-status8,
	body#mainbody .badge-dot.badge-status10 {
		background: rgba(255, 180, 180, 0.3) !important;
		border: 1px solid rgba(255, 180, 180, 0.44) !important;
	}

	body#mainbody .badge .fa,
	body#mainbody .badge-status .fa,
	body#mainbody .badgeneutral .fa {
		font-size: 12px !important;
		line-height: 12px !important;
		margin: 0 !important;
		vertical-align: -1px;
	}

	body#mainbody .ui-dialog,
	body#mainbody .ui-dialog-content,
	body#mainbody .ui-dialog-titlebar,
	body#mainbody .modal-content,
	body#mainbody .ficheaddleft,
	body#mainbody .ficheaddright {
		background: var(--formora-elevated) !important;
		border-color: var(--formora-line) !important;
		color: var(--formora-text) !important;
	}

	body#mainbody .ui-dialog,
	body#mainbody .modal-content {
		border-radius: var(--formora-radius-lg) !important;
		box-shadow: var(--formora-shadow-elevated) !important;
		overflow: hidden;
	}

	body#mainbody .ui-widget-overlay,
	body#mainbody .modal-backdrop {
		background: rgba(0, 0, 0, 0.38) !important;
	}

	body#mainbody .ui-tooltip,
	body#mainbody .tooltip,
	body#mainbody .tooltipster-base,
	body#mainbody .tooltipster-box {
		background: var(--tooltipbgcolor, var(--formora-elevated)) !important;
		border: 1px solid var(--formora-line) !important;
		border-radius: var(--formora-radius-sm) !important;
		box-shadow: var(--formora-shadow-menu) !important;
		color: var(--tooltipfontcolor, var(--formora-text)) !important;
	}

	body#mainbody .jnotify-container {
		overflow: visible !important;
	}

	body#mainbody .jnotify-container .jnotify-notification {
		border: 1px solid var(--formora-line) !important;
		border-radius: var(--formora-radius) !important;
		box-shadow: var(--formora-shadow-menu) !important;
		overflow: hidden;
	}

	body#mainbody .jnotify-background,
	body#mainbody div.jnotify-background,
	body#mainbody .jnotify-container .jnotify-background {
		background: transparent !important;
		border: 0 !important;
		box-shadow: none !important;
		pointer-events: none !important;
	}

	body#mainbody .jnotify-container .jnotify-notification .jnotify-message,
	body#mainbody .jnotify-container .jnotify-close {
		color: var(--formora-text) !important;
	}

	body#mainbody .jnotify-container .jnotify-notification-ok {
		background: var(--formora-toast-success-bg) !important;
		border-color: var(--formora-toast-success-border) !important;
	}

	body#mainbody .jnotify-container .jnotify-notification-ok .jnotify-message {
		color: var(--formora-text) !important;
	}

	body#mainbody .jnotify-container .jnotify-notification-ok .jnotify-close {
		color: var(--formora-toast-success-text) !important;
	}

	body#mainbody .jnotify-container .jnotify-notification-warning {
		background: var(--formora-toast-warning-bg) !important;
		border-color: var(--formora-toast-warning-border) !important;
	}

	body#mainbody .jnotify-container .jnotify-notification-warning .jnotify-message {
		color: var(--formora-text) !important;
	}

	body#mainbody .jnotify-container .jnotify-notification-warning .jnotify-close {
		color: var(--formora-toast-warning-text) !important;
	}

	body#mainbody .jnotify-container .jnotify-notification-error {
		background: var(--formora-toast-error-bg) !important;
		border-color: var(--formora-toast-error-border) !important;
	}

	body#mainbody .jnotify-container .jnotify-notification-error .jnotify-message {
		color: var(--formora-text) !important;
	}

	body#mainbody .jnotify-container .jnotify-notification-error .jnotify-close {
		color: var(--formora-toast-error-text) !important;
	}

	body#mainbody .jnotify-container .jnotify-notification .jnotify-message :is(b, strong) {
		color: inherit !important;
	}

	body#mainbody [class*="fa-"],
	body#mainbody .fa,
	body#mainbody .fas,
	body#mainbody .far,
	body#mainbody .fab {
		color: inherit;
	}

	body#mainbody :is(.pictofixedwidth[class*="fa-"], .fa[class*="fa-"], .fas[class*="fa-"], .far[class*="fa-"], .fab[class*="fa-"]) {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		vertical-align: -2px;
	}

	body#mainbody :is(.pictofixedwidth[class*="fa-"], .fa[class*="fa-"], .fas[class*="fa-"], .far[class*="fa-"], .fab[class*="fa-"])::before {
		color: currentColor;
	}

	body#mainbody img,
	body#mainbody .flag-sprite,
	body#mainbody [class*="flag"],
	body#mainbody .fi,
	body#mainbody [class^="fi-"],
	body#mainbody [class*=" fi-"] {
		color: initial;
		filter: none;
	}

	body.bodylogin {
		background-color: var(--formora-bg) !important;
	}

	body.bodylogin .login_table {
		background: var(--formora-surface) !important;
		border: 1px solid var(--formora-line-soft) !important;
		border-radius: var(--formora-radius-lg) !important;
		box-shadow: var(--formora-shadow-elevated) !important;
		color: var(--formora-text) !important;
	}

	body.bodylogin .login_table .tagtable {
		background: transparent !important;
		border: 0 !important;
		box-shadow: none !important;
	}

	body.bodylogin .login_table input#username,
	body.bodylogin .login_table input#password,
	body.bodylogin .login_table input#securitycode {
		background: var(--inputbackgroundcolor, var(--formora-surface)) !important;
		border-color: var(--inputbordercolor, var(--formora-line)) !important;
		color: var(--formora-text) !important;
	}

	body.bodylogin .login_table input::placeholder {
		color: var(--formora-muted) !important;
		opacity: 0.54;
	}

	body.bodylogin .butActionLogin,
	body.bodylogin .butActionLogin:link,
	body.bodylogin .butActionLogin:visited,
	body.bodylogin .butActionLogin:hover,
	body.bodylogin .butActionLogin:active {
		background: var(--formora-surface-alt) !important;
		border: 1px solid var(--formora-line) !important;
		border-radius: var(--formora-radius-sm) !important;
		box-shadow: none !important;
		color: var(--formora-text) !important;
		font-weight: 650 !important;
		min-height: 44px;
	}

	body.bodylogin .butActionLogin:hover,
	body.bodylogin .butActionLogin:focus {
		background: var(--formora-neutral-hover-bg) !important;
		border-color: var(--formora-accent) !important;
		box-shadow: 0 0 0 3px var(--formora-focus-ring) !important;
		color: var(--formora-text) !important;
	}

	body#mainbody .login_block.usedropdown {
		align-items: center !important;
		display: flex !important;
		height: 60px !important;
		justify-content: flex-end !important;
		line-height: normal !important;
		top: 0 !important;
	}

	body#mainbody .login_block.usedropdown > :is(.login_block_tools, .login_block_other, .login_block_user) {
		align-items: center !important;
		display: flex !important;
		height: 100% !important;
		line-height: normal !important;
	}

	body#mainbody .login_block.usedropdown :is(.atoplogin, .login_block_elem, .login_block_other > .inline-block) {
		align-items: center !important;
		display: inline-flex !important;
		line-height: normal !important;
	}

	body#mainbody .login_block.usedropdown #topmenu-quickadd-dropdown,
	body#mainbody .login_block.usedropdown #topmenu-quickadd-dropdown > a.dropdown-toggle,
	body#mainbody .login_block.usedropdown #topmenu-login-dropdown > a.dropdown-toggle {
		align-items: center !important;
		display: inline-flex !important;
		height: 60px !important;
		line-height: normal !important;
	}

	/* Final action/menu dropdown override: menu rows must not inherit primary button fills. */
	body#mainbody .dropdown-holder.open > .dropdown-content,
	body#mainbody .tabsAction .dropdown.open > .dropdown-menu,
	body#mainbody .tabsActionNoBottom .dropdown.open > .dropdown-menu,
	body#mainbody .tabBar .dropdown.open > .dropdown-menu {
		background-color: var(--formora-surface) !important;
		background-image: none !important;
		border: 1px solid var(--formora-line) !important;
		box-shadow: 0 8px 18px rgba(31, 35, 40, 0.12) !important;
		color: var(--formora-text) !important;
	}

	body#mainbody .dropdown-holder.open > .dropdown-content::before {
		border-color: transparent transparent var(--formora-surface) transparent !important;
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
		border-bottom: 1px solid var(--formora-line-soft) !important;
		border-radius: 0 !important;
		box-sizing: border-box !important;
		box-shadow: none !important;
		color: var(--formora-text) !important;
		display: block !important;
		line-height: 20px !important;
		margin: 0 !important;
		min-height: 40px !important;
		min-width: 0 !important;
		padding: 10px 12px !important;
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
		background-color: var(--formora-neutral-hover-bg) !important;
		background-image: none !important;
		border-bottom-color: var(--formora-line) !important;
		box-shadow: none !important;
		color: var(--formora-accent) !important;
		text-decoration: none !important;
	}

	body#mainbody .dropdown-holder > .dropdown-content > a.butActionDelete,
	body#mainbody .dropdown-holder > .dropdown-content > a.butActionDelete:link,
	body#mainbody .dropdown-holder > .dropdown-content > a.butActionDelete:visited {
		color: var(--formora-danger) !important;
	}

	body#mainbody .dropdown-holder > .dropdown-content > a.butActionDelete:hover,
	body#mainbody .dropdown-holder > .dropdown-content > a.butActionDelete:focus {
		background-color: var(--formora-danger-bg) !important;
		background-image: none !important;
		border-bottom-color: #ff8182 !important;
		color: var(--formora-danger) !important;
	}

	body#mainbody .dropdown-holder > .dropdown-content > a.butActionRefused,
	body#mainbody .dropdown-holder > .dropdown-content > span.butActionRefused,
	body#mainbody .dropdown-holder > .dropdown-content > a.butActionRefused:hover,
	body#mainbody .dropdown-holder > .dropdown-content > span.butActionRefused:hover {
		color: var(--formora-muted) !important;
		cursor: not-allowed !important;
	}

	body#mainbody #topmenu-quickadd-dropdown .dropdown-menu .dropdown-item.quickadd-item {
		background-color: transparent !important;
		background-image: none !important;
		border: 0 !important;
		border-radius: var(--formora-radius-sm) !important;
		box-shadow: none !important;
		box-sizing: border-box !important;
		color: var(--formora-text) !important;
		display: block !important;
		margin: 0 !important;
		padding: 8px 12px !important;
		text-decoration: none !important;
		width: 100% !important;
	}

	body#mainbody #topmenu-quickadd-dropdown .dropdown-menu .dropdown-item.quickadd-item:hover,
	body#mainbody #topmenu-quickadd-dropdown .dropdown-menu .dropdown-item.quickadd-item:focus {
		background-color: var(--formora-neutral-hover-bg) !important;
		background-image: none !important;
		color: var(--formora-text) !important;
		text-decoration: none !important;
	}

	body#mainbody #topmenu-quickadd-dropdown .dropdown-menu .dropdown-item.quickadd-item > span,
	body#mainbody #topmenu-quickadd-dropdown .dropdown-menu .dropdown-item.quickadd-item:hover > span,
	body#mainbody #topmenu-quickadd-dropdown .dropdown-menu .dropdown-item.quickadd-item:focus > span {
		background: transparent !important;
		border: 0 !important;
		box-shadow: none !important;
	}

	body#mainbody #topmenu-quickadd-dropdown .dropdown-menu .dropdown-item.quickadd-item > span::before,
	body#mainbody #topmenu-quickadd-dropdown .dropdown-menu .dropdown-item.quickadd-item:hover > span::before,
	body#mainbody #topmenu-quickadd-dropdown .dropdown-menu .dropdown-item.quickadd-item:focus > span::before {
		background-color: currentColor !important;
		border: 0 !important;
		box-shadow: none !important;
		color: inherit !important;
	}

	body#mainbody input[name="confirmmassactioninvisible"],
	body#mainbody input[name="confirmmassaction"].hideobject:not([style*="display: inline"]) {
		display: none !important;
		height: 0 !important;
		left: -10000px !important;
		min-height: 0 !important;
		min-width: 0 !important;
		opacity: 0 !important;
		padding: 0 !important;
		position: absolute !important;
		top: auto !important;
		visibility: hidden !important;
		width: 0 !important;
	}

	body#mainbody.bodyforlist:has(.checkforselect) .select2-container:has(#select2-massaction-container) {
		display: none !important;
		height: 0 !important;
		left: -10000px !important;
		min-height: 0 !important;
		min-width: 0 !important;
		opacity: 0 !important;
		padding: 0 !important;
		position: absolute !important;
		top: auto !important;
		visibility: hidden !important;
		width: 0 !important;
	}

	body#mainbody.bodyforlist:not(.poslist):not(:has(.checkforselect)):has(#massaction) #id-right .fiche table.table-fiche-title .col-center:has(#massaction),
	body#mainbody.bodyforlist:not(.poslist):not(:has(.checkforselect)):has(#massaction) #id-right .fiche table.table-fiche-title .col-center:has(#massaction) > div,
	body#mainbody.bodyforlist:not(.poslist):not(:has(.checkforselect)):has(#massaction) #id-right .fiche table.table-fiche-title .select2-container:has(#select2-massaction-container),
	body#mainbody.bodyforlist:not(.poslist):not(:has(.checkforselect)):has(#massaction) #id-right .fiche table.table-fiche-title input[name="confirmmassaction"] {
		display: none !important;
		height: 0 !important;
		margin: 0 !important;
		max-width: 0 !important;
		min-height: 0 !important;
		min-width: 0 !important;
		opacity: 0 !important;
		padding: 0 !important;
		visibility: hidden !important;
		width: 0 !important;
	}

	body#mainbody.bodyforlist:has(.checkforselect:checked) .select2-container:has(#select2-massaction-container) {
		display: inline-block !important;
		height: auto !important;
		left: auto !important;
		min-height: var(--formora-control-height) !important;
		opacity: 1 !important;
		padding: 0 !important;
		position: static !important;
		top: auto !important;
		visibility: visible !important;
		width: 260px !important;
	}

	body#mainbody .select2-container:has(#select2-massaction-container) {
		width: 260px !important;
	}

	body#mainbody .select2-container:has(#select2-massaction-container) .select2-selection__rendered {
		overflow: hidden !important;
		text-overflow: ellipsis !important;
		white-space: nowrap !important;
	}

	body#mainbody .select2-container:has(#select2-massaction-container) .select2-selection__rendered :is(.pictofixedwidth, img, .fa, .fas, .far, .fab) {
		display: none !important;
	}

	body#mainbody.poslist #id-container::before {
		content: none !important;
		display: none !important;
	}

	body#mainbody.poslist #massaction,
	body#mainbody.poslist .select2-container:has(#select2-massaction-container),
	body#mainbody.poslist input[name="confirmmassaction"],
	body#mainbody.poslist input[name="confirmmassactioninvisible"] {
		border: 0 !important;
		display: none !important;
		height: 0 !important;
		left: -10000px !important;
		margin: 0 !important;
		min-height: 0 !important;
		min-width: 0 !important;
		opacity: 0 !important;
		padding: 0 !important;
		position: absolute !important;
		top: auto !important;
		visibility: hidden !important;
		width: 0 !important;
	}

	body#mainbody.poslist .toptitle .col-center:has(#massaction) {
		padding: 0 !important;
		width: 0 !important;
	}

	body#mainbody.poslist :is(#id-left, .side-nav) {
		display: none !important;
		min-height: 0 !important;
		width: 0 !important;
	}

	body#mainbody.poslist #id-right {
		display: block !important;
		width: 100% !important;
	}

	/* Shared TakePOS structure keeps POS aligned with Formora in both light and dark palettes. */
	body.bodytakepos,
	body.takepossplitphp,
	body:not(#mainbody):has(.takepospay),
	body:not(#mainbody):has(.calcbutton),
	body:not(#mainbody):has(.postablelines) {
		background: var(--formora-bg) !important;
		color: var(--formora-text) !important;
	}

	body.bodytakepos .container,
	body.bodytakepos .row1,
	body.bodytakepos .row1withhead,
	body.bodytakepos .row2,
	body.bodytakepos .row2withhead,
	body.bodytakepos :is(.div1, .div2, .div3, .div4, .div5),
	body.takepossplitphp :is(.splitsale, .rowsplit, .headersplit),
	body:not(#mainbody):has(.takepospay) :is(.center, .div-table-responsive-no-min) {
		background: var(--formora-bg) !important;
		color: var(--formora-text) !important;
	}

	body.bodytakepos .header,
	body.bodytakepos .topnav {
		background: var(--formora-surface) !important;
		border-bottom: 1px solid var(--formora-line-soft) !important;
	}

	body.bodytakepos .topnav {
		align-items: center !important;
		box-sizing: border-box !important;
		display: flex !important;
		flex-wrap: nowrap !important;
		height: 64px !important;
		min-height: 64px;
		overflow-x: auto !important;
		overflow-y: hidden !important;
		white-space: nowrap;
	}

	body.bodytakepos .topnav-left,
	body.bodytakepos .topnav-right {
		align-items: center !important;
		display: flex !important;
		flex-wrap: nowrap !important;
		float: none !important;
		height: 64px;
		min-width: 0;
	}

	body.bodytakepos .topnav-left {
		flex: 1 1 auto;
		overflow: hidden;
	}

	body.bodytakepos .topnav-right {
		flex: 0 0 auto;
		margin-left: auto;
	}

	body.bodytakepos .topnav .login_block_other.takepos {
		align-items: center !important;
		display: flex !important;
		gap: 10px;
		height: 64px;
		margin-top: 0 !important;
	}

	body.bodytakepos .topnav-left > .inline-block {
		align-items: center !important;
		display: flex !important;
		flex: 0 0 auto;
		flex-wrap: nowrap !important;
		gap: 10px;
		height: 64px;
		white-space: nowrap;
	}

	body.bodytakepos .topnav-left a,
	body.bodytakepos .topnav-right a {
		align-items: center !important;
		display: flex !important;
		flex: 0 0 auto;
		float: none !important;
		justify-content: center !important;
	}

	body.bodytakepos .topnav-left a span {
		align-items: center;
		display: inline-flex;
		white-space: nowrap;
	}

	body.bodytakepos .topnav a,
	body.bodytakepos .topnav :is(.fa, .fas, .far, .fab)::before {
		color: var(--formora-text) !important;
	}

	body.bodytakepos .topnav-left a,
	body.bodytakepos .topnav .login_block_other a {
		border: 1px solid transparent !important;
		border-radius: var(--formora-radius-sm) !important;
	}

	body.bodytakepos .topnav-left a:hover:not(.nohover),
	body.bodytakepos .topnav .login_block_other a:hover:not(.nohover),
	body.bodytakepos .topnav-left a:focus,
	body.bodytakepos .topnav .login_block_other a:focus {
		background: var(--formora-neutral-hover-bg) !important;
		border-color: var(--formora-line-soft) !important;
		color: var(--formora-text) !important;
	}

	body.bodytakepos .topnav-left a:active,
	body.bodytakepos .topnav .login_block_other a:active {
		background: var(--formora-accent-soft) !important;
		border-color: var(--formora-accent) !important;
		color: var(--formora-accent-hover) !important;
	}

	:is(body#mainbody, body.bodytakepos) .login_block_user .dropdown-menu {
		background: var(--formora-elevated) !important;
		border: 1px solid var(--formora-line) !important;
		border-radius: var(--formora-radius) !important;
		box-shadow: var(--formora-shadow-menu) !important;
		box-sizing: border-box;
		color: var(--formora-text) !important;
		overflow: hidden;
	}

	:is(body#mainbody, body.bodytakepos) .login_block_user .dropdown-menu :is(.user-header, .user-body, .user-footer) {
		background: var(--formora-elevated) !important;
		color: var(--formora-text) !important;
	}

	:is(body#mainbody, body.bodytakepos) .login_block_user .dropdown-menu .user-header {
		border-bottom: 1px solid var(--formora-line-soft) !important;
		border-radius: calc(var(--formora-radius) - 1px) calc(var(--formora-radius) - 1px) 0 0 !important;
	}

	:is(body#mainbody, body.bodytakepos) .login_block_user .dropdown-menu .user-body {
		border-radius: 0 !important;
	}

	:is(body#mainbody, body.bodytakepos) .login_block_user .dropdown-menu .user-body :is(a, span, div, p, td, th, strong, b) {
		color: inherit !important;
	}

	:is(body#mainbody, body.bodytakepos) .login_block_user :is(#topmenulogincompanyinfo-btn, #topmenuloginmoreinfo-btn) {
		align-items: center;
		color: var(--formora-text) !important;
		cursor: pointer;
		display: flex;
		gap: 8px;
		line-height: 1.35;
		min-height: 30px;
		padding: 3px 0;
		width: 100%;
	}

	:is(body#mainbody, body.bodytakepos) .login_block_user #topmenulogincompanyinfo + br {
		display: none;
	}

	:is(body#mainbody, body.bodytakepos) .login_block_user #topmenuloginmoreinfo-btn {
		margin-top: 2px;
	}

	:is(body#mainbody, body.bodytakepos) .login_block_user :is(#topmenulogincompanyinfo, #topmenuloginmoreinfo) {
		line-height: 1.5;
		margin: 4px 0 8px 24px;
	}

	:is(body#mainbody, body.bodytakepos) .login_block_user :is(#topmenulogincompanyinfo-btn, #topmenuloginmoreinfo-btn) > :is(.fa-caret-right, .fa-caret-down) {
		color: var(--formora-icon-muted) !important;
		flex: 0 0 auto;
		font-size: 0.86em;
		height: 1em;
		line-height: 1;
		width: 1em;
	}

	:is(body#mainbody, body.bodytakepos) .login_block_user :is(#topmenulogincompanyinfo-btn, #topmenuloginmoreinfo-btn) > :is(.fa-caret-right, .fa-caret-down)::before {
		background-color: currentColor !important;
		color: inherit !important;
		content: "" !important;
		display: inline-block;
		height: 1em;
		vertical-align: -0.12em;
		width: 1em;
	}

	:is(body#mainbody, body.bodytakepos) .login_block_user :is(#topmenulogincompanyinfo-btn, #topmenuloginmoreinfo-btn) > .fa-caret-right::before {
		-webkit-mask: var(--formora-symbol-chevron-right) center / contain no-repeat;
		mask: var(--formora-symbol-chevron-right) center / contain no-repeat;
	}

	:is(body#mainbody, body.bodytakepos) .login_block_user :is(#topmenulogincompanyinfo-btn, #topmenuloginmoreinfo-btn) > .fa-caret-down::before {
		-webkit-mask: var(--formora-symbol-sort-down) center / contain no-repeat;
		mask: var(--formora-symbol-sort-down) center / contain no-repeat;
	}

	:is(body#mainbody, body.bodytakepos) .login_block_user :is(#topmenulogincompanyinfo-btn, #topmenuloginmoreinfo-btn):hover {
		color: var(--formora-accent-hover) !important;
	}

	:is(body#mainbody, body.bodytakepos) .login_block_user :is(#topmenulogincompanyinfo-btn, #topmenuloginmoreinfo-btn):hover > :is(.fa-caret-right, .fa-caret-down) {
		color: var(--formora-accent) !important;
	}

	:is(body#mainbody, body.bodytakepos) .login_block_user .dropdown-menu .user-footer {
		background: var(--formora-surface-alt) !important;
		border-top: 1px solid var(--formora-line-soft) !important;
		border-radius: 0 0 calc(var(--formora-radius) - 1px) calc(var(--formora-radius) - 1px) !important;
	}

	:is(body#mainbody, body.bodytakepos) .login_block_user .button-top-menu-dropdown {
		align-items: center !important;
		background: var(--formora-surface) !important;
		border: 1px solid var(--formora-line-soft) !important;
		border-radius: var(--formora-radius-sm) !important;
		box-shadow: none !important;
		box-sizing: border-box !important;
		color: var(--formora-accent) !important;
		display: inline-flex !important;
		font-weight: 600 !important;
		gap: 7px;
		justify-content: center !important;
		min-height: 34px;
		padding: 7px 12px !important;
	}

	:is(body#mainbody, body.bodytakepos) .login_block_user .button-top-menu-dropdown:hover,
	:is(body#mainbody, body.bodytakepos) .login_block_user .button-top-menu-dropdown:focus {
		background: var(--formora-accent-soft) !important;
		border-color: var(--formora-accent) !important;
		color: var(--formora-accent-hover) !important;
		outline: 0 !important;
	}

	:is(body#mainbody, body.bodytakepos) .login_block_user .button-top-menu-dropdown :is(.fa, .fas, .far, .fab) {
		background: transparent !important;
		color: inherit !important;
	}

	:is(body#mainbody, body.bodytakepos) .login_block_user .button-top-menu-dropdown :is(.fa, .fas, .far, .fab)::before {
		color: inherit !important;
	}

	body#mainbody .login_block_user .button-top-menu-dropdown :is(.fa-user, .fa-address-card)::before,
	body.bodytakepos .login_block_user .button-top-menu-dropdown :is(.fa-user, .fa-address-card)::before {
		background-color: currentColor !important;
	}

	body.bodytakepos .login_block_user .button-top-menu-dropdown :is(.fa-user, .fa-address-card)::before {
		content: "" !important;
		display: inline-block !important;
		height: 14px !important;
		vertical-align: -0.12em;
		width: 14px !important;
	}

	body.bodytakepos .login_block_user .button-top-menu-dropdown .fa-user::before {
		-webkit-mask: var(--formora-symbol-user) center / contain no-repeat;
		mask: var(--formora-symbol-user) center / contain no-repeat;
	}

	body.bodytakepos .login_block_user .button-top-menu-dropdown .fa-address-card::before {
		-webkit-mask: var(--formora-symbol-address-book) center / contain no-repeat;
		mask: var(--formora-symbol-address-book) center / contain no-repeat;
	}

	:is(body#mainbody, body.bodytakepos) .login_block_user .dropdown-user-image {
		background: var(--formora-surface-alt) !important;
		border: 1px solid var(--formora-line) !important;
		box-shadow: none !important;
	}

	body.bodytakepos .topnav input[type="text"],
	body:not(#mainbody) input.takepospay,
	body:not(#mainbody) textarea.takepospay,
	body:not(#mainbody) select.takepospay {
		background: var(--formora-surface-alt) !important;
		border: 1px solid var(--formora-line) !important;
		border-radius: var(--formora-radius-sm) !important;
		color: var(--formora-text) !important;
	}

	body.bodytakepos .topnav input[type="text"] {
		box-sizing: border-box !important;
		float: none !important;
		font-size: 1.3em !important;
		height: 44px !important;
		line-height: 44px !important;
		margin: 0 10px 0 6px !important;
		max-width: 360px !important;
		min-width: 240px;
		padding: 0 14px !important;
	}

	body.bodytakepos .topnav input[type="text"]:focus,
	body:not(#mainbody) input.takepospay:focus,
	body:not(#mainbody) textarea.takepospay:focus,
	body:not(#mainbody) select.takepospay:focus {
		border-color: var(--formora-accent) !important;
		box-shadow: 0 0 0 3px var(--formora-focus-ring) !important;
		outline: 0 !important;
	}

	body.bodytakepos div.paymentbordline,
	body:not(#mainbody) div.paymentbordline {
		background: var(--formora-elevated) !important;
		border: 1px solid var(--formora-line-soft) !important;
		color: var(--formora-text) !important;
	}

	body:not(#mainbody):has(.takepospay) div.paymentbordline.center {
		background: var(--formora-elevated) !important;
	}

	body.bodytakepos .colorwhite,
	body:not(#mainbody) .colorwhite {
		color: var(--formora-text) !important;
	}

	body.bodytakepos .colorred,
	body:not(#mainbody) .colorred {
		color: var(--formora-danger) !important;
	}

	body.bodytakepos .colorgreen,
	body.bodytakepos .order td,
	body:not(#mainbody) .colorgreen,
	body:not(#mainbody) .order td {
		color: var(--formora-status-success-bg) !important;
	}

	body.bodytakepos :is(div.wrapper, div.wrapper2, .tab-category),
	body.takepossplitphp .headercontent {
		background: var(--formora-surface) !important;
		border-color: var(--formora-line-soft) !important;
		color: var(--formora-text) !important;
	}

	body.bodytakepos :is(div.wrapper, div.wrapper2, .tab-category):hover {
		background: var(--formora-elevated) !important;
		border-color: var(--formora-line) !important;
	}

	body.bodytakepos button,
	body.bodytakepos input[type="button"],
	body.bodytakepos input[type="submit"],
	body:not(#mainbody) button.calcbutton,
	body:not(#mainbody) button.calcbutton2,
	body:not(#mainbody) button.calcbutton3,
	body:not(#mainbody) input.button.takepospay,
	body:not(#mainbody) button.item_value {
		border: 1px solid var(--formora-line-soft) !important;
		box-shadow: none !important;
		transition: background-color 0.14s ease, border-color 0.14s ease, color 0.14s ease, transform 0.08s ease !important;
	}

	body.bodytakepos button :is(.fa, .fas, .far, .fab),
	body.bodytakepos button :is(.fa, .fas, .far, .fab)::before,
	body:not(#mainbody) button:is(.calcbutton, .calcbutton2, .calcbutton3, .actionbutton, .productbutton, .item_value) :is(.fa, .fas, .far, .fab),
	body:not(#mainbody) button:is(.calcbutton, .calcbutton2, .calcbutton3, .actionbutton, .productbutton, .item_value) :is(.fa, .fas, .far, .fab)::before {
		background: transparent !important;
		color: inherit !important;
	}

	body.bodytakepos button.calcbutton,
	body.bodytakepos button.calcbutton3,
	body.bodytakepos button.actionbutton,
	body.bodytakepos button.productbutton,
	body.bodytakepos button.item_value,
	body:not(#mainbody) button.calcbutton,
	body:not(#mainbody) button.calcbutton3,
	body:not(#mainbody) button.actionbutton,
	body:not(#mainbody) button.productbutton,
	body:not(#mainbody) button.item_value {
		background: var(--formora-surface) !important;
		border-color: var(--formora-line-soft) !important;
		color: var(--formora-text) !important;
	}

	body.bodytakepos button.calcbutton2,
	body:not(#mainbody) button.calcbutton2 {
		background: var(--formora-accent) !important;
		border-color: var(--formora-accent) !important;
		color: #ffffff !important;
	}

	body.bodytakepos button.calcbutton2.clicked,
	body:not(#mainbody) button.calcbutton2.clicked {
		background: var(--formora-accent-hover) !important;
		border-color: var(--formora-accent-hover) !important;
		color: #ffffff !important;
	}

	body.bodytakepos button.calcbutton.poscolorblue,
	body.bodytakepos button.calcbutton3.poscolorblue,
	body:not(#mainbody) button.calcbutton.poscolorblue,
	body:not(#mainbody) button.calcbutton3.poscolorblue {
		background: var(--formora-accent) !important;
		border-color: var(--formora-accent) !important;
		color: #ffffff !important;
	}

	body.bodytakepos button.calcbutton2.poscolordelete,
	body.bodytakepos button.calcbutton3.poscolordelete,
	body:not(#mainbody) button.calcbutton2.poscolordelete,
	body:not(#mainbody) button.calcbutton3.poscolordelete {
		background: var(--formora-danger-bg) !important;
		border-color: rgba(215, 0, 21, 0.28) !important;
		color: var(--formora-danger) !important;
	}

	body.bodytakepos button:is(.calcbutton, .calcbutton2, .calcbutton3, .actionbutton, .productbutton, .block):active,
	body.bodytakepos input:is([type="button"], [type="submit"]).takepospay:active,
	body:not(#mainbody) button:is(.calcbutton, .calcbutton2, .calcbutton3, .actionbutton, .productbutton, .item_value):active,
	body:not(#mainbody) input.button.takepospay:active {
		background: var(--formora-accent-soft) !important;
		border-color: var(--formora-accent) !important;
		color: var(--formora-accent-hover) !important;
		transform: translateY(1px);
	}

	body.bodytakepos button:is(.calcbutton, .calcbutton3, .actionbutton, .productbutton, .block):hover,
	body.bodytakepos input:is([type="button"], [type="submit"]).takepospay:hover,
	body:not(#mainbody) button:is(.calcbutton, .calcbutton3, .actionbutton, .productbutton, .item_value):hover,
	body:not(#mainbody) input.button.takepospay:hover {
		background: var(--formora-elevated) !important;
		border-color: var(--formora-line) !important;
		color: var(--formora-text) !important;
	}

	body.bodytakepos button.calcbutton2:hover,
	body:not(#mainbody) button.calcbutton2:hover,
	body.bodytakepos button.calcbutton.poscolorblue:hover,
	body.bodytakepos button.calcbutton3.poscolorblue:hover,
	body:not(#mainbody) button.calcbutton.poscolorblue:hover,
	body:not(#mainbody) button.calcbutton3.poscolorblue:hover,
	body.bodytakepos button.calcbutton.poscolorblue:active,
	body.bodytakepos button.calcbutton3.poscolorblue:active,
	body:not(#mainbody) button.calcbutton.poscolorblue:active,
	body:not(#mainbody) button.calcbutton3.poscolorblue:active {
		background: var(--formora-accent-hover) !important;
		border-color: var(--formora-accent-hover) !important;
		color: #ffffff !important;
	}

	body.bodytakepos button.calcbutton2.poscolordelete:hover,
	body.bodytakepos button.calcbutton3.poscolordelete:hover,
	body:not(#mainbody) button.calcbutton2.poscolordelete:hover,
	body:not(#mainbody) button.calcbutton3.poscolordelete:hover,
	body.bodytakepos button.calcbutton2.poscolordelete:active,
	body.bodytakepos button.calcbutton3.poscolordelete:active,
	body:not(#mainbody) button.calcbutton2.poscolordelete:active,
	body:not(#mainbody) button.calcbutton3.poscolordelete:active {
		background: var(--formora-danger-hover-bg) !important;
		border-color: var(--formora-danger) !important;
		color: var(--formora-danger) !important;
	}

	body.bodytakepos button.item_value.selected,
	body:not(#mainbody) button.item_value.selected {
		background: var(--formora-accent-soft) !important;
		border-color: var(--formora-accent) !important;
		color: var(--formora-accent-hover) !important;
		font-weight: 700;
	}

	<?php
	if (getDolGlobalInt('THEME_DARKMODEENABLED')) {
		if (getDolGlobalInt('THEME_DARKMODEENABLED') != 2) {
			print "@media (prefers-color-scheme: dark) {";
		} else {
			print "@media not print {";
		}
	?>
	/* TakePOS loads its own stylesheet after the theme, so these rules keep the POS surface in the dark palette. */
	body.bodytakepos,
	body.takepossplitphp,
	body:not(#mainbody):has(.takepospay),
	body:not(#mainbody):has(.calcbutton),
	body:not(#mainbody):has(.postablelines) {
		background: var(--formora-bg) !important;
		color: var(--formora-text) !important;
	}

	body.bodytakepos .container,
	body.bodytakepos .row1,
	body.bodytakepos .row1withhead,
	body.bodytakepos .row2,
	body.bodytakepos .row2withhead,
	body.bodytakepos :is(.div1, .div2, .div3, .div4, .div5),
	body.takepossplitphp :is(.splitsale, .rowsplit, .headersplit),
	body:not(#mainbody):has(.takepospay) :is(.center, .div-table-responsive-no-min) {
		background: var(--formora-bg) !important;
		color: var(--formora-text) !important;
	}

	body.bodytakepos .header,
	body.bodytakepos .topnav {
		background: var(--formora-surface) !important;
		border-bottom: 1px solid var(--formora-line-soft) !important;
	}

	body.bodytakepos .topnav {
		align-items: center !important;
		box-sizing: border-box !important;
		display: flex !important;
		flex-wrap: nowrap !important;
		height: 64px !important;
		min-height: 64px;
		overflow-x: auto !important;
		overflow-y: hidden !important;
		white-space: nowrap;
	}

	body.bodytakepos .topnav-left,
	body.bodytakepos .topnav-right {
		align-items: center !important;
		display: flex !important;
		flex-wrap: nowrap !important;
		float: none !important;
		height: 64px;
		min-width: 0;
	}

	body.bodytakepos .topnav-left {
		flex: 1 1 auto;
		overflow: hidden;
	}

	body.bodytakepos .topnav-right {
		flex: 0 0 auto;
		margin-left: auto;
	}

	body.bodytakepos .topnav .login_block_other.takepos {
		align-items: center !important;
		display: flex !important;
		gap: 10px;
		height: 64px;
		margin-top: 0 !important;
	}

	body.bodytakepos .topnav-left > .inline-block {
		align-items: center !important;
		display: flex !important;
		flex: 0 0 auto;
		flex-wrap: nowrap !important;
		gap: 10px;
		height: 64px;
		white-space: nowrap;
	}

	body.bodytakepos .topnav-left a,
	body.bodytakepos .topnav-right a {
		align-items: center !important;
		display: flex !important;
		flex: 0 0 auto;
		float: none !important;
		justify-content: center !important;
	}

	body.bodytakepos .topnav-left a span {
		align-items: center;
		display: inline-flex;
		white-space: nowrap;
	}

	body.bodytakepos .topnav a,
	body.bodytakepos .topnav :is(.fa, .fas, .far, .fab)::before {
		color: var(--formora-text) !important;
	}

	body.bodytakepos .topnav-left a,
	body.bodytakepos .topnav .login_block_other a {
		border: 1px solid transparent !important;
		border-radius: var(--formora-radius-sm) !important;
	}

	body.bodytakepos .topnav-left a:hover:not(.nohover),
	body.bodytakepos .topnav .login_block_other a:hover:not(.nohover),
	body.bodytakepos .topnav-left a:focus,
	body.bodytakepos .topnav .login_block_other a:focus {
		background: var(--formora-neutral-hover-bg) !important;
		border-color: var(--formora-line-soft) !important;
		color: var(--formora-text) !important;
	}

	body.bodytakepos .topnav-left a:active,
	body.bodytakepos .topnav .login_block_other a:active {
		background: var(--formora-accent-soft) !important;
		border-color: var(--formora-accent) !important;
		color: var(--formora-accent-hover) !important;
	}

	body.bodytakepos .topnav input[type="text"],
	body:not(#mainbody) input.takepospay,
	body:not(#mainbody) textarea.takepospay,
	body:not(#mainbody) select.takepospay {
		background: var(--formora-surface-alt) !important;
		border: 1px solid var(--formora-line) !important;
		border-radius: var(--formora-radius-sm) !important;
		color: var(--formora-text) !important;
	}

	body.bodytakepos .topnav input[type="text"] {
		box-sizing: border-box !important;
		float: none !important;
		font-size: 1.3em !important;
		height: 44px !important;
		line-height: 44px !important;
		margin: 0 10px 0 6px !important;
		max-width: 360px !important;
		min-width: 240px;
		padding: 0 14px !important;
	}

	body.bodytakepos .topnav input[type="text"]:focus,
	body:not(#mainbody) input.takepospay:focus,
	body:not(#mainbody) textarea.takepospay:focus,
	body:not(#mainbody) select.takepospay:focus {
		border-color: var(--formora-accent) !important;
		box-shadow: 0 0 0 3px var(--formora-focus-ring) !important;
		outline: 0 !important;
	}

	body.bodytakepos :is(div.wrapper, div.wrapper2, .tab-category),
	body.takepossplitphp .headercontent {
		background: var(--formora-surface) !important;
		border-color: var(--formora-line-soft) !important;
		color: var(--formora-text) !important;
	}

	body.bodytakepos :is(div.wrapper, div.wrapper2, .tab-category):hover {
		background: var(--formora-elevated) !important;
		border-color: #4c5057 !important;
	}

	body.bodytakepos .div4 .tab-category.active {
		background: var(--formora-accent-soft) !important;
		border-color: var(--formora-accent) !important;
		color: var(--formora-accent-hover) !important;
	}

	body.bodytakepos div.description {
		background: linear-gradient(to bottom, rgba(28, 28, 30, 0), rgba(28, 28, 30, 0.78), rgba(28, 28, 30, 0.98)) !important;
		color: var(--formora-text) !important;
	}

	body.bodytakepos .catwatermark {
		background: var(--formora-elevated) !important;
		color: var(--formora-text) !important;
		opacity: 0.72 !important;
	}

	body.bodytakepos .productprice {
		background: var(--formora-accent-soft) !important;
		color: var(--formora-accent-hover) !important;
	}

	body.bodytakepos table.postablelines,
	body:not(#mainbody) table.postablelines {
		background: var(--formora-surface) !important;
		color: var(--formora-text) !important;
	}

	body.bodytakepos table.postablelines :is(th, td),
	body:not(#mainbody) table.postablelines :is(th, td) {
		border-color: var(--formora-line-soft) !important;
		color: var(--formora-text) !important;
	}

	body.bodytakepos .posinvoiceline td,
	body:not(#mainbody) .posinvoiceline td {
		background: var(--formora-elevated) !important;
	}

	body.bodytakepos tr.selected,
	body.bodytakepos tr.selected td,
	body:not(#mainbody) tr.selected,
	body:not(#mainbody) tr.selected td {
		background: var(--formora-accent-soft) !important;
		color: var(--formora-text) !important;
	}

	body.bodytakepos button,
	body.bodytakepos input[type="button"],
	body.bodytakepos input[type="submit"],
	body:not(#mainbody) button.calcbutton,
	body:not(#mainbody) button.calcbutton2,
	body:not(#mainbody) button.calcbutton3,
	body:not(#mainbody) input.button.takepospay,
	body:not(#mainbody) button.item_value {
		border: 1px solid var(--formora-line-soft) !important;
		box-shadow: none !important;
		transition: background-color 0.14s ease, border-color 0.14s ease, color 0.14s ease, transform 0.08s ease !important;
	}

	body.bodytakepos button :is(.fa, .fas, .far, .fab),
	body.bodytakepos button :is(.fa, .fas, .far, .fab)::before,
	body:not(#mainbody) button:is(.calcbutton, .calcbutton2, .calcbutton3, .actionbutton, .productbutton, .item_value) :is(.fa, .fas, .far, .fab),
	body:not(#mainbody) button:is(.calcbutton, .calcbutton2, .calcbutton3, .actionbutton, .productbutton, .item_value) :is(.fa, .fas, .far, .fab)::before {
		background: transparent !important;
		color: inherit !important;
	}

	body.bodytakepos button.calcbutton,
	body.bodytakepos button.calcbutton3,
	body:not(#mainbody) button.calcbutton,
	body:not(#mainbody) button.calcbutton3 {
		background: var(--formora-elevated) !important;
		color: var(--formora-text) !important;
	}

	body.bodytakepos button.calcbutton2,
	body:not(#mainbody) button.calcbutton2 {
		background: #17314f !important;
		border-color: rgba(78, 163, 255, 0.32) !important;
		color: #f5f5f7 !important;
	}

	body.bodytakepos button.calcbutton2.clicked,
	body:not(#mainbody) button.calcbutton2.clicked {
		background: var(--formora-accent) !important;
		border-color: var(--formora-accent-hover) !important;
		color: #ffffff !important;
	}

	body.bodytakepos button.actionbutton,
	body:not(#mainbody) button.actionbutton {
		background: #2f2f33 !important;
		border-color: var(--formora-line-soft) !important;
		color: var(--formora-text) !important;
	}

	body.bodytakepos button.productbutton,
	body:not(#mainbody) button.productbutton {
		background: #2a2b2f !important;
		border-color: var(--formora-line-soft) !important;
		color: var(--formora-text) !important;
	}

	body.bodytakepos button.calcbutton.poscolorblue,
	body.bodytakepos button.calcbutton3.poscolorblue,
	body:not(#mainbody) button.calcbutton.poscolorblue,
	body:not(#mainbody) button.calcbutton3.poscolorblue {
		background: var(--formora-accent) !important;
		border-color: var(--formora-accent-hover) !important;
		color: #ffffff !important;
	}

	body.bodytakepos button.calcbutton2.poscolordelete,
	body.bodytakepos button.calcbutton3.poscolordelete,
	body:not(#mainbody) button.calcbutton2.poscolordelete,
	body:not(#mainbody) button.calcbutton3.poscolordelete {
		background: rgba(255, 69, 58, 0.18) !important;
		border-color: rgba(255, 69, 58, 0.36) !important;
		color: var(--formora-danger) !important;
	}

	body.bodytakepos button:is(.calcbutton, .calcbutton2, .calcbutton3, .actionbutton, .productbutton, .block):active,
	body.bodytakepos input:is([type="button"], [type="submit"]).takepospay:active,
	body:not(#mainbody) button:is(.calcbutton, .calcbutton2, .calcbutton3, .actionbutton, .productbutton, .item_value):active,
	body:not(#mainbody) input.button.takepospay:active {
		background: var(--formora-accent-soft) !important;
		border-color: var(--formora-accent-hover) !important;
		color: var(--formora-accent-hover) !important;
		transform: translateY(1px);
	}

	body.bodytakepos button.calcbutton:hover,
	body.bodytakepos button.calcbutton3:hover,
	body:not(#mainbody) button.calcbutton:hover,
	body:not(#mainbody) button.calcbutton3:hover {
		background: #303034 !important;
		border-color: #4a4a4f !important;
		color: var(--formora-text) !important;
	}

	body.bodytakepos button.calcbutton2:hover,
	body:not(#mainbody) button.calcbutton2:hover {
		background: #204a78 !important;
		border-color: rgba(78, 163, 255, 0.58) !important;
		color: #ffffff !important;
	}

	body.bodytakepos button.actionbutton:hover,
	body:not(#mainbody) button.actionbutton:hover {
		background: #34363a !important;
		border-color: #4c5057 !important;
		color: var(--formora-text) !important;
	}

	body.bodytakepos button.productbutton:hover,
	body:not(#mainbody) button.productbutton:hover {
		background: #34363a !important;
		border-color: #4c5057 !important;
		color: var(--formora-text) !important;
	}

	body.bodytakepos button.block:hover,
	body.bodytakepos input:is([type="button"], [type="submit"]).takepospay:hover,
	body:not(#mainbody) button.item_value:hover,
	body:not(#mainbody) input.button.takepospay:hover {
		background: #303034 !important;
		border-color: #4a4a4f !important;
		color: var(--formora-text) !important;
	}

	body.bodytakepos button.calcbutton.poscolorblue:hover,
	body.bodytakepos button.calcbutton3.poscolorblue:hover,
	body:not(#mainbody) button.calcbutton.poscolorblue:hover,
	body:not(#mainbody) button.calcbutton3.poscolorblue:hover,
	body.bodytakepos button.calcbutton.poscolorblue:active,
	body.bodytakepos button.calcbutton3.poscolorblue:active,
	body:not(#mainbody) button.calcbutton.poscolorblue:active,
	body:not(#mainbody) button.calcbutton3.poscolorblue:active {
		background: #0b8dff !important;
		border-color: #64b5ff !important;
		color: #ffffff !important;
	}

	body.bodytakepos button.calcbutton2.poscolordelete:hover,
	body.bodytakepos button.calcbutton3.poscolordelete:hover,
	body:not(#mainbody) button.calcbutton2.poscolordelete:hover,
	body:not(#mainbody) button.calcbutton3.poscolordelete:hover,
	body.bodytakepos button.calcbutton2.poscolordelete:active,
	body.bodytakepos button.calcbutton3.poscolordelete:active,
	body:not(#mainbody) button.calcbutton2.poscolordelete:active,
	body:not(#mainbody) button.calcbutton3.poscolordelete:active {
		background: rgba(255, 69, 58, 0.26) !important;
		border-color: rgba(255, 69, 58, 0.52) !important;
		color: #ff8a82 !important;
	}

	body.bodytakepos button.item_value,
	body:not(#mainbody) button.item_value {
		background: var(--formora-elevated) !important;
		color: var(--formora-text) !important;
	}

	body.bodytakepos button.item_value.selected,
	body:not(#mainbody) button.item_value.selected {
		background: var(--formora-accent-soft) !important;
		border-color: var(--formora-accent) !important;
		color: var(--formora-accent-hover) !important;
		font-weight: 700;
	}

	body.bodytakepos div.paymentbordline,
	body:not(#mainbody) div.paymentbordline {
		background: var(--formora-elevated) !important;
		border: 1px solid var(--formora-line-soft) !important;
		color: var(--formora-text) !important;
	}

	body:not(#mainbody):has(.takepospay) div.paymentbordline.center {
		background: var(--formora-elevated) !important;
	}

	body.bodytakepos .colorwhite,
	body:not(#mainbody) .colorwhite {
		color: var(--formora-text) !important;
	}

	body.bodytakepos .colorred,
	body:not(#mainbody) .colorred {
		color: var(--formora-danger) !important;
	}

	body.bodytakepos .colorgreen,
	body.bodytakepos .order td,
	body:not(#mainbody) .colorgreen,
	body:not(#mainbody) .order td {
		color: var(--formora-status-success-text) !important;
	}

	body.bodytakepos .modal {
		background: rgba(0, 0, 0, 0.68) !important;
	}

	body.bodytakepos .modal-content {
		background: var(--formora-surface) !important;
		border: 1px solid var(--formora-line) !important;
		border-radius: var(--formora-radius) !important;
		box-shadow: 0 24px 80px rgba(0, 0, 0, 0.45) !important;
		color: var(--formora-text) !important;
		overflow: hidden;
	}

	body.bodytakepos .modal-header {
		background: var(--formora-elevated) !important;
		border-bottom: 1px solid var(--formora-line-soft) !important;
		color: var(--formora-text) !important;
	}

	body.bodytakepos .modal-body {
		background: var(--formora-surface) !important;
		color: var(--formora-text) !important;
	}

	body.bodytakepos .close {
		color: var(--formora-muted) !important;
	}

	body.bodytakepos .close:hover,
	body.bodytakepos .close:focus {
		color: var(--formora-text) !important;
	}

	body.bodytakepos .block,
	body:not(#mainbody) .block {
		background: var(--formora-elevated) !important;
		border: 1px solid var(--formora-line-soft) !important;
		color: var(--formora-text) !important;
	}

	body.bodytakepos #cboxOverlay {
		background: #000000 !important;
		opacity: 0.78 !important;
	}

	body.bodytakepos #colorbox {
		outline: 0 !important;
	}

	body.bodytakepos :is(#cboxTopLeft, #cboxTopCenter, #cboxTopRight, #cboxMiddleLeft, #cboxMiddleRight, #cboxBottomLeft, #cboxBottomCenter, #cboxBottomRight) {
		background: var(--formora-surface) !important;
	}

	body.bodytakepos :is(#cboxContent, #cboxLoadedContent, .cboxIframe) {
		background: var(--formora-bg) !important;
		color: var(--formora-text) !important;
	}

	body.bodytakepos #cboxContent {
		border: 1px solid var(--formora-line) !important;
		border-radius: var(--formora-radius) !important;
		box-shadow: 0 24px 80px rgba(0, 0, 0, 0.5) !important;
		overflow: hidden !important;
	}

	body.bodytakepos #cboxTitle,
	body.bodytakepos #cboxCurrent {
		color: var(--formora-text) !important;
	}

	body.bodytakepos #cboxError {
		background: var(--formora-surface) !important;
		border-color: var(--formora-line) !important;
		color: var(--formora-text) !important;
	}

	body.bodytakepos :is(#cboxPrevious, #cboxNext, #cboxSlideshow, #cboxClose):active {
		background-color: transparent !important;
		color: inherit !important;
	}
	<?php
		print "}\n";
	}
	?>

		@media only screen and (max-width: 1180px) {
			body#mainbody:not(.bodytakepos):not(.bodylogin) #id-top {
				box-sizing: border-box !important;
				height: 60px !important;
				min-height: 60px !important;
				overflow: visible !important;
				position: relative !important;
			}

			body#mainbody:not(.bodytakepos):not(.bodylogin) #tmenu_tooltip {
				box-sizing: border-box !important;
				height: 60px !important;
				min-height: 60px !important;
				max-width: calc(100% - 116px) !important;
				overflow-x: auto !important;
				overflow-y: hidden !important;
				padding-right: 0 !important;
				scrollbar-width: none;
				white-space: nowrap !important;
				width: calc(100% - 116px) !important;
			}

			body#mainbody:not(.bodytakepos):not(.bodylogin) #tmenu_tooltip::-webkit-scrollbar {
				display: none;
			}

			body#mainbody:not(.bodytakepos):not(.bodylogin) #id-top :is(.tmenudiv, ul.tmenu) {
				align-items: stretch !important;
				display: flex !important;
				flex-wrap: nowrap !important;
				height: 60px !important;
				margin: 0 !important;
				min-width: max-content !important;
				overflow: visible !important;
				padding: 0 !important;
				width: max-content !important;
			}

			body#mainbody:not(.bodytakepos):not(.bodylogin) #id-top :is(li.tmenu, li.tmenusel) {
				display: block !important;
				flex: 0 0 48px !important;
				float: none !important;
				height: 60px !important;
				margin-left: 4px !important;
				min-width: 48px !important;
				width: 48px !important;
			}

			body#mainbody:not(.bodytakepos):not(.bodylogin) #id-top :is(li.tmenu, li.tmenusel) div.tmenucenter {
				flex-direction: row !important;
				height: 54px !important;
				min-height: 54px !important;
				min-width: 44px !important;
				overflow: hidden !important;
				padding: 3px 0 !important;
				width: 44px !important;
			}

			body#mainbody:not(.bodytakepos):not(.bodylogin) #id-top :is(li.tmenu, li.tmenusel) div.tmenucenter > a.tmenuimage {
				height: 44px !important;
				min-height: 44px !important;
				width: 44px !important;
			}

			body#mainbody:not(.bodytakepos):not(.bodylogin) #id-top :is(li.tmenu, li.tmenusel) div.tmenucenter > a.tmenulabel {
				display: none !important;
			}

			body#mainbody:not(.bodytakepos):not(.bodylogin) .login_block.usedropdown {
				background: var(--formora-surface) !important;
				border-left: 1px solid var(--formora-line-soft) !important;
				box-sizing: border-box !important;
				height: 60px !important;
				left: auto !important;
				max-width: 116px !important;
				min-width: 0 !important;
				padding-left: 4px !important;
				position: absolute !important;
				right: 0 !important;
				top: 0 !important;
				width: auto !important;
				z-index: 30 !important;
			}

			body#mainbody:not(.bodytakepos):not(.bodylogin) .login_block.usedropdown .login_block_other {
				display: none !important;
			}

			body#mainbody:not(.bodytakepos):not(.bodylogin) .login_block.usedropdown > :is(.login_block_tools, .login_block_user) {
				align-items: center !important;
				display: flex !important;
				height: 60px !important;
				min-width: 0 !important;
			}

			body#mainbody:not(.bodytakepos):not(.bodylogin) .login_block.usedropdown #topmenu-quickadd-dropdown,
			body#mainbody:not(.bodytakepos):not(.bodylogin) .login_block.usedropdown #topmenu-quickadd-dropdown > a.dropdown-toggle,
			body#mainbody:not(.bodytakepos):not(.bodylogin) .login_block.usedropdown #topmenu-login-dropdown > a.dropdown-toggle {
				height: 60px !important;
				min-width: 38px !important;
			}

			body#mainbody:not(.bodytakepos):not(.bodylogin) .login_block.usedropdown :is(.atoploginusername, .login_block_elem_name .hidden-xs) {
				display: none !important;
			}

			body#mainbody:not(.bodytakepos):not(.bodylogin) .login_block.usedropdown .login_block_elem_name,
			body#mainbody:not(.bodytakepos):not(.bodylogin) .login_block.usedropdown #topmenu-login-dropdown {
				min-width: 0 !important;
				padding-left: 0 !important;
				padding-right: 0 !important;
			}

			body#mainbody:not(.bodytakepos):not(.bodylogin) :is(#topmenu-quickadd-dropdown, #topmenu-login-dropdown) > .dropdown-menu {
				bottom: auto !important;
				left: auto !important;
				max-height: calc(100vh - 72px) !important;
				max-width: calc(100vw - 12px) !important;
				overflow-x: hidden !important;
				overflow-y: auto !important;
				position: fixed !important;
				right: 6px !important;
				top: 60px !important;
				width: min(285px, calc(100vw - 12px)) !important;
			}

			body#mainbody:not(.bodytakepos):not(.bodylogin) .login_block_user .dropdown-menu .user-header {
				align-items: center !important;
				display: grid !important;
				gap: 10px !important;
				grid-template-columns: 48px minmax(0, 1fr) !important;
				min-height: 82px !important;
				padding: 12px !important;
				text-align: left !important;
			}

			body#mainbody:not(.bodytakepos):not(.bodylogin) .login_block_user .dropdown-menu .user-header > .dropdown-user-image {
				border-radius: 50% !important;
				box-sizing: border-box !important;
				display: block !important;
				grid-column: 1 !important;
				height: 48px !important;
				margin: 0 !important;
				object-fit: cover !important;
				width: 48px !important;
			}

			body#mainbody:not(.bodytakepos):not(.bodylogin) .login_block_user .dropdown-menu .user-header > p {
				grid-column: 2 !important;
				margin: 0 !important;
				min-width: 0 !important;
				overflow-wrap: anywhere !important;
				text-align: left !important;
			}

			body#mainbody:not(.bodytakepos):not(.bodylogin) #id-container {
				min-height: calc(100vh - 60px) !important;
			}

			body#mainbody:not(.bodytakepos):not(.bodylogin) .side-nav {
				box-sizing: border-box !important;
				width: <?php echo $leftmenuwidth + 20; ?>px !important;
				min-height: calc(100vh - 60px) !important;
				padding: 8px 0 0 !important;
				border-bottom: 0 !important;
			}

			body#mainbody:not(.bodytakepos):not(.bodylogin) .side-nav #id-left {
				padding-top: 8px !important;
			}

			body#mainbody:not(.bodytakepos):not(.bodylogin) .side-nav #id-left .blockvmenulast {
				border-bottom: 0 !important;
			}

		}

		@media only screen and (min-width: 768px) and (max-width: 1180px) {
			body#mainbody.bodyforlist:not(.poslist) #id-right .fiche table.table-fiche-title {
				align-items: center !important;
				column-gap: 0 !important;
				display: grid !important;
				grid-template-columns: 44px minmax(0, 1fr) minmax(0, auto) !important;
				row-gap: 8px !important;
			}

			body#mainbody.bodyforlist:not(.poslist) #id-right .fiche table.table-fiche-title > :is(tbody, thead, tfoot),
			body#mainbody.bodyforlist:not(.poslist) #id-right .fiche table.table-fiche-title > :is(tbody, thead, tfoot) > tr {
				display: contents !important;
			}

			body#mainbody.bodyforlist:not(.poslist) #id-right .fiche table.table-fiche-title .col-picto {
				grid-column: 1 !important;
				grid-row: 1 !important;
				width: 42px !important;
			}

			body#mainbody.bodyforlist:not(.poslist) #id-right .fiche table.table-fiche-title .col-title {
				grid-column: 2 !important;
				grid-row: 1 !important;
				min-width: 0 !important;
				overflow-wrap: anywhere !important;
				width: auto !important;
			}

			body#mainbody.bodyforlist:not(.poslist) #id-right .fiche table.table-fiche-title .col-right {
				box-sizing: border-box !important;
				grid-column: 3 !important;
				grid-row: 1 !important;
				justify-self: end !important;
				max-width: 360px !important;
				min-width: 0 !important;
				width: auto !important;
			}

			body#mainbody.bodyforlist:not(.poslist):not(:has(.checkforselect:checked)) #id-right .fiche table.table-fiche-title .col-center:has(#massaction) {
				display: none !important;
				height: 0 !important;
				padding: 0 !important;
				width: 0 !important;
			}

			body#mainbody.bodyforlist:not(.poslist):has(.checkforselect:checked) #id-right .fiche table.table-fiche-title .col-center:has(#massaction) {
				align-items: center !important;
				box-sizing: border-box !important;
				display: flex !important;
				flex-wrap: wrap !important;
				gap: 8px !important;
				grid-column: 1 / -1 !important;
				grid-row: 2 !important;
				justify-content: center !important;
				max-width: 100% !important;
				min-width: 0 !important;
				padding-left: 0 !important;
				padding-right: 0 !important;
				width: 100% !important;
			}

			body#mainbody.bodyforlist:not(.poslist):has(.checkforselect:checked) #id-right .fiche table.table-fiche-title .col-center:has(#massaction) .select2-container:has(#select2-massaction-container) {
				max-width: calc(100% - 108px) !important;
				width: 260px !important;
			}
		}

		@media only screen and (max-width: 767px) {
			body#mainbody.bodyforlist:not(.poslist) #id-right .fiche {
				max-width: none !important;
			}

			body#mainbody.bodyforlist:not(.poslist):has(.checkforselect:checked) #id-right .fiche table.table-fiche-title .col-right {
				display: none !important;
				min-width: 0 !important;
				padding: 0 !important;
				width: 0 !important;
			}

			body#mainbody.bodyforlist:not(.poslist):has(.checkforselect:checked) #id-right .fiche table.table-fiche-title .col-center:has(#massaction) {
				max-width: 184px !important;
				width: 184px !important;
			}

			body#mainbody.bodyforlist:not(.poslist):has(.checkforselect:checked) #id-right .fiche table.table-fiche-title .col-center:has(#massaction) > div {
				max-width: 184px !important;
				min-width: 0 !important;
				width: 184px !important;
			}

			body#mainbody.bodyforlist:not(.poslist):has(.checkforselect:checked) #id-right .fiche table.table-fiche-title .select2-container:has(#select2-massaction-container) {
				max-width: 184px !important;
				width: 184px !important;
			}

			body#mainbody.bodyforlist:not(.poslist) > .select2-container.limit.select2-container--open {
				max-width: 75px !important;
				width: 75px !important;
			}

			body#mainbody.bodyforlist:not(.poslist) > .select2-container.limit.select2-container--open .select2-dropdown {
				box-sizing: border-box !important;
				max-width: 75px !important;
				min-width: 75px !important;
				padding-left: 0 !important;
				padding-right: 0 !important;
				width: 75px !important;
			}

			body#mainbody.bodyforlist:not(.poslist) > .select2-container.limit.select2-container--open .select2-search--dropdown {
				display: none !important;
			}

			body#mainbody.bodyforlist:not(.poslist) > .select2-container.limit.select2-container--open .select2-results__options,
			body#mainbody.bodyforlist:not(.poslist) > .select2-container.limit.select2-container--open .select2-results__option {
				box-sizing: border-box !important;
				max-width: 100% !important;
				width: 100% !important;
			}

			table.table-fiche-title .titre,
			table.table-fiche-title div.titre,
			table.table-fiche-title td.col-title {
				word-break: keep-all;
				overflow-wrap: normal;
			}

			body#mainbody.bodyforlist #id-right .fiche .div-table-responsive {
				-webkit-overflow-scrolling: touch;
				border-bottom: 1px solid var(--formora-line-soft) !important;
				box-sizing: border-box !important;
				max-width: 100% !important;
				overflow-x: auto !important;
				padding-bottom: 6px !important;
				scrollbar-color: var(--formora-line) transparent;
				scrollbar-width: thin;
			}

			body#mainbody.bodyforlist #id-right .fiche .div-table-responsive::-webkit-scrollbar {
				height: 8px;
			}

			body#mainbody.bodyforlist #id-right .fiche .div-table-responsive::-webkit-scrollbar-thumb {
				background: var(--formora-line);
				border: 2px solid transparent;
				border-radius: 999px;
				background-clip: content-box;
			}

			body#mainbody.bodyforlist #id-right .fiche .div-table-responsive > table.liste {
				max-width: none !important;
				min-width: 100% !important;
			}

			body#mainbody:not(.bodyforlist):not(.bodytakepos):not(.bodylogin) #id-right .fiche :is(.boxhalfleft, .boxhalfright, .fichehalfleft, .fichehalfright) > .box.divboxtable.boxdraggable:has(> table.boxtable) {
				-webkit-overflow-scrolling: touch;
				box-sizing: border-box !important;
				max-width: 100% !important;
				overflow-x: auto !important;
				padding-bottom: 6px !important;
				scrollbar-color: var(--formora-line) transparent;
				scrollbar-width: thin;
			}

			body#mainbody:not(.bodyforlist):not(.bodytakepos):not(.bodylogin) #id-right .fiche :is(.boxhalfleft, .boxhalfright, .fichehalfleft, .fichehalfright) > .box.divboxtable.boxdraggable:has(> table.boxtable)::-webkit-scrollbar {
				height: 8px;
			}

			body#mainbody:not(.bodyforlist):not(.bodytakepos):not(.bodylogin) #id-right .fiche :is(.boxhalfleft, .boxhalfright, .fichehalfleft, .fichehalfright) > .box.divboxtable.boxdraggable:has(> table.boxtable)::-webkit-scrollbar-thumb {
				background: var(--formora-line);
				border: 2px solid transparent;
				border-radius: 999px;
				background-clip: content-box;
			}

			body#mainbody:not(.bodyforlist):not(.bodytakepos):not(.bodylogin) #id-right .fiche :is(.boxhalfleft, .boxhalfright, .fichehalfleft, .fichehalfright) > .box.divboxtable.boxdraggable > table.boxtable {
				max-width: none !important;
				min-width: 100% !important;
			}
		}

		@media only screen and (max-width: 480px) {
			body.bodytakepos .topnav {
				gap: 0 !important;
				overflow-x: hidden !important;
				padding: 0 4px !important;
			}

			body.bodytakepos .topnav-left {
				flex: 1 1 auto !important;
				gap: 4px !important;
				min-width: 0 !important;
				overflow: hidden !important;
			}

			body.bodytakepos .topnav-right {
				flex: 0 0 auto !important;
				margin-left: 4px !important;
				min-width: 0 !important;
			}

			body.bodytakepos .topnav-left > .inline-block:has(.topnav-terminalhour),
			body.bodytakepos .topnav-left > :is(#moreinfo, #infowarehouse),
			body.bodytakepos .topnav .login_block_other.takepos > a[href="/"] {
				display: none !important;
			}

			body.bodytakepos .topnav-left > :is(#customerandsales, #shoppingcart),
			body.bodytakepos .topnav .login_block_other.takepos,
			body.bodytakepos .topnav .login_block_user {
				align-items: center !important;
				display: flex !important;
				height: 64px !important;
				min-width: 0 !important;
			}

			body.bodytakepos #customerandsales {
				flex: 0 0 86px !important;
				max-width: 86px !important;
				overflow: hidden !important;
				width: 86px !important;
			}

			body.bodytakepos #customer {
				box-sizing: border-box !important;
				max-width: 86px !important;
				min-width: 0 !important;
				overflow: hidden !important;
				padding-left: 8px !important;
				padding-right: 8px !important;
				text-overflow: ellipsis !important;
				white-space: nowrap !important;
				width: 86px !important;
			}

			body.bodytakepos #shoppingcart {
				flex: 0 0 94px !important;
				gap: 4px !important;
				max-width: 94px !important;
				overflow: hidden !important;
				width: 94px !important;
			}

			body.bodytakepos #customerandsales > a,
			body.bodytakepos #shoppingcart > a {
				box-sizing: border-box !important;
				float: none !important;
				min-width: 0 !important;
				padding-left: 6px !important;
				padding-right: 6px !important;
			}

			body.bodytakepos #shoppingcart > a:first-child {
				max-width: 58px !important;
				overflow: hidden !important;
				text-overflow: ellipsis !important;
				white-space: nowrap !important;
				width: 58px !important;
			}

			body.bodytakepos #shoppingcart > a:last-child {
				width: 32px !important;
			}

			body.bodytakepos .topnav .login_block_other.takepos {
				gap: 4px !important;
			}

			body.bodytakepos .topnav input#search {
				box-sizing: border-box !important;
				min-width: 78px !important;
				width: 78px !important;
			}

			body.bodytakepos .topnav .login_block_other.takepos > a {
				min-width: 34px !important;
				width: 34px !important;
			}

			body.bodytakepos .login_block_user {
				flex: 0 0 48px !important;
				width: 48px !important;
			}

			body.bodytakepos #topmenu-login-dropdown,
			body.bodytakepos #topmenu-login-dropdown > a.dropdown-toggle {
				box-sizing: border-box !important;
				flex: 0 0 48px !important;
				max-width: 48px !important;
				min-width: 48px !important;
				overflow: hidden !important;
				width: 48px !important;
			}

			body.bodytakepos #topmenu-login-dropdown .atoploginusername {
				display: none !important;
			}

			body.bodytakepos #topmenu-login-dropdown > .dropdown-menu {
				bottom: auto !important;
				left: auto !important;
				max-height: calc(100vh - 76px) !important;
				max-width: calc(100vw - 12px) !important;
				overflow-x: hidden !important;
				overflow-y: auto !important;
				position: fixed !important;
				right: 6px !important;
				top: 64px !important;
				width: min(300px, calc(100vw - 12px)) !important;
			}
		}

<?php

if (is_object($db)) {
	$db->close();
}
