<?php
/**
Xcomic

$Id$
*/

/*
define('IN_XCOMIC', true);

$xcomicRootPath='../';
include_once($xcomicRootPath.'initialize.php');
*/

class AdminMessage
{
	
	var $noMenu; //To determine whether or not to display the menu
	
	function AdminMessage()
	{
		$this->noMenu = false; //Initialize to display menu
	}
		
	function say($inMsg, $type = 'message')
	{
		global $xcomicStartTime;
		
		//Determine section title
		//$sectionTitle;
		if ($type == 'error') {
			$sectionTitle = 'Error';
		} else {
			$sectionTitle = 'Message';
		}
		
		//Include script header
		include './includes/header.php';
		
		//Check to see if menu will be displayed
		if ($this->noMenu === false) {
			//Include script menu
			include './includes/menu.php';
		}
?>
<div class="wrap">
	<div class="section-title"><h2><?php echo $sectionTitle; ?></h2></div>
	<div class="section-body">
	<p class="error"><?php echo $inMsg; ?></p>
	</div>
</div>
<?php	

		//Include script footer
		include './includes/footer.php';
	}
	
	function error($inMsg)
	{
		$this->say($inMsg, 'error');	
	}
	
	function setNoMenu($inBoolean = 'true')
	{
		$this->noMenu = $inBoolean;	
	}
		
}

/*
//Testing
$x = new Message(true);
$x->say("This is a error message");
*/
?>