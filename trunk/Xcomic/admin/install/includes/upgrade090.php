<?php
/**
 * Xcomic - Comic Management Script
 * (http://xcomic.sourceforge.net)
 *
 * $Id$
 */

if (!defined('IN_XCOMIC') || !defined('IN_UPGRADE_SCRIPT')) {
	die("Hacking attempt");
}

// Define schema info
$available_dbms = array(
	'mysql'=> array(
		'LABEL'			=> 'MySQL 3.x',
		'SCHEMA'		=> 'mysql', 
		'DELIM'			=> ';',
		'DELIM_BASIC'	=> ';',
		'COMMENTS'		=> 'remove_remarks'
	)/*,
	'mysql4' => array(
		'LABEL'			=> 'MySQL 4.x',
		'SCHEMA'		=> 'mysql', 
		'DELIM'			=> ';', 
		'DELIM_BASIC'	=> ';',
		'COMMENTS'		=> 'remove_remarks'
	)/*,
	'postgres' => array(
		'LABEL'			=> 'PostgreSQL 7.x',
		'SCHEMA'		=> 'postgres', 
		'DELIM'			=> ';', 
		'DELIM_BASIC'	=> ';',
		'COMMENTS'		=> 'remove_comments'
	), 
	'mssql' => array(
		'LABEL'			=> 'MS SQL Server 7/2000',
		'SCHEMA'		=> 'mssql', 
		'DELIM'			=> 'GO', 
		'DELIM_BASIC'	=> ';',
		'COMMENTS'		=> 'remove_comments'
	),
	'mssql-odbc' =>	array(
		'LABEL'			=> 'MS SQL Server [ ODBC ]',
		'SCHEMA'		=> 'mssql', 
		'DELIM'			=> 'GO',
		'DELIM_BASIC'	=> ';',
		'COMMENTS'		=> 'remove_comments'
	)*/
);

$dbms_schema = $xcomicRootPath.'admin/install/sql/' . $available_dbms[$dbms]['SCHEMA'] . '_090_upgrade.sql';

$remove_remarks = $available_dbms[$dbms]['COMMENTS'];;
$delimiter = $available_dbms[$dbms]['DELIM']; 
$delimiter_basic = $available_dbms[$dbms]['DELIM_BASIC']; 

include_once $xcomicRootPath.'admin/install/includes/sql_parse.php'; //phpBB's DB schema cleaning
$sql_query = @fread(@fopen($dbms_schema, 'r'), @filesize($dbms_schema));
//Set up table prefix
$table_prefix = (!empty($table_prefix)) ? $table_prefix : (!empty($xcomicTablePrefix)) ? $xcomicTablePrefix : 'xcomic_';
$sql_query = str_replace('xcomic_', $table_prefix, $sql_query);

//Clean up SQL file
$sql_query = remove_remarks($sql_query);

$comicauto = $db->getOne("SELECT MAX(`cid`) FROM `".$table_prefix."comics`");
$newsauto = $db->getOne("SELECT MAX(`id`) FROM `".$table_prefix."news`");
$usersauto = $db->getOne("SELECT MAX(`uid`) FROM `".$table_prefix."users`");
$numconfigs = max($db->getOne("SELECT COUNT(*) FROM `".$table_prefix."config`")-2, 0);

// Replace vars
$sql_query = str_replace(array('comics_autoinc', 'news_autoinc', 'users_autoinc', 'num_configs'), array($comicauto, $newsauto, $usersauto, $numconfigs), $sql_query);

//Get SQL statements
$sql_query = split_sql_file($sql_query, $delimiter);

//Execute queries
for ($i = 0; $i < sizeof($sql_query); $i++)
{
	if (trim($sql_query[$i]) != '') 
    {
	    $result = $db->query($sql_query[$i]);

		if (PEAR::isError($result)) 
        {
			header('Location: '.$xcomicRootPath.'admin/install/error/dbqueryfailed.php');
			exit;
		}
	}
}

$message[] = "Applied database schema changes";

$users = array();

$newsposts =& $db->getAll("SELECT `id`,`username` FROM `".$table_prefix."news`");
foreach ($newsposts as $news)
{
    if (!isset($users[$news['username']]))
    {
        $users[$news['username']] = $db->getOne("SELECT `uid` FROM `".$table_prefix."users` WHERE `username`='$news[username]' LIMIT 1");
    }
    $db->query("UPDATE `".$table_prefix."news` SET `uid`=".$users[$news['username']]." WHERE `id`=$news[id] LIMIT 1");
}
$message[] = "Applied user ids to news.";
?>
