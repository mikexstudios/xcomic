<?php
/**
Xcomic

$Id$
*/

//Xcomic settings
define('IN_XCOMIC', true);
$xcomicRootPath = '../';
require_once './admininitialize.php';	//Include all admin common settings

$id = (!empty($_REQUEST['id'])) ? $security->secureText($_REQUEST['id']) : null;
//If username is null, redirect to users
if (empty($id)) {
	header('Location: users.php');
}

$mode = (!empty($_REQUEST['mode'])) ? $security->secureText($_REQUEST['mode']) : null;

//Check for form submission for delete user
if ($mode == 'delete')  {
	//Delete user using User Management class
	$deleteUser = new UserManagement($id);
	$deleteUser->deleteUser();
	
	//Display success
	$message->say('User has been sucecssfully deleted.');
}

//Check for form submission for edit user
if (isset($_POST['submit'])) {

$password = (!empty($_REQUEST['editPassword'])) ? $security->secureText($_REQUEST['editPassword']) : null;
$email = (!empty($_REQUEST['email'])) ? $security->allowOnlyEmail($_REQUEST['email']) : null;

//Check for error
/* Password can be blank
if(empty($password))
	$message->error('The password was left blank. Please click back and fill it in.');
*/
if (empty($email)) {
	$message->error('The email address was left blank. Please click back and fill it in.');
}
//Register user
$userFunctions = new UserManagement($id);

//Check if user exists
if ($userFunctions->userExists()) {
	$userFunctions->editUserInfo($email);
	//If password is left blank, do not change
	if (!empty($password)) {
		$userFunctions->changePassword($password);
    }
} else  {
	$message->error('The user you are trying to edit does not exist!');
}
	
//Display success
$message->say('User has been sucecssfully modified.');

} else {
	
//Check to see if mode is edit
if ($mode != 'edit')  {
	$message->error('Invalid mode specified!');
}

//Get user information
//Get list of users. SELECT [options go here]
$sql = 'SELECT username, email 
	FROM '.XCOMIC_USERS_TABLE."
	WHERE uid = '$id';";
$row = $db->getRow($sql);
if (PEAR::isError($row)) {
	$message->error("Could not get users list.");
}

//Include script header
include './includes/header.php';

//Include script menu
include './includes/menu.php';
?>
<div class="wrap">
 <h2>Edit <?php echo $row['username']; ?></h2>
 <div class="section-body">
  <form method="POST" action="" enctype="multipart/form-data">
   <label for="editPassword">Change Password (leave blank to keep current password):</label><br />
   <input type="password" name="editPassword" size="20" /><br />

   <label for="email">Change E-mail Address:</label><br />
   <input type="text" name="email" value="<?php echo $row['email']; ?>" size="20" /><br />

   <input type="submit" name="submit" value="Make Changes to User" />
  </form>
 </div>
</div>

<?php
//Include script footer
include './includes/footer.php';
} //End check form for submission
?>