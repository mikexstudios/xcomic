<?php
/**
Xcomic

$Id$
*/

/*
define('IN_XCOMIC', true);

$xcomicRootPath='../';
*/
include_once('ComicDisplay.class.php');



class LatestComicDisplay extends ComicDisplay {

	function LatestComicDisplay() {
		
		$this->ComicDisplay();
		
		$this->getComicInfo($this->getLatestComicId());
		
	}
	
	function getLatestComicId() {
		global $xcomicDb, $message;
		
		$sql = 'SELECT MAX(cid)
			FROM '.XCOMIC_COMICS_TABLE.';';
			
		//Make the changes happen
		if(!($result = $xcomicDb->sql_query($sql)))
		{
			$message->error('Unable to get latest comic id');
		}
		
		//Get the result (only one)
		$row = $xcomicDb->sql_fetchrow($result);
		
		//Return latest comic id
		return $row[0];
	}
	
}

/*
//Testing ComicDisplay
$x = new LatestComicDisplay();
//$x->getComicInfo(1);
echo $x->getFilename();
*/

?>