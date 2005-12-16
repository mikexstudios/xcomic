<?php
/**
 * Xcomic - comic management script
 * (http://xcomic.sourceforge.net)
 * 
 * $Id$
 */

define('IN_XCOMIC', true);
$xcomicRootPath='../../../';
//Include header
include_once $xcomicRootPath.'admin/install/includes/header.php';
?>
<div id="header">
	<h1><a href="http://xcomic.sourceforge.net/" title="Xcomic" class="headerlogo"><img src="<?php echo $xcomicRootPath;?>admin/styles/xcomic-small.gif" alt="Xcomic" /></a></h1>
	<div id="title">Unable to create new user!</div>
</div>

<div id="main">
     <div id="description">
     <p>
     Xcomic was not able to create a new user with the database connection 
     information you provided in step 1. One possible reason for this error 
     is that the user that you provided to access the database does not have 
     write capabilities (such as using the commands CREATE TABLE and INSERT). 
     Make sure the user has correct permissions.
     </p>
     <p>
     If you're unsure of what to do, you should probably contact your host. 
     If you still need help you can always visit the 
     <a href='http://xcomic.mikexstudios.com/forum'>Xcomic Support Forums</a>.
     </p>
     </div>
</div>

<?php
     include_once $xcomicRootPath.'admin/install/includes/footer.php';
?>
