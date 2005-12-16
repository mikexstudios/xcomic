<?php
/**
 * Xcomic - Comic Management Script
 * (http://xcomic.sourceforge.net)
 *
 * $Id$
 */

if (!defined('IN_XCOMIC')) {
	die("Hacking attempt");
}

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<title>Xcomic - <?php echo (defined('IN_UPGRADE_SCRIPT') ? 'Upgrade' : 'Installation'); ?></title>
<meta name="description" content="Xcomic - A comic management script" />
<meta name="keywords" content="xcomic, comic, manga, web comic, comic management script, web, script, php" />
<meta name="author" content="Xcomic group" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link rel="stylesheet" type="text/css" href="<?php echo $xcomicRootPath;?>admin/install/style.css" title="default" media="screen" />
</head>

<body>
<div id="page-container">

