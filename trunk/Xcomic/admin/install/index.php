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

//Include header
include_once $xcomicRootPath.'admin/install/includes/header.php';
?>
<div id="header">
	<h1><a href="http://xcomic.sourceforge.net/" title="Xcomic" class="headerlogo"><img src="<?php echo $xcomicRootPath;?>admin/styles/xcomic-small.gif" alt="Xcomic" /></a></h1>
	<div id="title">Installation</div>
</div>

<div id="main">
     <div id="description">
     <p>
     Welcome to the installation of Xcomic! Before we begin, please make sure
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
          Obtain the following information to access the database that Xcomic 
          will be using:
               <ul>
                    <li>Database name</li>
                    <li>Database username</li>
                    <li>Database password</li>
                    <li>Database host</li>
                    <li>Table prefix (if you want to run more than one Xcomic installation in a single database)</li>
               </ul>
          
          </li>
     </ol>
     <!--
     <p>
     <strong>Warning: If your permissions are not set correctly, the configuration
     file will not be written, and you will not be able to upload any comics. If
     this installation fails, please double check your permissions.</strong>
     </p>
     <p>
     <strong>Warning: If you enter incorrect database connectivity information,
     the configuration file will not be written and Xcomic will not install
     successfully. If this installation fails, please check your database
     connectivity information.</strong>
     </p>
     -->
     <form action="step01.php" method="post">
          <input type="submit" name="submit" value="Continue &gt;" class="continuebutton" />
     </form>
     </div>
</div>

<?php
     include_once $xcomicRootPath.'admin/install/includes/footer.php';
?>
