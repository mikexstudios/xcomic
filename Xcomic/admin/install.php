<?php
/**
Xcomic


$Id$
*/

define('IN_XCOMIC', true);
$xcomicRootPath = '../';

function installXcomic() {
	global $xcomicRootPath;
	
	//Include extension info
	include_once($xcomicRootPath.'extension.inc');
	
	//Select correct database file
	$dbms=(!empty($_REQUEST['dbms'])) ? $_REQUEST['dbms'] : NULL;
	include_once($xcomicRootPath.'includes/selectDatabase.php');
	
	//Get rest of database info
	$inDbHost=(!empty($_REQUEST['dbhost'])) ? $_REQUEST['dbhost'] : NULL;
	$inDbName=(!empty($_REQUEST['dbname'])) ? $_REQUEST['dbname'] : NULL;
	$inDbUser=(!empty($_REQUEST['dbuser'])) ? $_REQUEST['dbuser'] : NULL;
	$inDbPass=(!empty($_REQUEST['dbpass'])) ? $_REQUEST['dbpass'] : NULL;
	
	//Try to connect to database
	$xcomicDb = new sql_db($inDbHost, $inDbUser, $inDbPass, $inDbName, false);
	if(!$xcomicDb->db_connect_id)
	{
	   die('Could not connect to the database');
	}
	
	//Write database connection do config.php
	// Write out the config file.
	$config_data = '<?php'."\n\n";
	$config_data .= "\n// Xcomic auto-generated config file\n// Do not change anything in this file!\n\n";
	$config_data .= '$dbms = \'' . $dbms . '\';' . "\n\n";
	$config_data .= '$xcomicDbHost = \'' . $inDbHost . '\';' . "\n";
	$config_data .= '$xcomicDbName = \'' . $inDbName . '\';' . "\n";
	$config_data .= '$xcomicDbUser = \'' . $inDbUser . '\';' . "\n";
	$config_data .= '$xcomicDbPasswd = \'' . $inDbPass . '\';' . "\n\n";
	$config_data .= '$table_prefix = \'' . $xcomicTablePrefix . '\';' . "\n\n";
	$config_data .= 'define(\'XCOMIC_INSTALLED\', true);'."\n\n";	
	$config_data .= '?' . '>'; // Done this to prevent highlighting editors getting confused!
	
	if (!($fp = @fopen($xcomicRootPath . 'includes/config.'.$phpEx, 'w')))
	{
		die('Could not open includes/config.php for writing!');
	}
	else
	{
		$result = @fputs($fp, $config_data, strlen($config_data));
		@fclose($fp);
	}
	
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
		), 
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
		'msaccess' => array(
			'LABEL'			=> 'MS Access [ ODBC ]',
			'SCHEMA'		=> '', 
			'DELIM'			=> '', 
			'DELIM_BASIC'	=> ';',
			'COMMENTS'		=> ''
		),
		'mssql-odbc' =>	array(
			'LABEL'			=> 'MS SQL Server [ ODBC ]',
			'SCHEMA'		=> 'mssql', 
			'DELIM'			=> 'GO',
			'DELIM_BASIC'	=> ';',
			'COMMENTS'		=> 'remove_comments'
		)
	);
	
	$dbms_schema = 'sql/' . $available_dbms[$dbms]['SCHEMA'] . '_schema.sql';

	$remove_remarks = $available_dbms[$dbms]['COMMENTS'];;
	$delimiter = $available_dbms[$dbms]['DELIM']; 
	$delimiter_basic = $available_dbms[$dbms]['DELIM_BASIC']; 
	
	include_once('./includes/sql_parse.php'); //phpBB's DB schema cleaning
	$sql_query = @fread(@fopen($dbms_schema, 'r'), @filesize($dbms_schema));
	$sql_query = preg_replace('/xcomic_/', $table_prefix, $sql_query);

	$sql_query = remove_remarks($sql_query);
	
	//echo $sql_query;
	
	$sql_query = split_sql_file($sql_query, $delimiter);
	
	//Add URL Information
	$inBaseUrl=(!empty($_REQUEST['baseurl'])) ? $_REQUEST['baseurl'] : NULL;
	$inUrlToXcomic=(!empty($_REQUEST['urltoxcomic'])) ? $_REQUEST['urltoxcomic'] : NULL;
	$sql_query[] = "INSERT INTO config VALUES ('baseUrl', '$inBaseUrl')";
	$sql_query[] = "INSERT INTO config VALUES ('urlToXcomic', '$inUrlToXcomic')";

	
	for ($i = 0; $i < sizeof($sql_query); $i++)
	{
		if (trim($sql_query[$i]) != '')
		{
			if (!($result = $xcomicDb->sql_query($sql_query[$i])))
			{
				$error = $xcomicDb->sql_error();
				die("Install error: $error");
			}
		}
	}

	
	//Display success page
	displayHeader();
