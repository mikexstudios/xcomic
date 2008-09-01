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

include_once $xcomicRootPath.'admin/install/includes/header.php';
?>

<div id="header">
	<h1><a href="http://xcomic.sourceforge.net/" title="Xcomic" class="headerlogo"><img src="<?php echo $xcomicRootPath;?>admin/styles/xcomic-small.gif" alt="Xcomic" /></a></h1>
	<div id="title">Xcomic Upgrade</div>
</div>

<div id="main">
     <div id="description">
     <p>
     Welcome to the Xcomic upgrade script! Before we begin, please make sure
     you have completed the following tasks:
     </p>
     <ol>
          <li>
          Make sure that this script has the permissions to write to the
          directories <code>includes/</code> and <code>comics/</code>. If you 
          are running Xcomic on a Linux server, this means that you must 
          <code>chmod 777 includes</code> and <code>chmod 777 comics</code>.
          Windows users should have write permissions by default.
          </li>
          <li>
          Enter your administrative username and password in the fields below to
          process the upgrade.
          </li>
     </ol>
     <form action="upgradeprocess.php" method="post">
          <label for="username">Administrator Username</label>
          <input type="text" name="username" /><br />
          <label for="password">Administrator Password</label>
          <input type="password" name="password" /><br />
          <input type="submit" name="submit" value="Continue &gt;" class="continuebutton" />
     </form>
     </div>
</div>

<?php
include_once $xcomicRootPath.'admin/install/includes/footer.php';
?>