<?php
/**
Xcomic

$Id$
*/

define('IN_XCOMIC', true);

//$xcomicRootPath='./'; //define in file that calls this file
require_once($xcomicRootPath.'initialize.php');	//Include all page common settings
include_once($xcomicRootPath.'includes/Security.'.$classEx);
include_once($xcomicRootPath.'includes/LatestComicDisplay.class.php'); //Also includes ComicDisplay
include_once($xcomicRootPath.'includes/LatestNewsDisplay.class.php'); //Also includes NewsDisplay
include_once($xcomicRootPath.'includes/ComicAssociatedNewsDisplay.class.php'); //Also includes NewsDisplay
include_once($xcomicRootPath.'includes/ComicStatusDisplay.class.php');
include_once($xcomicRootPath.'includes/UserInformation.class.php');

class Xcomic {
	
	var $comicDisplay;
	var $newsDisplay;
	var $security;
	
	var $cid;
	
	function Xcomic() {
		//Create security object
		$this->security = new Security();
		
		$this->cid=(!empty($_REQUEST[IN_CID])) ? $this->security->allowOnlyNumbers($_REQUEST[IN_CID]) : NULL; //Default to NULL
		
		//echo $this->cid;
		
		if($this->cid==NULL)
		{
			//Latest comic
			$this->comicDisplay = new LatestComicDisplay();
		}
		else
		{
			$this->comicDisplay = new ComicDisplay($this->cid);
		}
		
	}
	
	function getImageCode() {
		global $xcomicTemplate, $configInfo;
		
		$xcomicTemplate->setVars(array(
			'COMIC_IMAGE_URL' => $configInfo['urlToXcomic'].'/'.COMICS_DIR.'/'.$this->comicDisplay->getFilename(),
			'COMIC_TITLE' => $this->comicDisplay->getTitle(),
			'COMIC_IMAGE_WIDTH' => '650',
			'COMIC_IMAGE_HEIGHT' => '975',
			)
		);
		
		$display = new display($configInfo['usingTheme'].'/comicImage.tpl');
		$display->showPage();
		
		//Return html image tag
		//return '<img src="'.$configInfo['urlToXcomic'].'/'.COMICS_DIR.'/'.$this->comicDisplay->getFilename().'" alt="'.$this->comicDisplay->getTitle().'">';	
	}
	
	function selectNewsDisplay($inCategory='default') {
		
		//If cid is defined, use ComicAssociatedNewsDisplay
		//Otherwise, use LatestNewsDisplay
		if(!empty($this->cid))
		{
			$this->newsDisplay = new ComicAssociatedNewsDisplay($this->cid, $inCategory);
		}
		else
		{
			$this->newsDisplay = new LatestNewsDisplay($inCategory);
		}
					
	}
	
	function getNewsCode($inCategory='default') {
		global $xcomicTemplate, $configInfo;
		
		//Create NewsDisplay object depending on cid
		$this->selectNewsDisplay($inCategory);
		
		//Create UserInformation Object
		$userInfo = new UserInformation($this->newsDisplay->getUsername());
		
		//If user is deleted, their email could be blank. Therefore, we set
		//email to a blank string
		$userEmail = $userInfo->getEmail();
		if(empty($userEmail))
			$userEmail = '';
		
		$xcomicTemplate->setVars(array(
			'CONSOLE_LINK' => '',
			'CONSOLE_IMAGE_URL' => '',
			'CONSOLE_IMAGE_WIDTH' => '275',
			'CONSOLE_IMAGE_HEIGHT' => '225',
			'NEWS_TITLE' => $this->newsDisplay->getTitle(),
			'NEWS_USER_EMAIL' => $userEmail,
			'NEWS_USERNAME' => $this->newsDisplay->getUsername(),
			'NEWS_CONTENT' => $this->newsDisplay->getContent(),
			)
		);
		
		$display = new display($configInfo['usingTheme'].'/newsPost.tpl');
		$display->showPage();			
	}
	
