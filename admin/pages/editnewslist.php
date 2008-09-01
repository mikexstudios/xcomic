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
          $xadmin->menu->addEntry('Edit News', basename(__FILE__), 14); //After Post News
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
	
//Get list of comics in database and display in table format
include_once $xcomicRootPath.'includes/NewsListing.class.php';
$listNews = new NewsListing($db, true);
$newsList = $listNews->getNewsList(); //Array of news listings
$numNews = $listNews->numNews(); //Number of elements in that array

//Include script header
include $xcomicRootPath.'admin/includes/header.php';

//Include script menu
include $xcomicRootPath.'admin/includes/menu.php';
?>
<div class="wrap">
 <h2>Edit News</h2>
 <div class="section-body">
  <table width="100%" cellpadding="3" cellspacing="3"> 
   <tr> 
    <th scope="col">News ID</th> 
    <th scope="col">Date</th>  
    <th scope="col">Title</th> 
    <th scope="col">Username</th> 
    <th scope="col">Edit</th> 
    <th scope="col">Delete</th> 
   </tr> 
<?php
//Since $comicsList is in ascending order. We want the most recent news first
//Therefore, set the for loop counting backwards
for ($newsCount = $numNews-1; $newsCount >= 0 ; $newsCount--) {
?>
   <tr style='background-color: #eee'> 
    <th scope="row"><?php echo $newsList[$newsCount]['id']; ?></th> 
    <td><?php echo date('Y-m-d', $newsList[$newsCount]['date']); ?> <br /> <?php echo date('G:i:s', $newsList[$newsCount]['date']); ?></td> 
    <td><?php echo $newsList[$newsCount]['title']; ?></td> 
    <td><?php echo $newsList[$newsCount]['username']; ?></td>
    <td><a href='editnews.php?action=edit&amp;nid=<?php echo $newsList[$newsCount]['id']; ?>' class='edit'>Edit</a></td> 
    <td><a href='editnews.php?action=delete&amp;nid=<?php echo $newsList[$newsCount]['id']; ?>' class='delete' onclick="return confirm('You are about to delete this news entry. \'OK\' to delete, \'Cancel\' to stop.')">Delete</a></td>
   </tr> 
<?php
} //End of for loop
?>  
  </table>
 </div>
</div>

<?php
//Include script footer
include $xcomicRootPath.'admin/includes/footer.php';
?>
