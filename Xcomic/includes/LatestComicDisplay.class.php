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
		$this->getComicInfo($this->getLatestComicId());
	}
	
	function getLatestComicId()
	{
		global $message;
		
		$sql = '
		    SELECT MAX(cid)
			FROM '.XCOMIC_COMICS_TABLE.';';
	    $result = $this->dbc->getOne($sql);
		//Make the changes happen
		if (PEAR::isError($result)) {
			#$message->error('Unable to get latest comic id');
            die('Unable to get latest comic id');
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