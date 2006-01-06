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

//The database connection is okay! Now write config.php file and populate
//the database. It would really suck if the install failed after this point
//since config.php has already been written...

if(!file_exists($xcomicRootPath.'/includes/config.php.sample'))
{
     header('Location: '.$xcomicRootPath.'admin/install/error/configsamplemissing.php');
     exit;
}

/*
Sample file:

//This is the provided sample Xcomic configuation file. Please run 
//admin/install/index.php to install the script instead! 
//$Id$

if (!defined('IN_XCOMIC')) {
	die("Hacking attempt");
}

$dbms = 'mysql'; //Database type

$xcomicDbHost = 'localhost'; //Database host
$xcomicDbName = 'xcomicdb'; //Database name
$xcomicDbUser = 'xcomicuser'; //Database user
$xcomicDbPassword = 'password'; //Database password

$tablePrefix = 'xcomic_'; //Prefix for creating the tables

*/

//Read the config.php.sample file
$configSampleFile = implode('', file($xcomicRootPath.'/includes/config.php.sample'));
//Set search and replaces
$search[] = 'mysql'; $replace[] = $dbms;
$search[] = 'localhost'; $replace[] = $inDbHost;
$search[] = 'xcomicdb'; $replace[] = $inDbName;
$search[] = 'xcomicuser'; $replace[] = $inDbUser;
$search[] = 'password'; $replace[] = $inDbPass;
$search[] = 'xcomic_'; $replace[] = $inPrefix;

$configSampleFile = str_replace($search, $replace, $configSampleFile);
//echo $configSampleFile;

//Since the installation is broken up into different files, a way to
//keep the config.php check that will lock out attackers is to generate
//a temporary config file that will be renamed during the last step.
if (!($fp = @fopen($xcomicRootPath.'includes/config.temp.php', 'w')))
{
     header('Location: '.$xcomicRootPath.'admin/install/error/writingconfig.php');
     exit;
	//die('ERROR: includes/config.php could not be created. This is probably because you did not chmod 777 your includes directory. Please click back and try again after you have fixed this problem.');
}
else
{
	$result = @fputs($fp, $configSampleFile, strlen($configSampleFile));
	@fclose($fp);
	//Make the config file world-writable so that the user can delete it if
	//the webserver steals permissions on it.
	chmod($xcomicRootPath . 'includes/config.temp.php', 0755);
}


?>
