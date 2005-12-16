<?php
/**
 * Xcomic - comic management script
 * (http://xcomic.sourceforge.net)
 * 
 * $Id$
 */

define('IN_XCOMIC', true);
$xcomicRootPath='../../';
//Include header
include_once $xcomicRootPath.'admin/install/includes/header.php';
?>
<div id="header">
	<h1><a href="http://xcomic.sourceforge.net/" title="Xcomic" class="headerlogo"><img src="<?php echo $xcomicRootPath;?>admin/styles/xcomic-small.gif" alt="Xcomic" /></a></h1>
	<div id="title">Already Installed!</div>
</div>

<div id="main">
     <div id="description">
     <p>
     Xcomic has already been installed. Therefore, this entire install script has
     been disabled. If you wish to reinstall Xcomic, please delete config.php from
     /includes/.
     </p>
     </div>
</div>

<?php
     include_once $xcomicRootPath.'admin/install/includes/footer.php';
?>