?>
	<div class="section-title"><h2>Successful Install!</h2></div>
	<div class="section-body">
	Congratulations! Your install was a success! Proceed to 
	the <a href="index.php">administrative pages</a> of Xcomic and login
	with:
	<p><strong>Username: admin</strong></p>
	<p><strong>Password: changethis</strong></p>
	Make sure you change your password in the user management area after you
	login.
	</div>
<?php
	displayFooter();

}

function displayHeader() {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<title>Xcomic - A comic management script by mikeXstudios</title>
<meta name="description" content="Xcomic - A comic management script by mikeXstudios" />
<meta name="keywords" content="xcomic, comic, web, script, php, mikexstudios" />
<meta name="author" content="mikexstudios" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link rel="stylesheet" type="text/css" href="./styles/admin.css" title="default" media="screen" />
</head>

<body>
<div id="page-container">

<div id="adminheader">
	<h1><a href="http://xcomic.sourceforge.net/" rel="external" title="Visit Xcomic">Xcomic</a></h1>
</div>

<div class="wrap">
<?php 
} //for display Header

function displayFooter() {
?>
</div>

<div id="footer">
	<cite>Powered by <a href="http://www.mikexstudios.com" title="Powered by Xcomic, state-of-the-art web comic publishing platform"><strong>Xcomic</strong></a></cite> &copy; 2004 mikeXstudios.
</div>

<!-- End div for page-container -->
</div>
</body>
</html>
<?php
} //for display Footer

//Main action

//First check if config.php already exists. If so, do not execute this script
if(file_exists($xcomicRootPath.'includes/config.php'))
{
	displayHeader();
?>	
	
	<div class="section-title"><h2>Already Installed!</h2></div>
	<div class="section-body">
	config.php in the includes directory already exists. That means that this script has already been
	installed. If you would like to re-install this script, delete config.php.
	</div>
	
<?php	
	displayFooter();
	exit;
}

if($_REQUEST['action']=='install')
{
	installXcomic();
}
else
{
	//Display install page
	displayHeader();
?>
	<div class="section-title"><h2>Install Xcomic!</h2></div>
	<div class="section-body">
	<form method="POST" action="install.php">
	<input type="hidden" name="action" value="install">
	<p>
	Welcome to the install of Xcomic. Installation is quite simple, just enter in your 
	database information and urls (that we may have already guessed for you) and click 
	install!
	</p>
	<p><strong>Database Information</strong></p>
	<p>
	Database type (currently only works for MySQL):<br />
	<select name="dbms">
		<option value="mysql">MySQL 3.x</option>
		<option value="mysql4">MySQL 4.x</option>
		<option value="postgres">PostgreSQL 7.x</option>
		<option value="mssql">MS SQL Server 7/2000</option>
		<option value="msaccess">MS Access [ ODBC ]</option>
		<option value="mssql-odbc">MS SQL Server [ ODBC ]</option>
	</select>
	</p>
	<p>
	Database host:<br /><input type="text" name="dbhost" size="20" value="localhost" />
	</p>
	<p>
	Database name:<br /><input type="text" name="dbname" size="20" />
	</p>
	<p>
	Database user:<br /><input type="text" name="dbuser" size="20" />
	</p>
	<p>
	Database password:<br /><input type="text" name="dbpass" size="20" />
	</p>
	
	<p><strong>URL Information</strong></p>
	<p>We tried to guess these urls for you. Double check to make sure that they are correct.</p>
	<p>
	Base url:<br /><input type="text" name="baseurl" size="20" value="http://<?php echo $_SERVER["HTTP_HOST"]; ?>" />
	</p>
	<p>
	Url to Xcomic:<br /><input type="text" name="urltoxcomic" size="20" value="http://<?php echo $_SERVER["HTTP_HOST"].str_replace('/admin/install.php', '', $_SERVER["PHP_SELF"]); ?>" />
	</p>
	
	<p>
	<input type="submit" name="submit" value="Install!" />
	</p>
	</form>
	</p>
	</div>

<?php
	displayFooter();

}//Ending tag for else

exit;
?>