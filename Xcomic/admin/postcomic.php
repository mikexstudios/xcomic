<?php
/**
Xcomic

$Id$
*/

//Xcomic settings
define('IN_XCOMIC', true);
$xcomicRootPath = "../";

require_once('./admininitialize.php');	//Include all admin common settings

//Form field variables
$formComicTitle = 'comicTitle';
$formComicFile = 'comicFile';
$formModeValue = 'postcomic';

//Check for form submission
if($_REQUEST['mode'] == $formModeValue) {
	$comicTitle=(!empty($_REQUEST[$formComicTitle])) ? $security->secureText($_REQUEST[$formComicTitle]) : NULL;
	$comicFile=(!empty($_FILES[$formComicFile])) ? $_FILES[$formComicFile] : NULL; //Default to left
	
	include_once('./classes/PostComic.'.$classEx);
	//Check for error
	if($comicTitle==NULL)
		$message->error('The comic title was left blank. Please click back and fill it in.');
	
	if($comicFile==NULL)
		$message->error('No comic file was uploaded. Please click back and correct this mistake.');
	
	//echo $comicFile['tmp_name'];
	
	//Actually post the news
	$postComic = new PostComic($comicFile, $comicTitle);
	if($postComic->saveFile())
		$postComic->sendToDatabase();
	
	//Display success page
	$message->say('The comic has been sucessfully posted.');
}
else {

//Include script header
include('./includes/header.php');

//Include script menu
include('./includes/menu.php');
?>
<div class="wrap">
	<div class="section-title"><h2>Post New Comic</h2></div>
	<div class="section-body">
	<form method="POST" action="" enctype="multipart/form-data">
	<input type="hidden" name="mode" value="<?php echo $formModeValue; ?>">
	<p>
	Comic Title:<br /><input type="text" name="<?php echo $formComicTitle; ?>" size="80" />
	</p>
	
	<p>
	Upload Comic:<br />
	<input type="file" size="35" name="<?php echo $formComicFile; ?>">
	</p>

	<p>
	<input type="submit" name="submit" value="Post!" />
	</p>
	</form>
	</div>
</div>

<?php

//Include script footer
include('./includes/footer.php');

} //End check form for submission

?>