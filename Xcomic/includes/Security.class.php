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
			foreach ($array as $k => $v) {
				if (is_array($v)) {
					$array[$k] = $this->addMagicQuotes($v);
				} else {
					$array[$k] = addslashes($v);
				}
			}
			return $array;
		} else {
			//Magic quotes is enabled. Just return it.
			return $array;
		}
	} 

	
	
	
}

/*	
//Test Security
$x = new Security();
echo $x->allowOnlyChars("Hello, my name is mike! 12345")."\n";
echo $x->allowOnlyNumbers("Hello, my name is mike! 12345")."\n";
echo $x->allowOnlyWordsNumbers("Hello, my name is mike! 12345 $#&(@#$2")."\n";
echo $x->secureText("Hello, my name is mike! 12345 $#&(@#$2")."\n";
echo $x->addMagicQuotes("Hello, my name is mike! 12345 ''")."\n";
*/
?>