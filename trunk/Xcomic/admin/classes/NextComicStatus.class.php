<?php
/**
Xcomic

$Id$
*/


class NextComicStatus
{
	
	//date is *nix timestamp
	var $nextDate;
	var $pctStatus;
	var $comment;
	var $dbc;

	function NextComicStatus(&$dbc, $inNextDate = null, $inPctStatus = null, $inComment = null)
	{
        if (DB::isConnection($dbc)) {
            $this->dbc =& $dbc;
        }
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
		global $message;
		
		//Change comic status
		$sql = '
		    UPDATE '.XCOMIC_NEXTCOMICSTATUS_TABLE.'  SET
			    nextdate = '.$this->dbc->quoteSmart($this->nextDate).',
			    percentstatus = '.$this->dbc->quoteSmart($this->pctStatus).',
			    comments = '.$this->dbc->quoteSmart($this->comment).'
			WHERE ncid = 0'; //ncid is constant
        $result = $this->dbc->query($sql);
		if (PEAR::isError($result)) {
			$message->error('Could not update next comic status.');
		}
	}
}

/*
//Testing

*/	


?>