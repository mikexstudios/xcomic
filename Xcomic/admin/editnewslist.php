<?php
/**
Xcomic

$Id$
*/

//Xcomic settings
define('IN_XCOMIC', true);
$xcomicRootPath = '../';
require_once './admininitialize.php';	//Include all admin common settings
	
//Get list of comics in database and display in table format
include_once $xcomicRootPath.'includes/NewsListing.'.$classEx;
$listNews = new NewsListing();
$newsList = $listNews->getNewsList(); //Array of news listings
$numNews = $listNews->numNews(); //Number of elements in that array

//Include script header
include './includes/header.php';

//Include script menu
include './includes/menu.php';
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
include './includes/footer.php';
?>