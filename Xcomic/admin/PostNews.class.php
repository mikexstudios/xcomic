<?php
/**
Xcomic

postNews - posts left or right rant

$Id$
*/

define('IN_XCOMIC', true);

/*
$xcomicRootPath='../';
include_once($xcomicRootPath.'initialize.php');
*/

class PostNews {
	
	var $title;
	var $content;
	var $category; //left or right
	var $username;
	
	function PostNews($inContent, $inTitle, $inCategory='default', $inUsername) {
		$this->content = $inContent;
		$this->title = $inTitle;
		$this->category = $inCategory;
		$this->username = $inUsername;
	}
	
	function sendToDatabase() {
		global $xcomicDb;
		
		$sql='INSERT INTO '.XCOMIC_NEWS_TABLE." (title , category, date, username, content)
			VALUES ( 
				'$this->title', 
				'$this->category', 
				".time().", 
				'$this->username',
				'$this->content'
				)";
				
		//echo $sql;
		
		//Make the changes happen
		if ($result = $xcomicDb->sql_query($sql))
		{
			//Expect only one match so there is no need to loop.
			//$xcmsDb->sql_fetchrow($result);
		}
		else
		{
			echo "ERROR: Unable to add new news";
		}
	}
}

/*
//Testing
$x = new PostNews("This is a test", "Title1", "left");
$x->sendToDatabase();
*/

?>