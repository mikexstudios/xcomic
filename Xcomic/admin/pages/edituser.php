<?php
/**
Xcomic

$Id$
*/

//Check to see if being included by another file (ie. Xadmin.php)
if(basename($_SERVER['PHP_SELF']) !== basename(__FILE__))
{
     //This file will not be registered with the menu.
     return;
}

//Every admin page requires the following:
$xcomicRootPath = '../../';
require_once $xcomicRootPath.'admin/includes/admininitialize.php';
include_once $xcomicRootPath.'admin/Xadmin.php';
$xadmin = new Xadmin($db);

$id = (!empty($_REQUEST['id'])) ? $security->secureText($_REQUEST['id']) : null;
//If username is null, redirect to users
if (empty($id)) {
	header('Location: '.$xcomicRootPath.'admin/pages/users.php');
}

$mode = (!empty($_REQUEST['mode'])) ? $security->secureText($_REQUEST['mode']) : null;

//Check for form submission for delete user
if ($mode == 'delete')  {
	//Delete user using User Management class
	$deleteUser = new UserManagement($db, $id);
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
$userFunctions = new UserManagement($db, $id);

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
include $xcomicRootPath.'admin/includes/header.php';

//Include script menu
include $xcomicRootPath.'admin/includes/menu.php';
?>
<div class="wrap">
 <h2>Edit <?php echo $row['username']; ?></h2>
 <div class="section-body">
  <form method="post" action="">
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
include $xcomicRootPath.'admin/includes/footer.php';
} //End check form for submission
?>
