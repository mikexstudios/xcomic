<?php
/**
Xcms - content management system by mikeXstudios
built off of phpbb and wiki system

//Syntax-
//Wrapper for markup systems

$Id$
*/

//Xcms settings
define('IN_XCMS', true);
//$xcmsRootPath = "../";
				
class Syntax {
	
	//Class variables
	var $text;
	
	//Search and replacing
	var $strSearch, $strReplace, $regSearch, $regReplace;
	
	//Constructors
	function Syntax() {

	}
	
	//The following two functions have been lifted off of Wordpress:
	function wptexturize() {
		$text=$this->text;
		$output = '';
		// Capture tags and everything inside them
		$textarr = preg_split("/(<.*>)/Us", $text, -1, PREG_SPLIT_DELIM_CAPTURE);
		$stop = count($textarr); $next = true; // loop stuff
		for ($i = 0; $i < $stop; $i++) {
			$curl = $textarr[$i];
	
			if (isset($curl{0}) && '<' != $curl{0} && $next) { // If it's not a tag
				$curl = str_replace('---', '&#8212;', $curl);
				$curl = str_replace('--', '&#8211;', $curl);
				$curl = str_replace("...", '&#8230;', $curl);
				$curl = str_replace('``', '&#8220;', $curl);
	
				// This is a hack, look at this more later. It works pretty well though.
				$cockney = array("'tain't","'twere","'twas","'tis","'twill","'til","'bout","'nuff","'round");
				$cockneyreplace = array("&#8217;tain&#8217;t","&#8217;twere","&#8217;twas","&#8217;tis","&#8217;twill","&#8217;til","&#8217;bout","&#8217;nuff","&#8217;round");
				$curl = str_replace($cockney, $cockneyreplace, $curl);
	
				$curl = preg_replace("/'s/", '&#8217;s', $curl);
				$curl = preg_replace("/'(\d\d(?:&#8217;|')?s)/", "&#8217;$1", $curl);
				$curl = preg_replace('/(\s|\A|")\'/', '$1&#8216;', $curl);
				$curl = preg_replace('/(\d+)"/', '$1&Prime;', $curl);
				$curl = preg_replace("/(\d+)'/", '$1&prime;', $curl);
				$curl = preg_replace("/(\S)'([^'\s])/", "$1&#8217;$2", $curl);
				$curl = preg_replace('/(\s|\A)"(?!\s)/', '$1&#8220;$2', $curl);
				$curl = preg_replace('/"(\s|\Z)/', '&#8221;$1', $curl);
				$curl = preg_replace("/'([\s.]|\Z)/", '&#8217;$1', $curl);
				$curl = preg_replace("/\(tm\)/i", '&#8482;', $curl);
				$curl = preg_replace("/\(c\)/i", '&#169;', $curl);
				$curl = preg_replace("/\(r\)/i", '&#174;', $curl);
				$curl = str_replace("''", '&#8221;', $curl);
				
				$curl = preg_replace('/(d+)x(\d+)/', "$1&#215;$2", $curl);
	
			} elseif (strstr($curl, '<code') || strstr($curl, '<pre') || strstr($curl, '<kbd' || strstr($curl, '<style') || strstr($curl, '<script'))) {
				// strstr is fast
				$next = false;
			} else {
				$next = true;
			}
			$output .= $curl;
		}
		
		$this->text=$output;
	}
	
	function wpautop() {
		$pee=$this->text; 
		$br = 1;
		$pee = $pee . "\n"; // just to make things a little easier, pad the end
		$pee = preg_replace('|<br />\s*<br />|', "\n\n", $pee);
		// Space things out a little
		$pee = preg_replace('!(<(?:table|thead|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|select|form|blockquote|math|p|h[1-6])[^>]*>)!', "\n$1", $pee); 
		$pee = preg_replace('!(</(?:table|thead|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|select|form|blockquote|math|p|h[1-6])>)!', "$1\n", $pee);
		$pee = str_replace(array("\r\n", "\r"), "\n", $pee); // cross-platform newlines 
		$pee = preg_replace("/\n\n+/", "\n\n", $pee); // take care of duplicates
		$pee = preg_replace('/\n?(.+?)(?:\n\s*\n|\z)/s', "\t<p>$1</p>\n", $pee); // make paragraphs, including one at the end 
		$pee = preg_replace('|<p>\s*?</p>|', '', $pee); // under certain strange conditions it could create a P of entirely whitespace 
	    $pee = preg_replace('!<p>\s*(</?(?:table|thead|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|select|form|blockquote|math|p|h[1-6])[^>]*>)\s*</p>!', "$1", $pee); // don't pee all over a tag
		$pee = preg_replace("|<p>(<li.+?)</p>|", "$1", $pee); // problem with nested lists
		$pee = preg_replace('|<p><blockquote([^>]*)>|i', "<blockquote$1><p>", $pee);
		$pee = str_replace('</blockquote></p>', '</p></blockquote>', $pee);
		$pee = preg_replace('!<p>\s*(</?(?:table|thead|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|select|form|blockquote|math|p|h[1-6])[^>]*>)!', "$1", $pee);
		$pee = preg_replace('!(</?(?:table|thead|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|select|form|blockquote|math|p|h[1-6])[^>]*>)\s*</p>!', "$1", $pee); 
		if ($br) $pee = preg_replace('|(?<!<br />)\s*\n|', "<br />\n", $pee); // optionally make line breaks
		$pee = preg_replace('!(</?(?:table|thead|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|select|form|blockquote|math|p|h[1-6])[^>]*>)\s*<br />!', "$1", $pee);
		$pee = preg_replace('!<br />(\s*</?(?:p|li|div|dl|dd|dt|th|pre|td|ul|ol)>)!', '$1', $pee);
		$pee = preg_replace('!(<pre.*?>)(.*?)</pre>!ise', " stripslashes('$1') .  clean_pre('$2')  . '</pre>' ", $pee);
		
		$this->text=$pee; 
	}
	
	function parse($inText) {
		$this->text = $inText;
		
		$this->wpautop();
		$this->wptexturize();

		return $this->text;
	}
	

}

/*	
//Test Syntax
$syntax=<<<testtext
Hi, this is just a test.

I have nothing to really say here. This is a <a href="http://www.asdf.com">asdf</a>.

-mX "This is a quote!"

testtext;

$x = new Syntax();
echo $x->parse($syntax);
*/

?>