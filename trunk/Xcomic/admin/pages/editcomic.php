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
	include_once $xcomicRootPath.'admin/classes/EditComic.class.php';
	$editComic = new EditComic($db, $comicId);
	$editComic->deleteComic();
	
	$message->say('The comic was successfully deleted');
}

//Check for form edit submission
if (isset($_POST['submit'])) {
	$comicTitle = (!empty($_REQUEST[$formComicTitle])) ? $_REQUEST[$formComicTitle] : null;
	//Must use $_FILES[$formComicFile]['name'] since empty on just $_FILES[$formComicFile] will always return false.
	$comicFile = (!empty($_FILES[$formComicFile]['name'])) ? $_FILES[$formComicFile] : null; //Default to left
	
	$Date = new DateWidget();
	$Date->processWidget();
	
	//Check for error
	if (empty($comicTitle)) {
		$message->error('The comic title was left blank. Please click back and fill it in.');
	}
	//Process file first and then the title. That way, if the file fails to upload, no changes
	//are made.
	include_once $xcomicRootPath.'admin/classes/EditComic.class.php';
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
	$editComic->changeDate($Date->getTime());

	//Display success page
	$message->say('The comic has been sucessfully modified.');
} else {

//Make sure we are in edit mode
if ($action != 'edit') {
	$message->error('Invalid mode specified!');
}
//Get comic information from id
include_once $xcomicRootPath.'includes/Comic.class.php';
$comicInformation = new Comic($db, $comicId, true);
$comicTitle = $comicInformation->getTitle();
$comicTime = $comicInformation->queryComicInfo($comicId);
$comicDate = new DateWidget($comicTime['date']);

//Include script header
include $xcomicRootPath.'admin/includes/header.php';

//Include script menu
include $xcomicRootPath.'admin/includes/menu.php';
?>
<div class="wrap">
 <h2>Edit Comic</h2>
 <div class="section-body">
  <form method="POST" action="" enctype="multipart/form-data">
   <input style="display: none;" type="hidden" name="<?php echo $urlComicId; ?>" value="<?php echo $comicId; ?>" />

   <?php $comicDate->printWidget(); ?><br />

   <label for="<?php echo $formComicTitle; ?>">Change Comic Title:</labal><br />
   <input type="text" name="<?php echo $formComicTitle; ?>" value="<?php echo $comicTitle; ?>" size="80" /><br />

   <label for="<?php echo $formComicFile; ?>">Upload New Comic (leave blank to keep the same comic file):</label><br />
   <input type="file" size="35" name="<?php echo $formComicFile; ?>"><br />

   <input type="submit" name="submit" value="Update!" />
  </form>
 </div>
</div>

<?php
//Include script footer
include $xcomicRootPath.'admin/includes/footer.php';
} //End check form for submission
?>
