<?php
/**
Xcomic

$Id$
*/

//Xcomic settings
define('IN_XCOMIC', true);
$xcomicRootPath = "../";
require_once './admininitialize.php';	//Include all admin common settings

//Form field variables
$sectionTitle = 'Options';
$formNewsTitle = 'newsTitle';
$formNewsContent = 'newsContent';
$formModeAction = 'mode';
$formModeValue = 'changeoptions';

//Check for form submission
if (isset($_REQUEST[$formModeAction]) && $_REQUEST[$formModeAction] == $formModeValue) {
	//Get all of the options. Check each option for existance. Weed out the extraneous 
	//ones (malicious and hidden form fields)
	foreach ($_POST as $key => $value)  {
		if ($settings->doesSettingExist($key)) {
			//Setting exists. Make changes for each setting
			//$optionList[$key] = $value;
			$settings->changeSettingValue($key, $value);
		}
		//echo $key.' = '.$value."\n";
	}
	
	//Display success page
	$message->say('Settings have been successfully changed.');		
} else {
	
//Grab settings array
$configInfo = $settings->getConfigInfoArray();

//Include script header
include './includes/header.php';

//Include script menu
include './includes/menu.php';
?>
<div class="wrap">
 <h2><?php echo $sectionTitle; ?></h2>
 <div class="section-body">
  <form method="POST" action="" enctype="multipart/form-data">
   <input style="display: none;" type="hidden" name="<?php echo $formModeAction; ?>" value="<?php echo $formModeValue; ?>">

   <table width="100%" cellpadding="2" cellspacing="5" class="editform"> 
<?php
foreach ($configInfo as $singleConfig) {
//print_r($singleConfig);
?>
    <tr valign="top">
     <th scope="row"><?php echo $singleConfig['name']; ?></th>
     <td>
      <input name="<?php echo $singleConfig['option']; ?>" type="text" id="<?php echo $singleConfig['option']; ?>" value="<?php echo $singleConfig['value']; ?>" size="50" />
      <br />
      <?php echo $singleConfig['description']; ?>
     </td>
    </tr>
<?php
} //Ending for foreach loop
?> 	  
   </table>

   <p class="submit">
    <input type="submit" name="submit" value="Make Changes!" />
   </p>
  </form>
 </div>
</div>

<?php
//Include script footer
include './includes/footer.php';
} //End check form for submission
?>