<?php
/**
Xcomic

$Id$
*/

/**
 * Plugin registry for available active plugins.
 */
class PluginRegistry
{
    var $allPluginInfo;
    var $dbc;
    var $plugins = array();
    
    function PluginRegistry(&$dbc, $all=false)
    {
        if (DB::isConnection($dbc)) {
            $this->dbc =& $dbc;
        }

        $this->allPluginInfo = $this->queryPluginInfo($all);
    }
    
    function queryPluginInfo($all)
    {
        global $table_prefix;

        $sql = '
			SELECT `name`, `active`, `order`, `type`
			FROM `'.XCOMIC_PLUGINS_TABLE.'` '.
            (!$all ? 'WHERE `active`=1' : '')
            .' ORDER BY `order` ASC';
		$result = $this->dbc->getAll($sql);
		if (PEAR::isError($result)) {
            die('Unable to get plugin information');
		}

		return $result;
    }

    function registerPlugins()
    {
        global $xcomicRootPath;

        foreach ($this->allPluginInfo as $plugin)
        {
            $pluginName = $plugin['name'];
            include_once $xcomicRootPath.PLUGINS_DIR.'/'.$pluginName.'.plugin.php';
            if (!class_exists($pluginName))
                echo "Plugin class doesn't exist: ".$pluginName;
	        $this->plugins[$pluginName] = new $pluginName ();
	    }
	}
	
	function getLoadedPlugins()
	{
        return array_keys($this->plugins);
    }
    
    function getPlugin($pluginName)
    {
        if (isset($this->plugins[$pluginName]))
            return $this->plugins[$pluginName];
        return null;
    }
}
?>