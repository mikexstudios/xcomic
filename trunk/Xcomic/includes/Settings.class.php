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
			$this->configInfo[$row['option']]['option'] = $row['option'];
			$this->configInfo[$row['option']]['vartype'] = $row['vartype'];
			$this->configInfo[$row['option']]['displaycode'] = $row['displaycode'];
			$this->configInfo[$row['option']]['name'] = $row['name'];
			$this->configInfo[$row['option']]['description'] = $row['description'];
			switch ($row['displaycode'])
			{
                case 'number':
                    $this->configInfo[$row['option']]['value'] = (int)$row['value'];
                    break;
                case 'boolean':
                    $this->configInfo[$row['option']]['value'] = (bool)$row['value'];
                    break;
                default:
                    $this->configInfo[$row['option']]['value'] = $row['value'];
                    break;
            }
		}

	}

	//Kind of useless, but used by doesSettingExist to check for existance
	function getOrder($inKey)
	{
		return $this->configInfo[$inKey]['order'];
	}
	
	function getVarType($inKey)
	{
		return $this->configInfo[$inKey]['vartype'];
	}
	
	function getDisplayCode($inKey)
	{
		return $this->configInfo[$inKey]['displaycode'];
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
	
	function addNewSetting($inKey, $inValue, $inVarType, $inDisplayCode, $inName, $inDescription)
	{
		global $message;
		
		$order = $this->dbc->getOne("SELECT MAX(`order`)+1 FROM ".XCOMIC_CONFIG_TABLE);

		$sql = '
		    INSERT INTO '.XCOMIC_CONFIG_TABLE." (`order`, `option`, `value`, `vartype`, `displaycode`, `name`, `description`)
			VALUES ('$order', '$inKey', '$inValue', '$inVarType', '$inDisplayCode', '$inName', '$inDescription')";
		$result = $this->dbc->query($sql);
		if (PEAR::isError($result)) {
			#$message->error("Could not add new setting!");
			die('Could not add new setting!');
		}	
	}
	
	function changeSettingValue($inKey, $inValue)
	{
		global $message;

        switch ($this->getVarType($inKey))
        {
            case 'number':
                $inValue = (int)$inValue;
                break;
            case 'boolean':
                $inValue = (int)(bool)$inValue;
                break;
            default:
                break;
        }

		$sql = '
		    UPDATE '.XCOMIC_CONFIG_TABLE."
			SET `value` = '$inValue'
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
			SET `description` = '$inDescription'
			WHERE `option` = '$inKey'";
		$result = $this->dbc->query($sql);
		if (PEAR::isError($result)) {
			#$message->error("Could not change description for setting!");
			die('Could not change description for setting!');
		}
	}
	
	function doesSettingExist($inKey)
	{
		if (array_key_exists($inKey, $this->configInfo) && !empty($this->configInfo[$inKey]['option'])) {
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
