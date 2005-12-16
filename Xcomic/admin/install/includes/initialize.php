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

define('XCOMIC_CODE_VERSION', '0.9.0'); // Version of uploaded code!

function parseVersion($ver)
{
    $spaced = explode(' ', $ver);
    $parsed = explode('.', $spaced[0]);
    return array(
        'major' => $parsed[0],
        'minor' => $parsed[1],
        'micro' => $parsed[2],
        'rest' => (count($spaced) > 1 ? $spaced[1] : '')
    );
}

function versionCompare($dbver, $codever)
{
    if (empty($dbver))
        return -1;
    $db = parseVersion($dbver);
    $code = parseVersion($codever);
    
    if ($db['major'] < $code['major'])
        return -1;
    if ($db['major'] > $code['major'])
        return 1;

    if ($db['minor'] < $code['minor'])
        return -1;
    if ($db['minor'] > $code['minor'])
        return 1;

    if ($db['micro'] < $code['micro'])
        return -1;
    if ($db['micro'] > $code['micro'])
        return 1;
    
    // Check rest

    return 0;
}

if (file_exists($xcomicRootPath."includes/config.php"))
{
    @include_once($xcomicRootPath."includes/config.php");

    if (!defined('XCOMIC_INSTALLED')) // Invalid config file
    {
        unlink($xcomicRootPath."includes/config.php"); // Delete, and continue install
    }
    else if (defined('XCOMIC_INSTALLED') && !defined('IN_UPGRADE_SCRIPT'))
    {
        header("Location: installed.php");
        exit;
    }
}


if (defined('IN_UPGRADE_SCRIPT'))
{
    if (!file_exists($xcomicRootPath."includes/config.php")) // Not actually installed.
    {
        header("Location: index.php");
        exit;
    }
    
    if ($dbms == 'mysql4') // mysql4 isn't really valid
        $dbms = 'mysql';

    if (!isset($dbHost)) // Pre-0.9.0 config files
    {
        $dbHost = $xcomicDbHost;
        $dbName = $xcomicDbName;
        $dbUser = $xcomicDbUser;
        $dbPasswd = $xcomicDbPasswd;
    }

    $dsn = array(
         'phptype'  => $dbms,
         'username' => $dbUser,
         'password' => $dbPasswd,
         'hostspec' => $dbHost,
         'database' => $dbName,
    );
    $options = null;

    include_once($xcomicRootPath."admin/install/includes/checkandcreatedbconnection.php");
    $db->setFetchMode(DB_FETCHMODE_ASSOC);
    
    // Now check database version...
    $db_version = $db->getOne("SELECT `value` FROM `".$table_prefix."config` WHERE `option`='version' LIMIT 1");
    if (!empty($db_version) && versionCompare($db_version, XCOMIC_CODE_VERSION) >= 0)
    {
        header("Location: noupgrade.php");
        exit;
    }
}
?>