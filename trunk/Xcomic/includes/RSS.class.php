<?php
//Note that this still needs to be properly phpDoc'd!

/**
 * XML_RSSCreator: Create RSS documents (RSS 0.9x, 1.0, 2.0, Atom)
 *
 * PHP versions 4 and 5
 *
 * @category   XML
 * @package    RSSCreator
 * @author     Michael Calder <keamos@gmail.com>
 * @copyright  2005
 * @license    http://www.gnu.org/copyleft/lesser.html  LGPL License 2.1
 * @version    CVS: $Id$
 * @link       http://rsscreator.doesntexist.com/
 * @see        XML_RSS
 */

require_once 'XML/Serializer.php';

/**
 * Error codes
 */
define('XML_RSSCREATOR_OK', 1);
define('XML_RSSCREATOR_ERROR_UNSUPPORTED', -1);
define('XML_RSSCREATOR_ERROR_NEED_MORE_DATA', -2);
define('XML_RSSCREATOR_ERROR_MISSING_ARGUEMENT', -3);
define('XML_RSSCREATOR_ERROR_NEED_ITEMS', -4);
define('XML_RSSCREATOR_ERROR_FOPEN_FAILED', -6);
define('XML_RSSCREATOR_ERROR_FWRITE_FAILED', -7);
define('XML_RSSCREATOR_ERROR_FCLOSE_FAILED', -8);

/**
 * Main class for creation of RSS files
 *
 * @category   XML
 * @package    RSSCreator
 * @author     Michael Calder <keamos@gmail.com>
 * @copyright  2005
 * @license    http://www.gnu.org/copyleft/lesser.html  LGPL License 2.1
 * @version    Release: @package_version@
 * @link       http://rsscreator.doesntexist.com
 * @see        XML_RSS
 */
class XML_RSSCreator
{
	/**
	 * Constructor for the class
	 * Default type for files is RSS 2.0
	 *
	 * @param string Type of RSS document to create
	 */
	function XML_RSSCreator($type = 'RSS 2.0')
	{
		$this->type = $type;
	}

	/**
	 * Creates RFC 822 formatted date, required for RSS 2.0 compliance
	 * 
	 * @param int Timestamp (seconds since epoch)
	 *
	 * @return string RFC 822 formatted date
     */
	function rfc822Date($time) {
		//Create the "Monday, May 23 ..." portion of the date
		$date = date('D, d M Y H:i:s', $time);
		//Get the timezone for the date
		$timezone = date('T', $time);
		//Create an array from the string
		$timezone = explode(' ', $timezone);
		
		$i = 0;
		$shortenedTZ = '';
		$sizeOf = sizeOf($timezone);
		//Create the 'EST' portion from 'Eastern Standard Time'
		while ($i < $sizeOf) {
			$shortenedTZ = $shortenedTZ . substr($timezone[$i], '0', '1');
			$i++;
		}
		
		$date = $date . ' ' . $shortenedTZ;
		return $date;
	}

	/**
	 * Add's an item to be included in the syndicated document
	 * In RSS, this is <item> and Atom this is <entry>
	 *
	 * @param array Data to add to the item
	 *
	 * @return int XML_RSSCREATOR_OK or XML_RSSCREATOR_ERROR_NEED_MORE_DATA
	 */
	function addItem($newItem)
	{
		if (!is_array($newItem))
		{
			return XML_RSSCREATOR_ERROR_NEED_MORE_DATA;
		}
		
		if ($this->type == 'RSS 2.0')
		{
			/*
			 * Check if title and description are not set, as one of them
			 * is required for RSS 2.0 compliance
			 */
			if ((isset($newItem['title']) !== TRUE) && (isset($newItem['description']) !== TRUE))
			{
				return XML_RSSCREATOR_ERROR_NEED_MORE_DATA;
			}

			$this->items[] = $newItem;
			return XML_RSSCREATOR_OK;
		}
	}
	
