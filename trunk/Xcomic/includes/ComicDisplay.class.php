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


class ComicDisplay
{	
	//var $comicsDir;
	var $comicInfo; //Latest comic info
	var $cid; //Holds current comic id
	var $dbc;
	
	function ComicDisplay(&$dbc, $inCid = null)
	{	
		global $xcomicRootPath;
        if (DB::isConnection($dbc)) {
            $this->dbc =& $dbc;
        }
		//Set comics directory
		//$this->comicsDir = $xcomicRootPath.COMICS_DIR;
		
		if ($inCid != null) {
			$this->cid = $inCid;
			$this->getComicInfo($inCid);
		}
	
	}
	
	function queryComicInfo($inCid, $idOperator = '=', $inOrderBy = '')
	{
		global $db, $message;
		
		$sql = '
			SELECT cid, title, filename, date
			FROM '.XCOMIC_COMICS_TABLE." 
			WHERE cid $idOperator $inCid
			ORDER BY cid $inOrderBy
			";
		$result = $this->dbc->getRow($sql);
		
		//Suppress error message since legitimate calls to queryComicInfo
		//can return no results which are interpreted as an error. There
		//should be a better workaround for this however.
		//Note: This only happens for when cid is 1 or the latest.
		/* 
		if (PEAR::isError($result)) {
			echo 'Unable to obtain comic information.';
		}
		*/
		
		return $result;
	}
	
	function getComicInfo($inCid)
	{	
		//Set this to current comic id
		$this->cid = $inCid;
		
		$this->comicInfo = $this->queryComicInfo($inCid);	
	}
	
	function setCurrentComicId($inCid)
	{
		$this->cid = $inCid;		
	}
	
	function nextId()
	{
		$next = $this->queryComicInfo($this->cid, '>', 'ASC');
		$nextId = $next['cid'];
		
		if (empty($nextId)) {
			//There is no next Id
			return false;
		} else {
			return $nextId;
		}
	}
	
	function prevId()
	{
		$prev = $this->queryComicInfo($this->cid, '<', 'DESC');
		$prevId = $prev['cid'];
		
		if (empty($prevId)) {
			//There is no prev Id
			return false;
		} else {
			return $prevId;
		}
	}
	
	function getId()
	{
		return $this->comicInfo['cid'];
	}
	
	function getFilename()
	{
		return $this->comicInfo['filename'];
	}
	
	function getTitle()
	{
		return $this->comicInfo['title'];
	}
	
	function getNewsItem()
	{
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