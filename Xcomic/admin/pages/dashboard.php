<?php
/**
Xcomic

$Id$
*/

//Check to see if being included by another file (ie. Xadmin.php)
if(basename($_SERVER['PHP_SELF']) !== basename(__FILE__))
{
     //This file is being included. Assume Xadmin.php is 
     //including the file.
     
     if(empty($xadmin))
     {
          echo 'Error: This file requires Xadmin.php to function correctly.';
     }  
     
     //Check to see if already registered in the menu
     if(!$xadmin->menu->isLinkToInMenu(basename(__FILE__)))
     {
          //Not already registered, so register
          $xadmin->menu->addEntry('Dashboard', basename(__FILE__), 0); //First link
     }
     
     //Exit so rest of file doesn't display. When including
     //a file, control to the base file can be returned by
     //using the return construct.
     return;
}

//Every admin page requires the following:
$xcomicRootPath = '../../';
//The following are not needed since this is just a simple redirect.
//require_once $xcomicRootPath.'admin/includes/admininitialize.php';
//include_once $xcomicRootPath.'admin/Xadmin.php';
//$xadmin = new Xadmin($db);

header('Location: '.$xcomicRootPath.'admin/index.php');
?>
