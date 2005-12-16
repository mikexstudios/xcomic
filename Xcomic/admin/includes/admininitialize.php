<?php
/**
Xcomic

//Initialize - creates global variables used throughout the script
//

$Id$
*/

//$xcomicRootPath = '../';
//Include everything from the main initialize.php
include_once $xcomicRootPath.'initialize.php';

//Create Security Class
include_once $xcomicRootPath.'includes/Security.class.php';
$security = new Security($db);

//Create AdminMessage class
include_once $xcomicRootPath.'admin/classes/AdminMessage.class.php';
$message = new AdminMessage();

//Authorize User
include_once $xcomicRootPath.'admin/includes/authorization.php'; //Verify that user is logged in
?>
