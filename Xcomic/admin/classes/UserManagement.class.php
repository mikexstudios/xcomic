<?php
/**
Xcomic

$Id$
*/

$xcomicRootPath = '../';
include_once $xcomicRootPath.'initialize.php';


//Start sessions
session_start();

class UserManagement
{	
    var $username, $id; //password is unencrypted
    var $md5pass;
    var $dbc;
	
	function UserManagement(&$dbc, $id = null, $inPassword = null)
	{
		global $message;
		
        if (DB::isConnection($dbc)) {
            $this->dbc =& $dbc;
        }

		if (!empty($id)) {
			$this->id = $id;
		}
		if (!empty($inPassword)) {
			$this->md5pass($inPassword);
		}
		
		/*
		//Set message to admin
		$message->setAdmin(true);
		*/
	}
	
	function setUsername($inUsername)
	{
		$this->username = $inUsername;
	}
	
	function setPassword($passwd)
	{
	    $this->md5pass($passwd);
	}
	
	function md5pass($passwd)
	{
		$this->md5pass = md5($passwd);
	}
	
	function setMd5Password($inMd5Password)
	{
		$this->md5pass = $inMd5Password;	
	}
	
	function registerUser($username, $password, $email)
	{
		global $message;
		
		$this->setUsername($username);
		
		//Check to see if username has been taken
		if ($this->userExists()) {
			$message->error('Sorry, that username has already been taken. Please select another one.');
		}
		
		$this->setPassword($password);
		
		$id = $this->dbc->nextId(XCOMIC_USERS_TABLE);
		$sql = '
		    INSERT INTO '.XCOMIC_USERS_TABLE.' (uid, username, password, email) 
			VALUES (
			    '.$id.',
				'.$this->dbc->quoteSmart($username).', 
				'.$this->dbc->quoteSmart($this->md5pass).',
				'.$this->dbc->quoteSmart($email).'
				)';
		$result = $this->dbc->query($sql);
		if (PEAR::isError($result)) {
			$message->error('Unable to add new user.');
		}
	}
	
	//Can add more fields
	function editUserInfo($inEmail)
	{
		global $message;
		
		//Update the email for the username
		$sql = '
		    UPDATE '.XCOMIC_USERS_TABLE.' 
			SET email = '.$this->dbc->quoteSmart($inEmail).' 
			WHERE uid = '.$this->id;
		$result = $this->dbc->query($sql);
		if (PEAR::isError($result)) {
			$message->error('Unable to edit user info.');
		}
		
	}
	
	function changePassword($inNewPassword)
	{
		global $message;
		
		//Update the password in this class
		$this->setPassword($inNewPassword);
		
		//Make changes to DB
		$sql = '
		    UPDATE '.XCOMIC_USERS_TABLE.'
			SET password = '.$this->dbc->quoteSmart($this->md5pass).' 
			WHERE uid = '.$this->id;
	    $result = $this->dbc->query($sql);
		if (PEAR::isError($result)) {
			$message->error('Unable to change user password.');
		}
	}
	
	function deleteUser()
	{
		global $message;
		
		//Check if user exists
		if (!$this->userExists()) {
			$message->error("Can't delete non-existant user!");
		}
		
		//Delete from DB
		$sql = '
		    DELETE FROM '.XCOMIC_USERS_TABLE.'
			WHERE uid = '.$this->id;
		$result = $this->dbc->query($sql);
		if (PEAR::isError($result)) {
			$message->error('Unable to delete user.');
		}
	}
	
	function getUsername()
	{
	    if (isset($this->username)) {
	        return $this->username;
	    }
		global $message;

		$sql = '
		    SELECT username
			FROM '.XCOMIC_USERS_TABLE.' 
			WHERE uid = '.$this->id;
	    $result = $this->dbc->getOne($sql);
		if (PEAR::isError($result)) {
		    $message->error('Unable to get username.');
		}

		//Return username
		$this->username = $result;
		return $result;
	}
	
	function getUid()
	{
	    global $message;
		
		$sql = '
		    SELECT uid
			FROM '.XCOMIC_USERS_TABLE.' 
			WHERE username = '.$this->dbc->quoteSmart($this->username);
	    $result = $this->dbc->getOne($sql);
		if (PEAR::isError($result)) {
		    $message->error('Unable to get user id.');
		}
		
		//Return username
		$this->id = $result;
		return $result;  
	}
	
