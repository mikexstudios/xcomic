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
		
		if(empty($this->cid))
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
		global $xcomicTemplate, $configInfo, $message, $settings;
		
		//Check for non-existant comic
		if ($this->comicDisplay->getTitle()=='')
		{
			echo 'You have selected a non-existant comic!';
			return;
		}
		
		//Set variables
		$comicImageUrl = $settings->getSetting('urlToXcomic').'/'.COMICS_DIR.'/'.$this->comicDisplay->getFilename();
		$comicTitle = $this->comicDisplay->getTitle();
		$comicImageWidth = '650';
		$comicImageHeight = '975';
?>
			<div id="comic"><img src="<?php echo $comicImageUrl; ?>" width="<?php echo $comicImageWidth; ?>" height="<?php echo $comicImageHeight; ?>" alt="<?php echo $comicTitle; ?>"></div>
<?php		

	}
	
	function selectNewsDisplay($inCategory='default') {
		global $xcomicRootPath;
		
		//If cid is defined, use ComicAssociatedNewsDisplay
		//Otherwise, use LatestNewsDisplay
		if(!empty($this->cid))
		{
			include_once($xcomicRootPath.'includes/ComicAssociatedNewsDisplay.class.php'); //Also includes NewsDisplay
			$this->newsDisplay = new ComicAssociatedNewsDisplay($this->cid, $inCategory);
		}
		else
		{
			include_once($xcomicRootPath.'includes/LatestNewsDisplay.class.php'); //Also includes NewsDisplay
			$this->newsDisplay = new LatestNewsDisplay($inCategory);
		}
					
	}
	
	function getNewsCode($inCategory='default') {
		global $xcomicRootPath, $settings;
		
		//Display variables
		/*
		$consoleLink = '';
		$consoleImageUrl = '';
		$consoleImageWidth = '';
		$consoleImageHeight = '';
		*/
		$newsTitle = '';
		$newsDate = '';
		$newsTime = '';
		$newsUserEmail = '';
		$newsUsername = '';
		$newsContent = '';
		
		//Create NewsDisplay object depending on cid
		$this->selectNewsDisplay($inCategory);
		
		//Create UserInformation Object
		include_once($xcomicRootPath.'includes/UserInformation.class.php');
		$userInfo = new UserInformation($this->newsDisplay->getUsername());
		
		//If user is deleted, their email could be blank. Therefore, we set
		//email to a blank string
		$userEmail = $userInfo->getEmail();
		if(empty($userEmail))
			$userEmail = '';
		
		//Set variables
		/*
		$consoleLink = '';
		$consoleImageUrl = '';
		$consoleImageWidth = '275';
		$consoleImageHeight = '275';
		*/
		$newsTitle = $this->newsDisplay->getTitle();
		$newsDate = date('l - F jS, Y', $this->newsDisplay->getDate()); //ex. Wednesday - March 15th, 2004
		$newsTime = date('G:i:s', $this->newsDisplay->getDate());
		$newsUserEmail = $userEmail;
		$newsUsername = $this->newsDisplay->getUsername();
		$newsContent = $this->newsDisplay->getContent();
?>
<!--
<div class="console">
<a href="{CONSOLE_LINK}"><img src="{CONSOLE_IMAGE_URL}" width="{CONSOLE_IMAGE_WIDTH}" height="{CONSOLE_IMAGE_HEIGHT}"></a>
</div>
-->
<div class="post">
	<h2><?php echo $newsTitle; ?></h2>
	<small>On <?php echo $newsDate; ?> <?php echo $newsTime; ?> by <a href="mailto:<?php echo $newsUserEmail; ?>"><?php echo $newsUsername; ?></a></small> 
	<div class="entry">
	<?php echo $newsContent; ?>
	</div>
</div>
<?php
		
	}

	function getComicNavCode() {
		global $xcomicRootPath, $classEx, $settings;	
		
		//Display variables
		$prevComicLink = '';
		$prevComicText = '';
		$nextComicLink = '';
		$nextComicText = '';
		$comicOptionListCode=''; //Holds HTML for drop down
		$comicDropdownHeader = 'Archives';
		
		if($this->comicDisplay->prevId()==false)
		{
			$prevComicLink = '';
			$prevComicText = '';
		}
		else
		{
			$prevComicLink = $settings->getSetting('baseUrl').$_SERVER["PHP_SELF"].'?cid='.$this->comicDisplay->prevId();
			$prevComicText = '< Previous';
		}
		
		if($this->comicDisplay->nextId()==false)
		{
			$nextComicLink = '';
			$nextComicText = '';
		}
		else
		{
			$nextComicLink = $configInfo['baseUrl'].$_SERVER["PHP_SELF"].'?cid='.$this->comicDisplay->nextId();
			$nextComicText = 'Next >';
		}
				
		//Generate drop down box ---------------------
		include_once($xcomicRootPath.'includes/ComicListing.'.$classEx);
		$listComics = new ComicListing();
		$comicsList = $listComics->getComicList(); //Array of comic listings
		$numComics = $listComics->numComics(); //Number of elements in that array
		
		//Since $comicsList is in ascending order. We want the most recent comic first
		//Therefore, set the for loop counting backwards
		for($comicCount = $numComics-1; $comicCount >= 0 ; $comicCount--)
		{
			$comicOptionListCode .= '<option value="'.$comicsList[$comicCount]['cid'].'">'.date('Y-m-d', $comicsList[$comicCount]['date']).' ['.$comicsList[$comicCount]['cid'].'] '.$comicsList[$comicCount]['title']."</option>\n";
		}
		//--------------------------------------------
		
		//Display page
?>
<ul id="comicnav" class="comicnav-left">
<li class="comicnav-link"><a href="<?php echo $prevComicLink; ?>"><?php echo $prevComicText; ?></a></li>
</ul>

<ul id="comicnav" class="comicnav-right">
	<li class="comicnav-link"><a href="<?php echo $nextComicLink; ?>"><?php echo $nextComicText; ?></a></li>
</ul>

<form class="comicdropdown-form">
<script language="javascript">
<!--
	//From MegaTokyo (http://www.megatokyo.com)
        function StripJump(cid)        {
                if (cid != '')  {
                        top.location.href = "<?php echo $_SERVER['PHP_SELF']; ?>" + "?cid=" + cid;
                }
        }
//-->
</script>
<select onchange="StripJump(this.options[selectedIndex].value);" name="cid"> 
<option value="" selected><?php echo $comicDropdownHeader; ?></option> 
<?php echo $comicOptionListCode; ?>
</select>
</form>
<?php

	}
	
	function getExecutionTime() {
			global $xcomicStartTime;
			
			//Calculate the time needed to execute the script
			$xcomicEndTime = strtok(microtime(), " ") + strtok(" ");
			$executionTime = $xcomicEndTime-$xcomicStartTime;
			
			//Rounded off to three places
			echo sprintf('%01.3f', $executionTime);
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