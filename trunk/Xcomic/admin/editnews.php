<?php
/**
Xcomic

$Id$
*/

//Xcomic settings
define('IN_XCOMIC', true);
$xcomicRootPath = '../';
require_once './admininitialize.php';	//Include all admin common settings

//Check for correct input
$newsId = (!empty($_REQUEST['nid'])) ? $security->secureText($_REQUEST['nid']) : null;
if (empty($newsId)) {
	$message->error('No News Id specified!');
}
//Check for delete submission
$action = (!empty($_REQUEST['action'])) ? $security->secureText($_REQUEST['action']) : null;
if ($action == 'delete') {
	//Use the EditNews class to delete the news
	include_once './classes/News.'.$classEx;
	$news =& new News($db);
	$news->delete($newsId);
	
	$message->say('The news entry was successfully deleted');
}

//Check for form edit submission
if (isset($_POST['submit'])) {
	$newsTitle = (!empty($_REQUEST['newsTitle'])) ? $security->secureText($_REQUEST['newsTitle']) : null;
	$newsContent = (!empty($_REQUEST['newsContent'])) ? $security->secureText($_REQUEST['newsContent']) : null;
	
	//Check for error
	if (is_null($newsTitle)) {
		$message->error('The news title was left blank. Please click back and fill it in.');
    }
	if (is_null($newsContent)) {
		$message->error('The news content was left blank. Please click back and fill it in.');
	}
	//Texturize. Convert into HTML
	include_once './classes/Syntax.'.$classEx;
	$syntax = new Syntax();
	$newsContent = $syntax->parse($newsContent);
	
	//Make changes to the existing news entry
	include_once './classes/News.'.$classEx;
	$news = new News($db);
    $news->updateNews($newsId, $newsTitle, $newsContent);
	
	//Display success page
	$message->say('News entry has been sucessfully modified.');
} else {

//Make sure we are in edit mode
if ($action != 'edit') {
	$message->error('Invalid mode specified!');
}
//Get comic information from id
include_once $xcomicRootPath.'includes/NewsDisplay.'.$classEx;
$newsInformation = new NewsDisplay($db, $newsId);
$newsTitle = $newsInformation->getTitle();
$newsContent = $newsInformation->getContent();

//Include script header
include './includes/header.php';

//Include script menu
include './includes/menu.php';
?>
<div class="wrap">
 <h2>Edit News</h2>
 <div class="section-body">
  <form method="post" action="" enctype="multipart/form-data">
   <label for="<?php echo 'newsTitle'; ?>">Title:</label><br />
   <input type="text" name="<?php echo 'newsTitle'; ?>" size="80" value="<?php echo $newsTitle; ?>" /><br />

   <label for="<?php echo 'newsContent'; ?>">Content:<br />
   <textarea wrap="soft" name="<?php echo 'newsContent'; ?>" rows="20" cols="70"><?php echo $newsContent; ?></textarea><br />

   <input type="submit" name="submit" value="Post!" />
  </form>
 </div>
</div>
<?php
//Include script footer
include './includes/footer.php';
} //End check form for submission
?>