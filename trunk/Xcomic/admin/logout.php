<?php
/**
Xcomic

$Id$
*/
//Xcomic settings
define('IN_XCOMIC', true);
$xcomicRootPath = '../';
require_once './admininitialize.php';	//Include all admin common settings

//Log user out
$userManagement->logout();

//Display logout
//Set a new header for message
$message->setNoMenu(true);
$message->say('You have been successfully logged out. Click <a href="index.php">here to log back in</a>.');
?>