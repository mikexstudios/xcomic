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
	var $associatedNewsIds; //Array of news ids associated with the currentCid
	var $curAssociatedNewsId=0; //Focused nid
	var $currentCid;
	
	function ComicAssociatedNewsDisplay(&$dbc, $inCid='')
	{
		$this->NewsDisplay($dbc);
		
		if(!empty($inCid))
		{
			//Create ComicDisplay object
			$this->comicDisplay = new ComicDisplay($dbc, $inCid);
			$this->associateNews();
		}
		
	}
	
	function setCid($inCid) {
		$this->currentCid = $inCid;
		$this->comicDisplay->setCurrentComicId($inCid);
	}
	
	function associateNews() {
		$this->getAssociatedNewsIds();
		
		//If next news id does not exist, display the last
		//most recent news. This replaces the LatestNewsDisplay class
		$nextNid = $this->getNextAssociatedNewsId();
		if($nextNid)
		{
			$this->getNewsInfo($nextNid);
		}
		else
		{
			//Set to previous comic id
			$this->setCid($this->comicDisplay->prevId());
			$this->getAssociatedNewsIds();
			$this->getNewsInfo($this->getNextAssociatedNewsId());
		}	
	}
	
	function getAssociatedNewsIds($order='ASC') {
		global $xcomicDb, $message;
		
		//comicDate holds *nix timestamp
		$comicDate = $this->comicDisplay->getDate();
		
		//Get the news id associated with a comic
		$sql = 'SELECT id
			FROM '.XCOMIC_NEWS_TABLE."
			WHERE date >= '$comicDate'";
		
		//Get the date of the next comic (if exists)
		$nextCid = $this->comicDisplay->nextId();
		if($nextCid) //A next comic exists
		{
			$this->comicDisplay->setCurrentComicId($nextCid);
			$this->comicDisplay->getComicInfo($nextCid);
			$nextComicDate = $this->comicDisplay->getDate();
			
			//Add to SQL query
			$sql .= " AND date < '$nextComicDate'";
		}
		
		//Add sorting
		$sql .= " ORDER BY id $order"; //Should make this variable
		
		//Make the changes happen
		$result = $this->dbc->getAll($sql);
		if (PEAR::isError($result)) {
			echo 'Unable to get the news ids associated with the selected comic.';
			exit;
		}
		
		//Place associated id(s) in array. Note: Do NOT send null values into 
		//the associatedNewsIds array (since this will mess up getNextAssociatedNewsId()
		//Forunately, the foreach loop takes care of this.
		foreach ($result as $row) {
			$this->associatedNewsIds[] = $row['id'];
			//print_r($this->associatedNewsIds);
		}

	}
	
	function getNextAssociatedNewsId() {
		
		$nid = $this->associatedNewsIds[$this->curAssociatedNewsId];
		if(isset($nid))
		{
			$this->curAssociatedNewsId++;
			return $nid;
		}
		else
		{
			return false;
		}	
	}
	
	//Called by Xcomic.php in the news generating loop to that the next news
	//entry is in focus.
	function updateForNextId() {
		$nextId = $this->getNextAssociatedNewsId();
		if($nextId)
		{
			$this->getNewsInfo($nextId);
			return true;
		}
		else
		{
			return false;
		}
	}
	
}

/*
//Testing LatestNewsDisplay

*/
?>