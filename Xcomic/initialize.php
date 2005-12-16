<?php
/**
Xcomic - Comic Management Script

//Initialize - creates global variables used throughout the script

$Id$
*/

//Calculating the time needed to execute this script
$xcomicStartTime = strtok(microtime(), ' ') + strtok(' ');

//Add local PEAR packages to include path
//ini_set("include_path", $xcomicRootPath.'includes' . PATH_SEPARATOR . ini_get("include_path"));

//NOTE: $xcomicRootPath is defined in the file that includes initialize

//Database---------------------------------------
     //Include database configuration information
	include_once $xcomicRootPath.'includes/config.php';

    if (!defined('USE_XCOMIC_PEAR') || !constant('USE_XCOMIC_PEAR'))
    {
	   @include 'DB.php'; // PEAR library. Note include, NOT require, and lack of _once.
	}
	if (!class_exists('DB'))
	{
        // No PEAR library. Use our own
        //@ini_set("include_path", $xcomicRootPath.'includes' . PATH_SEPARATOR . ini_get("include_path"));
        require $xcomicRootPath.'includes/DB.php';
    }

	//Create database object
     $dsn = array(
          'phptype'  => $dbms,
          'username' => $dbUser,
          'password' => $dbPasswd,
          'hostspec' => $dbHost,
          'database' => $dbName,
     );
    
     $options = array(
          'debug'       => 2,
          'portability' => DB_PORTABILITY_ALL,
     );

     $db = DB::connect($dsn, $options);
	if (PEAR::isError($db)) {
	   die('Could not connect to the database');
	}
	$db->setFetchMode(DB_FETCHMODE_ASSOC);
//-----------------------------------------------

//Import global constants
include_once $xcomicRootPath.'includes/constants.php';

//Configuration Information----------------------
include_once $xcomicRootPath.'includes/Settings.class.php';
$settings = new Settings($db);
//-----------------------------------------------

//Set path to template directory so that other functions
//can refer to it easily. The trailing slash is not included
//because it will help users in template use.
//(Question marks removed since php parses them)
//Eg. <php echo get('theme_path'); >/style.css rather than
//    <php echo get('theme_path'); >style.css
$themePath = $xcomicRootPath.THEMES_DIR.'/'.strtolower($settings->getSetting('usingTheme'));
//$themePath = $xcomicRootPath.'xc-themes/kubrick';


?>
