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

class Settings
{	
	var $configInfo; //Holds all configuration info records
    var $dbc;

	function Settings(&$dbc)
	{
        if (DB::isConnection($dbc)) {
            $this->dbc =& $dbc;
        }
		//Grab settings from database
		$this->getConfigInfo();	
	}
	
	function getConfigInfo()
	{
		global $message;

		$sql = '
		    SELECT * 
		    FROM ' . XCOMIC_CONFIG_TABLE . ' ORDER BY `order` ASC';
		
		$result = $this->dbc->getAll($sql);
		if (PEAR::isError($result)) {
			#$message->error("Could not query config information");
			die('Could not query config information');
		}
		
		foreach ($result as $row) {
			//Place configuration information in array
			$this->configInfo[$row['option']]['order'] = $row['order'];
			$this->configInfo[$row['option']]['type'] = $row['type'];
			$this->configInfo[$row['option']]['option'] = $row['option'];
			$this->configInfo[$row['option']]['value'] = $row['value'];
			$this->configInfo[$row['option']]['name'] = $row['name'];
			$this->configInfo[$row['option']]['description'] = $row['description'];
		}

	}
	
	//Kind of useless, but used by doesSettingExist to check for existance
	function getOrder($inKey)
	{
		return $this->configInfo[$inKey]['order'];
	}
	
	function getType($inKey)
	{
		return $this->configInfo[$inKey]['type'];
	}

	function getOption($inKey)
	{
		return $this->configInfo[$inKey]['option'];	
	}
	
	function getSetting($inKey)
	{
		return $this->configInfo[$inKey]['value']; //Returns blank if nothing
	}
	
	function getName($inKey)
	{
		return $this->configInfo[$inKey]['name'];	
	}
	
	function getDescription($inKey)
	{
		return $this->configInfo[$inKey]['description'];	
	}
	
	function getConfigInfoArray()
	{
		return $this->configInfo;	
	}
	
	function addNewSetting($inOrder, $inType, $inKey, $inValue, $inDescription)
	{
		global $message;
		
		$sql = '
		    INSERT INTO '.XCOMIC_CONFIG_TABLE." (order, type, option, value, description)
			VALUES ('$inOrder', '$inType', '$inKey', '$inValue', '$inDescription')";
		$result = $this->dbc->query($sql);
		if (PEAR::isError($result)) {
			#$message->error("Could not add new setting!");
			die('Could not add new setting!');
		}	
	}
	
	function changeSettingValue($inKey, $inValue)
	{
		global $message;

		$sql = '
		    UPDATE '.XCOMIC_CONFIG_TABLE."
			SET value = '$inValue'
			WHERE `option` = '$inKey'"; //OPTION is a sql reserved word
		$result = $this->dbc->query($sql);
		if (PEAR::isError($result)) {
			#$message->error('Could not change value for setting! SQL: '.$sql);
			die('Could not change value for setting! SQL: '.$sql);
		}	
	}
	
	function changeSettingDescription($inKey, $inDescription)
	{
		global $message;
		
		$sql = '
		    UPDATE '.XCOMIC_CONFIG_TABLE."
			SET description = '$inDescription'
			WHERE option = '$inKey'";
		$result = $this->dbc->query($sql);
		if (PEAR::isError($result)) {
			#$message->error("Could not change description for setting!");
			die('Could not change description for setting!');
		}	
	}
	
	function doesSettingExist($inKey)
	{
		//For some reason, I cannot place this directly inside empty()
		$option = $this->getOption($inKey); 
		if (!empty($option)) {
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