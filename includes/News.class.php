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


class News
{	
	var $newsInfo; //Latest news info
	var $id; //Holds current Id
	var $dbc;
	var $ignoredate;
	
	function News(&$dbc, $inId = null, $ignoredate = false)
	{
	    $this->dbc =& $dbc;
	    $this->ignoredate = $ignoredate;
		if ($inId != null) {
			$this->id = $inId;
			$this->getNewsInfo($inId);
		}
	}
	
	function queryNewsInfo($inId, $idOperator = '=', $inOrderBy = '')
	{
		global $message;
		
		//If the id is empty (most likely because the first posted
		//comic does not have any news associated to it), then skip
		//querying the news info and set newsInfo values to empty.
		if(empty($inId))
		{
			//Kind of a crude hack here since we have to manually specify
			//each of the hashes to empty values. -mX
			$this->newsInfo = array();
			$this->newsInfo['id'] = '';
			$this->newsInfo['title'] = '';
			$this->newsInfo['date'] = '';
			$this->newsInfo['username'] = '';
			$this->newsInfo['content'] = '';
			return;
		}
		
		$sql = '
			SELECT id, title, date, username, content
			FROM '.XCOMIC_NEWS_TABLE." 
			WHERE id $idOperator $inId
			".(!$this->ignoredate ? ("AND date <= ".time()) : '')."
			ORDER BY id $inOrderBy";
		$result = $this->dbc->getRow($sql);
		
		if (PEAR::isError($result)) {
			echo 'Unable to obtain news.';
		}
		
		
		return $result;		
	}
	
	function getNewsInfo($inId)
	{
		//Set this to current comic id
		$this->id = $inId;
		$this->newsInfo = $this->queryNewsInfo($this->id);
	}
	
	function setCurrentNewsId($inId)
	{
		$this->id = $inId;
	}
	
	function nextId($inCategory = 'default')
	{
		$next = $this->queryNewsInfo($this->id, '>', 'ASC');
		$nextId = $next['id'];
		
		if (empty($nextId)) {
			//There is no next Id
			return false;
		}
        return $nextId;
	}
	
	function prevId($inCategory = 'default')
	{
		$prev = $this->queryComicInfo($this->id, '<', 'DESC');
		$prevId = $prev['id'];
		
		if (empty($prevId)) {
			//There is no prev Id
			return false;
		}
        return $prevId;
	}
	
	function getId()
	{
		return $this->newsInfo['id'];
	}
	
	function getTitle()
	{
		return $this->newsInfo['title'];
	}
	
	function getDate()
	{
		return $this->newsInfo['date'];
	}
	
	function getUsername()
	{
		return $this->newsInfo['username'];
	}
	
	function getContent()
	{
		return $this->newsInfo['content'];
	}
	
}

/*
//Testing NewsDisplay
$x = new NewsDisplay();
$x->getNewsInfo(3);
echo $x->getTitle();
echo $x->getContent();
*/
?>
