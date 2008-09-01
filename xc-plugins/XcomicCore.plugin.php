<?php
/**
Xcomic - Comic Management Script
(http://xcomic.sourceforge.net)
--------------------------------

This file contains functions that deal with the script basic
core functions.

$Id$
*/

class XcomicCore extends Plugin
{
    function XcomicCore()
    {
        $this->registerAction('getThisScriptFilename', 'getThisScriptFilename');
        $this->registerAction('getExecutionTime', 'getExecutionTime');
        $this->registerAction('site_title', 'getSiteTitle');
        $this->registerAction('theme_path', 'getThemePath');
        $this->registerAction('rss_enabled', 'getRssEnabled');
    }
    
    function getPluginMetaData($meta)
    {
        switch ($meta)
        {
            case 'name': return 'Xcomic Core'; break;
            case 'description': return 'The builtin core plugin.'; break;
            case 'author': return 'Xcomic'; break;
            case 'website': return 'http://xcomic.sourceforge.net/'; break;
            default: return ''; break;
        }
    }

    function getThisScriptFilename() {
    	return $_SERVER['PHP_SELF'];
    }

    function getExecutionTime() {
    	global $xcomicStartTime;
    	$xcomicStartTime;
    	//Calculate the time needed to execute the script
    	$xcomicEndTime = strtok(microtime(), " ") + strtok(" ");
    	$executionTime = $xcomicEndTime-$xcomicStartTime;
    
    	//Rounded off to three places
    	return sprintf('%01.3f', $executionTime);
    }
    
    function getSiteTitle() {
    	global $xcomic, $settings;
    	
    	return $xcomic->security->removeMagicQuotes($settings->getSetting('title'));
    	//return $settings->getSetting('title');
    }
    
    function getThemePath() {
    	global $themePath;
    	
    	return $themePath;
    }
    
    function getRssEnabled() {
        global $settings;
    
        return $settings->getSetting('enableRSS');
    }
}
?>