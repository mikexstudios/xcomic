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


class ComicStatusDisplay {
	
	var $comicStatus;
	
	function ComicStatusDisplay() {
		$this->getComicStatusInfo();
	}
	
	function queryComicStatusInfo() {
		global $xcomicDb, $message;
		
		$sql = 'SELECT nextdate, percentstatus, comments
			FROM '.XCOMIC_NEXTCOMICSTATUS_TABLE.' 
			WHERE ncid=0';
		
		if(!($result = $xcomicDb->sql_query($sql)))
		{
			$message->error('Unable to get comic status info');
		}
		
		return $xcomicDb->sql_fetchrow($result);
	}
	
	function getComicStatusInfo() {
		
		$this->comicStatus = $this->queryComicStatusInfo();
		
	}
	
	function getComment() {
	
		return $this->comicStatus['comments'];
		
	}
	
	function getPercentStatus() {
	
		return $this->comicStatus['percentstatus'];
		
	}
	
	function getNextDate() {
	
		return $this->comicStatus['nextdate'];
		
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