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
include_once('NewsDisplay.class.php');



class LatestNewsDisplay extends NewsDisplay {
	
	function LatestNewsDisplay($inCategory='default') {

		$this->NewsDisplay($inCategory);
		
		$this->getNewsInfo($this->getLatestNewsId(), $inCategory);
		
	}
	
	function getLatestNewsId() {
		global $xcomicDb, $message;
		
		$sql = 'SELECT MAX(id)
			FROM '.XCOMIC_NEWS_TABLE.'
			WHERE category = "'.$this->category.'";';
			
		//Make the changes happen
		if(!($result = $xcomicDb->sql_query($sql)))
		{
			$message->error('Unable to get latest news id');
		}
		
		//Get the result (only one)
		$row = $xcomicDb->sql_fetchrow($result);
		
		//Return latest news id
		return $row[0];
	}
	
}

/*
//Testing LatestNewsDisplay
$x = new LatestNewsDisplay();
echo $x->getTitle();
echo $x->getContent();

$y = new LatestNewsDisplay('right');
echo $y->getTitle();
echo $y->getContent();
*/



?>