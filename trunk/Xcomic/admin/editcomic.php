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
$urlComicId = 'cid'; //From GET url
$modeDelete = 'delete';
$formComicTitle = 'comicTitle';
$formComicFile = 'comicFile';

//Check for correct input
$comicId = (!empty($_REQUEST[$urlComicId])) ? $security->secureText($_REQUEST[$urlComicId]) : null;
if (empty($comicId)) {
	$message->error('No Comid Id specified!');
}
//Check for delete submission
$action = (!empty($_REQUEST['action'])) ? $security->secureText($_REQUEST['action']) : null;
if ($action == $modeDelete) {
	//Use the EditComic class to delete the comic
	include_once './classes/EditComic.'.$classEx;
	$editComic = new EditComic($db, $comicId);
	$editComic->deleteComic();
	
	$message->say('The comic was successfully deleted');
}

//Check for form edit submission
if (isset($_POST['submit'])) {
	$comicTitle = (!empty($_REQUEST[$formComicTitle])) ? $security->secureText($_REQUEST[$formComicTitle]) : null;
	$comicDateMonth = (!empty($_REQUEST[formDateMonth])) ? $security->secureText($_REQUEST[formDateMonth]) : null;
	$comicDateDay = (!empty($_REQUEST[formDateDay])) ? $security->secureText($_REQUEST[formDateDay]) : null;
	$comicDateYear = (!empty($_REQUEST[formDateYear])) ? $security->secureText($_REQUEST[formDateYear]) : null;
	//Must use $_FILES[$formComicFile]['name'] since empty on just $_FILES[$formComicFile] will always return false.
	$comicFile = (!empty($_FILES[$formComicFile]['name'])) ? $_FILES[$formComicFile] : null; //Default to left
	
	//Check for error
	if (empty($comicTitle)) {
		$message->error('The comic title was left blank. Please click back and fill it in.');
	}
	if (empty($comicDateMonth) || empty($comicDateDay) || empty($comicDateYear)) {
		$message->error('One of the date fields was left blank. Please click back and fill it in.');
	}
	//Process file first and then the title. That way, if the file fails to upload, no changes
	//are made.
	include_once './classes/EditComic.'.$classEx;
	$editComic =& new EditComic($db, $comicId);
	
	//If the comicFile is not empty, make the edit
	if (!empty($comicFile['name'])) {
		if (!($editComic->changeFile($comicFile))) { //If changeFile failed
			$message->error('Unable to change the comic file!');
		}
	}
	
	//Change the title
	$editComic->changeTitle($comicTitle);
	
	//Change the date
	$editComic->changeDate(mktime(0, 0, 0, $comicDateMonth, $comicDateDay, $comicDateYear));

	//Display success page
	$message->say('The comic has been sucessfully modified.');
} else {

//Make sure we are in edit mode
if ($action != 'edit') {
	$message->error('Invalid mode specified!');
}
//Get comic information from id
include_once $xcomicRootPath.'includes/ComicDisplay.'.$classEx;
$comicInformation = new ComicDisplay($db, $comicId);
$comicTitle = $comicInformation->getTitle();
$comicTime = $comicInformation->queryComicInfo($comicId);
$comicDateMonth = date('m', $comicTime['date']);
$comicDateDay = date('d', $comicTime['date']);
$comicDateYear = date('y', $comicTime['date']);

//Include script header
include './includes/header.php';

//Include script menu
include './includes/menu.php';
?>
<div class="wrap">
 <h2>Edit Comic</h2>
 <div class="section-body">
  <form method="POST" action="" enctype="multipart/form-data">
   <input style="display: none;" type="hidden" name="<?php echo $urlComicId; ?>" value="<?php echo $comicId; ?>">

   <label for="date">Date (MM-DD-YY)</label><br />
   <input type="text" name="formDateMonth" value="<?php echo $comicDateMonth; ?>" size="3" /> <input type="text" name="formDateDay" value="<?php echo $comicDateDay; ?>" size="3" /> <input type="text" name="formDateYear" value="<?php echo $comicDateYear; ?>" size="3" /><br />

   <label for="<?php echo $formComicTitle; ?>">Change Comic Title:</labal><br />
   <input type="text" name="<?php echo $formComicTitle; ?>" value="<?php echo $comicTitle; ?>" size="80" /><br />

   <label for="<?php echo $formComicFile; ?>">Upload New Comic (leave blank to keep the same comic file):</label><br />
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