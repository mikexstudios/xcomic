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
	<div id="title">Error Writing config.php file!</div>
</div>

<div id="main">
     <div id="description">
     <p>
     Xcomic was not able to create the config.php file that stores your configuration
     information in /includes/. Please check the following:
     <ul>
          <li>If you are using a linux or unix based system, make sure you
          <code>chmod 755</code> the /includes/ directory. This usually involves
          right clicking the includes/ folder in your FTP client and selecting
          the chmod command. If you have shell access to your account issue the
          following command: <code>chmod 755 includes</code> while in the Xcomic
          root directory (where Xcomic.php and index.php are).</li>
          
          <li>If you are on a windows based system, /includes/ should already
          have write permissions. Please check that the directory exists.
          Otherwise please contact your host for help.</li>
     </ul>
     <p> 
     Once you have fixed the chmod problem, click back on the browser and try
     the last step again. If you still need help you can always visit the 
     <a href='http://xcomic.mikexstudios.com/forum'>Xcomic Support Forums</a>.
     </p>
     </div>
</div>

<?php
     include_once $xcomicRootPath.'admin/install/includes/footer.php';
?>
