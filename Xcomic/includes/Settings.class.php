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
			$this->configInfo[$row['option']]['option'] = $row['option'];
			$this->configInfo[$row['option']]['value'] = $row['value'];
			$this->configInfo[$row['option']]['name'] = $row['name'];
			$this->configInfo[$row['option']]['description'] = $row['description'];
		}

	}
	
	//Kind of useless, but used by doesSettingExist to check for existance
	function getOption($inKey) {
		return $this->configInfo[$inKey]['option'];	
	}
	
	function getSetting($inKey) {
		return $this->configInfo[$inKey]['value']; //Returns blank if nothing
	}
	
	function getName($inKey) {
		return $this->configInfo[$inKey]['name'];	
	}
	
	function getDescription($inKey) {
		return $this->configInfo[$inKey]['description'];	
	}
	
	function getConfigInfoArray() {
		return $this->configInfo;	
	}
	
	function addNewSetting($inKey, $inValue, $inDescription) {
		global $xcomicDb, $message;
		
		$sql = 'INSERT INTO '.XCOMIC_CONFIG_TABLE." (option, value, description)
			VALUES ('$inKey', '$inValue', '$inDescription')";
		
		if( !($result = $xcomicDb->sql_query($sql)) )
		{
			$message->error("Could not add new setting!");
		}	
	}
	
	function changeSettingValue($inKey, $inValue) {
		global $xcomicDb, $message;
		
		$sql = 'UPDATE '.XCOMIC_CONFIG_TABLE."
			SET value = '$inValue'
			WHERE `option` = '$inKey'"; //OPTION is a sql reserved word
		
		if( !($result = $xcomicDb->sql_query($sql)) )
		{
			$message->error('Could not change value for setting! SQL: '.$sql);
		}	
	}
	
	function changeSettingDescription($inKey, $inDescription) {
		global $xcomicDb, $message;
		
		$sql = 'UPDATE '.XCOMIC_CONFIG_TABLE."
			SET description = '$inDescription'
			WHERE option = '$inKey'";
		
		if( !($result = $xcomicDb->sql_query($sql)) )
		{
			$message->error("Could not change description for setting!");
		}	
	}
	
	function doesSettingExist($inKey) {
		//For some reason, I cannot place this directly inside empty()
		$option = $this->getOption($inKey); 
		if(!empty($option))
		{
			//Option exists
			return true;
		}	
		
		//Option does not exist
		return false;
	}
	
}

/*
//Testing Settings
$x = new Settings();
*/


?>