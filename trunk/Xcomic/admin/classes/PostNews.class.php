<?php
/**
Xcomic

postNews - posts left or right rant

$Id$
*/

class PostNews {
	
	var $title;
	var $content;
	var $username;
	
	function PostNews($inContent, $inTitle, $inUsername) {
		$this->content = $inContent;
		$this->title = $inTitle;
		$this->username = $inUsername;
	}
	
	function sendToDatabase() {
		global $xcomicDb, $message;
		
		$sql='INSERT INTO '.XCOMIC_NEWS_TABLE." (title , date, username, content)
			VALUES ( 
				'$this->title', 
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
			$message->error('Unable to add new news.');
		}
	}
}

/*
//Testing
$x = new PostNews("This is a test", "Title1", "left");
$x->sendToDatabase();
*/

?>