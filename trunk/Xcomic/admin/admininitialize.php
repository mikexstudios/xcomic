<?php
/**
Xcomic

//Initialize - creates global variables used throughout the script
//

$Id$
*/

//Include everything from the main initialize.php
include_once $xcomicRootPath.'initialize.php';

//Create Security Class
include_once $xcomicRootPath.'includes/Security.'.$classEx;
$security = new Security();

//Create AdminMessage class
include_once './classes/AdminMessage.'.$classEx;
$message = new AdminMessage();

//Authorize User
include_once './includes/authorization.php'; //Verify that user is logged in
?>