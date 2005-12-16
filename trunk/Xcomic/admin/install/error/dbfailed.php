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
	<div id="title">Database Connect Error</div>
</div>

<div id="main">
     <div id="description">
     <p>
     Xcomic could not connect to the database with the information you provided.
     There are a few things you should double check:
     </p>
     <ul>
          <li>Did you select the correct database type and version?</li>
          <li>Are you sure you have the correct database name?</li>
          <li>Are you sure you have the correct username and password?</li>
          <li>Are you sure that you have typed the correct hostname?</li>
          <li>Are you sure that the database server is running?</li>
     </ul>
     <p>
     <strong>Click the back button on your browser and try correcting the 
     information</strong>. If you're unsure what these terms mean you should 
     probably contact your host. If you still need help you can always visit the 
     <a href='http://xcomic.mikexstudios.com/forum'>Xcomic Support Forums</a>.
     </p>
     </div>
</div>

<?php
     include_once $xcomicRootPath.'admin/install/includes/footer.php';
?>
