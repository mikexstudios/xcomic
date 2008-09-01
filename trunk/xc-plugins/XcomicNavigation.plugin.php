<?php
/**
Xcomic - Comic Management Script
(http://xcomic.sourceforge.net)
--------------------------------

This file contains functions that generate comic navigation code
for templating use.

$Id$
*/

class XcomicNavigation extends Plugin
{
    function XcomicNavigation()
    {
        $this->registerAction('getPreviousComicUrl', 'getPreviousComicUrl');
        $this->registerAction('getNextComicUrl', 'getNextComicUrl');
        $this->registerAction('getComicArchiveOptionList', 'getComicArchiveOptionList');
        $this->registerAction('pageUrl', 'getStaticPageUrl');
    }
    
    function getPluginMetaData($meta)
    {
        switch ($meta)
        {
            case 'name': return 'Xcomic Navigation'; break;
            case 'description': return 'The builtin navigation plugin.'; break;
            case 'author': return 'Xcomic'; break;
            case 'website': return 'http://xcomic.sourceforge.net/'; break;
            default: return ''; break;
        }
    }

    function getPreviousComicUrl() {
    	global $xcomic, $settings;

    	if ($xcomic->comic->prevId() === false)
    	{
    		return '';
    	}

    	return '?cid='.$xcomic->comic->prevId();
    }

    function getNextComicUrl() {
    	global $xcomic, $settings;
    
    	if ($xcomic->comic->nextId() === false) {
    		return '';
    	}

    	return '?cid='.$xcomic->comic->nextId();
    }

    function getComicArchiveOptionList() {
    	global $xcomic, $xcomicRootPath, $settings;
    	
    	//Generate drop down box ---------------------
    	include_once $xcomicRootPath.'includes/ComicListing.class.php';
    	$listComics = new ComicListing($xcomic->dbc);
    	$comicsList = $listComics->getComicList(); //Array of comic listings
    	$numComics = $listComics->numComics(); //Number of elements in that array
    	
    	//Since $comicsList is in ascending order. We want the most recent comic first
    	//Therefore, set the for loop counting backwards
    	for($comicCount = $numComics-1; $comicCount >= 0 ; $comicCount--)
    	{
    		$comicOptionListCode .= '<option value="'.$comicsList[$comicCount]['cid'].'">'.date('Y-m-d', $comicsList[$comicCount]['date']).' ['.$comicsList[$comicCount]['cid'].'] '.$xcomic->security->removeMagicQuotes($comicsList[$comicCount]['title'])."</option>\n";
    	}
    	//-------------------------------------------- 
    		
    	return $comicOptionListCode;   
    }
    
    function getStaticPageUrl($page) {
        global $xcomic;
        
        if (!$xcomic->pages->getPageExists($page))
            return '';
        
        return '?page='.$page;
    }
}
?>