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
		global $db, $message;
		
		$sql = 'SELECT cid, title, filename, date
			FROM '.XCOMIC_COMICS_TABLE;
		$result = $db->getAll($sql);
		if (PEAR::isError($result)) {
			#$message->error('Unable to get comic information');
            die('Unable to get comic information');
		}

		return $result;
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