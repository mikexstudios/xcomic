<?php
/**
Xcomic

$Id$
*/

/*
define('IN_XCOMIC', true);

$xcomicRootPath='../';
include_once($xcomicRootPath.'initialize.php');
*/


class UserInformation {
	
	var $userInfo;
	
	function UserInformation($inUsername=NULL) {
		if(!empty($inUsername))
		{
			$this->getUserInfo($inUsername);
		}
	}
	
	function queryUserInfo($inUsername) {
		global $xcomicDb, $message;
		
		$sql = 'SELECT uid, username, password, email
			FROM '.XCOMIC_USERS_TABLE." 
			WHERE username='$inUsername'";
		
		if(!($result = $xcomicDb->sql_query($sql)))
		{
			$message->error('Unable to get user info');
		}
		
		return $xcomicDb->sql_fetchrow($result);
	}
	
	function getUserInfo($inUsername) {
		
		$this->userInfo = $this->queryUserInfo($inUsername);
		
	}
	
	function getId() {
		
		return $this->userInfo['uid'];
		
	}
	
	function getUsername() {
		
		return $this->userInfo['username'];
		
	}
	
	//Returns md5 password
	function getPassword() {
		
		return $this->userInfo['password'];
		
	}
	
	function getEmail() {
		
		return $this->userInfo['email'];
		
	}
	
}

/*
//Testing ComicDisplay
$x = new ComicDisplay();
//echo $x->getComicId();
$x->getComicInfo(1);
echo $x->getFilename();
if($x->nextId()==false)
	echo "false";
else
	echo $x->nextId();
	
if($x->prevId()==false)
	echo "false";
else
	echo $x->prevId();
*/


?>