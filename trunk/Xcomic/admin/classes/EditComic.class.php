<?php
/**
Xcomic

$Id$
*/

class EditComic
{	
	var $cid;
	var $comicDir;
	var $dbc;
	
	function EditComic(&$dbc, $inCid)
	{
		global $message, $xcomicRootPath;
        if (DB::isConnection($dbc)) {
            $this->dbc =& $dbc;
        }
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
	
	function setCid($inCid)
	{
		$this->cid = $inCid;	
	}
	
	function changeTitle($inNewTitle)
	{
		global $message;		
				
		$sql = '
		    UPDATE '.XCOMIC_COMICS_TABLE.'
			SET title = '.$this->dbc->quoteSmart($inNewTitle).'
			WHERE cid = '.$this->cid;
		$result = $this->dbc->query($sql);
		//Make the changes happen
		if (PEAR::isError($result)) {
			$message->error('Unable to change comic title. SQL: '.$sql);
		}
	}

	function changeDate($inNewDate)
	{
		global $message;		
				
		$sql = '
		    UPDATE '.XCOMIC_COMICS_TABLE.'
			SET date = '.$this->dbc->quoteSmart($inNewDate).'
			WHERE cid = '.$this->cid;
		$result = $this->dbc->query($sql);
		//Make the changes happen
		if (PEAR::isError($result)) {
			$message->error('Unable to change comic date. SQL: '.$sql);
		}
	}

	function changeFile($inNewFile)
	{
		global $xcomicRootPath, $classEx, $message;
		
		//Create a PostComic object and save the new file through that object
		//include_once $xcomicRootPath.'admin/classes/Comic.'.$classEx;
		include_once './classes/Comic.'.$classEx;
		$postComic = new Comic($this->dbc, $inNewFile);
		if ($postComic->saveFile()) { //If successful
			//Delete image for current cid first before
			//the new filename is written to database.
			$this->deleteFile();
		
			//Update database with new filename
			$newComicFilename = $inNewFile['name'];
			
			$sql = '
			    UPDATE '.XCOMIC_COMICS_TABLE.'
				SET filename = '.$this->dbc->quoteSmart($newComicFilename).'
				WHERE cid = '.$this->cid;
			$result = $this->dbc->query($sql);
			//Make the changes happen
			if (PEAR::isError($result)) {
				$message->error('Unable to change comic filename');
			}
			
			return true; //Successful
		}
		return false; //Failed
	}
	
	//TO DO: Add change of date/time
	
	function deleteFile()
	{
		global $xcomicRootPath, $classEx, $message;
		
		//Get filename for current cid------
		include_once $xcomicRootPath.'includes/ComicDisplay.'.$classEx;
		$comicInformation = new ComicDisplay($this->dbc, $this->cid);
		$currentComicFilename = $comicInformation->getFilename();
		//----------------------------------
		
		//Delete image for current cid
		if (!@unlink($this->comicDir.'/'.$currentComicFilename)) {
			//Don't die on error
			//$message->error($this->comicDir.'/'.$currentComicFilename.' could not be deleted!');
		}
		
	}
	
	function deleteComic()
	{
		global $message;
		
		//Delete file first
		$this->deleteFile();
		
		//Delete from database
		$sql = '
		    DELETE FROM '.XCOMIC_COMICS_TABLE."
			WHERE cid = $this->cid";
		$result = $this->dbc->query($sql);
		//Make the changes happen
		if (PEAR::isError($result)) {
			$message->error('Unable to delete comic!');
		}
		
	}

}

/*
//Testing
$x = new EditNews("This is a test", "Title1", "left");
$x->sendToDatabase();
*/

?>