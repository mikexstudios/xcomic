<?php
/**
Xcomic

$Id$
*/

//Xcomic settings
define('IN_XCOMIC', true);
$xcomicRootPath = '../';
require_once './admininitialize.php';	//Include all admin common settings

//Form field variables
$formNewUsername = 'newusername';
$formNewPassword = 'newpassword';
$formNewEmail = 'newemail';

//Check for form submission for add user
if (isset($_POST['submit'])) {
		
$username=(!empty($_REQUEST[$formNewUsername])) ? $security->secureText($_REQUEST[$formNewUsername]) : NULL;
$password=(!empty($_REQUEST[$formNewPassword])) ? $security->secureText($_REQUEST[$formNewPassword]) : NULL;
$email=(!empty($_REQUEST[$formNewEmail])) ? $security->allowOnlyEmail($_REQUEST[$formNewEmail]) : NULL;

//Check for error
if (empty($username)) {
	$message->error('The username was left blank. Please click back and fill it in.');
}
if (empty($password)) {
	$message->error('The password was left blank. Please click back and fill it in.');
}
if (empty($email)) {
	$message->error('The email address was left blank. Please click back and fill it in.');
}

//Register user
$user =& new UserManagement($db);
$user->registerUser($username, $password, $email);
	
//Display success
$message->say('New user has been sucecssfully added.');

} else {

//Generated variables
$listOfUsers=''; //HTML code for the list of users

//Get list of users
$sql = '
    SELECT uid, username 
	FROM '.XCOMIC_USERS_TABLE;
$result = $db->getAll($sql);
if (PEAR::isError($result)) {
	$message->error('Could not get users list.');
}

//Include script header
include './includes/header.php';

//Include script menu
include './includes/menu.php';
?>

<div class="wrap">
 <h2>Users</h2>
 <div class="section-body">
  <table cellpadding="3" cellspacing="3" width="100%">
   <tr>
    <th>Username</th>
    <th>Edit</th>
    <th>Delete</th>
   </tr>
<?php
foreach ($result as $row) {
?>
   <tr class="alternate">
    <td><strong><?php echo $row['username']; ?></strong></td>
    <td><a href="edituser.php?mode=<?php echo 'edit'; ?>&amp;id=<?php echo $row['uid']; ?>" class="edit">Edit</a></td>
    <?php if (sizeof($result) == "1") {	?> <td><center>Delete</center></td> <?php }
    else { ?>
    <td><a href="edituser.php?mode=<?php echo 'delete'; ?>&amp;id=<?php echo $row['uid']; ?>" class="delete" onclick="return confirm('You are about to delete this user. \'OK\' to delete, \'Cancel\' to stop.')">Delete</a></td>
    <?php } ?>
   </tr>
<?php
}
?>	
  </table>
 </div>
</div>

<div class="wrap">
 <h2>Add New User</h2>
 <div class="section-body">
  <form method="post" action="">
   <label for="<?php echo $formNewUsername; ?>">Username:</label><br />
   <input type="text" name="<?php echo $formNewUsername; ?>" size="20" /><br />

   <label for="<?php echo $formNewPassword; ?>">Password:</label><br />
   <input type="password" name="<?php echo $formNewPassword; ?>" size="20" /><br />

   <label for="<?php echo $formNewEmail; ?>">E-mail Address:</label><br />
   <input type="text" name="<?php echo $formNewEmail; ?>" size="20" /><br />

   <p class="submit">
    <input type="submit" name="submit" value="Add New User" />
   </p>
  </form>
 </div>
</div>

<?php

//Include script footer
include './includes/footer.php';

} //End check form for submission
?>