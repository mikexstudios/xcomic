<?php
/**
 * Xcomic - comic management script
 * 
 * $Id$
 */

//Might not even need this actually...

/**
 * Reference to the Xadmin class. Just declaring it first.
 *
 * Used to set an external reference to the Xadmin class. Allows
 * nested functions and outside functions to access the instance
 * of the Xadmin class.
 *
 * @global reference $xcomic
 */
$xadmin = null;

class Xadmin {
     
     var $dbc; //Database object
     var $menu; //Menu object
     
     function Xadmin(&$dbc) {
          global $xcomicRootPath;
          
		//Take database reference from initialize and set to class variable.
          if (DB::isConnection($dbc)) 
          {
               $this->dbc =& $dbc;
          }
          else 
          {
               die('Error: The DB reference could not be established!');
          }
		
		//Set external $xcomic reference so nested functions can make
		//call methods in this class and make changes if needed.
		$this->setExternalReference();
		
		$this->loadAndAssociateClasses();
		
		$dbFileList = $this->getStoredDirectoryFiles();
		$realFileList = $this->getDirectoryFiles($xcomicRootPath.'admin/pages/');
		
		if($this->isDirectoryFilesChanged($realFileList, $dbFileList))
		{
               //Make sure /admin/index.php is loaded before continuing. If
               //a file in /admin/pages/ tries to update the menu, the below
               //code will include the file itself and cause problems. Therefore,
               //whenever the /admin/pages/ directory is changed, /admin/index.php
               //is loaded.
               if(!preg_match('|/admin/index.php|',$_SERVER['PHP_SELF']))
               {
                    header('Location: '.$xcomicRootPath.'admin/index.php');
                    exit; //Keep the rest of the menu updates from happening.
               }
                            
               $this->updateDbWithNewDirectoryFiles($realFileList);
               //Wipe existing menu entries in DB and reload.
               $this->menu->removeAllEntries();
               $this->loadFilesInDirectory($xcomicRootPath.'admin/pages/');
		}
		
     }
     
     /**
	 * Sets an external global variable to $this (reference to this class).
	 *
	 * Since the actions functions are being loaded inside the loadFilesInDirectory()
	 * method of this class, they are functions existing inside another function. Therefore
	 * they cannot access the $this reference to this class. To work around this problem,
	 * an external global variable, $xcomic, is used to reference $this. This method
	 * sets the external, global variable.
	 *
	 * @access private
	 */
	function setExternalReference() {
		
		//As described by the PHP manual (http://www.php.net/manual/en/language.references.whatdo.php)
		//one cannot just assign a reference inside a function to a global variable like:
		//global $var; $var =& $this;
		//since $var is a reference to $GLOBALS[] array.
		$GLOBALS['xadmin'] =& $this;
		
	}
	
	/**
	 * Loads and constructs Xadmin classes (Menu, etc.).
	 *
	 * @access private
	 */
	function loadAndAssociateClasses() {
		//Menu class - manages the menu used by the administration system
		include_once 'classes/Menu.class.php';
		$this->menu = new Menu($this->dbc);		
	}
     
     /**
      * Returns an array of files in a given directory
      * 
      * @param string $inDir Path of directory
      * @return
      */
     function getDirectoryFiles($inDir, $ext = '.php') {
          $filesInDirectory = array();
     
		if ($handle = opendir($inDir)) 
		{
			//Need the !== so that directories called '0' don't break the loop
			while (false !== ($file = readdir($handle)))
			{
			    if (is_dir($inDir.$file))
                    continue; // Add recursion here...Currently only to prevent php errors when trying to include a directory.
				if (strlen($ext) && strpos($file, $ext) !== false)
				{ 
					$filesInDirectory[] = $file;
				}
			}
			closedir($handle); 
		}
		else
		{
               echo 'Error: Could not open directory: '.$inDir;
		}
		
		//print_r($filesInDirectory);
		
		return $filesInDirectory;
     }
     
	/**
	 * Scans the specified directory and includes all files in that directory. 
      * 
	 * This is intended for loading actions with each action file registering 
	 * themselves with registerAction().
	 *
	 * @access private
	 * @param string $inDir Directory of files to be loaded. NOTE: The directory should be input with the trailing slash.
	 * @param string $ext the extention of files to be loaded (defaults to '.php')
	 */
	function loadFilesInDirectory($inDir, $ext = '.php') {
		//Declare global here so that all of the action/included
		//files do not have to do so.
		global $xadmin; 

		if ($handle = opendir($inDir)) 
		{
			//Need the !== so that directories called '0' don't break the loop
			while (false !== ($file = readdir($handle))) 
			{
			    if (is_dir($inDir.$file))
			    {
                    if ($file != '.' && $file != '..')
                        $xadmin->loadFilesInDirectory($inDir.$file); // Recurse subdirectories
                    continue;
                }
				if (strpos($file, $ext) !== false) // Only php files, for safety.
				{ 
					include_once($inDir.$file);
				} 
			}
			closedir($handle); 
		}

	}

	function getStoredDirectoryFiles() {
          $sql = "
               SELECT value 
               FROM ".XCOMIC_ADMIN_VARS_TABLE."
               WHERE name = 'page_directory_files'
               ";
          //Execute query
          //$this->dbc->quoteSmart('
          $result = $this->dbc->getOne($sql);
          if (PEAR::isError($result))
          {
               echo 'Error: Unable to obtain page directory files value.';
          }
          
          //Check for empty result meaning no directory files stored.
          if(empty($result))
          {
               return array();
          }
          
          return explode(',', $result);
          
	}
     
     function isDirectoryFilesChanged($inDir01, $inDir02) {
          //print_r($inDir01);
          //print_r($inDir02);
          //print_r(array_diff($inDir01, $inDir02));
          //print_r(array_diff($inDir02, $inDir01));
          
          //Check for 
          
          //For some reason PHP errors for:
          //if(!empty(array_diff($inDir01, $inDir02)))
          //so it will be split into two statements. Also, since array_diff()
          //only returns the differences in the first arg (ex. If the
          //first arg is greater than the second but not vice versa), then
          //two comparisons need to be made.
          $diffResults01 = array_diff($inDir01, $inDir02);
          $diffResults02 = array_diff($inDir02, $inDir01);
          if(!empty($diffResults01) || !empty($diffResults02))
          {
               return true;
          }
          
          return false;
     }
     
     function updateDbWithNewDirectoryFiles($inDir) {
          
          $delimitedFileList = implode(',', $inDir);
          
          $sql = '
               UPDATE '.XCOMIC_ADMIN_VARS_TABLE.'
               SET value = '.$this->dbc->quoteSmart($delimitedFileList).'
               WHERE name = '.$this->dbc->quoteSmart('page_directory_files');
          
          $result = $this->dbc->query($sql);
          if (PEAR::isError($result)) 
          {
               echo 'Error: Unable to update DB with new admin page files list.';
          }
          
     }
     
     //function 
}

?>
