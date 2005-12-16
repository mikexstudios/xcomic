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
	<div id="title">Include 'DB.php' Error</div>
</div>

<div id="main">
     <div id="description">
     <p>
     Xcomic could not include the 
     <a href="http://pear.php.net/package-info.php?package=DB">PEAR::DB</a> module 
     which is used to access the database. There can be a few reasons for this
     error. Please check the following:
     </p>
     <ul>
          <li>Does /includes/DB.php, /includes/PEAR.php, and /includes/DB/ exist?
          Those files should have came with your Xcomic installation. If they are
          missing, you need to redownload Xcomic to get these files. They are used
          to fallback on when PEAR::DB is not installed on your host.</li>
          <!--<li>Are you able to use the PHP function: ini_set() to override settings
          in php.ini? If your host disabled the use of ini_set(), Xcomic will not
          be able to use PEAR::DB. Please contact your host for assistance. If
          your host will not enable the use of ini_set(), then ask your host to
          install PEAR::DB on the server.</li>-->
     </ul>
     <p>
     <strong>Please check and fix the problems if they exist</strong>. If you 
     still need help you can always visit the 
     <a href='http://xcomic.mikexstudios.com/forum'>Xcomic Support Forums</a>.
     </p>
     </div>
</div>

<?php
     include_once $xcomicRootPath.'admin/install/includes/footer.php';
?>
