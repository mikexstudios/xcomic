<?php
/**
Xcomic

//Initialize - creates global variables used throughout the script
//

$Id$
*/

//Calculating the time needed to execute this script
$xcomicStartTime = strtok(microtime(), " ") + strtok(" ");

//Variables and Constants for this file
define('IN_XCOMIC', true);
//$xcomicRootPath is defined in the file that includes initialize

//Include hacking check and php extension
include_once($xcomicRootPath.'extension.inc');

//Import global constants
include_once($xcomicRootPath.'includes/constants.'.$phpEx);

//Database---------------------------------------
	//Include database configuration information
	include_once($xcomicRootPath.'includes/config.'.$phpEx);
	
	//Use PEAR::DB
	include_once('DB.php'); //LATER: Check first to see if it and PEAR exists
	
	//Create database object
	$xcomicDb = DB::connect($xcomicDsn);
	if (DB::isError($xcomicDb))
	{
		die ("Could not connect to the database: $xcomicDb->getMessage()\n");
	}
//-----------------------------------------------

//Configuration Information----------------------
include_once($xcomicRootPath.'includes/Settings.'.$classEx);
$settings = new Settings();
//-----------------------------------------------


?>