<?php
/**
Xcomic

$Id$
*/

/**
 * Plugin base class. All plugins should extend this class.
 * Classes beginning with 'Xcomic' are reserved for Xcomic
 * distributed plugins.
 */
class Plugin
{
    /**
     * Registers a plugin action with the XComic core as a tag that can be
     * used later during script execution for template purposes.
     *
     * @access protected
     * @param string $tag Short variable-like name to associate with the function such as 'getimagetag'.
     * @param string $methodName Method (that exists on this object) to be registered such as 'getImageTag()'.
     */
    function registerAction($tag, $methodName)
    {
        global $xcomic;

        $xcomic->registerAction($tag, array(&$this, $methodName));
    }
    
    /**
     * Get the value of a local plugin option stored in the database.
     * Options can be named whatever the plugin writer wishes. Option names
     * are localized to their specific plugins.
     *
     * @access protected
     * @param string $name the name of the option to retrieve
     * @return string value of the option $name for this plugin.
     */
     function getOption($name)
     {
         global $xcomic, $table_prefix;

         return $xcomic->dbc->getOne("SELECT `value` FROM ".XCOMIC_PLUGIN_VARS_TABLE." ".
            "WHERE `plugin`=LOWER('".get_class($this)."') AND `option`='".$xcomic->security->secureText($name)."' LIMIT 1");
     }
     
     /**
      * Set the value of a local plugin option stored in the database.
      * Options can be named whatever the plugin writer wishes. Option names
      * are localized to their specific plugins.
      *
      * @access protected
      * @param string $name the name of the option to set
      * @param string $value the value of the option to set
      */
     function setOption($name, $value)
     {
         global $xcomic, $table_prefix;

         $xcomic->dbc->query("REPLACE INTO ".XCOMIC_PLUGIN_VARS_TABLE." SET ".
            "`plugin`=LOWER('".get_class($this)."'), ".
            "`option`='".$xcomic->security->secureText($name)."', ".
            "`value`='".$xcomic->security->secureText($value)."'");
     }
     
     /**
      * Remove a local plugin option from the database.
      *
      * @access protected
      * @param string $name the name of the option to remove from the database
      */
     function removeOption($name)
     {
         global $xcomic, $table_prefix;
         
         $xcomic->dbc->query("DELETE FROM ".XCOMIC_PLUGIN_VARS_TABLE." ".
            "WHERE `plugin`=LOWER('".get_class($this)."') AND `option`='".$xcomic->security->secureText($name)."' LIMIT 1");
     }

    /**
     * Get meta information on this plugin, including full name, description, author, etc.
     * Override this method to provide the basic meta information or more if needed.
     * All plugins should provide 'name' and 'description' meta data, and usually 'author' 'website'
     * and 'version' as well.
     *
     * @access public
     * @param string $meta the name of the meta data field to get, i.e. 'author' 'description' etc.
     * @return string the meta information
     */
    function getPluginMetaData($meta)
    {
        return '';
    }
    
    /**
     * Runs the plugin's installation script. This is run from the admin panel when the user
     * clicks on 'Install.'
     *
     * @access public
     * @param object $dbc the database connection
     */
     function install(&$dbc)
     {
     }
}
?>