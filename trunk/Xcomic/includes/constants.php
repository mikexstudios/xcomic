<?php
/**
Xcomic

$Id$
*/

if ( !defined('IN_XCOMIC') )
{
	die('Hacking attempt');
}

//URL IN
define('MODE_INURL', 'mode'); //how the page will be parsed in url
define('IN_CID', 'cid');

//FILE STRUCTURE & NAMES
define('TEMPLATES_DIR', 'templates');
define('COMICS_DIR', 'comics');

//DATABASE
define('XCOMIC_CONFIG_TABLE', $xcomicTablePrefix.'config');
define('XCOMIC_NEWS_TABLE', $xcomicTablePrefix.'news');
define('XCOMIC_COMICS_TABLE', $xcomicTablePrefix.'comics');
define('XCOMIC_NEWSCATEGORY_TABLE', $xcomicTablePrefix.'newscategories');
define('XCOMIC_USERS_TABLE', $xcomicTablePrefix.'users');
define('XCOMIC_NEXTCOMICSTATUS_TABLE', $xcomicTablePrefix.'nextcomicstatus');

//USER AUTH
define('SESSION_USERNAME', 'sessionUsername');
define('SESSION_PASSWORD', 'sessionPassword');
define('COOKIE_USERNAME', 'cookieUsername');
define('COOKIE_PASSWORD', 'cookiePassword');
define('AUTHIN_USERNAME', 'formUsername'); //Form processing
define('AUTHIN_PASSWORD', 'formPassword');

//OPTIONS
define('OPT_BASE_URL', 'baseurl');
define('OPT_URL_TO_XCOMIC', 'urltoxcomic');
define('OPT_USING_THEME', 'usingtheme');





?>