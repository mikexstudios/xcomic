<?php
/**
Xcomic

$Id$
*/

/*
define('IN_XCOMIC', true);

$xcomicRootPath='../';
include_once($xcomicRootPath.'initialize.php');
*/


class NextComicStatus {
	
	//date is *nix timestamp
	var $nextDate, $pctStatus, $comment;

	function NextComicStatus($inNextDate=NULL, $inPctStatus=NULL, $inComment=NULL) {
		$this->nextDate = $inNextDate;
		$this->pctStatus = $inPctStatus;
		$this->comment = $inComment;
	}
	
	function setNextDate($inNextDate) {
		$this->nextDate = $inNextDate;
	}
	
	function setNextPercentStatus($inPctStatus) {
		$this->pctStatus = $inPctStatus;
	}
	
	function setNextComment($inCommen) {
		$this->comment = $inComment;
	}
	
	function changeStatus() {
		global $xcomicDb, $message;
		
		//Change comic status
		$sql = 'UPDATE '.XCOMIC_NEXTCOMICSTATUS_TABLE." 
			SET nextdate = '$this->nextDate',
			percentstatus = '$this->pctStatus',
			comments = '$this->comment'
			WHERE ncid = 0"; //ncid is constant

		if( !($result = $xcomicDb->sql_query($sql)) )
		{
			$message->error("Could not update next comic status.");
		}
	}
}

/*
//Testing

*/	


?>