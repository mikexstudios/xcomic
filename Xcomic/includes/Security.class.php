<?php
/**
Xcms - content management system by mikeXstudios
built off of phpbb and wiki system

//Security-
//Secure Input

*/

//TODO: Magic quotes
	
class Security 
{	
	//Constructor
	//function Security() {
	//}
	
	//Allows only one connected string of letters, numbers, or _.
	//Note: Allows the null character to go through
	function allowOnlyChars($inString)
	{
		$pattern='/\W/i'; //i disregards case \W is any non-word(not a letter, number, or underscore)
		$replace=''; //Replace with nothing
		//Allow only words to pass
		return preg_replace($pattern, $replace, $inString);	
	}
	
	function allowOnlyNumbers($inString)
	{
		$pattern='/\D/i'; //i disregards case \D is any non-decimal digit
		$replace=''; //Replace with nothing
		//Allow only numbers to pass
		return preg_replace($pattern, $replace, $inString);	
	}
	
	function allowOnlyEmail($inString)
	{
		return $inString;	
	}
	
	//Allows words and whitespace characters. No punctuation.
	function allowOnlyWordsNumbers($inString)
	{
		$pattern='/[^\w\s]/i'; //i disregards case \w is any word or \s whitespace
		$replace=''; //Replace with nothing
		//Allow only words to pass
		return preg_replace($pattern, $replace, $inString);	
	}
	
	//Performs magic quotes on text through addslashes
	function secureText($inString)
	{
		return $this->slashText($inString);
	}
	
	function slashText($inString)
	{
		return $this->addMagicQuotes($inString);
	}
	
	//Reverses addslashes
	function unsecureText($inString)
	{
		return $this->unslashText($inString);
	}
	
	function unslashText($inString)
	{
		//Unquote something slashed by addslashes or magic quotes
		return stripslashes($inString);
	}
	
	function escapeHTML($inString)
	{
		return htmlentities($inString);	
	}
	
	function escapeSQL($inString)
	{
		return $this->addMagicQuotes($inString);	
	}
	
	/*
	//TO DO: Add more dangerous SQL tags
	function removeDangerousSQL($in_string) {
		$pattern='/(UNION)/i'; //i disregards case 
		$replace=''; //Replace with nothing
		return preg_replace($pattern, $replacement, $in_string);
	}
	*/
	
	function removeHTML($inString)
	{
		//Removes all HTML and PHP tags from a string
		return strip_tags($inString);	
	}
	
	/*
	//TO DO: Add more dangerous SQL tags
	function removeDangerousHTML($in_string) {
		$pattern='/(javascript|style)/i'; //i disregards case
		$replace=''; //Replace with nothing
		return preg_replace($pattern, $replacement, $in_string);
	}
	*/
	
	//From Wordpress
	function addMagicQuotes($array)
	{
		if (!get_magic_quotes_gpc()) {
			if (is_array($array)) {
				foreach ($array as $k => $v) {
					if (is_array($v)) {
						$array[$k] = $this->addMagicQuotes($v);
					} else {
						$array[$k] = addslashes($v);
					}
				}
			}else{
				$array = addslashes($array);
			}
			return $array;
		} else {
			//Magic quotes is enabled. Just return it.
			return $array;
		}
	} 

	//Use this if you know quotes are possibly being added by magic quotes
	//and that you didn't add any yourself.
	function removeMagicQuotes($inString)
	{
		if (!get_magic_quotes_gpc()) {
			return $inString;
		} else {
			$string = stripslashes($inString);
		}
		return $string;
	}
}

?>