<?php
/**
Xcomic

$Id$
*/

//Check to see if being included by another file (ie. Xadmin.php)
if(basename($_SERVER['PHP_SELF']) !== basename(__FILE__))
{
     //echo 'included'.__FILE__;
     //This file is being included. Assume Xadmin.php is 
     //including the file.
     
     if(empty($xadmin))
     {
          echo 'Error: This file requires Xadmin.php to function correctly.';
     }  
     
     //Check to see if already registered in the menu
     if(!$xadmin->menu->isLinkToInMenu(basename(__FILE__)))
     {
          //Not already registered, so register
          $xadmin->menu->addEntry('Post Comic', basename(__FILE__), 2); //Close to the front
     }
     
     //Exit so rest of file doesn't display. When including
     //a file, control to the base file can be returned by
     //using the return construct.
     return;
}

//Every admin page requires the following:
$xcomicRootPath = '../../';
require_once $xcomicRootPath.'admin/includes/admininitialize.php';
include_once $xcomicRootPath.'admin/Xadmin.php';
$xadmin = new Xadmin($db);

/*
//Xcomic settings
define('IN_XCOMIC', true);
$xcomicRootPath = '../';
require_once './admininitialize.php';	//Include all admin common settings
*/

//Form field variables
$form['comictitle'] = 'comictitle';
$form['comicfile'] = 'comicfile';

include_once $xcomicRootPath.'admin/classes/DateWidget.class.php';

//Check for form submission
if (isset($_POST['submit'])) {
	$comicTitle = (!empty($_REQUEST[$form['comictitle']])) ? $security->secureText($_REQUEST[$form['comictitle']]) : null;
	//Must use $_FILES[$form['comicfile']]['name'] since empty on just $_FILES[$form['comicfile']] will always return false.
	$comicFile = (!empty($_FILES[$form['comicfile']]['name'])) ? $_FILES[$form['comicfile']] : null; //Default to left
	
	$Date = new DateWidget();
	$Date->processWidget();

	include_once $xcomicRootPath.'admin/classes/Comic.class.php';
	//Check for error
	if ($comicTitle == null) {
		$message->error('The comic title was left blank. Please click back and fill it in.');
	}
	if ($comicFile == null) {
		$message->error('No comic file was uploaded. Please click back and correct this mistake.');
	}
	//echo $comicFile['tmp_name'];

	//Actually post the news
	$postComic =& new Comic($db, $comicFile, $comicTitle, $Date->getTime());
	if ($postComic->saveFile()) {
		$postComic->sendToDatabase();
	}
	//Display success page
	$message->say('The comic has been sucessfully posted.');
} else {

//Include script header
include_once $xcomicRootPath.'admin/includes/header.php';

//Include script menu
include_once $xcomicRootPath.'admin/includes/menu.php';
?>
<div class="wrap">
 <h2>Post New Comic</h2>
 <div class="section-body">
  <form method="POST" action="" enctype="multipart/form-data">
   <label for="<?php echo $form['comictitle']; ?>">Comic Title:</label><br />
   <input type="text" name="<?php echo $form['comictitle']; ?>" size="80" /><br />
   
   <?php
   $Date = new DateWidget(time());
   $Date->printWidget("Post Date:");
   ?><br />

   <label for="<?php echo $form['comicfile']; ?>">Upload Comic:</label><br />
   <input type="file" size="35" name="<?php echo $form['comicfile']; ?>"><br />
   <input type="submit" name="submit" value="Post!" />
  </form>
 </div>
</div>

<?php

//Include script footer
include_once $xcomicRootPath.'admin/includes/footer.php';

} //End check form for submission
?>
