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

class EditNews {
	
	var $nid;
	
	function EditNews($inNid) {
		$this->nid = $inNid;
	}
	
	function delete() {
		global $xcomicDb, $message;
		
		$sql='DELETE FROM '.XCOMIC_NEWS_TABLE."
			WHERE id = $this->nid";
		
		//Make the changes happen
		if (!($result = $xcomicDb->sql_query($sql)))
		{
			$message->error('Unable to delete news entry.');
		}
	}
	
	function changeTitle($inNewTitle) {
		global $xcomicDb, $message;
		
		$sql='UPDATE '.XCOMIC_NEWS_TABLE."
			SET title = '$inNewTitle'
			WHERE id = $this->nid";
		
		//Make the changes happen
		if (!($result = $xcomicDb->sql_query($sql)))
		{
			$message->error('Unable to change news title. SQL: '.$sql);
		}
	}
	
	function changeContent($inNewContent) {
		global $xcomicDb, $message;
		
		$sql='UPDATE '.XCOMIC_NEWS_TABLE."
			SET content = '$inNewContent'
			WHERE id = $this->nid";
		
		//Make the changes happen
		if (!($result = $xcomicDb->sql_query($sql)))
		{
			$message->error('Unable to change news content. SQL: '.$sql);
		}
	}
}

/*
//Testing
$x = new PostNews("This is a test", "Title1", "left");
$x->sendToDatabase();
*/

?>