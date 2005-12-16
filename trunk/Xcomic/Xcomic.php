<?php
/**
 * Xcomic - Comic Management Script
 *
 * $Id$
 */

/**
 * Reference to the Xcomic class. Just declaring it first.
 *
 * Used to set an external reference to the Xcomic class. Allows
 * nested functions and outside functions to access the instance
 * of the Xcomic class.
 *
 * @global reference $xcomic
 */
$xcomic = null;

/**
 * The core class of the Xcomic system. 
 *
 * Pulls together the base classes and loads the actions (plugin) system for 
 * the rest of the script to use. This is meant to be small and very extensible.
 */
class Xcomic {
	var $cid;
	var $actions = array();

	// {{{ Classes objects
     /**#@+
     * @access public
     * @var object
     */
     var $dbc;
	var $security; //Pretty much useless at the moment
	var $comic;
	var $news;
	var $user;
	var $plugins;
	var $pages;
	/**#@-*/
	// }}}

	/**
	 * Constructor for the Xcomic class.
	 *
	 * Takes a database reference as the only parameter. Therefore, a database
	 * object must be created first. This constructor also calls some private
	 * methods that sets up Xcomic's actions system and obtains the cid from
	 * REQUEST.
	 *
	 * @param reference &$dbc Database (PEAR::DB) reference
	 */
	function Xcomic(&$dbc) {
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
		
		//Get input (This might be better if cid was input through a function instead)...
		//Can't run input cid through security yet since it was not constructed yet! Will
		//secure cid before constructing class, however.
		$this->cid = (!empty($_REQUEST[IN_CID])) ? $_REQUEST[IN_CID] : null; //Default to NULL

		$this->loadAndAssociateCoreClasses();
		
		//$this->loadFilesInDirectory('xc-plugins/', '.plugin.php');
		$this->plugins->registerPlugins();
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
		$GLOBALS['xcomic'] =& $this;
	}

	/**
	 * Loads and constructs core Xcomic class (Comic, News, etc.).
	 *
	 * These core classes provide all the methods that can be used to access
	 * the database. Perhaps the core classes can be replaced by pure actions
	 * but that would destroy the elegancy of the classes in favor of architecture.
	 *
	 * @access private
	 */
	function loadAndAssociateCoreClasses() {
		//Security class - used to make sure that input text is secure.
		include_once 'includes/Security.class.php';
		$this->security = new Security($this->dbc);
		
		//Comic class
		//Secure input cid:
		$this->cid = $this->security->allowOnlyNumbers($this->cid);
		include_once 'includes/Comic.class.php';
		$this->comic = new Comic($this->dbc, $this->cid); //If cid is not set, it should be null
		
		//News class
		//include_once 'includes/News.class.php';
		//$this->news = new News($this->dbc, $this->cid); //If cid is not set, it should be null
		include_once 'includes/ComicAssociatedNews.class.php';
		$this->news = new ComicAssociatedNews($this->dbc, $this->comic->getId());
		
		//User class
		include_once 'includes/UserInformation.class.php';
		$this->user= new UserInformation($this->dbc);
		
		include_once 'includes/Plugin.class.php';
		include_once 'includes/PluginRegistry.class.php';
		$this->plugins = new PluginRegistry($this->dbc);

		include_once 'includes/StaticPages.class.php';
		$this->pages = new StaticPages($this->dbc);
	}
	
	//The following are part of Xcomic's new extensible features. Code based off of
	//Wordpress (http://www.wordpress.org).
	
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
		global $xcomic;

		if ($handle = opendir($inDir)) 
		{
			//Need the !== so that directories called '0' don't break the loop
			while (false !== ($file = readdir($handle)))
			{
			    if (is_dir($inDir.$file))
			    {
                    if ($file != '.' && $file != '..')
                        $xcomic->loadFilesInDirectory($inDir.$file); // Recurse subdirectories
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

	/**
	 * Registers a function with the core so that it can be called
	 * by a tag later during script execution. Useful for template
	 * purposes.
	 *
	 * @access public
	 * @param string $tag Short variable-like name to associate with the function such as 'getimagetag'.
	 * @param string $functionName Function (that exists) to be registered such as 'getImageTag()'.
	 */
	function registerAction($tag, $functionName) {
		
		//Check for existing action. Match tags.
		foreach($this->actions as $actionName=>$actionFunction)
		{
			if($tag == $actionName)
			{
				//Error
				echo 'Error: '.$tag;
				return;
			}
		}
		
		//Add new action to actions array
		$this->actions[$tag] = $functionName;  

	}
	
	/**
	 * Executes the function associated with the tag.
	 *
	 * @access public
	 * @param string $tag Short variable-like name associated with a function such as 'getimagetag'.
	 * @param mixed $arg1,... Optional arguments that are associated with the tag.
	 * @return mixed Returns whatever the function associated to the tag returns. Could possibly be nothing.
	 */
	function doAction($tag) {
		$args = array_slice(func_get_args(), 1); //Get all arguments after the first one ($tag).

		//Check to see if tag exists
		if ((is_string($this->actions[$tag]) && !function_exists($this->actions[$tag])) ||
            (is_array($this->actions[$tag]) && !method_exists($this->actions[$tag][0], $this->actions[$tag][1])))
		{
		  echo "Error: Invalid action '$tag'";
		  return '';
		}

		//Call associated function
		return call_user_func_array($this->actions[$tag], $args);
		
	}
}

/**
 * An alias for $xcomic->doAction() that is used in templating
 * so that users have an easier time using "tags". Can accept
 * additional arguments which will be passed to ->doAction().
 *
 * @param string $inTag Short variable-like name associated with a function such as 'getimagetag'.
 * @return mixed Returns whatever the function associated to the tag returns. Could possibly be nothing. Usually, expect a string.
 */
function get($inTag) {
	global $xcomic;

    if (func_num_args() > 1)
    {
        $args = func_get_args();
        return call_user_func_array(array(&$xcomic, 'doAction'), $args);
    }

	return $xcomic->doAction($inTag);
}

/**
 * Similar to get() as an allias for $xcomc->doAction, but prints
 * the output rather than returning it. Can accept
 * additional arguments which will be passed to ->doAction().
 * 
 * @param string $inTag Short variable-like name associated with a function such as 'getimagetag'.
 */
function out($inTag) {
    global $xcomic;
    
    if (func_num_args() > 1)
    {
        $args = func_get_args();
        echo call_user_func_array(array(&$xcomic, 'doAction'), $args);
        return;
    }

    echo $xcomic->doAction($inTag);
}

function theme_include($file)
{
    global $themePath;
    
    include $themePath.'/'.$file;
}

?>
