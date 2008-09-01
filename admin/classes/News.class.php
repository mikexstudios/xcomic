<?php
/**
Xcomic

postNews - posts left or right rant

$Id$
*/

class News
{

    var $dbc;

    function News(&$dbc)
    {
        if (DB::isConnection($dbc)) {
            $this->dbc =& $dbc;
        } 
    }

    function addNews($title, $content, $uid, $username, $date)
	{
		global $message;
		
		$id = $this->dbc->nextId(XCOMIC_NEWS_TABLE);
		$sql = '
		    INSERT INTO '.XCOMIC_NEWS_TABLE.' (id, title , date, uid, username, content)
			VALUES ( 
			    '.$id.',
				'.$this->dbc->quoteSmart($title).',
				'.$date.',
				'.$uid.',
				'.$this->dbc->quoteSmart($username).',
				'.$this->dbc->quoteSmart($content).'
				)';
				
       $result = $this->dbc->query($sql);
		if (PEAR::isError($result)) {
			$message->error('Unable to add new news.');
		}
	}
	
	function delete($id)
	{
		global $message;
		
		$sql = '
		    DELETE FROM '.XCOMIC_NEWS_TABLE."
			WHERE id = $id";
		$result = $this->dbc->query($sql);
		//Make the changes happen
		if (PEAR::isError($result)) {
			$message->error('Unable to delete news entry.');
		}
	}

    function updateNews($id, $title, $content, $date=null)
    {
        global $message;
        
        $sql = 'UPDATE '.XCOMIC_NEWS_TABLE.' SET
                title = '.$this->dbc->quoteSmart($title).',
                content = '.$this->dbc->quoteSmart($content).
                ($date != null ? (', date = '.$date) : '').'
                WHERE id = '.$id;
        
        $result = $this->dbc->query($sql);
        //Make the changes happen
        if (PEAR::isError($result)) {
            $message->error('Unable to change news. SQL: '.$sql);
        }
    }
}
?>
