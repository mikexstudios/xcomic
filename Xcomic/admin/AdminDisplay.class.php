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
include_once($xcomicRootPath.'/includes/Display.'.$classEx);

class AdminDisplay extends Display{
	
	var $overallHeader, $overallFooter;

	function AdminDisplay($inTemplate, $inHeader='overallHeader', $inFooter='overallFooter') {
	
		$this->Display($inTemplate);
		
		$this->overallHeader = $inHeader;
		$this->overallFooter = $inFooter;
		
	}
		
	function showFullPage($viewHeader=1, $viewFooter=1) {
		global $xcomicTemplate, $xcomicDb, $xcomicStartTime, $xcomicRootPath, $phpEx;
		global $configInfo;
		
		if($viewHeader==1)
		{
			//Send out page header
			include_once($xcomicRootPath.'includes/pageHeader.'.$phpEx);
			
			//Show overall header-----------------------------
			$xcomicTemplate->setFile('overallHeader', 'admin/'.$this->overallHeader.'.tpl');
			$xcomicTemplate->setVars(array(
				'SITENAME' => $configInfo['sitename']
				)
			);
			echo $xcomicTemplate->parse('overallHeader');
			//-------------------------------------------------
		}
		
		//Place the content in overallBody
		$xcomicTemplate->setFile('overallBody', 'admin/overallBody.tpl');
		$xcomicTemplate->setVars(array(
			'SECTION_BODY' => $this->returnPage()
			)
		);
		//Display parsed page
		echo $xcomicTemplate->parse('overallBody');
		
		if($viewFooter==1)
		{
			//Show overall footer------------------------------
			//Calculate the time needed to execute the script
			$xcomicEndTime = strtok(microtime(), " ") + strtok(" ");
			$executionTime = $xcomicEndTime-$xcomicStartTime;
	
			//Set up new Template section
			$xcomicTemplate->setFile('overallFooter', 'admin/'.$this->overallFooter.'.tpl');
			
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
		
		//Place the content in overallBody
		$xcomicTemplate->setFile('overallBody', 'admin/overallBody.tpl');
		$xcomicTemplate->setVars(array(
			'SECTION_BODY' => $this->returnPage()
			)
		);
		//Display parsed page
		echo $xcomicTemplate->parse('overallBody');	
	}

}

/*
//Testing
		$xcomicTemplate->setVars(array(
			'SECTION_TITLE' => 'Post New News',
			'MODE' => 'postnews',
			'TITLE' => '',
			'CONTENT' => '',
			)
		);
		
		$adminDisplay = new AdminDisplay('admin/postNews.tpl');
		$adminDisplay->showPage();
*/


?>