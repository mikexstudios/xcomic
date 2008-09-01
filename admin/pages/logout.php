<?php
/**
Xcomic

$Id$
*/

//Check to see if being included by another file (ie. Xadmin.php)
if(basename($_SERVER['PHP_SELF']) !== basename(__FILE__))
{
     //echo 'included'.__FILE__;
     //This file is being included. Assume Xadmin.php is 
     //including the file.
     
     if(empty($xadmin))
     {
          echo 'Error: This file requires Xadmin.php to function correctly.';
     }  
     
     //Check to see if already registered in the menu
     if(!$xadmin->menu->isLinkToInMenu(basename(__FILE__)))
     {
          //Not already registered, so register entry. Set position to a very
          //high number so that it will be at the very end of the menu.
          $xadmin->menu->addEntry('Logout', basename(__FILE__), 100); 
     }
     
     //Exit so rest of file doesn't display. When including
     //a file, control to the base file can be returned by
     //using the return construct.
     return;
}

//Every admin page requires the following:
$xcomicRootPath = '../../';
require_once $xcomicRootPath.'admin/includes/admininitialize.php';
include_once $xcomicRootPath.'admin/Xadmin.php';
$xadmin = new Xadmin($db);

/*
//Xcomic settings
define('IN_XCOMIC', true);
$xcomicRootPath = '../';
require_once './admininitialize.php';	//Include all admin common settings
*/

//Log user out
$userManagement->logout();

//Display logout
//Set a new header for message
$message->setNoMenu(true);
$message->say('You have been successfully logged out. Click <a href="../index.php">here to log back in</a>.');
?>
