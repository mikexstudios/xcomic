<?php
/**
 * Xcomic - Comic Management Script
 *
 * $Id$
 */
 
class StaticPages
{
    var $dbc;
    var $pageinfo = array();
    var $security;
    
    function StaticPages(&$dbc)
    {
        $this->dbc =& $dbc;
        $this->security = new Security($dbc);
        
        $this->getAllPageInfo();
    }

    function getPageInfo($page)
    {
        if (isset($this->pageinfo[$page]))
            return $this->pageinfo[$page];
        $result = $this->dbc->getRow("SELECT `pagename`, `themefile` ".
            "FROM ".XCOMIC_PAGES_TABLE.' '.
            "WHERE `pagename`='".strtolower($this->security->secureText($page))."' LIMIT 1");
        if (PEAR::isError($result))
            return;
        $this->pageinfo[$page] = $result;
        return $result;
    }
    
    function getAllPageInfo()
    {
        $result = $this->dbc->getAll("SELECT `pagename`, `themefile` FROM ".XCOMIC_PAGES_TABLE);
        if (PEAR::isError($result))
            return;
        foreach ($result as $res)
        {
            $this->pageinfo[$res['pagename']] = $res;
        }
        return $this->pageinfo;
    }

    function getPageExists($page)
    {
        $info = $this->getPageInfo($page);
        if (empty($info))
            return false;
        return true;
    }

    function getPageThemeFile($page)
    {
        $info = $this->getPageInfo($page);
        if (empty($info))
            return;
        return $info['themefile'];
    }
    
    function addPage($page, $themefile)
    {
        if (isset($this->pageinfo[$page]))
            return false;
        $this->dbc->query("INSERT INTO ".XCOMIC_PAGES_TABLE." SET ".
            "`pagename`='".strtolower($this->security->secureText($page))."', `themefile`='".$this->security->secureText($themefile)."'");
        return true;
    }

    function removePage($page)
    {
        $this->dbc->query("DELETE FROM ".XCOMIC_PAGES_TABLE." WHERE ".
            "`pagename`='".strtolower($this->security->secureText($page))."'");
        unset($this->pageinfo[$page]);
        return true;
    }
}
?>