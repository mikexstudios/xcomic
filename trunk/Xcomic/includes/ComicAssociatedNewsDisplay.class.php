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

include_once('NewsDisplay.class.php');
include_once('ComicDisplay.class.php');


class ComicAssociatedNewsDisplay extends NewsDisplay {
	
	var $comicDisplay;
	
	function ComicAssociatedNewsDisplay($inCid, $inCategory='default') {

		$this->NewsDisplay($inCategory);
		
		//Create ComicDisplay object
		$this->comicDisplay = new ComicDisplay($inCid);
		
		$this->getNewsInfo($this->getAssociatedNewsId(), $inCategory);
		
	}
	
	function getAssociatedNewsId() {
		global $xcomicDb, $message;
		
		//comicDate holds *nix timestamp
		$comicDate = $this->comicDisplay->getDate();
		
		//Get the latest news id at the date of
		//the comic or earlier. We assume here that
		//the higher the id, the more recent it is.
		$sql = 'SELECT MAX(id)
			FROM '.XCOMIC_NEWS_TABLE."
			WHERE date <= '$comicDate'";
			
		//Make the changes happen
		if(!($result = $xcomicDb->sql_query($sql)))
		{
			echo 'Unable to get the latest news id associated with the selected comic. SQL: '.$sql;
			exit;
		}
		
		//Get the result (only one)
		$row = $xcomicDb->sql_fetchrow($result);
		
		//BELOW: QUICK FIXES FOR NEWS DISPLAY. THERE SHOULD BE A BETTER WAY
		//TO SYNC COMIC WITH NEWS

		//Fix for cid=1: $row[0] would be empty so the next id wouldn't exist
		if(empty($row[0]))
		{
			$row[0] = 1;
			 //Set the current news id to less than 0 so that it will search for news
			 //that is >= 0
			$this->setCurrentNewsId($row[0]-1);
			return $this->nextId();
		}
		
		//Since the comic is posted before the news is, we want to return the news
		//post after the comic. This isn't the best way to do this, but it's alright
		//for now.
		$this->setCurrentNewsId($row[0]);
		if($this->nextId()!=false)
		{	
			return $this->nextId();
		}
		else
		{
			return $row[0];
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