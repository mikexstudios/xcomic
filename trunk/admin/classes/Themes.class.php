<?php
/**
 * Xcomic - comic management script
 * (http://xcomic.sourceforge.net)
 *
 * $Id$
 */
 
class Themes {
     var $dbc; //DB connection

     function Themes(&$dbc) {
		//Take database reference from initialize and set to class variable.
          if (DB::isConnection($dbc)) 
          {
               $this->dbc =& $dbc;
          }
          else 
          {
               die('Error: The DB reference could not be established!');
          }
     }
     
     /**
      * Returns a list of CSS file locations.
      *
      * Scans the themes directory recursively (to one directory depth) for
      * any CSS files. This is used with getThemeInfo() since the CSS files
      * contain the theme information.
      *
      * @access private
      * @param string $inDir Directory path to scan for CSS files. Include trailing /.
      * @return array Array of CSS file paths.
      */
     function getCSSFiles($inDir) {
          
          $listOfCSSPaths = array();

		if ($handle = opendir($inDir)) 
		{
			//Need the !== so that directories called '0' don't break the loop
			while (false !== ($file = readdir($handle))) 
			{ 
			  //echo $file;
                    //Make sure it is a directory
                    if(is_dir($inDir.$file) && ($file != '.' && $file != '..'))
                    {
                         //Check for styles.css file. If exists, place in array.
                         //echo $inDir.$file.'/style.css';
                         if(is_file($inDir.$file.'/style.css'))
                         {
                              $listOfCSSPaths[] = $inDir.$file.'/style.css';
                         }
                    
                         /*
                         //echo $inDir.$file;
                         //Try to open the directory for scanning
                         if($subHandle = opendir($inDir.$file))
                         {
                              //Need the !== so that directories called '0' don't break the loop
               			while (false !== ($subFile = readdir($subHandle))) 
               			{
                                   //Check for the styles.css file
                                   echo $inDir.$file.'/'.$subFile.'<br />';
                                   if(is_file($inDir.$file.'/'.$subFile.'/style.css') && ($subFile != '.' && $subFile != '..'))
                                   {
                                        echo $inDir.$file.'/'.$subFile.'/style.css';
                                        $listOfCSSPaths[] = $inDir.$file.'/'.$subFile.'/style.css';
                                   }
                              }
                              closedir($subHandle);
                         }
                         else
                         {
                              echo 'Error: Could not open directory: '.$file;
                         }
                         */
                    }
			}
			closedir($handle); 
		}
		else
		{
               echo 'Error: Could not open directory: '.$inDir;
		}
		
		return $listOfCSSPaths;
     }
     
     /**
      * Returns multidimensional array containing information about the themes.
      *
      * Each row of the array is for each theme. Each row contains values: name,
      * author, and description of the theme. This method scans the themes
      * folder looking for the styles.css file which contain the themes
      * information.
      *
      * @access public
      * @return array Multidimensional array containing information about the themes.
      */
     function getThemesInfo() {
          global $xcomicRootPath;
          
          $themes = array(); //Holds the themes information
          
          //Get theme files
          $listOfThemeFiles = $this->getCSSFiles($xcomicRootPath.THEMES_DIR.'/');
          
          //print_r($listOfThemeFiles);
          
          foreach($listOfThemeFiles as $eachThemeFile)
          {
               $themes[] = $this->get_theme_data($eachThemeFile);
          }
          
          return $themes;
     }
     
     /**
      * Returns an array with information about the given theme.
      *
      * This function was copied verbatim from Wordpress 1.5.1.3
      * (http://www.wordpress.org) removing the wptexture() and the
      * __() functions.
      *
      * @access private
      * @param string $theme_file Location of the .css file of a theme.
      * @return array Information about the given theme.
      */
     function get_theme_data($theme_file) {
     	$theme_data = implode('', file($theme_file));
     	preg_match("|Theme Name:(.*)|i", $theme_data, $theme_name);
     	preg_match("|Theme URI:(.*)|i", $theme_data, $theme_uri);
     	preg_match("|Description:(.*)|i", $theme_data, $description);
     	preg_match("|Author:(.*)|i", $theme_data, $author_name);
     	preg_match("|Author URI:(.*)|i", $theme_data, $author_uri);
     	if ( preg_match("|Template:(.*)|i", $theme_data, $template) )
     	    $template = $template[1];
     	else
     	    $template = '';
     	if ( preg_match("|Version:(.*)|i", $theme_data, $version) )
     		$version = $version[1];
     	else
     		$version ='';
     	if ( preg_match("|Status:(.*)|i", $theme_data, $status) )
     		$status = $status[1];
     	else
     		$status ='publish';
     
     	//$description = wptexturize($description[1]);
     	$description = $description[1];
     
     	$name = $theme_name[1];
     	$name = trim($name);
     	$theme = $name;
     	if ('' != $theme_uri[1] && '' != $name) {
     		$theme = '<a href="' . $theme_uri[1] . '" title="Visit theme homepage">' . $theme . '</a>';
     	}
     
     	if ('' == $author_uri[1]) {
     		$author = $author_name[1];
     	} else {
     		$author = '<a href="' . $author_uri[1] . '" title="Visit author homepage">' . $author_name[1] . '</a>';
     	}

     	return array('Name' => $name, 'Title' => $theme, 'Description' => $description, 'Author' => $author, 'Version' => $version, 'Template' => $template, 'Status' => $status);
     }
     
     /**
      * Returns the currently selected theme.
      *
      * Essentially a wrapper function for using Settings to grab the current
      * theme.
      *
      * @access public
      * @return string Currently selected theme.
      */
     function getCurrentTheme() {
          global $settings;
          
          return $settings->getSetting('usingTheme');
     }
     
     /**
      * Sets the current theme.
      *
      * @access public
      * @param string $inThemeName The theme name (should be the directory name holding the theme files).
      */
     function setCurrentTheme($inThemeName) {
		global $settings;
		
        if (!$settings->doesSettingExist('usingTheme'))
            $settings->addNewSetting('usingTheme', $inThemeName, 'string', '', 'Current Theme', 'The currently selected theme');
        else
		  $settings->changeSettingValue('usingTheme', $inThemeName);
     }

}
