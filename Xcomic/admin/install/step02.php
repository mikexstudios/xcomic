<?php
/**
 * Xcomic - comic management script
 * (http://xcomic.sourceforge.net)
 * 
 * $Id$
 */
 
$xcomicRootPath = '../../';
define('IN_XCOMIC', true);

//Need this here to check if the script has already been installed.
include_once $xcomicRootPath.'admin/install/includes/initialize.php';

//Check database connectivity information
//Select correct database file
$dbms = (!empty($_REQUEST['dbms'])) ? $_REQUEST['dbms'] : null;
//Get rest of database info
$inDbHost = (!empty($_REQUEST['dbhost'])) ? $_REQUEST['dbhost'] : null;
$inDbName = (!empty($_REQUEST['dbname'])) ? $_REQUEST['dbname'] : null;
$inDbUser = (!empty($_REQUEST['dbuser'])) ? $_REQUEST['dbuser'] : null;
$inDbPass = (!empty($_REQUEST['dbpass'])) ? $_REQUEST['dbpass'] : null;

//Check for empty values
if($dbms==null||$inDbHost==null||$inDbName==null||$inDbUser==null)
{
     //Db connection failed
     header('Location: '.$xcomicRootPath.'admin/install/error/dbfailed.php');
     exit;
}

//Create database object
$dsn = array(
     'phptype'  => $dbms,
     'username' => $inDbUser,
     'password' => $inDbPass,
     'hostspec' => $inDbHost,
     'database' => $inDbName,
);

//Check database connection and populate database
include_once $xcomicRootPath.'admin/install/includes/createdb.php';

//Write config.php file
include_once $xcomicRootPath.'admin/install/includes/createconfigfile.php';

//Include header.
include_once $xcomicRootPath.'admin/install/includes/header.php';
?>
<div id="header">
	<h1><a href="http://xcomic.sourceforge.net/" title="Xcomic" class="headerlogo"><img src="../styles/xcomic-small.gif" alt="Xcomic" /></a></h1>
	<div id="title">Step 2: User Information</div>
</div>

<div id="main">
     <div id="description">
     <p>
     An administrative user must be created before you can use the administration
     panel. Please provide the following information:
     </p>
     <form action="finish.php" method="post" >
          <table>
               <tr>
                    <th scope="row">Username</th> 
                    <td><input name="adminuser" type="text" size="25" value="admin" /></td> 
                    <td></td> 
               </tr>
               <tr>
                    <th scope="row">Password</th> 
                    <td><input name="adminpassword" type="password" size="25" value="" /></td> 
                    <td></td> 
               </tr>
               <tr>
                    <th scope="row">Password Again</th> 
                    <td><input name="adminpassword2" type="password" size="25" value="" /></td> 
                    <td></td> 
               </tr>
               <tr>
                    <th scope="row">Email Address</th> 
                    <td><input name="adminemail" type="text" size="25" value="" /></td> 
                    <td></td> 
               </tr>
          </table>
          <input type="submit" name="submit" value="Create New User and Continue &gt;" class="continuebutton" />
     </form>
     </div>
</div>

<?php
     include_once $xcomicRootPath.'admin/install/includes/footer.php';
?>
