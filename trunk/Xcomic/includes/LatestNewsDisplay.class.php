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
include_once 'NewsDisplay.class.php';



class LatestNewsDisplay extends NewsDisplay
{
	
	function LatestNewsDisplay()
	{
		$this->NewsDisplay();
		$this->getNewsInfo($this->getLatestNewsId());
	}
	
	function getLatestNewsId()
	{
		global $db, $message;
		
		$sql = 'SELECT MAX(id)
			FROM '.XCOMIC_NEWS_TABLE;
		$result = $db->getOne($sql);
		if (PEAR::isError($result)) {
			echo 'Unable to get latest news id. SQL: '.$sql;
		}

		//Return latest news id
		return $result;
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