<?php
/**
Xcomic

$Id$
*/

define('IN_XCOMIC', true);

/*
$xcomicRootPath = '../';
include_once $xcomicRootPath.'initialize.php';
*/

class PostComic
{
	
	var $title;
	var $comicFile; //$_FILES type
	var $comicFilename;
	var $comicDir;
	
	function PostComic($inComicFile, $inTitle = null)
	{
		global $message, $xcomicRootPath;
		
		$this->comicDir = $xcomicRootPath.COMICS_DIR;
		
		$this->comicFile = $inComicFile;
		//echo $inComicFile['tmp_name'];
		$this->title = $inTitle;
		
		//Make sure directory exists
		if (!is_dir($this->comicDir)) {
			$message->error($this->comicDir.' directory doesn\'t exist');
		}
	}
	
	function saveFile()
	{
		global $message;
		
		//Special thanks to simple_upload.php (author unknown) and Wordpress for
		//upload source code and examples.
		
		$tempName = $this->comicFile['tmp_name'];
		$fileName = $this->comicFile['name'];
		$this->comicFilename = $fileName; 
		$fileType = $this->comicFile['type']; 
		$fileSize = $this->comicFile['size']; 
		$result    = $this->comicFile['error'];
		$filePath = $this->comicDir.'/'.$fileName;
		
		//Security Checks-----------------------------------------
		//File Name Check
	    	if ($fileName == '') {
			    $message->error('Invalid File Name Specified: '.$fileName);
			}
	    	/*
	    	//File Size Check
	    	if ( $fileSize > 500000)
	        $message->error('The file size is over 500K.');
	     */
	    	//File Type Check
	    	if ($fileType == 'text/plain') {
			    $message->error('Sorry, You cannot upload any script file');
			}
		//---------------------------------------------------------

		//Upload---------------------------------------------------
		//Actually create/move the file
	    	$result  =  move_uploaded_file($tempName, $filePath);
		// move_uploaded_file() can fail if open_basedir in PHP.INI doesn't
		// include your tmp directory. Try copy instead?
		if (!$result) {
			$result = copy($tempName, $filePath);
	    }
		// Still couldn't get it. Give up.
		if (!$result) {
			$message->error('Couldn\'t Upload Your File to '.$filePath);
		}
		//---------------------------------------------------------
		
		//Delete temporary image, if needed
		@unlink($tempName);
		
		//Success
		return true;
		
	}
	
	function sendToDatabase()
	{
		global $db, $message;		
		
		$id = $db->nextId(XCOMIC_COMICS_TABLE);
		$sql = 'INSERT INTO '.XCOMIC_COMICS_TABLE.' (cid, title , filename , date)
			VALUES ( 
			    '.$id.',
				'.$db->quoteSmart($this->title).', 
				'.$db->quoteSmart($this->comicFilename).', 
				'.time().'
				)';
		$result = $db->query($sql);
		//Make the changes happen
		if (PEAR::isError($result)) {
			$message->error('ERROR: Unable to add new comic');
		}
	}

}

/*
//Testing
$x = new PostNews("This is a test", "Title1", "left");
$x->sendToDatabase();
*/
?>