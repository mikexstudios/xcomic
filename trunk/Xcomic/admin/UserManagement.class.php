<?php
/**
Xcomic

$Id$
*/


define('IN_XCOMIC', true);

$xcomicRootPath='../';
include_once($xcomicRootPath.'initialize.php');


//Start sessions
session_start();

class UserManagement {
	
	var $username, $password; //password is unencrypted
	var $md5pass;
	
	function UserManagement($inUsername=NULL, $inPassword=NULL) {
		global $message;
		
		if(!empty($inUsername))
			$this->username = $inUsername;
		if(!empty($inPassword))
		{
			$this->password = $inPassword;
			$this->md5pass();
		}
		
		/*
		//Set message to admin
		$message->setAdmin(true);
		*/
	}
	
	function setUsername($inUsername) {
		$this->username = $inUsername;
	}
	
	function setPassword($inPassword) {
		$this->password = $inPassword;
		
		//md5 the password
		$this->md5pass();
	}
	
	function md5pass() {
		$this->md5pass = md5($this->password);
	}
	
	function setMd5Password($inMd5Password) {
		$this->md5pass = $inMd5Password;	
	}
	
	function registerUser() {
		global $xcomicDb, $message;
		
		//Check to see if username has been taken
		if($this->isUsernameTaken())
		{
			$message->error('Sorry, that username has already been taken. Please select another one.');
		}
		
		$sql = 'INSERT INTO '.XCOMIC_USERS_TABLE." (username, password) 
			VALUES (
				'$this->username', 
				'$this->md5pass'
				);";
		if(!($result = $xcomicDb->sql_query($sql)))
		{
			$message->error('Unable to add new user.');
		}
	}
	
	//Can add more fields
	function editUserInfo($inEmail) {
		global $xcomicDb, $message;
		
		//Update the email for the username
		$sql = 'UPDATE '.XCOMIC_USERS_TABLE." 
			SET email = '$inEmail' 
			WHERE username = '$this->username';";
		
		if(!($result = $xcomicDb->sql_query($sql)))
		{
			$message->error('Unable to edit user info.');
		}
		
	}
	
	function changePassword($inNewPassword) {
		global $xcomicDb, $message;
		
		//Update the password in this class
		$this->setPassword($inNewPassword);
		
		//Make changes to DB
		$sql = 'UPDATE '.XCOMIC_USERS_TABLE." 
			SET password = '$this->md5pass' 
			WHERE username = '$this->username';";
			
		if(!($result = $xcomicDb->sql_query($sql)))
		{
			$message->error('Unable to change user password.');
		}
	}
	
	function deleteUser() {
		global $xcomicDb, $message;
		
		//Check if user exists
		if(!$this->userExists())
		{
			$message->error("Can't delete non-existant user!");
		}
		
		//Delete from DB
		$sql = 'DELETE FROM '.XCOMIC_USERS_TABLE." 
			WHERE username = '$this->username';";
		
		if(!($result = $xcomicDb->sql_query($sql)))
		{
			$message->error('Unable to delete user.');
		}
		
		
	}
	
	function getUid() {
		global $xcomicDb, $message;
		
		$sql = 'SELECT uid 
			FROM '.XCOMIC_USERS_TABLE." 
			WHERE username = '$this->username';";
			
		if(!($result = $xcomicDb->sql_query($sql)))
		{
			$message->error('Unable to get user id.');
		}		
		
		$userInfo = $xcomicDb->sql_fetchrow($result);
		
		//Return user id
		return $userInfo['uid'];
	}
	
	/**
	 * Returns true if the username has been taken
	 * by another user, false otherwise.
	 */
	function isUsernameTaken(){
		
		//Basically the same thing as userExists();
		return $this->userExists();
		
	}
	
	function userExists() {
		global $xcomicDb, $message;
		
		$sql = 'SELECT username
			FROM '.XCOMIC_USERS_TABLE.' 
			WHERE username = "'.$this->username.'";';
		
		if(!($result = $xcomicDb->sql_query($sql)))
		{
			$message->error('Unable to read usernames.');
		}
		
		if($xcomicDb->sql_numrows($result) > 0)
		{
			//User exists
			return true;
		}
		else
		{
			//User doesn't exist
			return false;
		}	
			
	}
	
