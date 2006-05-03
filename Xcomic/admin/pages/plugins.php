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
          $xadmin->menu->addEntry('Plugins', basename(__FILE__), 24); //After Options
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

include_once $xcomicRootPath.'includes/Plugin.class.php';
$xadmin->loadFilesInDirectory($xcomicRootPath.PLUGINS_DIR.'/', '.plugin.php');

include_once $xcomicRootPath.'includes/PluginRegistry.class.php';
$plugins = new PluginRegistry($db, true);

$currentFiles = array();
foreach ($plugins->allPluginInfo as $plugin)
{
    $currentFiles[] = $plugin['name'].'.plugin.php';
}

$allPlugins = $plugins->allPluginInfo;

$allFiles = $xadmin->getDirectoryFiles($xcomicRootPath.PLUGINS_DIR.'/', '.plugin.php');
foreach ($allFiles as $file)
{
    if (!in_array($file, $currentFiles))
    {
        $plugininfo = array('notInstalled' => true, 'name' => str_replace('.plugin.php', '', $file));
        $allPlugins[] = $plugininfo;
    }
}

//Check for form submission
if (isset($_REQUEST['action']))
{
	$action = $_REQUEST['action'];
	switch ($action)
	{
        case 'activation':
            {
                $pluginname = !empty($_GET['name']) ? $security->allowOnlyWordsNumbers($_GET['name']) : null;
                if ($pluginname == null)
                    $message->say("Invalid plugin selected.");

                $found = false;
                foreach ($plugins->allPluginInfo as $plugin)
                {
                    if ($plugin['name'] == $pluginname)
                    {
                        $found = true;
                        $theplugin = $plugin;
                    }
                }
                if (!$found)
                    $message->say("Plugin selected does not exist.");

                if ($theplugin['active'])
                {
                    $plugin['name'] = $security->secureText($plugin['name']);
                    $sql = "UPDATE `".XCOMIC_PLUGINS_TABLE."`
                    SET `active`=0
                    WHERE `name`='$plugin[name]' LIMIT 1";
                    $result = $db->query($sql);
                    if (PEAR::isError($result))
                        $message->say('Unable to deactivate plugin');

                    $message->say("Plugin deactivated.");
                }
                else
                {
                    $plugin['name'] = $security->secureText($plugin['name']);
                    $sql = "UPDATE `".XCOMIC_PLUGINS_TABLE."`
                    SET `active`=1
                    WHERE `name`='$plugin[name]' LIMIT 1";
                    $result = $db->query($sql);
                    if (PEAR::isError($result))
                        $message->say('Unable to activate plugin');

                    $message->say("Plugin activated.");
                }
            }
            break;
        case 'install':
            {
                $pluginname = !empty($_GET['name']) ? $security->allowOnlyWordsNumbers($_GET['name']) : null;
                if ($pluginname == null)
                    $message->say("Invalid plugin selected.");

                $found = false;
                foreach ($allPlugins as $plugin)
                {
                    if ($plugin['name'] == $pluginname)
                    {
                        $found = true;
                        $theplugin = $plugin;
                    }
                }
                if (!$found)
                    $message->say("Plugin selected does not exist.");

                eval($plugin['name'].'::install($db);');

                $order = $db->getOne("SELECT `order` FROM `".XCOMIC_PLUGINS_TABLE."` ORDER BY `order` DESC LIMIT 1");
                $plugin['name'] = $security->secureText($plugin['name']);
                $db->query("INSERT INTO `".XCOMIC_PLUGINS_TABLE."` SET `name`='$plugin[name]',`order`=".($order+1).",`type`='addon'");

                $message->say("Plugin successfully installed.");
            }
            break;
        default:
            $message->say("Unknown plugin action.");
            break;
    }
} else {

//Include script header
include $xcomicRootPath.'admin/includes/header.php';

//Include script menu
include $xcomicRootPath.'admin/includes/menu.php';
?>
<div class="wrap">
 <h2>Plugins</h2>
 <div class="section-body">
  <table width="100%" cellpadding="3" cellspacing="3">
   <tr>
    <th scope="col">Name</th>
    <th scope="col">Author</th>
    <th scope="col">Description</th>
    <th scope="col"></th>
   </tr>
<?php
foreach ($allPlugins as $plugin)
{
    if (isset($plugin['notInstalled']) && $plugin['notInstalled'])
    {
        $class = "alternate";
        $active = "Install";
        $link = 'install';
    }
    else
    {
        if ($plugin['active'])
        {
            $class = "alternate active";
            $active = "Active";
            if ($plugin['type'] != 'builtin')
                $link = 'activation';
            else
                $link = false;
        }
        else
        {
            $class = "alternate";
            $active = "Inactive";
            $link = 'activation';
        }
    }
?>
   <tr class="<?php echo $class; ?>">
    <td class="name">
        <?php eval("echo $plugin[name]::getPluginMetaData('name');"); ?>
        <?php eval("echo $plugin[name]::getPluginMetaData('version');"); ?>
    </td>
    <td class="auth"><a href="<?php eval ("echo $plugin[name]::getPluginMetaData('website');"); ?>">
        <?php eval("echo $plugin[name]::getPluginMetaData('author');"); ?></a></td>
    <td class="desc"><?php eval("echo $plugin[name]::getPluginMetaData('description');"); ?></td>
    <td class="togl">
        <?php if ($link) { ?><a class="edit" href="plugins.php?action=<?php echo $link; ?>&amp;name=<?php echo $plugin['name']; ?>" title="Toggle Activation"><?php } ?>
        <?php echo $active; ?>
        <?php if ($link) { ?></a><?php } ?>
    </td>
   </tr>
<?php
} // End foreach
?>
  </table>
 </div>
</div>
<?php
//Include script footer
include $xcomicRootPath.'admin/includes/footer.php';

} // End check of form submission
?>