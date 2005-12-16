<?php
/**
 * Xcomic - Comic Management Script
 * (http://xcomic.sourceforge.net)
 *
 * $Id$
 */

$xcomicRootPath = '../../';
define('IN_XCOMIC', true);

// Tell things we're in upgrade mode
define('IN_UPGRADE_SCRIPT', true);

//Check to see if not installed or if there is no need to upgrade
include_once $xcomicRootPath.'admin/install/includes/initialize.php';

include_once $xcomicRootPath.'includes/Security.class.php';
$security = new Security();

$username = !empty($_POST['username']) ? $security->secureText($security->allowOnlyChars($_POST['username'])) : null;
$password = !empty($_POST['password']) ? $security->allowOnlyChars($_POST['password']) : null;

$admin = $db->getRow("SELECT * FROM `".$table_prefix."users` WHERE `username`='$username' LIMIT 1");
if (PEAR::isError($admin) || !isset($admin['password']) || $admin['password'] != md5($password))
{
    header("Location: error/adminloginfailed.php");
    exit;
}

// Begin upgrading the database!
$message = array();

if (versionCompare($db_version, '0.9.0') < 0) // Versions pre-0.9.0
{
    // Create new-style config file
    $inDbHost = $dbHost;
    $inDbUser = $dbUser;
    $inDbPasswd = $dbPasswd;
    $inDbName = $dbName;
    include_one($xcomicRootPath.'admin/install/includes/createconfigfile.php');
    // Above is created in a temporary space. Now move it.
    unlink($xcomicRootPath."includes/config.php");
    if(!rename($xcomicRootPath.'includes/config.temp.php', $xcomicRootPath.'includes/config.php'))
    {
         header('Location: '.$xcomicRootPath.'admin/install/error/writingconfig.php');
         exit;
    }
    $message[] = "Updated config.php file";

    // Run the 0.8.x -> 0.9.0 upgrade script.
    include_once $xcomicRootPath.'admin/install/includes/upgrade090.php';
}

// Update version in database.
if (versionCompare($db_version, '0.9.0') < 0)
{
    // Insert version row...
    $order = $db->getOne("SELECT MAX(`order`) FROM `".$table_prefix."config`");
    $db->query("INSERT INTO `".$table_prefix."config` SET `order`=$order+1,`option`='version',`value`='".XCOMIC_CODE_VERSION."',`name`='Xcomic Version',`description`='The current version of Xcomic'");
    $message[] = "Finalized Xcomic version";
}
else
{
    $db->query("UPDATE `".$table_prefix."config` SET `value`='".XCOMIC_CODE_VERSION."' WHERE `option`='version' LIMIT 1");
    $message[] = "Finalized Xcomic version";
}

include_once $xcomicRootPath.'admin/install/includes/header.php';
?>

<div id="header">
	<h1><a href="http://xcomic.sourceforge.net/" title="Xcomic" class="headerlogo"><img src="<?php echo $xcomicRootPath;?>admin/styles/xcomic-small.gif" alt="Xcomic" /></a></h1>
	<div id="title">Xcomic Upgrade Completed</div>
</div>

<div id="main">
     <div id="description">
     <p>
     Upgrading...
     <?php
     foreach ($line in $message)
     {
         echo "...$line<br />";
     }
     ?>
     </p>
     <p>
        Congratulations! Xcomic successfully upgraded from <?php echo $db_version; ?> to <?php XCOMIC_CODE_VERSION; ?>!
     </p>
     <form action="../index.php" method="post">
          <input type="submit" name="submit" value="Visit the administration panel &gt;" class="continuebutton" />
     </form>
     </div>
</div>

<?php
include_once $xcomicRootPath.'admin/install/includes/footer.php';
?>