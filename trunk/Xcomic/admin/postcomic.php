<?php
/**
Xcomic

$Id$
*/

//Xcomic settings
define('IN_XCOMIC', true);
$xcomicRootPath = '../';
require_once './admininitialize.php';	//Include all admin common settings

//Form field variables
$formComicTitle = 'comicTitle';
$formComicFile = 'comicFile';

//Check for form submission
if (isset($_POST['submit'])) {
	$comicTitle = (!empty($_REQUEST[$formComicTitle])) ? $security->secureText($_REQUEST[$formComicTitle]) : null;
	//Must use $_FILES[$formComicFile]['name'] since empty on just $_FILES[$formComicFile] will always return false.
	$comicFile = (!empty($_FILES[$formComicFile]['name'])) ? $_FILES[$formComicFile] : null; //Default to left
	
	include_once './classes/PostComic.'.$classEx;
	//Check for error
	if ($comicTitle == null) {
		$message->error('The comic title was left blank. Please click back and fill it in.');
	}
	if ($comicFile == null) {
		$message->error('No comic file was uploaded. Please click back and correct this mistake.');
	}
	//echo $comicFile['tmp_name'];
	
	//Actually post the news
	$postComic = new PostComic($comicFile, $comicTitle);
	if ($postComic->saveFile()) {
		$postComic->sendToDatabase();
	}
	//Display success page
	$message->say('The comic has been sucessfully posted.');
} else {

//Include script header
include './includes/header.php';

//Include script menu
include './includes/menu.php';
?>
<div class="wrap">
 <h2>Post New Comic</h2>
 <div class="section-body">
  <form method="POST" action="" enctype="multipart/form-data">
   <label for="<?php echo $formComicTitle; ?>">Comic Title:</label><br />
   <input type="text" name="<?php echo $formComicTitle; ?>" size="80" /><br />
        
   <label for="<?php echo $formComicFile; ?>">Upload Comic:</label><br />
   <input type="file" size="35" name="<?php echo $formComicFile; ?>"><br />
   <input type="submit" name="submit" value="Post!" />
  </form>
 </div>
</div>

<?php

//Include script footer
include './includes/footer.php';

} //End check form for submission
?>