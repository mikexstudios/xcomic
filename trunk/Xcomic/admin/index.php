<?php
/**
Xcomic

$Id$
*/
//Xcomic settings
define('IN_XCOMIC', true);
$xcomicRootPath = '../';
require_once $xcomicRootPath.'initialize.php';	//Include all page common settings
							//Creates $xcomicDb connection. Grabs config info.

include_once './includes/authorization.php'; //Verify that user is logged in

//Since the script gets here, user has access. Go to admin panel's post screen.
$redirect = $settings->getSetting('urlToXcomic') . '/admin/postcomic.php';
header('Location: '.$redirect);
?>