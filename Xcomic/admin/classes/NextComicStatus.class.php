<?php
/**
Xcomic

$Id$
*/

/*
define('IN_XCOMIC', true);

$xcomicRootPath='../';
include_once $xcomicRootPath.'initialize.php';
*/


class NextComicStatus
{
	
	//date is *nix timestamp
	var $nextDate, $pctStatus, $comment;

	function NextComicStatus($inNextDate = null, $inPctStatus = null, $inComment = null)
	{
		$this->nextDate = $inNextDate;
		$this->pctStatus = $inPctStatus;
		$this->comment = $inComment;
	}
	
	function setNextDate($inNextDate)
	{
		$this->nextDate = $inNextDate;
	}
	
	function setNextPercentStatus($inPctStatus)
	{
		$this->pctStatus = $inPctStatus;
	}
	
	function setNextComment($inCommen)
	{
		$this->comment = $inComment;
	}
	
	function changeStatus()
	{
		global $db, $message;
		
		//Change comic status
		$sql = 'UPDATE '.XCOMIC_NEXTCOMICSTATUS_TABLE.' 
			SET
			nextdate = '.$db->quoteSmart($this->nextDate).',
			percentstatus = '.$db->quoteSmart($this->pctStatus).',
			comments = '.$db->quoteSmart($this->comment).'
			WHERE ncid = 0'; //ncid is constant
        $result = $db->query($sql);
		if (PEAR::isError($result)) {
			$message->error('Could not update next comic status.');
		}
	}
}

/*
//Testing

*/	


?>