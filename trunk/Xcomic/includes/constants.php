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

//NEWS POSTING
define('LEFT_SIDE', 'Left side');
define('RIGHT_SIDE', 'Right side');

//USER AUTH
define('SESSION_USERNAME', 'sessionUsername');
define('SESSION_PASSWORD', 'sessionPassword');
define('COOKIE_USERNAME', 'cookieUsername');
define('COOKIE_PASSWORD', 'cookiePassword');
define('AUTHIN_USERNAME', 'formUsername'); //Form processing
define('AUTHIN_PASSWORD', 'formPassword');

//ADD USER
define('ADDIN_USERNAME', 'addUsername'); //For the form
define('ADDIN_PASSWORD', 'addPassword');
define('ADDIN_EMAIL', 'addEmail');

//EDIT AND DELETE USER
define('GETIN_USERNAME', 'username'); //For the GET URL

//NEXT COMIC STATUS
define('NEXT_COMIC_MONTH', 'nextcomicmonth');
define('NEXT_COMIC_DAY', 'nextcomicday');
define('NEXT_COMIC_YEAR', 'nextcomicyear');
define('NEXT_COMIC_HOUR', 'nextcomichour');
define('NEXT_COMIC_MINUTES', 'nextcomicminutes');
define('NEXT_COMIC_PERCENT', 'nextcomicpercent');
define('NEXT_COMIC_COMMENT', 'nextcomiccomment');

//OPTIONS
define('OPT_BASE_URL', 'baseurl');
define('OPT_URL_TO_XCOMIC', 'urltoxcomic');
define('OPT_USING_THEME', 'usingtheme');





?>