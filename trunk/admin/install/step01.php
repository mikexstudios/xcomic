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

//Include header
include_once $xcomicRootPath.'admin/install/includes/header.php';
?>
<div id="header">
	<h1><a href="http://xcomic.sourceforge.net/" title="Xcomic" class="headerlogo"><img src="<?php echo $xcomicRootPath; ?>admin/styles/xcomic-small.gif" alt="Xcomic" /></a></h1>
	<div id="title">Step 1: Database Information</div>
</div>

<div id="main">
     <div id="description">
     <p>
     Please enter your database connectivity information. If you are unsure of
     what to enter, please contact your host:
     </p>
     <form action="step02.php" method="post" >
          <table>
               <tr>
                    <th scope="row">Database Type</th> 
                    <td>
                         <select name="dbms">
                    	<!--
                    		<option value="mysql">&lt=MySQL 4.0 (including 3.x)</option>
                    		<option value="mysqli">&gt;=MySQL 4.1 (including 5.x)</option>
                    	-->
                              <option value="mysql">MySQL 4.x</option>
                    		<option value="mysql">MySQL 3.x</option>
                    		<option value="mysql">MySQL 5.x</option>
                    	<!--
                    		<option value="pgsql">PostgreSQL</option>
                    		<option value="oci8">Oracle 8</option>
                    		<option value="sqlite">SQLite</option>
                    		<option value="odbc">ODBC</option>
                    		<option value="mssql">MS SQL Server 7/2000</option>
                    	-->
                    	</select>
                    </td> 
                    <td>The type of database Xcomic will be using. <em>Currently, only MySQL is supported.</em></td> 
               </tr>
               <tr>
                    <th scope="row">Database Host</th> 
                    <td><input name="dbhost" type="text" size="25" value="localhost" /></td> 
                    <td>Location of the database. Most likely localhost.</td> 
               </tr>
               <tr>
                    <th scope="row">Database Name</th> 
                    <td><input name="dbname" type="text" size="25" value="" /></td> 
                    <td>Name of the database that Xcomic will be using.</td> 
               </tr>
               <tr>
                    <th scope="row">Database User</th> 
                    <td><input name="dbuser" type="text" size="25" value="" /></td> 
                    <td>Username that can access the database.</td> 
               </tr>
               <tr>
                    <th scope="row">User Password</th> 
                    <td><input name="dbpass" type="text" size="25" value="" /></td> 
                    <td>Password of the database user.</td> 
               </tr>
               <tr>
                    <th scope="row">Table Prefix</th> 
                    <td><input name="tblprefix" type="text" size="25" value="xcomic_" /></td> 
                    <td>If you want to run multiple Xcomic installations in a single database, change this.</td> 
               </tr>
          </table>
          <input type="submit" name="submit" value="Check Database Information and Continue &gt;" class="continuebutton" />
     </form>
     </div>
</div>

<?php
     include_once $xcomicRootPath.'admin/install/includes/footer.php';
?>
