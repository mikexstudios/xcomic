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
include_once($xcomicRootPath.'includes/ComicListing.'.$classEx);
$listComics = new ComicListing();
$comicsList = $listComics->getComicList(); //Array of comic listings
$numComics = $listComics->numComics(); //Number of elements in that array

//Include script header
include './includes/header.php';

//Include script menu
include './includes/menu.php';
?>
<div class="wrap">
 <h2>Edit Comics</h2>
	<div class="section-body">
	<table width="100%" cellpadding="3" cellspacing="3"> 
	  <tr> 
	    <th scope="col">Comic ID</th> 
	    <th scope="col">Date</th>  
	    <th scope="col">Title</th> 
	    <th scope="col">Filename</th> 
	    <th scope="col">Edit</th> 
	    <th scope="col">Delete</th> 
	  </tr> 
<?php
//Since $comicsList is in ascending order. We want the most recent comic first
//Therefore, set the for loop counting backwards
for ($comicCount = $numComics-1; $comicCount >= 0 ; $comicCount--) {
?>
  <tr style='background-color: #eee'> 
    <th scope="row"><?php echo $comicsList[$comicCount]['cid']; ?></th> 
    <td><?php echo date('Y-m-d', $comicsList[$comicCount]['date']); ?> <br /> <?php echo date('G:i:s', $comicsList[$comicCount]['date']); ?></td> 
    <td><?php echo $comicsList[$comicCount]['title']; ?></td> 
    <td><?php echo $comicsList[$comicCount]['filename']; ?></td>
    <td><a href='editcomic.php?action=edit&amp;cid=<?php echo $comicsList[$comicCount]['cid']; ?>' class='edit'>Edit</a></td> 
    <td><a href='editcomic.php?action=delete&amp;cid=<?php echo $comicsList[$comicCount]['cid']; ?>' class='delete' onclick="return confirm('You are about to delete this comic. \'OK\' to delete, \'Cancel\' to stop.')">Delete</a></td> 
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