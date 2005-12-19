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
          $xadmin->menu->addEntry('Post News', basename(__FILE__), 10); //After Edit Comic
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

include_once $xcomicRootPath.'admin/classes/DateWidget.class.php';

//Check for form submission
if (isset($_POST['submit'])) {
	$newsTitle = (!empty($_REQUEST['newsTitle'])) ? $_REQUEST['newsTitle'] : null;
	$newsContent = (!empty($_REQUEST['newsContent'])) ? $_REQUEST['newsContent'] : null;

    $newsDate = new DateWidget();
    $newsDate->processWidget();

	//Check for error
	if (is_null($newsTitle)) {
		$message->error('The news title was left blank. Please click back and fill it in.');
    }
	if (is_null($newsContent)) {
		$message->error('The news content was left blank. Please click back and fill it in.');
	}
	//Texturize. Convert into HTML
	include_once $xcomicRootPath.'admin/classes/Syntax.class.php';
	$syntax = new Syntax();
	$newsContent = $syntax->parse($newsContent);
	
	//Actually post the news
	include_once $xcomicRootPath.'admin/classes/News.class.php';
	$news =& new News($db);
	$news->addNews($newsTitle, $newsContent, $userManagement->getUid(), $userManagement->getUsername(), $newsDate->getTime()); //Removed max(time(), )

	//Display success page
	$message->say('News has been sucessfully posted.');		
} else {

//Include script header
include $xcomicRootPath.'admin/includes/header.php';

//Include script menu
include $xcomicRootPath.'admin/includes/menu.php';
?>
<div class="wrap">
 <h2>Post New News</h2>
 <div class="section-body">
  <form method="POST" action="" enctype="multipart/form-data">
   <label for="<?php echo 'newsTitle'; ?>">Title:</label><br />
   <input type="text" name="<?php echo 'newsTitle'; ?>" size="80" value="" /><br />

   <label for="<?php echo 'newsContent'; ?>">Content:</label><br />
   <textarea wrap="soft" name="<?php echo 'newsContent'; ?>" rows="20" cols="70"></textarea><br />
   <?php
       $NewsDate = new DateWidget();
       $NewsDate->printWidget("Post Date:");
   ?><br />

   <input type="submit" name="submit" value="Post!" />
  </form>
 </div>
</div>

<?php

//Include script footer
include($xcomicRootPath.'admin/includes/footer.php');

} //End check form for submission

?>
