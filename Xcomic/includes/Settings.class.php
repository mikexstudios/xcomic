<?php
/**
Xcomic

$Id$
*/

/*
define('IN_XCOMIC', true);

$xcomicRootPath='../';
include_once($xcomicRootPath.'initialize.php');
*/

class Settings {
	
	var $configInfo; //Holds all configuration info records
	
	function Settings() {
		
		//Grab settings from database
		$this->getConfigInfo();
		
	}
	
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
			$this->configInfo[$row['option']] = $row['value'];
		}

	}
	
	function getSetting($inKey) {
		return $this->configInfo[$inKey]; //Returns blank if nothing
	}
	
}

/*
//Testing Settings
$x = new Settings();
*/


?>