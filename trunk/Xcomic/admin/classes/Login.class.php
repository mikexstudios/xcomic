<?php
/**
Xcomic

postNews - posts left or right rant

$Id$
*/

/*
define('IN_XCOMIC', true);

$xcomicRootPath='../';
include_once($xcomicRootPath.'initialize.php');
*/

class Login {
	
	
	function Login() {
		$this->showLogin();
	}

	function showLogin() {
		global $xcomicTemplate;
		
		$xcomicTemplate->setVars(array(
			'SECTION_TITLE' => 'Login',
			'MODE' => 'login',
			'INLOGIN_USERNAME' => AUTHIN_USERNAME,
			'INLOGIN_PASSWORD' => AUTHIN_PASSWORD
			)
		);
		
		$adminDisplay = new AdminDisplay('admin/loginBox.tpl', 'outsideHeader');
		$adminDisplay->showFullPage();	
	}
	
	function processLogin() {
		global $message;
		
		//Get input from form
		$inUsername=(!empty($_REQUEST[AUTHIN_USERNAME])) ? $this->security->allowOnlyChars($_REQUEST[AUTHIN_USERNAME]) : NULL;
		$inPassword=(!empty($_REQUEST[AUTHIN_PASSWORD])) ? $this->security->allowOnlyChars($_REQUEST[AUTHIN_PASSWORD]) : NULL;
		
		//Set them in User Management
		$this->userManagement->setUsername($inUsername);
		$this->userManagement->setPassword($inPassword);	
		
		//Process login information
		if($this->userManagement->processLogin('remember'))
		{
			$message->say('You have been successfully logged in.');
		}
		else
		{
			$message->setAdminHeader('outsideHeader');
			$message->error('Login failure: Incorrect username or password.');
		}
		
	}
	
}

/*
//Testing

*/

?>