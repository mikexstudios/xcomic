<?php
/**
Xcomic

$Id$
*/

/*
define('IN_XCOMIC', true);

$xcomicRootPath='../';
*/
include_once 'ComicDisplay.class.php';



class LatestComicDisplay extends ComicDisplay
{

	function LatestComicDisplay(&$dbc)
	{
		$this->ComicDisplay($dbc);
		$this->setCurrentComicId($this->getLatestComicId());
	}
	
	function getLatestComicId()
	{
		global $message;
		
		# [1074866] Bug with index fixed by Tom Parkison (trparky@toms-world.org)
		# Problem was pinpointed to MAX() SQL call requiring an 'AS cid' after MAX().
		$sql = 'SELECT MAX(cid) AS cid
			FROM '.XCOMIC_COMICS_TABLE;
			
	    $result = $this->dbc->getOne($sql);
		//Make the changes happen
		if (PEAR::isError($result)) {
			echo 'No comic found!';
		}
		
		//Return latest comic id
		return $result;
	}
	
}

/*
//Testing ComicDisplay
$x = new LatestComicDisplay();
//$x->getComicInfo(1);
echo $x->getFilename();
*/
?>