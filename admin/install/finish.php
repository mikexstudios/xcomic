<?php
/**
 * Xcomic - comic management script
 * (http://xcomic.sourceforge.net)
 * 
 * $Id$
 */
 
$xcomicRootPath = '../../';
define('IN_XCOMIC', true);

//Need this here to check if the script has already been installed.
include_once $xcomicRootPath.'admin/install/includes/initialize.php';

//Create admin user
$inNewUser = (!empty($_REQUEST['adminuser'])) ? $_REQUEST['adminuser'] : leftBlank();
$inNewPass = (!empty($_REQUEST['adminpassword'])) ? $_REQUEST['adminpassword'] : leftBlank();
$inNewPass2 = (!empty($_REQUEST['adminpassword2'])) ? $_REQUEST['adminpassword2'] : leftBlank();
$inNewEmail = (!empty($_REQUEST['adminemail'])) ? $_REQUEST['adminemail'] : '';

//If any of them are blank, error
function leftBlank() {
	global $xcomicRootPath;
	
	message("
     <p>
     Xcomic was not able to create a new user since you did not provide all of
     the necessary user information (a username and password). Please click
     back on your browser and correct this mistake.
     </p>
     <p>
     If you still need help you can always visit the 
     <a href='http://xcomic.mikexstudios.com/forum'>Xcomic Support Forums</a>.
     </p>
     ", 
	'Some User Fields Left Blank');
     exit;
}

//Check to see if both passwords are the same
if($inNewPass != $inNewPass2)
{
	global $xcomicRootPath;
	
	message("
     <p>
     Xcomic was not able to create a new user since the <strong>passwords you
	have entered do not match</strong>. Please click
     back on your browser and correct this mistake.
     </p>
     <p>
     If you still need help you can always visit the 
     <a href='http://xcomic.mikexstudios.com/forum'>Xcomic Support Forums</a>.
     </p>
     ", 
	'Passwords do not match');
     exit;
}

//Create the new user
include_once $xcomicRootPath.'admin/install/includes/createnewuser.php';

//Include header
include_once $xcomicRootPath.'admin/install/includes/header.php';

//Move the configuration file over since everything is successful. This must
//occur after the header or else the script will think it has already been installed.
if(!rename($xcomicRootPath.'includes/config.temp.php', $xcomicRootPath.'includes/config.php'))
{
     header('Location: '.$xcomicRootPath.'admin/install/error/writingconfig.php');
     exit;
}

?>
<div id="header">
	<h1><a href="http://xcomic.sourceforge.net/" title="Xcomic" class="headerlogo"><img src="../styles/xcomic-small.gif" alt="Xcomic" /></a></h1>
	<div id="title">Completed: Xcomic is installed!</div>
</div>

<div id="main">
     <div id="description">
     <p>
     <strong>Congratulations! Xcomic has been successfully installed!</strong>
     </p>
     <p>
     Please continue to  the administration panel by clicking the button below. 
     That is where you can start using all of Xcomic's features such as posting 
     new comics and writing news entries. If you ever run into any problems, drop 
     by the <a href="http://xcomic.mikexstudios.com/forum">Xcomic forums</a> where
     you can ask questions and get answers. Also, if you'd like, let others
     know about your site by adding it to the 
     <a href="http://xcomic.mikexstudios.com/SitesUsingXcomic">sites using 
     Xcomic page</a> (registration on the wiki is required). Good luck with
     your site!
     </p>
     <form action="../index.php" method="post" >
          <input type="submit" name="submit" value="Visit the administration panel &gt;" class="continuebutton" />
     </form>
     </div>
</div>

<?php
     include_once $xcomicRootPath.'admin/install/includes/footer.php';
?>
