<?php
/**
 * Xcomic - comic management script
 * (http://xcomic.sourceforge.net)
 * 
 * $Id$
 */

define('IN_XCOMIC', true);
define('IN_UPGRADE_SCRIPT', true);
$xcomicRootPath='../../';
//Include header
include_once $xcomicRootPath.'admin/install/includes/header.php';
?>
<div id="header">
	<h1><a href="http://xcomic.sourceforge.net/" title="Xcomic" class="headerlogo"><img src="<?php echo $xcomicRootPath;?>admin/styles/xcomic-small.gif" alt="Xcomic" /></a></h1>
	<div id="title">Upgrade not needed!</div>
</div>

<div id="main">
     <div id="description">
     <p>
     Xcomic has already been upgraded to the current version. Therefore, this entire upgrade script has
     been disabled.
     </p>
     </div>
</div>

<?php
     include_once $xcomicRootPath.'admin/install/includes/footer.php';
?>
