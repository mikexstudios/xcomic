<?php
/**
Xcomic

$Id$
*/

class Comic
{
	var $title;
	var $comicFile; //$_FILES type
	var $comicFilename;
	var $comicDir;
	var $cid;
	var $dbc;
	var $date;
	
	function Comic(&$dbc, $inComicFile, $inTitle = null, $comicDate = null)
	{
		global $message, $xcomicRootPath;
		
        if (DB::isConnection($dbc)) {
            $this->dbc =& $dbc;
        }
		$this->comicDir = $xcomicRootPath.COMICS_DIR;
		
		$this->comicFile = $inComicFile;
		//echo $inComicFile['tmp_name'];
		$this->title = $inTitle;
		$this->date = $comicDate ? $comicDate : time();

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
		
		if (file_exists($filePath))
		  $message->error("$fileName already exists. Please use a different file name.");
		
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
		//Fix added 3/10/05, see http://xcomic.mikexstudios.com/forum/viewtopic.php?pid=174
		//for reason
		if (getimagesize($tempName) === FALSE) {
			$message->error('Sorry, you can only upload images.');
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
		global $message;		
		
		$id = $this->dbc->nextId(XCOMIC_COMICS_TABLE);
		$sql = '
		    INSERT INTO '.XCOMIC_COMICS_TABLE.' (cid, title , filename , date)
			VALUES ( 
			    '.$id.',
				'.$this->dbc->quoteSmart($this->title).', 
				'.$this->dbc->quoteSmart($this->comicFilename).', 
				'.$this->date.'
				)';
		$result = $this->dbc->query($sql);
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
