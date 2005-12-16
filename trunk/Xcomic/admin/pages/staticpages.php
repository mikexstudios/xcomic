<?php
/**
Xcomic

$Id$
*/

//Check to see if being included by another file (ie. Xadmin.php)
if(basename($_SERVER['PHP_SELF']) !== basename(__FILE__))
{
     //This file is being included. Assume Xadmin.php is 
     //including the file.
     
     if(empty($xadmin))
     {
          echo 'Error: This file requires Xadmin.php to function correctly.';
     }  
     
     //Check to see if already registered in the menu
     if(!$xadmin->menu->isLinkToInMenu(basename(__FILE__)))
     {
          //Not already registered, so register
          $xadmin->menu->addEntry('Static Pages', basename(__FILE__), 17); //After Options
     }
     
     //Exit so rest of file doesn't display. When including
     //a file, control to the base file can be returned by
     //using the return construct.
     return;
}

//Every admin page requires the following:
$xcomicRootPath = '../../';
require_once $xcomicRootPath.'admin/includes/admininitialize.php';
include_once $xcomicRootPath.'admin/Xadmin.php';
$xadmin = new Xadmin($db);

include_once $xcomicRootPath.'includes/StaticPages.class.php';
$pages = new StaticPages($db);

$allpageinfo = $pages->getAllPageInfo();

if (isset($_REQUEST['action']))
{
	$action = $_REQUEST['action'];
	switch ($action)
	{
        case 'delete':
            $pagename = !empty($_GET['pagename']) ? $_GET['pagename'] : null;
            if ($pagename == null)
                $message->say("Invalid static page selected.");

            if (!isset($allpageinfo[$pagename]))
                $message->say("Selected static page does not exist.");

            $pages->removePage($pagename);
            $message->say("Static page deleted.");
            break;
        case 'add':
            $pagename = !empty($_POST['pagename']) ? $_POST['pagename'] : null;
            $themefile = !empty($_POST['themefile']) ? $_POST['themefile'] : null;
            if (!strlen(trim($pagename)) || !strlen(trim($themefile)))
                $message->say("Invalid static page name or theme file.");
            
            $pages->addPage($pagename, $themefile);
            $message->say("Static page added.");
            break;
        default:
            $message->say("Unknown static page action.");
            break;
    }
} else {

//Include script header
include $xcomicRootPath.'admin/includes/header.php';

//Include script menu
include $xcomicRootPath.'admin/includes/menu.php';
?>
<div class="wrap">
 <h2>Static Pages</h2>
 <div class="section-body">
   <table width="100%" cellpadding="3" cellspacing="3">
   <tr> 
    <th scope="col">Page</th>
    <th scope="col">Theme File</th>
    <th scope="col"></th>
   </tr>
<?php
foreach ($allpageinfo as $page)
{
?>
    <tr style='background-color: #eee'>
      <td class="name"><?php echo $page['pagename']; ?></td>
      <td><?php echo $page['themefile']; ?></td>
      <td class="togl"><a href="staticpages.php?action=delete&pagename=<?php echo $page['pagename']; ?>" class="edit" title="Delete Static Page">Delete</a></td>
    </tr>
<?php
}
?>
  </table>
 </div>
</div>

<div class="wrap">
 <h2>Add new Static Page</h2>
 <div class="section-body">
  <form method="post" action="">
    <input type="hidden" name="action" value="add" />
    <label for="pagename">Page:</label><br />
    <input type="text" name="pagename" /><br />
    <label for="themefile">Theme File:</label><br />
    <input type="text" name="themefile" /><br />
    <input type="submit" name="submit" value="Post!" />
  </form>
 </div>
</div>
<?php
//Include script footer
include $xcomicRootPath.'admin/includes/footer.php';
}
?>