<?php
/**
Xcomic

$Id$
*/

//Xcomic settings
define('IN_XCOMIC', true);
$xcomicRootPath = "../";

require_once('./admininitialize.php');	//Include all admin common settings

//Form field variables
$sectionTitle = 'User Functions';
$formNewUsername = 'newusername';
$formNewPassword = 'newpassword';
$formNewEmail = 'newemail';
$formModeAddUser = 'adduser';
$formModeDeleteUser = 'deleteuser';
$formEditDeleteUsername = 'username'; //For the GET url

//Check for form submission for delete user
if($_REQUEST['mode'] == $formModeDeleteUser) {

$username=(!empty($_REQUEST[$formEditDeleteUsername])) ? $security->allowOnlyChars($_REQUEST[$formEditDeleteUsername]) : NULL;

//Check for error
if(empty($username))
	$message->error('The username was left blank or invalidly filled.');

//Delete user using User Management class
$deleteUser = new UserManagement($username);
$deleteUser->deleteUser();

//Display success
$message->say('User has been sucecssfully deleted.');

}

//Check for form submission for add user
if($_REQUEST['mode'] == $formModeAddUser) {
		
$username=(!empty($_REQUEST[$formNewUsername])) ? $security->secureText($_REQUEST[$formNewUsername]) : NULL;
$password=(!empty($_REQUEST[$formNewPassword])) ? $security->secureText($_REQUEST[$formNewPassword]) : NULL;
$email=(!empty($_REQUEST[$formNewEmail])) ? $security->allowOnlyEmail($_REQUEST[$formNewEmail]) : NULL;

//Check for error
if(empty($username))
	$message->error('The username was left blank. Please click back and fill it in.');
if(empty($password))
	$message->error('The password was left blank. Please click back and fill it in.');
if(empty($email))
	$message->error('The email address was left blank. Please click back and fill it in.');

//Register user
$registerUser = new UserManagement($username, $password);
$registerUser->registerUser();

//Add extra info
$registerUser->editUserInfo($email);
	
//Display success
$message->say('New user has been sucecssfully added.');

}
else {

//Generated variables
$listOfUsers=''; //HTML code for the list of users

//Get list of users
$sql = 'SELECT username 
	FROM '.XCOMIC_USERS_TABLE.';';

if( !($result = $xcomicDb->sql_query($sql)) )
{
	$message->error("Could not get users list.");
}

while ( $row = $xcomicDb->sql_fetchrow($result) )
{
	//For each user, generate HTML
	$listOfUsers.='
	<tr class="each-user">
		<td class="list-username">'.$row['username'].'</td>
		<td class="list-userfunc"><a href="edituser.php?'.$formEditDeleteUsername.'='.$row['username'].'">Edit</a></td>
		<td class="list-userfunc"><a href="'.$_SERVER['PHP_SELF'].'?mode='.$formModeDeleteUser.'&'.$formEditDeleteUsername.'='.$row['username'].'">Delete</a></td>
	</tr>
	';
}

//Include script header
include('./includes/header.php');

//Include script menu
include('./includes/menu.php');
?>
<div class="wrap">
	<div class="section-title"><h2><?php echo $sectionTitle; ?></h2></div>
	<div class="section-body">
	<div class="user-list">
		<table cellspacing="0" cellpadding="5" class="user-list">
		<tr class="each-user">
			<td class="list-username">Username:</td>
			<td class="list-userfunc" colspan="2">Functions:</td>
		</tr>
		<?php echo $listOfUsers; ?>
		<!--
		<tr class="each-user">
			<td class="list-username">Admin</td>
			<td class="list-userfunc"><a href="">Edit</a></td>
			<td class="list-userfunc"><a href="">Delete</a></td>
		</tr>
		-->
		</table>
	</div>
	
	<p class="sub-section-title">Add a new user:</p>
	<p>
		<form method="POST" action="" enctype="multipart/form-data">
		<input type="hidden" name="mode" value="<?php echo $formModeAddUser; ?>">
		
		<p>
		Username:<br /><input type="text" name="<?php echo $formNewUsername; ?>" size="20" />
		</p>
		
		<p>
		Password:<br /><input type="password" name="<?php echo $formNewPassword; ?>" size="20" />
		</p>
		
		<p>
		E-mail Address:<br /><input type="text" name="<?php echo $formNewEmail; ?>" size="20" />
		</p>
	
		<p>
		<input type="submit" name="submit" value="Add New User" />
		</p>
		</form>
	</p>
	</div>
</div>

<?php

//Include script footer
include('./includes/footer.php');

} //End check form for submission

?>