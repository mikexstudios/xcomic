<?php
/**
Xcomic

$Id$
*/

define('IN_XCOMIC', true);

/*
$xcomicRootPath='../';
include_once($xcomicRootPath.'initialize.php');
*/

class EditComic {
	
	var $cid;
	var $comicDir;
	
	function EditComic($inCid) {
		global $message, $xcomicRootPath;
		
		$this->comicDir = $xcomicRootPath.COMICS_DIR;
		//Make sure directory exists
		if (!is_dir($this->comicDir)) {
			$message->error($this->comicDir.' directory doesn\'t exist');
		}
		
		$this->cid = $inCid;
		
		/*
		$inNewFile = $inComicFile;
		//echo $inComicFile['tmp_name'];
		$this->title = $inTitle;
		*/

	}
	
	function changeTitle($inNewTitle) {
		global $xcomicDb, $message;		
				
		$sql='UPDATE '.XCOMIC_COMICS_TABLE."
			SET title = '$inNewTitle'
			WHERE cid = $this->cid";
		
		//Make the changes happen
		if ($result = $xcomicDb->sql_query($sql))
		{
			//Expect only one match so there is no need to loop.
			//$xcmsDb->sql_fetchrow($result);
		}
		else
		{
			$message->error('Unable to change comic title. SQL: '.$sql);
		}
	}
	
	function changeFile($inNewFile) {
		global $xcomicRootPath, $classEx, $xcomicDb, $message;
		
		//Create a PostComic object and save the new file through that object
		include_once($xcomicRootPath.'admin/classes/PostComic.'.$classEx);
		$postComic = new PostComic($inNewFile);
		if($postComic->saveFile()) //If successful
		{
			//Get filename for current cid------
			include_once($xcomicRootPath.'includes/ComicDisplay.'.$classEx);
			$comicInformation = new ComicDisplay($this->cid);
			$currentComicFilename = $comicInformation->getFilename();
			//----------------------------------
			
			//Update database with new filename
			$newComicFilename = $inNewFile['name'];
			
			$sql='UPDATE '.XCOMIC_COMICS_TABLE."
				SET filename = '$newComicFilename'
				WHERE cid = $this->cid";
			
			//Make the changes happen
			if ($result = $xcomicDb->sql_query($sql))
			{
				//Expect only one match so there is no need to loop.
				//$xcmsDb->sql_fetchrow($result);
			}
			else
			{
				$message->error('Unable to change comic filename');
			}
			
			//Delete image for current cid
			if(!@unlink($this->comicDir.'/'.$currentComicFilename))
			{
				//Don't die on error
				//$message->error($this->comicDir.'/'.$currentComicFilename.' could not be deleted!');
			}
			
			return true; //Successful
		}
		else
		{
			return false; //Failed
		}
	}
	
	//TO DO: Add change of date/time

}

/*
//Testing
$x = new EditNews("This is a test", "Title1", "left");
$x->sendToDatabase();
*/

?>