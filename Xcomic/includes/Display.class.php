<?php
/**
Xcomic

$Id$
*/

define('IN_XCOMIC', true);

/*
$xcomicRootPath='./';
include_once('initialize.php');
*/

class Display {
	
	//Class variables
	var $template; //prepareContentPage will use this template;
	var $isAdmin;
	var $stdTemplateName='stdTemplate';
	
	//Constructors
	
	function Display($inTemplate) {
		global $xcomicTemplate;
		
		$this->template=$inTemplate;
		//echo $inTemplate.'|'.$this->stdTemplateName;
		$xcomicTemplate->setFile($this->stdTemplateName, $this->template);
	}
	
	function setVars($nameOrArray, $value=NULL) {
		global $xcomicTemplate;
		
		$xcomicTemplate->setVars($nameOrArray, $value);
	}
	
	function showFullPage($viewHeader=1, $viewFooter=1) {
		global $xcomicTemplate, $xcomicDb, $xcomicStartTime, $xcomicRootPath, $phpEx;
		global $configInfo;
		
		if($viewHeader==1)
		{
			//Send out page header
			include_once($xcomicRootPath.'includes/pageHeader.'.$phpEx);
			
			//Show overall header-----------------------------
			$xcomicTemplate->setFile('overallHeader', $configInfo['usingTheme'].'/overallHeader.tpl');
			$xcomicTemplate->setVars(array(
				'SITENAME' => $configInfo['sitename']
				)
			);
			echo $xcomicTemplate->parse('overallHeader');
			//-------------------------------------------------
		}
		
		//Display and parse the template
		echo $this->returnPage();
		
		if($viewFooter==1)
		{
			//Show overall footer------------------------------
			//Calculate the time needed to execute the script
			$xcomicEndTime = strtok(microtime(), " ") + strtok(" ");
			$executionTime = $xcomicEndTime-$xcomicStartTime;
	
			//Set up new Template section
			$xcomicTemplate->setFile('overallFooter', $configInfo['usingTheme'].'/overallFooter.tpl');
			
			$xcomicTemplate->setVars(array(
				'XCOMIC_VERSION' => $configInfo['version'],
				'EXECUTION_TIME' => "Page generated in ". sprintf("%01.3f", $executionTime) ." seconds"
				)
			);
			
			echo $xcomicTemplate->parse('overallFooter');
			
			//Send out page footer
			include_once($xcomicRootPath.'includes/pageTail.'.$phpEx);
			//-------------------------------------------------
		}
	}
	
	function showPage() {
		global $xcomicTemplate, $xcomicDb, $xcomicRootPath, $phpEx;
		global $configInfo;
		
		//Display and parse the template
		echo $this->returnPage();
	}
	
	//Instead of displaying the page, output is written to a variable
	function returnPage() {
	
		global $xcomicTemplate;
		
		//Display and parse the template
		return $xcomicTemplate->parse($this->stdTemplateName);
		
	}
}

/*
//Testing
$x = new PostNews("This is a test", "Title1", "left");
$x->sendToDatabase();
*/

?>