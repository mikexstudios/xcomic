<?php
/**
Xcomic

$Id$
*/

include_once 'classes/UserManagement.'.$classEx; //Login/Logout

//Create User Management object
$userManagement = new UserManagement();

//AUTHORIZATION: Check to see if user has the privledge to access this
if (!($userManagement->isLoggedIn())) {	
	//User does not have access. Display login page.
	$redirect = $settings->getSetting('urlToXcomic') . '/admin/login.php';
	header('Location: '.$redirect);
	exit; //To keep script for further execution
}
?>