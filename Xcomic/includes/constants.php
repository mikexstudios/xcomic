<?php
/**
Xcomic

$Id$
*/

//URL IN
define('MODE_INURL', 'mode'); //how the page will be parsed in url
define('IN_CID', 'cid');

//FILE STRUCTURE & NAMES
define('COMICS_DIR', 'comics');
define('THEMES_DIR', 'xc-themes');
define('PLUGINS_DIR', 'xc-plugins');

//DATABASE
define('XCOMIC_ADMIN_MENU_TABLE', $table_prefix.'admin_menu');
define('XCOMIC_ADMIN_VARS_TABLE', $table_prefix.'admin_vars');
define('XCOMIC_COMICS_TABLE', $table_prefix.'comics');
define('XCOMIC_CONFIG_TABLE', $table_prefix.'config');
define('XCOMIC_NEWS_TABLE', $table_prefix.'news');
define('XCOMIC_PAGES_TABLE', $table_prefix.'pages');
define('XCOMIC_PLUGINS_TABLE', $table_prefix.'plugins');
define('XCOMIC_PLUGIN_VARS_TABLE', $table_prefix.'plugin_vars');
define('XCOMIC_USERS_TABLE', $table_prefix.'users');

//USER AUTH
define('SESSION_USERNAME', 'sessionUsername');
define('SESSION_PASSWORD', 'sessionPassword');
define('COOKIE_USERNAME', 'cookieUsername');
define('COOKIE_PASSWORD', 'cookiePassword');
define('AUTHIN_USERNAME', 'formUsername'); //Form processing
define('AUTHIN_PASSWORD', 'formPassword');

?>