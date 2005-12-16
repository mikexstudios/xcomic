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
          $xadmin->menu->addEntry('Themes', basename(__FILE__), 16); //Between News and Users
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

//Create Theme object
include_once $xcomicRootPath.'admin/classes/Themes.class.php';
$themes = new Themes($db);

//Get available themes
$listOfThemes = $themes->getThemesInfo();
//print_r($listOfThemes);

//Check for form submission
if (isset($_REQUEST['action'])) {
	$action = $_REQUEST['action'];
	switch ($action)
	{
        case 'activate':
            {
                $themename = !empty($_GET['name']) ? $security->allowOnlyWordsNumbers($_GET['name']) : null;
                if ($themename == null)
                    $message->say("Invalid theme selected.");
                
                $found = false;
                foreach ($listOfThemes as $theme)
                {
                    if ($theme['Name'] == $themename)
                    {
                        $found = true;
                        $thetheme = $theme;
                    }
                }
                if (!$found)
                    $message->say("Theme selected does not exist.");
                
                $themes->setCurrentTheme($themename);
                $message->say("Current theme changed to $thetheme[Title].");
            }
            break;
        default:
            $message->say("Unknown theme action.");
            break;
    }
} else {

//Include script header
include_once $xcomicRootPath.'admin/includes/header.php';

//Include script menu
include_once $xcomicRootPath.'admin/includes/menu.php';

?>
<div class="wrap">
 <h2>Current Themes</h2>
 <div class="section-body">
     <table width="100%" cellpadding="3" cellspacing="3">
     	<tr>
     		<th>Name</th>
     		<th>Author</th>
     		<th>Description</th>
     		<th></th>
     	</tr>
<?php
$currentTheme = $themes->getCurrentTheme();

foreach($listOfThemes as $eachTheme)
{
     //Set variables
     $name = $eachTheme['Name'];
     $template = $eachTheme['Template'];
     //$stylesheet = $eachTheme['Stylesheet'];
     $title = $eachTheme['Title'];
     $version = $eachTheme['Version'];
     $description = $eachTheme['Description'];
     $author = $eachTheme['Author'];

     if ($currentTheme == $name)
     {
        $class = 'alternate active';
        $action = 'Current Theme';
        $link = false;
     }
     else
     {
        $class = '';
        $action = 'Activate Theme';
        $link = true;
     }
?>
          <tr class="<?php echo $class; ?>"><td class="name"><?php echo $title; ?> <?php echo $version; ?></td>
     	     <td class="auth"><?php echo $author; ?></td>
     	     <td class="desc"><?php echo $description; ?></td>
     	     <td class="togl"><?php if ($link) { ?><a href='themes.php?action=activate&amp;name=<?php echo $name; ?>' title='Select this theme' class='edit'><?php } ?>
              <?php echo $action; ?>
              <?php if ($link) { ?></a><?php } ?>
             </td>
          </tr>
<?php
} //End foreach
?>
     </table>
  </form>
 </div>
</div>

<?php

//Include script footer
include_once $xcomicRootPath.'admin/includes/footer.php';

} //End check form for submission
?>
