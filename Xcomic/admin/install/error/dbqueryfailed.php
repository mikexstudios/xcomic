<?php
/**
 * Xcomic - comic management script
 * (http://xcomic.sourceforge.net)
 * 
 * $Id$
 */

define('IN_XCOMIC', true);
$xcomicRootPath='../../../';
// Tell things we're in upgrade mode
define('IN_UPGRADE_SCRIPT', true);
//Include header
include_once $xcomicRootPath.'admin/install/includes/header.php';
?>
<div id="header">
	<h1><a href="http://xcomic.sourceforge.net/" title="Xcomic" class="headerlogo"><img src="<?php echo $xcomicRootPath;?>admin/styles/xcomic-small.gif" alt="Xcomic" /></a></h1>
	<div id="title">Unable to create and alter tables!</div>
</div>

<div id="main">
     <div id="description">
     <p>
     Xcomic was not able to create or alter tables necessary for the upgrade.
     Please check the following:
     </p>
     <ul>
          <li>One possible reason for this error is that the user that you 
          provided to access the database does not have write capabilities
          (such as using the commands CREATE TABLE, ALTER TABLE, and INSERT). Make sure the
          user has correct permissions.</li>
          <li>Another reason could be you have modified the database on your own. In this case,
          this upgrade script will not be able to function correctly and you will have to make
          changes manually.</li>
     </ul>
     <p>
     If you need help you can always visit the
     <a href='http://xcomic.mikexstudios.com/forum'>Xcomic Support Forums</a>.
     </p>
     </div>
</div>

<?php
     include_once $xcomicRootPath.'admin/install/includes/footer.php';
?>
