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
	
	//Include db.php
	include_once($xcomicRootPath.'includes/selectDatabase.'.$phpEx);
	
	//Create database object
	$xcomicDb = new sql_db($xcomicDbHost, $xcomicDbUser, $xcomicDbPasswd, $xcomicDbName, false);
	if(!$xcomicDb->db_connect_id)
	{
	   die('Could not connect to the database');
	}
//-----------------------------------------------

//Template---------------------------------------
	//Include Template wrapper class
	include_once($xcomicRootPath.'includes/Template.class.'.$phpEx);
	
	//Grab configuration information
	$configInfo = getConfigInfo();
	
	//Create instance of template class.
	$xcomicTemplate = new Template();
	
	//Set root path to template directory 
	$xcomicTemplate->setRootPath($xcomicRootPath.TEMPLATES_DIR);
	
	//Template settings
	$xcomicTemplate->extTemplateObj->setWarningLevel(E_YAPTER_ERROR);
//-----------------------------------------------

//Message----------------------------------------
include_once($xcomicRootPath.'includes/Message.'.$classEx);
$message = new Message();
//-----------------------------------------------

function getConfigInfo() {
	
	global $xcomicDb, $message;
	
	$sql = "SELECT * 
	FROM " . XCOMIC_CONFIG_TABLE;
	
	if( !($result = $xcomicDb->sql_query($sql)) )
	{
		$message->error("Could not query config information");
	}
	
	while ( $row = $xcomicDb->sql_fetchrow($result) )
	{
		//Place configuration information in array
		$configInfo[$row['option']] = $row['value'];
	}
	
	return $configInfo;
	
}







?>