	function authUser() {
		global $xcomicDb, $message;
		
		//Verify that the user exists
		if(!($this->userExists($this->username)))
		{
			return false;
			
			//The following could weaken security
			//$message->error('The entered username does not exist.');
		}
		
		//Grab password from db
		$sql = 'SELECT password 
			FROM '.XCOMIC_USERS_TABLE." 
			WHERE username = '$this->username';";
		
		if(!($result = $xcomicDb->sql_query($sql)))
		{
			$message->error('Unable to retrieve user information.');
		}		
		
		$userInfo = $xcomicDb->sql_fetchrow($result);
		
		//Return user id
		if($this->md5pass == $userInfo['password'])
		{
			//User authenticated
			return true;
		}
		else
		{
			//Bad authentication. Password failure.
			return false;
		}
	}
	
	function registerSessionVariables() {
		
		//Set session variables
		$_SESSION[SESSION_USERNAME] = $this->username;
		$_SESSION[SESSION_PASSWORD] = $this->md5pass;
		
	}
	
	function setCookies($func='login') {
		
		$cookieTime = 60*60*24*100; //Cookie persists for 100 days
		
		//If logging out
		if($func=='login')
		{
			//Set cookies from session variables
			setcookie(COOKIE_USERNAME, $_SESSION[SESSION_USERNAME], time()+$cookieTime, "/");
			setcookie(COOKIE_PASSWORD, $_SESSION[SESSION_PASSWORD], time()+$cookieTime, "/");
		}
		//Logout
		else
		{
			//Minus the time set to logout. (Setting the time in the past)
			setcookie(COOKIE_USERNAME, '', time()-$cookieTime, "/");
			setcookie(COOKIE_PASSWORD, '', time()-$cookieTime, "/");	
		}
	}
	
	function isAlreadyLoggedIn() {
		
		//If cookies exists, set session variables with them
		if(!empty($_COOKIE[COOKIE_USERNAME]) && !empty($_COOKIE[COOKIE_PASSWORD]))
		{
			$_SESSION[SESSION_USERNAME] = $_COOKIE[COOKIE_USERNAME];
			$_SESSION[SESSION_PASSWORD] = $_COOKIE[COOKIE_PASSWORD];
		}
		
		//echo $_SESSION[SESSION_USERNAME];
		//echo $_SESSION[SESSION_PASSWORD];
		
		//Check if session variables have been set
		if(!empty($_SESSION[SESSION_USERNAME]) && !empty($_SESSION[SESSION_PASSWORD]))
		{
			
			//Set username and password
			$this->setUsername($_SESSION[SESSION_USERNAME]);
			$this->setMd5Password($_SESSION[SESSION_PASSWORD]);
			
			//Authenticate user
			if($this->authUser())
			{
				//User logged in
				return true;
			}
			else
			{
				//Session variables are incorrect. Unset
				unset($_SESSION[SESSION_USERNAME]);
				unset($_SESSION[SESSION_PASSWORD]);
				
				//User not logged in
				return false;	
			}
		}
		else
		{
			//User not logged in
			return false;
		}
		
	}
	
	function getUsername() {
	
		return $this->username;
		
	}
	
	function processLogin($alsoDo='') {
		
		//If authentication is correct, set sessions
		if($this->authUser())
		{
			$this->registerSessionVariables();
			
			if($alsoDo == 'remember')
			{
				$this->rememberMe();
			}
			
			//Sucess
			return true;
		}
		else
		{
			//Failure
			return false;
		}
		
	}
	
	//Set cookies to remember the user
	function rememberMe() {
		$this->setCookies();
	}
	
	function logout() {
		
		//Clear cookies
		$this->setCookies('logout');
		
		/* Kill session variables */
		unset($_SESSION[SESSION_USERNAME]);
		unset($_SESSION[SESSION_PASSWORD]);
		$_SESSION = array(); // reset session array
		session_destroy();   // destroy session.
	}

}


/*
//Testing
$x = new UserManagement('test', 'test');
$x->registerUser();
$x->editUserInfo('test@test.com');
*/


?>