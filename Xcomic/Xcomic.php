<?php
/**
Xcomic

$Id$
*/

define('IN_XCOMIC', true);

//$xcomicRootPath='./'; //define in file that calls this file
require_once $xcomicRootPath.'initialize.php';	//Include all page common settings
include_once $xcomicRootPath.'includes/Security.'.$classEx;
include_once $xcomicRootPath.'includes/LatestComicDisplay.class.php'; //Also includes ComicDisplay

class Xcomic {
    var $comicDisplay;
    var $newsDisplay;
    var $security;
    var $cid;
    var $dbc;

    function Xcomic(&$dbc) {
        $this->dbc =& $dbc;
        //Create security object
        $this->security = new Security($dbc);

        $this->cid = (!empty($_REQUEST[IN_CID])) ? $this->security->allowOnlyNumbers($_REQUEST[IN_CID]) : NULL; //Default to NULL

		if (empty($this->cid)) {
			//Latest comic
			$this->comicDisplay = new LatestComicDisplay($this->dbc);
		} else {
			$this->comicDisplay = new ComicDisplay($this->dbc, $this->cid);
		}    
    }

    function getComicTitle() {
        return '<div id="comictitle">' . $this->comicDisplay->getTitle() .' </div>';
    }

	function getImageCode() {
		global $message, $settings;
		
		//Check for non-existant comic
		if ($this->comicDisplay->getTitle() == '') {
			return 'You have selected a non-existant comic!';
		}
		
		//Set variables
		$comicImageUrl = COMICS_DIR.'/'.$this->comicDisplay->getFilename();
		$comicTitle = $this->comicDisplay->getTitle();
		return '<div id="comic"><img src="' . $comicImageUrl . '" alt="' . $comicTitle . '" /></div>';	
	}

	function selectNewsDisplay() {
		global $xcomicRootPath;
		
		//If cid is defined, use ComicAssociatedNewsDisplay
		//Otherwise, use LatestNewsDisplay
		if (!empty($this->cid)) {
			include_once $xcomicRootPath.'includes/ComicAssociatedNewsDisplay.class.php'; //Also includes NewsDisplay
			$this->newsDisplay = new ComicAssociatedNewsDisplay($this->dbc, $this->cid);
		} else {
			include_once $xcomicRootPath.'includes/LatestNewsDisplay.class.php'; //Also includes NewsDisplay
			$this->newsDisplay = new LatestNewsDisplay($this->dbc);
		}
					
	}
	
	function getNewsCode() {
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
		$this->selectNewsDisplay();
		
		//Create UserInformation Object
		include_once $xcomicRootPath.'includes/UserInformation.class.php';
		$userInfo = new UserInformation($this->dbc, $this->newsDisplay->getUsername());
		
		//If user is deleted, their email could be blank. Therefore, we set
		//email to a blank string
		$userEmail = $userInfo->getEmail();
		if (empty($userEmail)) {
			$userEmail = '';
		}

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
		
		if ($this->comicDisplay->prevId() === false) {
			$prevComicLink = '';
			$prevComicText = '';
		} else {
			$prevComicLink = $settings->getSetting('baseUrl').$_SERVER["PHP_SELF"].'?cid='.$this->comicDisplay->prevId();
			$prevComicText = '< Previous';
		}
		
		if ($this->comicDisplay->nextId() === false) {
			$nextComicLink = '';
			$nextComicText = '';
		} else {
			$nextComicLink = $settings->getSetting('baseUrl').$_SERVER["PHP_SELF"].'?cid='.$this->comicDisplay->nextId();
			$nextComicText = 'Next >';
		}
				
		//Generate drop down box ---------------------
		include_once $xcomicRootPath.'includes/ComicListing.class.php';
		$listComics = new ComicListing($this->dbc);
		$comicsList = $listComics->getComicList(); //Array of comic listings

		//Since $comicsList is in ascending order. We want the most recent comic first
		//Therefore, set the for loop counting backwards
		foreach ($comicsList as $row) {
			$comicOptionListCode .= '<option value="'.$row['cid'].'">'.date('Y-m-d', $row['date']).' ['.$row['cid'].'] '.$row['title']."</option>\n";
		}
		//--------------------------------------------
		
		//Display page
?>
<ul class="comicnav comicnav-left">
<li class="comicnav-link"><a href="<?php echo $prevComicLink; ?>"><?php echo $prevComicText; ?></a></li>
</ul>

<ul class="comicnav comicnav-right">
	<li class="comicnav-link"><a href="<?php echo $nextComicLink; ?>"><?php echo $nextComicText; ?></a></li>
</ul>

<form class="comicdropdown-form" action="" method="post">
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
<option value="" selected="selected"><?php echo $comicDropdownHeader; ?></option> 
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