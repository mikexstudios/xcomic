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

include_once 'News.class.php';
include_once 'Comic.class.php';

class ComicAssociatedNews extends News
{
	
	var $comic;
	var $associatedNewsIds; //Array of news ids associated with the currentCid
	var $curAssociatedNewsId=0; //Focused nid
	var $currentCid;
	
	function ComicAssociatedNews(&$dbc, $inCid='')
	{
		$this->News($dbc);
		
		if(!empty($inCid))
		{
			//Create Comic object
			$this->comic = new Comic($dbc, $inCid);
			$this->associateNews();
		}
		
	}
	
	function setCid($inCid) {
		$this->currentCid = $inCid;
		$this->comic->setCurrentComicId($inCid);
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
			$this->setCid($this->comic->prevId());
			$this->getAssociatedNewsIds();
			$this->getNewsInfo($this->getNextAssociatedNewsId());
		}	
	}
	
	function getAssociatedNewsIds($order='ASC') {
		global $xcomicDb, $message;
		
		//comicDate holds *nix timestamp
		$comicDate = $this->comic->getDate();
		
		//Get the news id associated with a comic
		$sql = 'SELECT id
			FROM '.XCOMIC_NEWS_TABLE."
			WHERE date >= '$comicDate'";
		
		//Get the date of the next comic (if exists)
		$nextCid = $this->comic->nextId();
		if($nextCid) //A next comic exists
		{
			$this->comic->setCurrentComicId($nextCid);
			$this->comic->getComicInfo($nextCid);
			$nextComicDate = $this->comic->getDate();
			
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

		if(isset($this->associatedNewsIds[$this->curAssociatedNewsId]))
		{
			return $this->associatedNewsIds[$this->curAssociatedNewsId++];
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
//Testing 

*/
?>