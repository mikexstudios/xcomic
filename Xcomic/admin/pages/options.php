<?php
/**
Xcomic

$Id$
*/

//Check to see if being included by another file (ie. Xadmin.php)
if(basename($_SERVER['PHP_SELF']) !== basename(__FILE__))
{
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
          $xadmin->menu->addEntry('Options', basename(__FILE__), 22); //After Users
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
		//echo $key.' = '.$value."<br />";
	}
	
	//Display success page
	$message->say('Settings have been successfully changed.');		
} else {
	
//Grab settings array
$configInfo = $settings->getConfigInfoArray();

//Include script header
include $xcomicRootPath.'admin/includes/header.php';

//Include script menu
include $xcomicRootPath.'admin/includes/menu.php';
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
    if (empty($singleConfig['displaycode']))
        continue;
?>
    <tr valign="top">
     <th scope="row"><?php echo $singleConfig['name']; ?></th>
     <td>
      <?php
	if ($singleConfig['displaycode'] == 'text') { ?>
		<input name="<?php echo $singleConfig['option']; ?>" type="text" id="<?php echo $singleConfig['option']; ?>" value="<?php echo $singleConfig['value']; ?>" size="50" />
	<?php } elseif ($singleConfig['displaycode'] == 'yesno') { ?>
		<input name="<?php echo $singleConfig['option']; ?>" type="radio" value="1" <?php if ($singleConfig['value'] == '1') { echo 'CHECKED'; } ?> /> Yes <input name="<?php echo $singleConfig['option']; ?>" type="radio" value="0" <?php if ($singleConfig['value'] == '0') { echo 'CHECKED'; } ?> /> No
	<?php } elseif ($singleConfig['displaycode'] == 'number') { ?>
		<input name="<?php echo $singleConfig['option']; ?>" type="text" id="<?php echo $singleConfig['option']; ?>" value="<?php echo $singleConfig['value']; ?>" size="5" />
	<?php } ?>
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
include $xcomicRootPath.'admin/includes/footer.php';
} //End check form for submission
?>
