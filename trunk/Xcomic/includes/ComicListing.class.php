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

class ComicListing {
	
	var $allComicInfo; //Holds all comic info records
	
	function ComicListing() {
		
	}
	
	function queryComicInfo() {
		global $xcomicDb, $message;
		
		$sql = 'SELECT cid, title, filename, date
			FROM '.XCOMIC_COMICS_TABLE;
		
		if(!($result = $xcomicDb->sql_query($sql)))
		{
			$message->error('Unable to get comic information');
		}
		
		//Place all returned queries in an array
		for($rowCount=0; $rowCount < $xcomicDb->sql_numrows($result); $rowCount++)
		{
			$rows[] = $xcomicDb->sql_fetchrow($result);
		}
		
		return $rows;
	}
	
	function getComicList() {
		
		$this->allComicInfo = $this->queryComicInfo();
		return $this->allComicInfo;
		
	}
	
	//Returns num of rows in array. Useful for 
	//using in a for loop.
	function numComics() {
	
		return count($this->allComicInfo);
		
	}

	
}

/*
//Testing ComicDisplay
$x = new ComicDisplay();
//echo $x->getComicId();
$x->getComicInfo(1);
echo $x->getFilename();
if($x->nextId()==false)
	echo "false";
else
	echo $x->nextId();
	
if($x->prevId()==false)
	echo "false";
else
	echo $x->prevId();
*/


?>