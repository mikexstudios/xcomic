<?php
/**
Xcomic

$Id$
*/

//Xcomic settings
define('IN_XCOMIC', true);
$xcomicRootPath = '../';
require_once './admininitialize.php';	//Include all admin common settings

//Check for form submission
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
	
	//Actually post the news
	include_once './classes/News.'.$classEx;
	$news =& new News;
	$news->addNews($newsTitle, $newsContent, $userManagement->getUid(), $userManagement->getUsername());
	
	//Display success page
	$message->say('News has been sucessfully posted.');		
} else {

//Include script header
include './includes/header.php';

//Include script menu
include './includes/menu.php';
?>
<div class="wrap">
 <h2>Post New News</h2>
 <div class="section-body">
  <form method="POST" action="" enctype="multipart/form-data">	
   <label for="<?php echo 'newsTitle'; ?>">Title:</label><br />
   <input type="text" name="<?php echo 'newsTitle'; ?>" size="80" value="" /><br />

   <label for="<?php echo 'newsContent'; ?>">Content:</label><br />
   <textarea wrap="soft" name="<?php echo 'newsContent'; ?>" rows="20" cols="70"></textarea><br />

   <input type="submit" name="submit" value="Post!" />
  </form>
 </div>
</div>

<?php

//Include script footer
include('./includes/footer.php');

} //End check form for submission

?>