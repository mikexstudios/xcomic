<?php
/**
Xcms - content management system by mikeXstudios
built off of phpbb and wiki system

//Security-
//Secure Input

*/

//TODO: Magic quotes
	
class Security {
	
	//Constructor
	//function Security() {
	//}
	
	//Allows only one connected string of letters, numbers, or _.
	//Note: Allows the null character to go through
	function allowOnlyChars($in_string) {
		$pattern='/\W/i'; //i disregards case \W is any non-word(not a letter, number, or underscore)
		$replace=''; //Replace with nothing
		//Allow only words to pass
		return preg_replace($pattern, $replacement, $in_string);	
	}
	
	function allowOnlyNumbers($inString) {
		return $inString;	
	}
	
	function allowEmail($inString) {
		return $inString;	
	}
	
	//Allows words and whitespace characters
	function allowOnlyWords($in_string) {
		$pattern='/[^\w\s]/i'; //i disregards case \w is any word or \s whitespace
		$replace=''; //Replace with nothing
		//Allow only words to pass
		return preg_replace($pattern, $replacement, $in_string);	
	}
	
	function escapeHTML($in_string) {
		return htmlentities($in_string);	
	}
	
	function escapeSQL($in_string) {
		return addslashes($in_string);	
	}
	
	//TO DO: Add more dangerous SQL tags
	function removeDangerousSQL($in_string) {
		$pattern='/(UNION)/i'; //i disregards case 
		$replace=''; //Replace with nothing
		return preg_replace($pattern, $replacement, $in_string);
	}
	
	//TO DO: Add more dangerous SQL tags
	function removeDangerousHTML($in_string) {
		$pattern='/(javascript|style)/i'; //i disregards case
		$replace=''; //Replace with nothing
		return preg_replace($pattern, $replacement, $in_string);
	}
	
	
	//HTML is escaped. Dangerous HTML is parsed.
	function allowBasicWriting($inString) {
		//return $this->removeDangerousHTML(htmlentities($in_string));
		return $inString;
	}
	
	/*
	function stripHTML() {
		
	}
	*/
	
	
	
}

/*			
//Test Security
$x = new Security();
echo $x->allowOnlyChars("Hello, my name is mike!")."\n";
echo $x->allowOnlyWords("Hello, my name is mike!")."\n";
echo $x->removeDangerousSQL("Hello, my name is mike! UNION SELECT ALL WHERE 1=1")."\n";
*/


?>