	/**
	 * Creates the RSS document
	 * If $this->file is set,
	 * @return int XML_RSSCREATOR_OK, XML_RSSCREATOR_ERROR_NEED_MORE_DATA, XML_RSSCREATOR_ERROR_FOPEN_FAILED, XML_RSSCREATOR_ERROR_FWRITE_FAILED, or XML_RSSCREATOR_ERROR_FCLOSE_FAILED
	 * If $this->file is not set,
	 * @return string RSS data or int XML_RSSCREATOR_ERROR_NEED_MORE_DATA
	 */
	function save()
	{
		if (!$this->file)
		{
			$string = 1;
		}

		//We're doing RSS 2.0
		if ($this->type == 'RSS 2.0')
		{
			/*
			 * Check that we got all required channel-level variables
			 * and if not, return an error
			 */
			if ((!$this->title) || (!$this->link) || (!$this->desc))
			{
				return XML_RSSCREATOR_ERROR_NEED_MORE_DATA;
			}

			$serializer_options = array(
				'addDecl' => true,
				'rootName' => 'rss version="2.0"',
				'defaultTagName' => 'item'
			);
			$serializer = new XML_Serializer($serializer_options);

			$channeltags = array(
				'lang' => 'language',
				'copy' => 'copyright',
				'manEditor' => 'managingEditor',
				'webMaster' => 'webMaster',
				'pubDate' => 'pubDate',
				'buildDate' => 'lastBuildDate',
				'category' => 'category',
				'generator' => 'generator',
				'docs' => 'docs',
				'cloud' => 'cloud',
				'ttl' => 'ttl',
				'image' => 'image',
				'rating' => 'rating',
				'textInput' => 'textInput',
				'skipHours' => 'skipHours',
				'skipDays' => 'skipDays'
			);

			//Loop through all of the optional <channel> data
			//and add them to an assoc. array if they're set, 
			//otherwise skip them
			$totalTags = sizeOf($channeltags);
			for ($i = 0; $i < $totalTags; $i++)
			{
				if (isset($this->$channeltags[$i]['0']) === TRUE)
				{
					$optionalChannelData[$channeltags][$i]['1'] = $this->$channeltags[$i]['0'];
				}
			}

			//Base <channel> data
			$rssData = array(
				'channel' => array(
					'title' => $this->title,
					'link' => $this->link,
					'description' => $this->desc,
				)
			);
			//Automagically add each optional channel to the
			//rssData array so they get serialized properly
			if (isset($optionalChannelData) === TRUE)
			{
				foreach ($optionalChannelData AS $key => $value)
				{
					$rssData['channel'][$key] = $value;
				}
			}
			
			/*
			 * Automagically add the items (hack-ish), as we need multiple <item>
			 * groups, but you can't have multiple parts of an array be named the
			 * same with different data and corresponding keys, so we set a default
			 * key and add indexed arrays instead that get added to 
			 * <$serializer_options['defaultTagName']>
			 */
			$totalItems = sizeOf($this->items);
			for ($i = 0; $i < $totalItems; $i++)
			{
				$rssData['channel'][$i] = $this->items[$i];
			}

			//Now we serialize the data
			$result = $serializer->serialize($rssData);
			$serialized = $serializer->getSerializedData();

			//Replace the incorrect closing tag with a proper one and return a string when set to
			if ($string == 1)
			{
				return str_replace('</rss version="2.0">', '</rss>', $serialized);
			}
			//Otherwise write to file
			elseif (($string == 0) && (isset($this->file) === TRUE))
			{
				if (!$handle = @fopen($this->file, 'wb')) {
					return XML_RSSCREATOR_ERROR_FOPEN_FAILED;
				}
				if (@fwrite($handle, str_replace('</rss version="2.0">', '</rss>', $serialized)) === FALSE)
				{
					return XML_RSSCREATOR_ERROR_FWRITE_FAILED;
				}
				if (@fclose($handle) === FALSE)
				{
					return XML_RSSCREATOR_ERROR_FCLOSE_FAILED;
				}
				return XML_RSSCREATOR_OK;
			}
		}
	}
}
?>
