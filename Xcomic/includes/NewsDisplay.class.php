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


class NewsDisplay {
	
	var $newsInfo; //Latest news info
	var $id; //Holds current Id
	var $category;
	
	function NewsDisplay($inCategory='default', $inId=NULL) {
		
		$this->category = $inCategory;
		
		if($inId!=NULL)
		{
			$this->id = $inId;
			$this->getComicInfo($inId);
		}
	
	}
	
	function queryNewsInfo($inId, $inCategory, $idOperator='=') {
		global $xcomicDb, $message;
		
		$sql = 'SELECT id, title, date, username, content
			FROM '.XCOMIC_NEWS_TABLE." 
			WHERE id $idOperator '$inId'";
		
		if(!($result = $xcomicDb->sql_query($sql)))
		{
			echo 'Unable to get latest news info. SQL: '.$sql;
		}
		
		return $xcomicDb->sql_fetchrow($result);		
	}
	
	function getNewsInfo($inId, $inCategory='default') {
		
		//Set this to current comic id
		$this->id = $inId;
		
		//Set to current category
		$this->category = $inCategory;
		
		$this->newsInfo = $this->queryNewsInfo($inId, $inCategory);
		
	}
	
	function setCurrentNewsId($inId) {
	
		$this->id = $inId;
			
	}
	
	function nextId($inCategory='default') {
		
		$next = $this->queryNewsInfo($this->id, $this->category, '>');
		
		$nextId = $next['id'];
		
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
	
	function prevId($inCategory='default') {
		$prev = $this->queryComicInfo($this->id, $this->category, '<');
		$prevId = $prev['id'];
		
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
		
		return $this->newsInfo['id'];
		
	}
	
	function getTitle() {
	
		return $this->newsInfo['title'];
		
	}
	
	function getDate() {
	
		return $this->newsInfo['date'];
		
	}
	
	function getUsername() {
	
		return $this->newsInfo['username'];
		
	}
	
	function getContent() {
	
		return $this->newsInfo['content'];
		
	}
	
	function getCategory() {
	
		return $this->newsInfo['category'];
		
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