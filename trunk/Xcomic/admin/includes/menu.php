<?php
/**
Xcomic

$Id$
*/

/*
$xcomicRootPath = "../";
define('IN_XCOMIC', true);

require_once($xcomicRootPath.'initialize.php');	//Include all page common settings

include_once($xcomicRootPath.'includes/Display.'.$classEx);
*/


$menuEntries = array (
				array('linkname' => 'Post Comic', 'scriptname' => 'postcomic.php'),
				array('linkname' => 'Edit Comics', 'scriptname' => 'editcomiclist.php'),
				//array('linkname' => 'Comic Status', 'scriptname' => 'comicstatus.php'),
				array('linkname' => 'Post News', 'scriptname' => 'postnews.php'),
				array('linkname' => 'Edit News', 'scriptname' => 'editnewslist.php'),
				//array('linkname' => 'News Categories', 'scriptname' => 'newscategories.php'),
				array('linkname' => 'Users', 'scriptname' => 'users.php'),
				array('linkname' => 'Options', 'scriptname' => 'options.php'),
				array('linkname' => 'View Site', 'scriptname' => 'viewsite.php'),
				array('linkname' => 'Logout', 'scriptname' => 'logout.php')
				);
	
		
//Output HTML for menu
?>
<div id="navigation">
	<ul id="adminmenu">
<?php
//Each link
foreach($menuEntries as $singleEntry)
{
?>
	<li class="menu-link"><a href="<?php echo $singleEntry['scriptname']; ?>"><?php echo $singleEntry['linkname']; ?></a></li>
		
<?php
}
?>
	</ul>
</div>
<?php
//End of script
?>