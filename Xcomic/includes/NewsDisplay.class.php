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


class NewsDisplay
{	
	var $newsInfo; //Latest news info
	var $id; //Holds current Id
	
	function NewsDisplay($inId = null)
	{
		if ($inId != null) {
			$this->id = $inId;
			$this->getNewsInfo($inId);
		}
	}
	
	function queryNewsInfo($inId, $idOperator = '=', $inOrderBy = '')
	{
		global $db, $message;
		
		$sql = 'SELECT id, title, date, username, content
			FROM '.XCOMIC_NEWS_TABLE." 
			WHERE id $idOperator $inId
			ORDER BY id $inOrderBy";
		$result = $db->getRow($sql);
		if (PEAR::isError($result)) {
			echo 'Unable to get latest news info. SQL: '.$sql;
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
		} else {
			return $nextId;
		}
	}
	
	function prevId($inCategory = 'default')
	{
		$prev = $this->queryComicInfo($this->id, '<', 'DESC');
		$prevId = $prev['id'];
		
		if (empty($prevId)) {
			//There is no prev Id
			return false;
		} else {
			return $prevId;
		}
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