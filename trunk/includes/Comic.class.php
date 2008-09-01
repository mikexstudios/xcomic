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


class Comic
{
	//var $comicsDir;
	var $comicInfo; //Latest comic info
	var $cid; //Holds current comic id
	var $dbc;
	var $ignoredate;

	function Comic(&$dbc, $inCid = null, $ignoredate = false)
	{
		global $xcomicRootPath;
        if (DB::isConnection($dbc)) {
            $this->dbc =& $dbc;
        }
        else {
        	echo 'No DB connection!';
        }
		//Set comics directory
		//$this->comicsDir = $xcomicRootPath.COMICS_DIR;
		
		$this->ignoredate = $ignoredate;

		if ($inCid != null) {
			$this->setCurrentComicId($inCid);
		}
		else {
			//Default $inCid to latest cid. Removes need for separate
			//redundant LatestComicDisplay class.
			$this->setCurrentComicId($this->getLatestComicId());
		}

	}

	function queryComicInfo($inCid, $idOperator = '=', $inOrderBy = '')
	{
		//If the latest comic id is also null, that indicates no comic 
		//postings. Therefore, we can skip querying the comic and set
		//the comicInfo array to empty values.
		if(empty($inCid))
		{
			//Kind of a crude hack here since we have to manually specify
			//each of the hashes to empty values. -mX
			$this->comicInfo = array();
			$this->comicInfo['cid'] = '';
			$this->comicInfo['filename'] = '';
			$this->comicInfo['title'] = '';
			$this->comicInfo['newsitem'] = '';
			$this->comicInfo['date'] = '';
			return;
		}
		
		global $db, $message;

		$sql = '
			SELECT cid, title, filename, date
			FROM '.XCOMIC_COMICS_TABLE."
			WHERE cid $idOperator $inCid
			".(!$this->ignoredate ? ("AND date <= ".time()) : '')."
			ORDER BY cid $inOrderBy
			LIMIT 1";
		$result = $this->dbc->getRow($sql);

		if (PEAR::isError($result)) {
			echo 'Unable to obtain comic information.';
		}
		
		return $result;
	}

	function getComicInfo($inCid)
	{
		//Set this to current comic id
		//$this->cid = $inCid;

		$this->comicInfo = $this->queryComicInfo($inCid, '=', '');
	}

	function getLatestComicId()
	{
		global $message;

		# [1074866] Bug with index fixed by Tom Parkison (trparky@toms-world.org)
		# Problem was pinpointed to MAX() SQL call requiring an 'AS cid' after MAX().
		$sql = 'SELECT MAX(cid) AS cid
			FROM '.XCOMIC_COMICS_TABLE.
            (!$this->ignoredate ? (' WHERE date <= '.time()) : '');

	    $result = $this->dbc->getOne($sql);
		//Make the changes happen
		if (PEAR::isError($result)) {
			echo 'No comic found!';
		}

		//Return latest comic id
		return $result;
	}

	function setCurrentComicId($inCid)
	{
		$this->cid = $inCid;
		//Update comic information for this new cid
		$this->getComicInfo($this->cid);
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
