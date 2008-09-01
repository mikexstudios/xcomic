<?php
/**
Xcomic - Comic Management Script
(http://xcomic.sourceforge.net)
--------------------------------

This file contains functions that construct the comic display
classes and create functions that return information about a specified
comic for templating use.

$Id$
*/

class XcomicDisplay extends Plugin
{
    function XcomicDisplay()
    {
        $this->registerAction('getComicTitle', 'getComicTitle');
        $this->registerAction('getComicImageUrl', 'getComicImageUrl');
    }
    
    function getPluginMetaData($meta)
    {
        switch ($meta)
        {
            case 'name': return 'Xcomic Comic Display'; break;
            case 'description': return 'The builtin comic display plugin.'; break;
            case 'author': return 'Xcomic'; break;
            case 'website': return 'http://xcomic.sourceforge.net/'; break;
            default: return ''; break;
        }
    }

    function getComicTitle() {
    	global $xcomic;

    	return $xcomic->security->removeMagicQuotes($xcomic->comic->getTitle());
    }
    
    function getComicImageUrl() {
    	global $xcomic;
    	
    	//Check for non-existant comic. Could be a better way to
    	//do this.
    	if ($xcomic->comic->getTitle() == '') {
    		return 'The comic you selected does not exist!';
    	}

    	//Set variables
    	$comicImageUrl = COMICS_DIR.'/'.$xcomic->comic->getFilename();

    	return $comicImageUrl;
    }
}
?>