<?php
/*****************************************************************************
  $Id$

  Yapter 2.13b1 - Yet Another PHP Template Engine ®
  Copyright (C) 2001-2002 Vincent Driessen

  Current modifications by mikeXstudios for Xcms
 *****************************************************************************/

include_once('yapter.php');

class YapterX extends Yapter {
	
	function YapterX() {
		
		//Set Yapter warning level
		$level = E_YAPTER_ALL;
		$this->warningLevel = $level;

		$this->startTime = $this->getmicrotime();
		//This is overridden so that the constructor doesn't need a file
		//$this->addBlockFromFile($this->_ROOT, $file);
		$this->missing_list = array();
	}
	
	function setTemplateFile($inTemplateName, $inFilename) {
		$this->addBlockFromFile($inTemplateName, $inFilename);
	}
	
	
	
	
	


}
?>
