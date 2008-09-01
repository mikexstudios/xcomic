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
    var $ignoredate;

	function NewsListing(&$dbc, $ignoredate = false)
	{
        if (DB::isConnection($dbc)) {
            $this->dbc =& $dbc;
        }
        $this->ignoredate = $ignoredate;
	}
	
	function queryNewsInfo()
	{
		global $db, $message;
		
		$sql = '
		    SELECT
		        id, title, date, username, content
			FROM '.XCOMIC_NEWS_TABLE.
			(!$this->ignoredate ? (' WHERE date <= '.time()) : '');
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
