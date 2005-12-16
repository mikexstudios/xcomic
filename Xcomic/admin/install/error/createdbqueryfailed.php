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
	<div id="title">Unable to create and populate tables!</div>
</div>

<div id="main">
     <div id="description">
     <p>
     Xcomic was not able to create the neccessary tables used by the script
     with the database connection information you provided in step 1. Please
     check the following:
     </p>
     <ul>
          <li>One possible reason for this error is that the user that you 
          provided to access the database does not have write capabilities 
          (such as using the commands CREATE TABLE and INSERT). Make sure the
          user has correct permissions.</li>
          <li>Make sure that the database is empty before installing Xcomic.
          There is a possibility that the old tables might have conflicted with
          the new tables. Use a tool such as phpMyAdmin to DROP all tables in
          the database. You can also delete the database and recreate it too.</li>
     </ul>
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
