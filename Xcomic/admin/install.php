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
	include_once $xcomicRootPath.'extension.inc';
	
	//Select correct database file
	$dbms = (!empty($_REQUEST['dbms'])) ? $_REQUEST['dbms'] : null;
	
	//Get rest of database info
	$inDbHost = (!empty($_REQUEST['dbhost'])) ? $_REQUEST['dbhost'] : null;
	$inDbName = (!empty($_REQUEST['dbname'])) ? $_REQUEST['dbname'] : null;
	$inDbUser = (!empty($_REQUEST['dbuser'])) ? $_REQUEST['dbuser'] : null;
	$inDbPass = (!empty($_REQUEST['dbpass'])) ? $_REQUEST['dbpass'] : null;
	$inUsUser = (!empty($_REQUEST['useruser'])) ? $_REQUEST['useruser'] : 'admin';
	$inUsPass = (!empty($_REQUEST['userpass'])) ? $_REQUEST['userpass'] : 'changethis';
	$inUsMail = (!empty($_REQUEST['usermail'])) ? $_REQUEST['usermail'] : 'example@example.com';
	$xcomicTablePrefix = (!empty($_REQUEST['tblprefix'])) ? $_REQUEST['tblprefix'] : null;

	require_once 'DB.php';
	//Create database object
    $dsn = array(
        'phptype'  => $dbms,
        'username' => $inDbUser,
        'password' => $inDbPass,
        'hostspec' => $inDbHost,
        'database' => $inDbName,
    );
    
    //print_r($dsn);
    
    $options = array(
        'debug'       => 2,
        'portability' => DB_PORTABILITY_ALL,
    );

	$db = DB::connect($dsn, $options);
	if (PEAR::isError($db)) {
	   die('Could not connect to the database');
	}
	$db->setFetchMode(DB_FETCHMODE_ASSOC);
	
	//Write database connection do config.php
	// Write out the config file.
	$config_data = '<?php';
	$config_data .= "\n// Xcomic auto-generated config file\n// Do not change anything in this file!\n\n";
	$config_data .= '$dbms = \'' . $dbms . '\';' . "\n\n";
	$config_data .= '$dbHost = \'' . $inDbHost . '\';' . "\n";
	$config_data .= '$dbName = \'' . $inDbName . '\';' . "\n";
	$config_data .= '$dbUser = \'' . $inDbUser . '\';' . "\n";
	$config_data .= '$dbPasswd = \'' . $inDbPass . '\';' . "\n\n";
	$config_data .= '$table_prefix = \'' . $xcomicTablePrefix . '\';' . "\n\n";
	$config_data .= 'define(\'XCOMIC_INSTALLED\', true);'."\n\n";	
	$config_data .= '?' . '>'; // Done this to prevent highlighting editors getting confused!
	
	if (!($fp = @fopen($xcomicRootPath . 'includes/config.'.$phpEx, 'w'))) {
		die('Could not open includes/config.php for writing!');
	} else {
		$result = @fputs($fp, $config_data, strlen($config_data));
		@fclose($fp);
		//Make the config file world-writable so that the user can delete it if
		//the webserver steals permissions on it
		chmod($xcomicRootPath . 'includes/config.'.$phpEx, 0666);
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
	
	include_once './includes/sql_parse.php'; //phpBB's DB schema cleaning
	$sql_query = @fread(@fopen($dbms_schema, 'r'), @filesize($dbms_schema));
	$table_prefix = (!empty($table_prefix)) ? $table_prefix : (!empty($xcomicTablePrefix)) ? $xcomicTablePrefix : 'xcomic_';
	$sql_query = str_replace('xcomic_', $table_prefix, $sql_query);

	$sql_query = remove_remarks($sql_query);
	
	$sql_query = split_sql_file($sql_query, $delimiter);
	
	//Add URL Information
	$inBaseUrl=(!empty($_REQUEST['baseurl'])) ? $_REQUEST['baseurl'] : null;
	$inUrlToXcomic=(!empty($_REQUEST['urltoxcomic'])) ? $_REQUEST['urltoxcomic'] : null;
	$sql_query[] = "INSERT INTO " . $table_prefix . "config VALUES ('baseUrl', '$inBaseUrl', 'Base url', 'The base url that Xcomic is running on (ie. http://www.yoururl.com)')";
	$sql_query[] = "INSERT INTO " . $table_prefix . "config VALUES ('urlToXcomic', '$inUrlToXcomic', 'Url to Xcomic', 'The full url to the installation of Xcomic (ie. http://www.xcomic.com/xcomic)')";

    $id = $db->nextId($table_prefix . 'users'); //This assumes that the users table is named users //Not anymore...
    $sql_query[] = "INSERT INTO " . $table_prefix . "users VALUES ($id, '".$inUsUser."', '".md5($inUsPass)."', '".$inUsMail."')";
	
	for ($i = 0; $i < sizeof($sql_query); $i++) {
		if (trim($sql_query[$i]) != '') {
		    $result = $db->query($sql_query[$i]);
			if (PEAR::isError($result)) {
				//$error = $db->sql_error(); // Function no longer exists, but there is an object provided as part of $result
				//when there is an error
				die('Install error');
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
	<p><strong>Username: <?=$inUsUser?></strong></p>
	<p><strong>Password: <?=$inUsPass?></strong></p>
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
if (file_exists($xcomicRootPath.'includes/config.php')) {
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

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'install') {
	installXcomic();
} else {
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
	Database type (currently only tested on MySQL 3.x and 4.x):<br />
	<select name="dbms">
	<!--
		<option value="mysql">&lt=MySQL 4.0 (including 3.x)</option>
		<option value="mysqli">&gt;=MySQL 4.1 (including 5.x)</option>
	-->
		<option value="mysql">MySQL 3.x</option>
		<option value="mysql">MySQL 4.x</option>
		<option value="mysqli">MySQL 5.x</option>
		<option value="pgsql">PostgreSQL</option>
		<option value="oci8">Oracle 8</option>
		<option value="sqlite">SQLite</option>
		<option value="msql">Mini-SQL</option>
		<option value="odbc">ODBC</option>
		<option value="mssql">MS SQL Server 7/2000</option>
		<option value="dbase">Dbase</option>
		<option value="fbsql">FrontBase</option>
		<option value="ibase">Interbase</option>
		<option value="ifx">Informix</option>
		<option value="sybase">Sybase</option>
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
	<p>
	Database table prefix:<br /><input type="text" name="tblprefix" size="20" value="xcomic_" />
	</p>
	
	<p><strong>Administrative user</strong> (if left blank, defaults will be used.)</p>
	<p>
	Administrative username:<br /><input type="text" name="useruser" size="20" />
	</p>
	Administrative password:<br /><input type="password" name="userpass" size="20" />
	</p>
	<p>
	Administrative e-mail address:<br /><input type="text" name="usermail" size="20" />
	</p>

	<p><strong>URL Information</strong></p>
	<p>We tried to guess these urls for you. Double check to make sure that they are correct.</p>
	<p>
	Base url:<br /><input type="text" name="baseurl" size="20" value="http://<?php echo $_SERVER["HTTP_HOST"]; ?>" />
	<br />
	<small>The base url that Xcomic is running on (ie. http://www.yoururl.com)</small>
	</p>
	<p>
	Url to Xcomic:<br /><input type="text" name="urltoxcomic" size="20" value="http://<?php echo $_SERVER["HTTP_HOST"].str_replace('/admin/install.php', '', $_SERVER["PHP_SELF"]); ?>" />
	<br />
	<small>The full url to the installation of Xcomic (ie. http://www.xcomic.com/xcomic)</small>
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