	function userExists() {
		global $message;
		
		if (!isset($this->username) && !isset($this->id)) {
		    return false;
		}
		
		$sql = '
		    SELECT username
			FROM '.XCOMIC_USERS_TABLE.' 
			WHERE ';
		    if (isset($this->id)) {
			    $sql .= 'uid = '.$this->id;
			} elseif (isset($this->username)) {
			    $sql .= 'username = '.$this->dbc->quoteSmart($this->username);
			} else {
				//Neither id or username is set so the hypothetical user
				//does not exist.
				return false;
			}

		$result = $this->dbc->getOne($sql);

		if (PEAR::isError($result)) {
			echo 'Unable to read usernames.';
			//$message->error('Unable to read usernames.');
		}
		
		if (!empty($result)) {
			//User exists
			return true;
		} 
		//User doesn't exist
	    return false;
	}
	
	function authUser()
	{
		global $message;
	
		//Verify that the user exists
		if (!$this->userExists()) {
			return false;
			
			//The following could weaken security
			//$message->error('The entered username does not exist.');
		}

		//Grab password from db
		$sql = '
		    SELECT password 
			FROM '.XCOMIC_USERS_TABLE.'
			WHERE username = '.$this->dbc->quoteSmart($this->username);

		$result = $this->dbc->getOne($sql);
		if (PEAR::isError($result)) {
			$message->error('Unable to retrieve user information.');
		}
		
		//Return user id
		if ($this->md5pass == $result) {
			//User authenticated
			return true;
		}
		//Bad authentication. Password failure.
		return false;
	}
	
	function registerSessionVariables()
	{
		//Set session variables
		$_SESSION['SESSION_USERNAME'] = $this->username;
		$_SESSION['SESSION_PASSWORD'] = $this->md5pass;
	}
	
	function setCookies($func = 'login')
	{
		
		$cookieTime = 60*60*24*100; //Cookie persists for 100 days
		
		//If logging out
		if ($func == 'login') {
			//Set cookies from session variables
			setcookie(COOKIE_USERNAME, $_SESSION['SESSION_USERNAME'], time()+$cookieTime, "/");
			setcookie(COOKIE_PASSWORD, $_SESSION['SESSION_PASSWORD'], time()+$cookieTime, "/");
		} else {//Logout
			//Minus the time set to logout. (Setting the time in the past)
			setcookie(COOKIE_USERNAME, '', time()-$cookieTime, "/");
			setcookie(COOKIE_PASSWORD, '', time()-$cookieTime, "/");	
		}
	}
	
	function isLoggedIn()
	{
		
		//If cookies exists, set session variables with them
		if (!empty($_COOKIE['COOKIE_USERNAME']) && !empty($_COOKIE['COOKIE_PASSWORD'])) {
			$_SESSION['SESSION_USERNAME'] = $_COOKIE['COOKIE_USERNAME'];
			$_SESSION['SESSION_PASSWORD'] = $_COOKIE['COOKIE_PASSWORD'];
		}
		
		//echo $_SESSION['SESSION_USERNAME'];
		//echo $_SESSION['SESSION_PASSWORD'];
		
		//Check if session variables have been set
		if (!empty($_SESSION['SESSION_USERNAME']) && !empty($_SESSION['SESSION_PASSWORD'])) {
			
			//Set username and password
			$this->setUsername($_SESSION['SESSION_USERNAME']);
			$this->setMd5Password($_SESSION['SESSION_PASSWORD']);
			
			//Authenticate user
			if ($this->authUser()) {
				//User logged in
				return true;
			} else {
				//Session variables are incorrect. Unset
				unset($_SESSION['SESSION_USERNAME']);
				unset($_SESSION['SESSION_PASSWORD']);
				
				//User not logged in
				return false;	
			}
		} else {
			//User not logged in
			return false;
		}
		
	}
	
	function processLogin($alsoDo = '')
	{
		
		//If authentication is correct, set sessions
		if ($this->authUser()) {
			$this->registerSessionVariables();
			
			if ($alsoDo == 'remember') {
				$this->rememberMe();
			}
			
			//Success
			return true;
		}
		//Failure
		return false;
	}
	
	//Set cookies to remember the user
	function rememberMe()
	{
		$this->setCookies();
	}
	
	function logout()
	{
		
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