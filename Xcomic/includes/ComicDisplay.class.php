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


class ComicDisplay {
	
	//var $comicsDir;
	var $comicInfo; //Latest comic info
	var $cid; //Holds current comic id
	
	function ComicDisplay($inCid=NULL) {
		
		global $xcomicRootPath;
		
		//Set comics directory
		//$this->comicsDir = $xcomicRootPath.COMICS_DIR;
		
		if($inCid!=NULL)
		{
			$this->cid = $inCid;
			$this->getComicInfo($inCid);
		}
	
	}
	
	function queryComicInfo($inCid, $idOperator='=', $inOrderBy='') {
		global $xcomicDb, $message;
		
		$sql = 'SELECT cid, title, filename, date
			FROM '.XCOMIC_COMICS_TABLE." 
			WHERE cid $idOperator $inCid
			ORDER BY cid $inOrderBy";
		
		if(!($result = $xcomicDb->sql_query($sql)))
		{
			echo 'Unable to get latest comic info';
		}
		
		return $xcomicDb->sql_fetchrow($result);
	}
	
	function getComicInfo($inCid) {
		
		//Set this to current comic id
		$this->cid = $inCid;
		
		$this->comicInfo = $this->queryComicInfo($inCid);
		
	}
	
	function setCurrentComicId($inCid) {
	
		$this->cid = $inCid;
			
	}
	
	function nextId() {
		$next = $this->queryComicInfo($this->cid, '>', 'ASC');
		$nextId = $next['cid'];
		
		if(empty($nextId))
		{
			//There is no next Id
			return false;
		}
		else
		{
			return $nextId;
		}
	}
	
	function prevId() {
		$prev = $this->queryComicInfo($this->cid, '<', 'DESC');
		$prevId = $prev['cid'];
		
		if(empty($prevId))
		{
			//There is no prev Id
			return false;
		}
		else
		{
			return $prevId;
		}
	}
	
	function getId() {
		
		return $this->comicInfo['cid'];
		
	}
	
	function getFilename() {
	
		return $this->comicInfo['filename'];
		
	}
	
	function getTitle() {
	
		return $this->comicInfo['title'];
		
	}
	
	function getNewsItem() {
	
		return $this->comicInfo['newsitem'];
		
	}
	
	function getDate() {
	
		return $this->comicInfo['date'];
		
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