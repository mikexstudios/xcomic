<?php
	/*
	YOU CAN REMOVE THIS PART AFTER INSTALLATION.
	--------------------------------------------
	(Added for a more user friendly installation.
	 Remove the following config file check to 
	 increase page execution speed.)
	*/
	//Check for a config file
	if (!file_exists('includes/config.php')) {
		//Redirect to installation page
		header('Location: admin/install/index.php');	
	}
	/*
	--------------------------------------------
	*/
	
	
	/* 
	DO NOT REMOVE THE FOLLLOWING. IT CALLS THE XCOMIC SCRIPT.
	Xcomic - Comic Management Script
	(http://xcomic.sourceforge.net)
	---------------------------------------------------------
	$Id$
	*/
	//Include Xcomic core operation files
	$xcomicRootPath = './';
	require_once $xcomicRootPath.'initialize.php';	//Include all page common settings
	include_once $xcomicRootPath.'Xcomic.php';

	//Create Xcomic object. Do not use the variable $xcomic
	//since it is already used.
	$xcomic = new Xcomic($db);
	
	include_once($xcomicRootPath.'/includes/pageHeader.php');

	//Get path for currently selected theme and include it.
	//Redirection is not used since that would destroy the
	//point of creating the Xcomic object.
	$thispage = isset($_REQUEST['page']) ? $_REQUEST['page'] : null;
	if (strlen($thispage) && $xcomic->pages->getPageExists($thispage))
        include($themePath.'/'.$xcomic->pages->getPageThemeFile($thispage));
    else
        include($themePath.'/'.basename(__FILE__));

    include_once($xcomicRootPath.'/includes/pageTail.php');

	/*
	---------------------------------------------------------
	*/
?>
