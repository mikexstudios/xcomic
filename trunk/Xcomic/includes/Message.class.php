<?php
/**
Xcomic

$Id$
*/

define('IN_XCOMIC', true);

/*
$xcomicRootPath='../';
include_once($xcomicRootPath.'initialize.php');
*/

include_once($xcomicRootPath.'admin/AdminDisplay.'.$classEx);
include_once($xcomicRootPath.'includes/Display.'.$classEx);


class Message {
	
	var $display; //Display obj
	
	//outOfAdmin displays the message page without the admin functions
	function Message($inAdmin=false) {
		global $configInfo;
		
		if($inAdmin==true)
		{
			$this->display = new AdminDisplay('admin/message.tpl');
		}
		else
		{
			$this->display = new Display($configInfo['usingTheme'].'/message.tpl');
		}
	}
		
	function say($inMsg, $type='message') {
		global $xcomicTemplate;
		
		$xcomicTemplate->setVars(array(
			'MESSAGE' => $inMsg
			)
		);
		
		if($type=='error')
		{
			$xcomicTemplate->setVars('SECTION_TITLE', 'Error:');
		}
		else
		{
			$xcomicTemplate->setVars('SECTION_TITLE', 'Message:');
		}
		
		$this->display->showFullPage();
	}
	
	function error($inMsg) {
		$this->say($inMsg, 'error');	
	}
	
	function setAdmin($inBool) {
	
		if($inBool == true)
		{
			$this->display = new AdminDisplay('admin/message.tpl');
		}
		
	}
	
	function setAdminHeader($inHeader='overallHeader') {
	
		$this->display = new AdminDisplay('admin/message.tpl', $inHeader);
		
	}
		
}

/*
//Testing
$x = new Message(true);
$x->say("This is a error message");
*/




?>