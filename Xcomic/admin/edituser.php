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
$sectionTitle = 'Edit User';
$username = ''; //User being edited
$formUsername = 'username';
$formPassword = 'password';
$formEmail = 'email';
$formModeAction = 'edituser';
$urlUsername = 'username'; //For the GET url
$formModeEditUser = 'edit';
$formModeDeleteUser = 'delete';

$username=(!empty($_REQUEST[$urlUsername])) ? $security->secureText($_REQUEST[$urlUsername]) : NULL;
//If username is null, redirect to users
if(empty($username))
	header('Location: users.php');

//Check for form submission for delete user
$mode=(!empty($_REQUEST['mode'])) ? $security->secureText($_REQUEST['mode']) : NULL;
if($mode == $formModeDeleteUser) 
{
	//Delete user using User Management class
	$deleteUser = new UserManagement($username);
	$deleteUser->deleteUser();
	
	//Display success
	$message->say('User has been sucecssfully deleted.');
}

//Check for form submission for edit user
if($_REQUEST['mode'] == $formModeAction) {

$password=(!empty($_REQUEST[$formPassword])) ? $security->secureText($_REQUEST[$formPassword]) : NULL;
$email=(!empty($_REQUEST[$formEmail])) ? $security->allowOnlyEmail($_REQUEST[$formEmail]) : NULL;

//Check for error
/* Password can be blank
if(empty($password))
	$message->error('The password was left blank. Please click back and fill it in.');
*/
if(empty($email))
	$message->error('The email address was left blank. Please click back and fill it in.');

//Register user
$userFunctions = new UserManagement($username, $password);

//Check if user exists
if($userFunctions->userExists())
{
	$userFunctions->editUserInfo($email);
	//If password is left blank, do not change
	if(!empty($password))
		$userFunctions->changePassword($password);
}
else 
{
	$message->error('The user you are trying to edit does not exist!');
}
	
//Display success
$message->say('User has been sucecssfully modified.');

}
else {
	
//Check to see if mode is edit
if($mode != $formModeEditUser) 
{
	$message->error('Invalid mode specified!');
}

//Get user information
//Get list of users. SELECT [options go here]
$sql = 'SELECT email 
	FROM '.XCOMIC_USERS_TABLE."
	WHERE username = '$username';";

if( !($result = $xcomicDb->sql_query($sql)) )
{
	$message->error("Could not get users list.");
}
$row = $xcomicDb->sql_fetchrow($result);

//Set variables
$userEmail = $row['email'];

//Include script header
include('./includes/header.php');

//Include script menu
include('./includes/menu.php');
?>
<div class="wrap">
	<div class="section-title"><h2><?php echo $sectionTitle; ?></h2></div>
	<div class="section-body">
	<div class="user-list">
	<p>
		<form method="POST" action="" enctype="multipart/form-data">
		<input type="hidden" name="mode" value="<?php echo $formModeAction; ?>">
		
		<p>
		Username: <input type="hidden" name="<?php echo $formUsername; ?>" value="<?php echo $username; ?>" /><strong><?php echo $username; ?></strong>
		</p>
		
		<p>
		Change Password (leave blank to keep current password):<br /><input type="password" name="<?php echo $formPassword; ?>" size="20" />
		</p>
		
		<p>
		Change E-mail Address:<br /><input type="text" name="<?php echo $formEmail; ?>" value="<?php echo $userEmail; ?>" size="20" />
		</p>
	
		<p>
		<input type="submit" name="submit" value="Make Changes to User" />
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