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
$formNewUsername = 'newusername';
$formNewPassword = 'newpassword';
$formNewEmail = 'newemail';
$formModeAddUser = 'adduser';
$formModeEditUser = 'edit';
$formModeDeleteUser = 'delete';
$formEditDeleteUsername = 'username'; //For the GET url

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

//Include script header
include('./includes/header.php');

//Include script menu
include('./includes/menu.php');
?>

<div class="wrap">
	<div class="section-title"><h2>Users</h2></div>
	<div class="section-body">
	<table cellpadding="3" cellspacing="3" width="100%">
		<tr>
			<th>Username</th>
			<th>Edit</th>
			<th>Delete</th>
		</tr>
<?php
while ( $row = $xcomicDb->sql_fetchrow($result) )
{
?>
		<tr class="alternate">
			<td><strong><?php echo $row['username']; ?></strong></td>
			<td><a href="edituser.php?mode=<?php echo $formModeEditUser; ?>&<?php echo $formEditDeleteUsername; ?>=<?php echo $row['username']; ?>" class="edit">Edit</a></td>
			<td><a href="edituser.php?mode=<?php echo $formModeDeleteUser; ?>&<?php echo $formEditDeleteUsername; ?>=<?php echo $row['username']; ?>" class="delete" onclick="return confirm('You are about to delete this user. \'OK\' to delete, \'Cancel\' to stop.')">Delete</a></td>
		</tr>
<?php
}
?>	
	</table>
	</div>
</div>

<div class="wrap">
	<div class="section-title"><h2>Add New User</h2></div>
	<div class="section-body">
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
	
		<p class="submit">
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