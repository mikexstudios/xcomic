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

class NewsListing {
	
	var $allNewsInfo; //Holds all News info records
	
	function NewsListing() {
		
	}
	
	function queryNewsInfo() {
		global $xcomicDb, $message;
		
		$sql = 'SELECT id, title, date, username, content
			FROM '.XCOMIC_NEWS_TABLE;
		
		if(!($result = $xcomicDb->sql_query($sql)))
		{
			$message->error('Unable to get news information. SQL: '.$sql);
		}
		
		//Place all returned queries in an array
		for($rowCount=0; $rowCount < $xcomicDb->sql_numrows($result); $rowCount++)
		{
			$rows[] = $xcomicDb->sql_fetchrow($result);
		}
		
		return $rows;
	}
	
	function getNewsList() {
		
		$this->allNewsInfo = $this->queryNewsInfo();
		return $this->allNewsInfo;
		
	}
	
	//Returns num of rows in array. Useful for 
	//using in a for loop.
	function numNews() {
	
		return count($this->allNewsInfo);
		
	}

	
}

/*
//Testing NewsListing
$x = new NewsListing();
*/


?>