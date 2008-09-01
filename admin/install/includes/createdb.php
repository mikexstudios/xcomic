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

//Make sure database connection exists
include_once $xcomicRootPath.'admin/install/includes/checkandcreatedbconnection.php';

//Now that we have a database connection, let's read the schema
//and try to load it. This whole section was lifted off of phpBB's
//install file

// Define schema info
$available_dbms = array(
	'mysql'=> array(
		'LABEL'			=> 'MySQL 3.x',
		'SCHEMA'		=> 'mysql', 
		'DELIM'			=> ';',
		'DELIM_BASIC'	=> ';',
		'COMMENTS'		=> 'remove_remarks'
	), 
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

$dbms_schema = $xcomicRootPath.'admin/install/sql/' . $available_dbms[$dbms]['SCHEMA'] . '_schema.sql';

$remove_remarks = $available_dbms[$dbms]['COMMENTS'];;
$delimiter = $available_dbms[$dbms]['DELIM']; 
$delimiter_basic = $available_dbms[$dbms]['DELIM_BASIC']; 

include_once $xcomicRootPath.'admin/install/includes/sql_parse.php'; //phpBB's DB schema cleaning
$sql_query = @fread(@fopen($dbms_schema, 'r'), @filesize($dbms_schema));
//Set up table prefix
$table_prefix = (!empty($inPrefix)) ? $inPrefix : 'xcomic_';
$sql_query = str_replace('xcomic_', $table_prefix, $sql_query);

//Clean up SQL file
$sql_query = remove_remarks($sql_query);

//Get SQL statements
$sql_query = split_sql_file($sql_query, $delimiter);

//Add Options
$sql_query[] = 'INSERT INTO `' . $table_prefix . 'config` VALUES ("1", "version", "'.XCOMIC_CODE_VERSION.'", "string", "", "Xcomic Version", "The current version of Xcomic")';
$sql_query[] = 'INSERT INTO `' . $table_prefix . 'config` VALUES ("2", "title", "Xcomic", "string", "text", "Title", "A title for the comic")';
$sql_query[] = 'INSERT INTO `' . $table_prefix . 'config` VALUES ("3", "enableRSS", "1", "boolean", "yesno", "Enable RSS", "Enable Really Simple Syndication of comics")';
$sql_query[] = 'INSERT INTO `' . $table_prefix . 'config` VALUES ("4", "rssNumComics", "5", "number", "number", "Number of comics to syndicate", "The number of comics syndicated in the RSS feed")';
$sql_query[] = 'INSERT INTO `' . $table_prefix . 'config` VALUES ("5", "usingTheme", "Kubrick", "string", "", "Current Theme", "The currently selected theme")';
$sql_query[] = 'INSERT INTO `' . $table_prefix . 'config` VALUES ("6", "gzipcompress", "0", "boolean", "yesno", "GZip Compression", " Compression can speed up pages on browsers that support it, but requires additional server processing")';

//Execute queries
for ($i = 0; $i < sizeof($sql_query); $i++) 
{
	if (trim($sql_query[$i]) != '') 
     {
	    $result = $db->query($sql_query[$i]);
	    
		if (PEAR::isError($result)) 
          {
			header('Location: '.$xcomicRootPath.'admin/install/error/createdbqueryfailed.php');
			exit;
		}
	}
}

?>
