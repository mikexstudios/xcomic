<?php
/**
Xcomic

$Id$
*/

//Xcomic settings
define('IN_XCOMIC', true);
$xcomicRootPath = "../";

require_once($xcomicRootPath.'initialize.php');	//Include all page common settings
							//Creates $xcomicDb connection. Grabs config info.
include_once($xcomicRootPath.'includes/Security.'.$classEx);
include_once('./classes/UserManagement.'.$classEx); //Login/Logout

//Form field variables
$formUsername = 'username';
$formPassword = 'password';

//Create objects
$security = new Security();
$userManagement = new UserManagement();

//Get input from form
$inUsername=(!empty($_REQUEST[$formUsername])) ? $security->allowOnlyChars($_REQUEST[$formUsername]) : NULL;
$inPassword=(!empty($_REQUEST[$formPassword])) ? $security->allowOnlyChars($_REQUEST[$formPassword]) : NULL;
	
//Set them in User Management
$userManagement->setUsername($inUsername);
$userManagement->setPassword($inPassword);	

//Process login information
if($userManagement->processLogin('remember'))
{
	//Redirect to index.php
	header('Location: index.php');
}


//Otherwise, show login page
	
//Include script header
include('./includes/header.php');
							
?>
<div class="wrap">
	<div class="section-title"><h2>Login</h2></div>
	<div class="section-body">
	<form method="POST" action="" enctype="multipart/form-data">
	
	<p>
	Username:<br /><input type="text" name="<?php echo $formUsername; ?>" size="20" />
	</p>
	
	<p>
	Password:<br /><input type="password" name="<?php echo $formPassword; ?>" size="20" />
	</p>

	<p>
	<input type="submit" name="submit" value="Login" />
	</p>
	</form>
	</div>
</div>
<?php

//Include script footer
include('./includes/footer.php');

//End of script
?>