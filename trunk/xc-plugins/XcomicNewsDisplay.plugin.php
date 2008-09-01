<?php
/**
Xcomic - Comic Management Script
(http://xcomic.sourceforge.net)
--------------------------------

This file contains functions that construct the news display
classes and create functions that return information about a specified
news item for templating use.

$Id$
*/

class XcomicNewsDisplay extends Plugin
{
    function XcomicNewsDisplay()
    {
        $this->registerAction('getNewsTitle', 'getNewsTitle');
        $this->registerAction('getNewsDate', 'getNewsDate');
        $this->registerAction('getNewsTime', 'getNewsTime');
        $this->registerAction('getNewsAuthor', 'getNewsAuthor');
        $this->registerAction('getNewsAuthorEmail', 'getNewsAuthorEmail');
        $this->registerAction('getNewsContent', 'getNewsContent');
        $this->registerAction('hasNextNews', 'hasNextNews');
    }
    
    function getPluginMetaData($meta)
    {
        switch ($meta)
        {
            case 'name': return 'Xcomic News Display'; break;
            case 'description': return 'The builtin news display plugin.'; break;
            case 'author': return 'Xcomic'; break;
            case 'website': return 'http://xcomic.sourceforge.net/'; break;
            default: return ''; break;
        }
    }

    function getNewsTitle() {
    	global $xcomic;
    	
    	return $xcomic->news->getTitle();
    }
    
    function getNewsDate() {
    	global $xcomic;
    	
    	return date('l - F jS, Y', $xcomic->news->getDate()); //ex. Wednesday - March 15th, 2004
    }
    
    function getNewsTime() {
    	global $xcomic;
    	
    	return date('G:i:s', $xcomic->news->getDate());
    }
    
    function getNewsAuthor() {
    	global $xcomic;
    	
    	return $xcomic->news->getUsername();
    }
    
    function getNewsAuthorEmail() {
    	global $xcomic;
    	
    	//Query user info
    	$xcomic->user->getUserInfo($this->getNewsAuthor());

    	//If user is deleted, their email could be blank. Therefore, we set
    	//email to a blank string
    	$userEmail = $xcomic->user->getEmail();
    	if(empty($userEmail)) {
    		$userEmail = '';
    	}
    		
    	return $userEmail;    
    }
    	
    function getNewsContent() {
    	global $xcomic;
    	
    	return $xcomic->security->removeMagicQuotes($xcomic->news->getContent()); 
    }

    function hasNextNews() {
    	global $xcomic;

    	return $xcomic->news->updateForNextId();
    }
}
?>