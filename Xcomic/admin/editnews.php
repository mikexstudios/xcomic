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
$sectionTitle = 'Edit News';
$urlNewsId = 'nid'; //From GET url
$urlModeAction = 'action';
$modeEdit = 'edit';
$modeDelete = 'delete';
$formNewsTitle = 'newsTitle';
$formNewsContent = 'newsContent';
$formModeAction = 'mode';
$formModeValue = 'editnews';

//Check for correct input
$newsId=(!empty($_REQUEST[$urlNewsId])) ? $security->secureText($_REQUEST[$urlNewsId]) : NULL;
if(empty($newsId))
	$message->error('No News Id specified!');

//Check for delete submission
$action=(!empty($_REQUEST[$urlModeAction])) ? $security->secureText($_REQUEST[$urlModeAction]) : NULL;
if($action == $modeDelete)
{
	//Use the EditNews class to delete the news
	include_once('./classes/EditNews.'.$classEx);
	$editNews = new EditNews($newsId);
	$editNews->delete();
	
	$message->say('The news entry was successfully deleted');
}

//Check for form edit submission
if($_REQUEST[$formModeAction] == $formModeValue) {
	$newsTitle=(!empty($_REQUEST[$formNewsTitle])) ? $security->secureText($_REQUEST[$formNewsTitle]) : NULL;
	$newsContent=(!empty($_REQUEST[$formNewsContent])) ? $security->secureText($_REQUEST[$formNewsContent]) : NULL;
	
	//Check for error
	if($newsTitle==NULL)
		$message->error('The news title was left blank. Please click back and fill it in.');
	if($newsContent==NULL)
		$message->error('The news content was left blank. Please click back and fill it in.');
	
	//Texturize. Convert into HTML
	include_once('./classes/Syntax.'.$classEx);
	$syntax = new Syntax();
	$newsContent = $syntax->parse($newsContent);
	
	//Make changes to the existing news entry
	include_once('./classes/EditNews.'.$classEx);
	$editNews = new EditNews($newsId);
	//Change title
	$editNews->changeTitle($newsTitle);
	//Change content
	$editNews->changeContent($newsContent);
	
	//Display success page
	$message->say('News entry has been sucessfully modified.');
}
else {

//Make sure we are in edit mode
if($action != $modeEdit)
	$message->error('Invalid mode specified!');

//Get comic information from id
include_once($xcomicRootPath.'includes/NewsDisplay.'.$classEx);
$newsInformation = new NewsDisplay($newsId);
$newsTitle = $newsInformation->getTitle();
$newsContent = $newsInformation->getContent();

//Include script header
include('./includes/header.php');

//Include script menu
include('./includes/menu.php');
?>
<div class="wrap">
	<div class="section-title"><h2><?php echo $sectionTitle; ?></h2></div>
	<div class="section-body">
	<form method="POST" action="" enctype="multipart/form-data">
	<input type="hidden" name="<?php echo $formModeAction; ?>" value="<?php echo $formModeValue; ?>">
	
	<p>
	Title:<br /><input type="text" name="<?php echo $formNewsTitle; ?>" size="80" value="<?php echo $newsTitle; ?>" />
	</p>
	
	<p>
	Content:<br />
	<textarea wrap="soft" name="<?php echo $formNewsContent; ?>" rows="20" cols="70"><?php echo $newsContent; ?></textarea>
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