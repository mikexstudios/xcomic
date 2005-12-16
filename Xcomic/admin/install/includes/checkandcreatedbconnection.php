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

//Add local PEAR packages to include path
//@ini_set("include_path", $xcomicRootPath.'includes/' . PATH_SEPARATOR . ini_get("include_path"));

if (!defined('USE_XCOMIC_PEAR') || !constant('USE_XCOMIC_PEAR'))
{
    @include 'DB.php'; // PEAR library. Note include, NOT require, and lack of _once.
}
if (!class_exists('DB'))
{
    // No PEAR library. Use our own
    //@ini_set("include_path", $xcomicRootPath.'includes' . PATH_SEPARATOR . ini_get("include_path"));
    @include $xcomicRootPath.'includes/DB.php';

    if(!class_exists('DB'))
    {
         header('Location: '.$xcomicRootPath.'admin/install/error/includedbfailed.php');
         exit;
    }
}

//Create the $dsn in the file that includes this for maximum functionality.

$db = DB::connect($dsn, $options);


//Check connection. Make sure incoming data is not all null. Allow for
//empty password or empty prefix
if (PEAR::isError($db))
{
     //Db connection failed
     header('Location: '.$xcomicRootPath.'admin/install/error/dbfailed.php');
     exit;
}

?>
