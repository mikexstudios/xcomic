<?php
/**
Xcomic

//Initialize - creates global variables used throughout the script
//

$Id$
*/

//Calculating the time needed to execute this script
$xcomicStartTime = strtok(microtime(), ' ') + strtok(' ');

//$xcomicRootPath is defined in the file that includes initialize

//Include hacking check and php extension
include_once $xcomicRootPath.'extension.inc';

//Database---------------------------------------
	//Include database configuration information
	include_once $xcomicRootPath.'includes/config.'.$phpEx;
	
	require_once 'DB.php';
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
include_once $xcomicRootPath.'includes/constants.'.$phpEx;

//Configuration Information----------------------
include_once $xcomicRootPath.'includes/Settings.'.$classEx;
$settings = new Settings();
//-----------------------------------------------


?>