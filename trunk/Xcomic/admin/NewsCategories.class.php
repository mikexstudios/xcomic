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


class NewsCategories {
	
	var $catNames = array();

	function NewsCategories() {
		
	}
	
	function queryCategoryInfo() {
		global $xcomicDb, $message;
		
		$sql = 'SELECT catid, catname
			FROM '.XCOMIC_NEWSCATEGORY_TABLE.';';
		
		if(!($result = $xcomicDb->sql_query($sql)))
		{
			$message->error('Unable to get latest category info');
		}
		
		//NOTE: This return the result!
		return $result; 
	}

	function getCategoryNames() {
		global $xcomicDb;
		
		$result = $this->queryCategoryInfo();
		
		while ( $row = $xcomicDb->sql_fetchrow($result))
		{
			//Place category names in an array
			$this->catNames[] = $row['catname'];
		}
		
		
		/*
		//Loop through array
		reset($catNames);
		while(list($key, $value) = each($catNames))
		{
			echo $key.' '.$value;
		}
		*/
		
		return $this->catNames;
		
	}
}

/*
//Testing
$x = new NewsCategories();
$catNames = $x->getCategoryNames();
		
//Loop through array
reset($catNames);
while(list($key, $value) = each($catNames))
{
	echo $key.' '.$value;

}
*/	


?>