<?php
/**
Xcomic

$Id$
*/

/*
define('IN_XCOMIC', true);

$xcomicRootPath='../';
include_once $xcomicRootPath.'initialize.php';
*/

include_once 'NewsDisplay.class.php';
include_once 'ComicDisplay.class.php';

class ComicAssociatedNewsDisplay extends NewsDisplay
{
	
	var $comicDisplay;
	
	function ComicAssociatedNewsDisplay($inCid, $inCategory = 'default')
	{

		$this->NewsDisplay($inCategory);
		
		//Create ComicDisplay object
		$this->comicDisplay = new ComicDisplay($inCid);
		
		$this->getNewsInfo($this->getAssociatedNewsId(), $inCategory);
		
	}
	
	function getAssociatedNewsId()
	{
		global $db, $message;
		
		//comicDate holds *nix timestamp
		$comicDate = $this->comicDisplay->getDate();
		
		//Get the latest news id at the date of
		//the comic or earlier. We assume here that
		//the higher the id, the more recent it is.
		$sql = 'SELECT MAX(id)
			FROM '.XCOMIC_NEWS_TABLE."
			WHERE date <= '$comicDate'";
			
		$result = $db->getOne($sql);
		if (PEAR::isError($result)) {
			echo 'Unable to get the latest news id associated with the selected comic. SQL: '.$sql;
			exit;
		}
		
		//BELOW: QUICK FIXES FOR NEWS DISPLAY. THERE SHOULD BE A BETTER WAY
		//TO SYNC COMIC WITH NEWS

		//Fix for cid=1: $result would be empty so the next id wouldn't exist
		if (empty($result)) {
			$result = 1;
			 //Set the current news id to less than 0 so that it will search for news
			 //that is >= 0
			$this->setCurrentNewsId($result - 1);
			return $this->nextId();
		}
		
		//Since the comic is posted before the news is, we want to return the news
		//post after the comic. This isn't the best way to do this, but it's alright
		//for now.
		$this->setCurrentNewsId($result);
		if ($this->nextId() != false) {	
			return $this->nextId();
		} else {
			return $result;
		}
	}
	
}

/*
//Testing LatestNewsDisplay
$x = new ComicAssociatedNewsDisplay('1');
echo $x->getTitle();
echo $x->getContent();
*/
?>