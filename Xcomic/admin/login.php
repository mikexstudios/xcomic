<?php
/**
Xcomic

$Id$
*/

//Xcomic settings
define('IN_XCOMIC', true);
$xcomicRootPath = '../';

require_once $xcomicRootPath.'initialize.php';	//Include all page common settings
							//Creates $db connection. Grabs config info.
include_once $xcomicRootPath.'includes/Security.'.$classEx;

//Create AdminMessage class
include_once './classes/AdminMessage.'.$classEx;
$message = new AdminMessage();

include_once './classes/UserManagement.'.$classEx; //Login/Logout

//Form field variables
$formUsername = 'loginUsername';
$formPassword = 'loginPassword';

//Create objects
$security = new Security($db);
$userManagement = new UserManagement($db);

//Get input from form
$inUsername = !empty($_REQUEST['username']) ? $security->allowOnlyChars($_REQUEST['username']) : null;
$inPassword = !empty($_REQUEST['password']) ? $security->allowOnlyChars($_REQUEST['password']) : null;

//Set them in User Management
$userManagement->setUsername($inUsername);
$userManagement->setPassword($inPassword);	

//Process login information
if ($userManagement->processLogin('remember')) {
	header('Location: index.php');
}

//Otherwise, show login page
	
//Include script header
include './includes/header.php';
							
?>
<div class="wrap">
 <h2>Login</h2>
 <div class="section-body">
  <form method="post" action="">
   <label for="username">Username:</label><br />
   <input type="text" name="username" size="20" /><br />

   <label for="password">Password:</label><br />
   <input type="password" name="password" size="20" /><br />

   <input type="submit" name="submit" value="Login" />
  </form>
 </div>
</div>
<?php
//Include script footer
include './includes/footer.php';
//End of script
?>