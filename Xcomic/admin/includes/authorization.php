<?php
/**
Xcomic

$Id$
*/

include_once $xcomicRootPath.'admin/classes/UserManagement.class.php'; //Login/Logout

//Create User Management object
$userManagement = new UserManagement($db);

//AUTHORIZATION: Check to see if user has the privledge to access this
if (!$userManagement->isLoggedIn()) {	
	//User does not have access. Display login page.
    header('Location: '.$xcomicRootPath.'admin/login.php');
	exit; //To keep script for further execution
}
?>
