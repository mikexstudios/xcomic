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
$sectionTitle = 'Post New News';
$formNewsTitle = 'newsTitle';
$formNewsContent = 'newsContent';
$formModeValue = 'postnews';

//Check for form submission
if($_REQUEST['mode'] == $formModeValue) {
		
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
	
	//Actually post the news
	include_once('./classes/PostNews.'.$classEx);
	$postNews = new PostNews($newsContent, $newsTitle, $userManagement->getUsername());
	$postNews->sendToDatabase();
	
	//Display success page
	$message->say('News has been sucessfully posted.');		
}
else {

//Include script header
include('./includes/header.php');

//Include script menu
include('./includes/menu.php');
?>
<div class="wrap">
	<div class="section-title"><h2><?php echo $sectionTitle; ?></h2></div>
	<div class="section-body">
	<form method="POST" action="" enctype="multipart/form-data">
	<input type="hidden" name="mode" value="<?php echo $formModeValue; ?>">
	
	<p>
	Title:<br /><input type="text" name="<?php echo $formNewsTitle; ?>" size="80" value="" />
	</p>
	
	<p>
	Content:<br />
	<textarea wrap="soft" name="<?php echo $formNewsContent; ?>" rows="20" cols="70"></textarea>
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