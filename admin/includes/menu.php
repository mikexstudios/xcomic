<?php
/**
Xcomic

$Id$
*/

/*
$xcomicRootPath = "../";
define('IN_XCOMIC', true);

require_once $xcomicRootPath.'initialize.php';	//Include all page common settings

include_once $xcomicRootPath.'includes/Display.class.php';
*/

if(!isset($xadmin))
{
     die('xadmin not set');
}

//Display top menu row
$menuEntriesTop = $xadmin->menu->getMenuEntries('top');
//Output HTML for top menu
?>
<div id="navigation">
	<ul id="adminmenu">
<?php
//Each link
foreach($menuEntriesTop as $singleEntry) {
?>
	<li class="menu-link"><a href="<?php echo $xcomicRootPath; ?>admin/pages/<?php echo $singleEntry['linkto']; ?>"><?php echo $singleEntry['name']; ?></a></li>
		
<?php
} //End foreach
?>
	</ul>

<?php
//Display sub menu row (if necessary)
$menuEntriesSub = $xadmin->menu->getMenuEntries('sub');
if(!empty($menuEntriesSub)) {
?>

	<ul id="adminmenu2">
<?php
//Each link
foreach($menuEntriesTop as $singleEntry) {
?>
	<li class="menu-link"><a href="pages/<?php echo $singleEntry['linkto']; ?>"><?php echo $singleEntry['name']; ?></a></li>
		
<?php
} //End foreach
?>
	</ul>
<?php
} //End sub menu if check
?>
</div>
<?php
//End of script
?>
