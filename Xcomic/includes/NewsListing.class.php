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

class NewsListing
{
	
	var $allNewsInfo; //Holds all News info records
    var $dbc;

	function NewsListing(&$dbc)
	{
        if (DB::isConnection($dbc)) {
            $this->dbc =& $dbc;
        }
	}
	
	function queryNewsInfo()
	{
		global $db, $message;
		
		$sql = '
		    SELECT
		        id, title, date, username, content
			FROM '.XCOMIC_NEWS_TABLE;
		$result = $this->dbc->getAll($sql);
		if (PEAR::isError($result)) {
			#$message->error('Unable to get news information. SQL: '.$sql);
			die('Unable to get news information. SQL: '.$sql);
		}
		
		return $result;
	}
	
	function getNewsList()
	{
		$this->allNewsInfo = $this->queryNewsInfo();
		return $this->allNewsInfo;
	}
	
	//Returns num of rows in array. Useful for 
	//using in a for loop.
	function numNews()
	{
		return count($this->allNewsInfo);
	}
}

/*
//Testing NewsListing
$x = new NewsListing();
*/
?>