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
	
	function LatestNewsDisplay(&$dbc)
	{
		$this->NewsDisplay($dbc);
		$this->getNewsInfo($this->getLatestNewsId());
	}
	
	function getLatestNewsId()
	{
		global $message;
		
		# [1074866] Bug with index fixed by Tom Parkison (trparky@toms-world.org)
		# Problem was pinpointed to MAX() SQL call requiring an 'AS id' after MAX().
		$sql = 'SELECT MAX(id) AS id
			FROM '.XCOMIC_NEWS_TABLE;
			
		$result = $this->dbc->getOne($sql);
		if (PEAR::isError($result)) {
			echo 'No news found!';
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