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
$sectionTitle = 'Edit Comic';
$urlComicId = 'cid'; //From GET url
$urlModeAction = 'action';
$modeEdit = 'edit';
$modeDelete = 'delete';
$formComicTitle = 'comicTitle';
$formComicFile = 'comicFile';
$formModeEdit = 'editcomic';

//Check for correct input
$comicId=(!empty($_REQUEST[$urlComicId])) ? $security->secureText($_REQUEST[$urlComicId]) : NULL;
if(empty($comicId))
	$message->error('No Comid Id specified!');

//Check for delete submission
$action=(!empty($_REQUEST[$urlModeAction])) ? $security->secureText($_REQUEST[$urlModeAction]) : NULL;
if($action == $modeDelete)
{
	//Use the EditComic class to delete the comic
	include_once('./classes/EditComic.'.$classEx);
	$editComic = new EditComic($comicId);
	$editComic->deleteComic();
	
	$message->say('The comic was successfully deleted');
}

//Check for form edit submission
if($_REQUEST['mode'] == $formModeEdit) {
	$comicTitle=(!empty($_REQUEST[$formComicTitle])) ? $security->secureText($_REQUEST[$formComicTitle]) : NULL;
	//Must use $_FILES[$formComicFile]['name'] since empty on just $_FILES[$formComicFile] will always return false.
	$comicFile=(!empty($_FILES[$formComicFile]['name'])) ? $_FILES[$formComicFile] : NULL; //Default to left
	
	//Check for error
	if(empty($comicTitle))
		$message->error('The comic title was left blank. Please click back and fill it in.');
	
	//Process file first and then the title. That way, if the file fails to upload, no changes
	//are made.
	include_once('./classes/EditComic.'.$classEx);
	$editComic = new EditComic($comicId);
	
	//If the comicFile is not empty, make the edit
	if(!empty($comicFile['name']))
	{
		if(!($editComic->changeFile($comicFile))) //If changeFile failed
		{
			$message->error('Unable to change the comic file!');
		}
	}
	
	//Change the title
	$editComic->changeTitle($comicTitle);
	
	//Display success page
	$message->say('The comic has been sucessfully modified.');
}
else {

//Make sure we are in edit mode
if($action != $modeEdit)
	$message->error('Invalid mode specified!');

//Get comic information from id
include_once($xcomicRootPath.'includes/ComicDisplay.'.$classEx);
$comicInformation = new ComicDisplay($comicId);
$comicTitle = $comicInformation->getTitle();

//Include script header
include('./includes/header.php');

//Include script menu
include('./includes/menu.php');
?>
<div class="wrap">
	<div class="section-title"><h2><?php echo $sectionTitle; ?></h2></div>
	<div class="section-body">
	<form method="POST" action="" enctype="multipart/form-data">
	<input type="hidden" name="mode" value="<?php echo $formModeEdit; ?>">
	
	<input type="hidden" name="<?php echo $urlComicId; ?>" value="<?php echo $comicId; ?>">
	
	<p>
	Change Comic Title:<br /><input type="text" name="<?php echo $formComicTitle; ?>" value="<?php echo $comicTitle; ?>" size="80" />
	</p>
	
	<p>
	Upload New Comic (leave blank to keep the same comic file):<br />
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