	function getPrevNextCode() {
		global $xcomicTemplate, $configInfo;
		
		if($this->comicDisplay->prevId()==false)
		{
			$xcomicTemplate->setVars(array(
				'PREV_COMIC_LINK' => '',
				'PREV_COMIC_TEXT' => '',
				)
			);
		}
		else
		{
			$xcomicTemplate->setVars(array(
				'PREV_COMIC_LINK' => $configInfo['baseUrl'].$_SERVER["PHP_SELF"].'?cid='.$this->comicDisplay->prevId(),
				'PREV_COMIC_TEXT' => '<<-Previous Comic',
				)
			);
		}
		
		if($this->comicDisplay->nextId()==false)
		{
			$xcomicTemplate->setVars(array(
				'NEXT_COMIC_LINK' => '',
				'NEXT_COMIC_TEXT' => '',
				)
			);
		}
		else
		{
			$xcomicTemplate->setVars(array(
				'NEXT_COMIC_LINK' => $configInfo['baseUrl'].$_SERVER["PHP_SELF"].'?cid='.$this->comicDisplay->nextId(),
				'NEXT_COMIC_TEXT' => 'Next Comic-->',
				)
			);
		}
		
		/*
		$xcomicTemplate->setVars(array(
			'PREV_COMIC_LINK' => $configInfo['baseUrl'].$_SERVER["PHP_SELF"].'?cid='.$this->comicDisplay->prevId(),
			'NEXT_COMIC_LINK' => $configInfo['baseUrl'].$_SERVER["PHP_SELF"].'?cid='.$this->comicDisplay->nextId(),
			'PREV_COMIC_TEXT' => '<<-Previous Comic',
			'NEXT_COMIC_TEXT' => 'Next Comic-->',
			)
		);
		*/
		
		$display = new display($configInfo['usingTheme'].'/comicNav.tpl');
		$display->showPage();	
	}
	
	
	function getComicStatusCode($inStatusBarColor='Black', $inStatusBarMaxWidth='200', $inStatusBarHeight = '5') {
		global $xcomicTemplate, $configInfo;
		
		$statusBarMaxWidth = $inStatusBarMaxWidth; //In px
		$statusBarHeight = $inStatusBarHeight;
		$statusBarColor = $inStatusBarColor; //CSS constant or Hex with #
		
		//Get latest comic date
		$latestComic = new LatestComicDisplay();
		$lastComicDate = date('m/d/y', $latestComic->getDate());
		
		$todaysDate = date('m/d/y g:i a', time());
		
		//Get date for next comic via ComicStatusDisplay
		$statusDisplay = new ComicStatusDisplay();
		$statusDisplay->getComicStatusInfo();
		
		$date = $statusDisplay->getNextDate();
		$nextComicDate = date('m/d/y g:i a', $date);
		
		 //Use porportions to calculate corresponding percent for a given max image width
		$statusBarWidth = ($statusBarMaxWidth*$statusDisplay->getPercentStatus())/100;
		
		
		
		$xcomicTemplate->setVars(array(
			'LAST_COMIC_DATE' => $lastComicDate,
			'TODAYS_DATE' => $todaysDate,
			'NEXT_COMIC_DATE' => $nextComicDate,
			'STATUS_BAR_COLOR' => $statusBarColor,
			'STATUS_BAR_WIDTH' => $statusBarWidth,
			'STATUS_BAR_HEIGHT' => $statusBarHeight,
			'NUMBER_PERCENT_STATUS' => $statusDisplay->getPercentStatus().'%',
			'PATH_TO_TRANSPARENT_GIF' => $configInfo['urlToXcomic'].'/templates/'.$configInfo['usingTheme'],
			'NEXT_COMIC_COMMENT' => $statusDisplay->getComment(),
			)
		);
		
		$display = new display($configInfo['usingTheme'].'/comicStatus.tpl');
		$display->showPage();
	}
	
	
	
}

/*
//Testing Xcomic
$x = new Xcomic();
$x->getImageCode();
$x->getPrevNextCode();
$x->getNewsCode();
*/



?>