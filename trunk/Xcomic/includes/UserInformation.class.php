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


class UserInformation
{
	
	var $userInfo;
    var $dbc;

	function UserInformation(&$dbc, $inUsername = null)
	{
        if (DB::isConnection($dbc)) {
            $this->dbc =& $dbc;
        }
		if (!empty($inUsername)) {
			$this->getUserInfo($inUsername);
		}
	}
	
	function queryUserInfo($inUsername)
	{
		global $db, $message;
		
		$sql = '
		    SELECT
		        uid, username, password, email
			FROM '.XCOMIC_USERS_TABLE." 
			WHERE username='$inUsername'";
		$result = $this->dbc->getRow($sql);
		if (PEAR::isError($result)) {
			#$message->error('Unable to get user info');
			die('Unable to get user info');
		}
		
		return $result;
	}
	
	function getUserInfo($inUsername)
	{
		$this->userInfo = $this->queryUserInfo($inUsername);
	}
	
	function getId()
    {
		return $this->userInfo['uid'];
	}
	
	function getUsername()
	{
		return $this->userInfo['username'];
	}
	
	//Returns md5 password
	function getPassword() {
		return $this->userInfo['password'];
	}
	
	function getEmail()
	{
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