<?php
/**
Xcomic - Comic Management Script

$Id$
*/

/**
 * Manages and produces the admin menu system.
 *
 * Meant to be a dynamic menu system where admin files can add and edit
 * the admin menu at will so that new menu functions can be contained totally
 * in a single file minimizing the need to keep editing a menu file to add
 * new menu entries.
 */
class Menu {
     
     var $dbc; //Database object
     var $menuEntries = array();

     function Menu(&$dbc) {
          //Take database reference from initialize and set to class variable.
          if (DB::isConnection($dbc)) 
          {
               $this->dbc =& $dbc;
          }
          else
          {
               echo 'Error: Database connection not obtained in class Menu.';
          }
     }
     
     /**
      * Executes DB SQL query to retrieve menu information.
      * 
      * @access public
      * @param string $sqlSuffix (optional) append SQL to the end of the query such as WHERE.
      * @return array Array of strings, the DB records, are returned.
      */
     function queryForMenu($sqlSuffix='') {
          
          $sql = '
               SELECT linkto, name, associatedpage, level, position
               FROM '.XCOMIC_ADMIN_MENU_TABLE.'
               '.$sqlSuffix;
          $result = $this->dbc->getAll($sql);
          if(PEAR::isError($result))
          {
               die('Unable to retrieve menu information from DB');
          }
          
          return $result;
     
     }
     
     /**
      * Adds a new entry to the menu.
      *
      * @param string $inName The name that will be displayed in the menu.
      * @param string $inLinkTo What this menu entry will link to. Try to use relative paths. Usually, this is just the script filename (ie. 'editcomic.php').
      * @param string $inPosition (default='') Denotes position of entry in the menu list. Values: {a number with 0 being the front and n being the back}. If not defined, defaults to end.
      * @param string $inLevel Set to 'top' for the main menu; 'sub' for a sub-menu. If set to 'sub', the associated page of the sub-menu must be set.
      * @param string $inAssociatedPage (default='all') Set to 'all' for all pages. If the entry will be in a sub-menu, the associated page should be set to a relative path (usually a filename).
      */
     function addEntry($inName, $inLinkTo, $inPosition='', $inLevel='top', $inAssociatedPage='all') {
          
          $sql = '
               INSERT INTO '.XCOMIC_ADMIN_MENU_TABLE.'
               VALUES (
                    '.$this->dbc->quoteSmart($inLinkTo).', 
                    '.$this->dbc->quoteSmart($inName).', 
                    '.$this->dbc->quoteSmart($inAssociatedPage).', 
                    '.$this->dbc->quoteSmart($inLevel).', 
                    '.$this->dbc->quoteSmart($inPosition).'
                    )
               ';
          $result = $this->dbc->query($sql);
          if (PEAR::isError($result)) 
          {
               echo 'Error: Unable to add new menu entry';
          }
     }
     
     /**
      * Removes a menu entry from the database.
      * 
      * @param string $inLinkTo What the menu entry links to. This is the relative path of the file which is usually just the filename.
      */ 
     function removeEntry($inLinkTo) {
          
          $sql = '
               DELETE FROM '.XCOMIC_ADMIN_MENU_TABLE.'
               WHERE linkto = '.$this->dbc->quoteSmart($inLinkTo);
          $result = $this->dbc->query($sql);
          if (PEAR::isError($result)) 
          {
               echo 'Error: Unable to delete menu entry';
          }
     }
     
     /**
      * Deletes all menu entries from the database.
      */
     function removeAllEntries() {
          $sql = 'TRUNCATE TABLE '.XCOMIC_ADMIN_MENU_TABLE;
          $result = $this->dbc->query($sql);
          if (PEAR::isError($result)) 
          {
               echo 'Error: Unable to delete all menu entries.';
          }
     }
     
     /**
      * Returns an array with the menu entries.
      *
      * The multidimensional array is as follows: Suppose this method returned
      * array $menuEntries. The elements of $menuEntries contain an array for
      * each menu entry. $menuEntries[0] will be an array with the following
      * elements: ('linkto', 'name', 'associatedpage', level'). Each of these
      * can therefore be accessed like: $menuEntries[0]['name'].
      *
      * @access public
      * @param string $inLevel (default='all') Level of the menu entries. 'all' returns all entries. 'top' is the main "top-level" menu. 'sub' is a "sub-level" menu.
      * @param string $inOrderBy (default='position') Defines how the menu entries should be ordered. Set to 'position' for ordering by position. Otherwise, the order is by the 'linkto' name.
      * @return array Menu entries array for the given level.
      */
     function getMenuEntries($inLevel='all', $inOrderBy='position') {
          switch($inLevel)
          {
               case 'top':
                    $sqlSuffix = 'WHERE level = "top"';
                    break;
               case 'sub':
                    $sqlSuffix = 'WHERE level = "sub"';
                    break;
               default:
                    $sqlSuffix = ''; //Selects all entries
          }
          
          //The only option right now is to order by position so that the
          //menu entries can be ordered properly.
          switch($inOrderBy)
          {
               case 'position':
                    $sqlSuffix .= 'ORDER BY '.$this->dbc->quoteSmart('position').' ASC';
               default:
                    $sqlSuffix .= '';
          }
          
          return $this->queryForMenu($sqlSuffix);
     }
     
     /**
      * Returns the menu array sorted by position.
      *
      * @access public
      * @param array $inMenuEntries Menu entries array (from getMenuEntries or queryFromMenu)
      * @return array Menu entries array with elements arranged by position.
      */
     function sortMenuByPosition($inMenuEntries) {
          
     }
     
     /**
      * Determines if the given 'link to' file exists in the menu entries.
      *
      * @access public
      * @param string $inLinkTo A relative path to a file that will be matched against the menu entries.
      * @return boolean True if a match exists. False is there is no match.
      */
     function isLinkToInMenu($inLinkTo) {
          //echo $inLinkTo;
          $menuEntries = $this->getMenuEntries();
          //print_r($menuEntries);
          foreach($menuEntries as $entry)
          {
               //echo $entry['linkto'];
               if (strcasecmp($entry['linkto'], $inLinkTo) == 0)
               {
                    return true; //There is a match
               }
          }
          
          return false; //No match
     }

}
