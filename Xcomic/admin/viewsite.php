<?php
/**
Xcomic

$Id$
*/
//Xcomic settings
define('IN_XCOMIC', true);
$xcomicRootPath = "../";

require_once('./admininitialize.php');	//Include all admin common settings

//Redirect user to the site

$redirect = $settings->getSetting('urlToXcomic');

header('Location: '.$redirect);

?>