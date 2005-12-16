<?php
function rfc822($ctime) {
	//Makes RFC822-formatted time/date string
	//Required to follow RSS 2.0 specification
	$date = date('D, d M Y H:i:s', $ctime);
	$tz = date('T', $ctime);
	$tz = explode(' ', $tz); $i = 0;
	$shortz = '';
	while ($i < sizeOf($tz)) {
		$shortz .= substr($tz[$i], '0', '1');
		$i++;
	}
	$date .= " $shortz";
	return $date;
}

//Do necessary things
$xcomicRootPath = './';
require_once $xcomicRootPath.'initialize.php';	//Include all page common settings
include $xcomicRootPath.'Xcomic.php';
$xcomic = new Xcomic($db);

//Check if RSS is enabled
$rssEnabled = $settings->getSetting('enableRSS');
if (!$rssEnabled) {
	die;
}
else {
	//For execution time
	$start = microtime();

	//Information for <channel>
	$comicTitle = stripslashes($settings->getSetting('title'));
	$comicLink = 'http://'.$_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . '/';
	$comicTotal = $settings->getSetting('rssNumComics');
	//$comicID = $xcomic->comicDisplay->getLatestComicID();
	
	//Array of the comics to be syndicated
	$comics = $xcomic->dbc->getAll('SELECT * FROM '.XCOMIC_COMICS_TABLE.' ORDER BY cid DESC LIMIT '.$comicTotal);
	
	//There were no comics retrieved for some reason (including none posted in the first place)
	if (sizeOf($comics) == '0') { die; }

	$comicDate = $xcomic->comic->getDate();

	header('Content-type: text/xml');
	echo '<?xml version="1.0" encoding="UTF-8"?>
		<rss version="2.0">
			<channel>
				<title>'.$comicTitle.'</title>
				<link>'.$comicLink.'</link>
				<description>'.$comicTitle.' RSS Feed</description>
				<pubDate>'.rfc822($comicDate).'</pubDate>
				<lastBuildDate>'.rfc822(time()).'</lastBuildDate>
				<docs>http://blogs.law.harvard.edu/tech/rss</docs>';
	$i = 0;
	while ($i < sizeOf($comics)) {
		$title = stripslashes($comics[$i]['title']);
		$link = $comicLink . $comics[$i]['filename'];
		$guid = $comicLink . 'index.php?cid=' . $comics[$i]['cid'];
		echo '
			<item>
				<title>'.$title.'</title>
				<link>'.$link.'</link>
				<description>'.$title.'</description>
				<pubDate>'.rfc822($comics[$i]['date']).'</pubDate>
				<guid>'.$guid.'</guid>
			</item>';
		$i++;
	}
	$finish = microtime();
	$total = $finish - $start;
	echo '<generator>Xcomic, http://xcomic.sourceforge.net, generated in '.$total.'s</generator></channel></rss>';
}
?>