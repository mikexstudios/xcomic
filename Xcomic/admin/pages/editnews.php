<?php
/**
Xcomic

$Id$
*/

//Check to see if being included by another file (ie. Xadmin.php)
if(basename($_SERVER['PHP_SELF']) !== basename(__FILE__))
{
     //This file will not be registered with the menu.
     return;
}

//Every admin page requires the following:
$xcomicRootPath = '../../';
require_once $xcomicRootPath.'admin/includes/admininitialize.php';
include_once $xcomicRootPath.'admin/Xadmin.php';
$xadmin = new Xadmin($db);

include_once $xcomicRootPath.'admin/classes/DateWidget.class.php';

//Check for correct input
$newsId = (!empty($_REQUEST['nid'])) ? $security->secureText($_REQUEST['nid']) : null;
if (empty($newsId)) {
	$message->error('No News Id specified!');
}
//Check for delete submission
$action = (!empty($_REQUEST['action'])) ? $security->secureText($_REQUEST['action']) : null;
if ($action == 'delete') {
	//Use the EditNews class to delete the news
	include_once $xcomicRootPath.'admin/classes/News.class.php';
	$news =& new News($db);
	$news->delete($newsId);

	$message->say('The news entry was successfully deleted');
}

//Check for form edit submission
if (isset($_POST['submit'])) {
	$newsTitle = (!empty($_REQUEST['newsTitle'])) ? $security->secureText($_REQUEST['newsTitle']) : null;
	$newsContent = (!empty($_REQUEST['newsContent'])) ? $security->secureText($_REQUEST['newsContent']) : null;
	
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
	
	//Make changes to the existing news entry
	include_once $xcomicRootPath.'admin/classes/News.class.php';
	$news = new News($db);
    $news->updateNews($newsId, $newsTitle, $newsContent, $newsDate->getTime());
	
	//Display success page
	$message->say('News entry has been sucessfully modified.');
} else {

//Make sure we are in edit mode
if ($action != 'edit') {
	$message->error('Invalid mode specified!');
}
//Get comic information from id
include_once $xcomicRootPath.'includes/News.class.php';
$newsInformation = new News($db, $newsId, true);
$newsTitle = $newsInformation->getTitle();
$newsContent = $newsInformation->getContent();
$NewsDate = new DateWidget($newsInformation->getDate());

//Include script header
include $xcomicRootPath.'admin/includes/header.php';

//Include script menu
include $xcomicRootPath.'admin/includes/menu.php';
?>
<div class="wrap">
 <h2>Edit News</h2>
 <div class="section-body">
  <form method="post" action="" enctype="multipart/form-data">
   <label for="<?php echo 'newsTitle'; ?>">Title:</label><br />
   <input type="text" name="<?php echo 'newsTitle'; ?>" size="80" value="<?php echo $newsTitle; ?>" /><br />

   <label for="<?php echo 'newsContent'; ?>">Content:<br />
   <textarea wrap="soft" name="<?php echo 'newsContent'; ?>" rows="20" cols="70"><?php echo $newsContent; ?></textarea><br />
   <?php $NewsDate->printWidget(); ?><br />

   <input type="submit" name="submit" value="Post!" />
  </form>
 </div>
</div>
<?php
//Include script footer
include $xcomicRootPath.'admin/includes/footer.php';
} //End check form for submission
?>
