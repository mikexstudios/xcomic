<?php
function rfc822($ctime) {
	//Makes RFC822-formatted time/date string
	//Required to follow RSS 2.0 specification
	$date = date('D, d M Y H:i:s', $ctime);
	$tz = date('T', $ctime);
	$tz = explode(' ', $tz); $i = 0;
	while ($i < sizeOf($tz)) {
		$shortz .= substr($tz[$i], '0', '1');
		$i++;
	}
	$date .= " $shortz";
	return $date;
}

//Do necessary things
$xcomicRootPath = './';
include $xcomicRootPath.'Xcomic.php';
$xcomic = new Xcomic($db);

//Check if RSS is enabled
$rssEnabled = (bool) $settings->getSetting('enableRSS');
if ($rssEnabled === FALSE) {
	die;
}
elseif ($rssEnabled === TRUE) {
	//For execution time
	$start = microtime();
	
	//Information for <channel>
	$comicTitle = stripslashes($settings->getSetting('title'));
	$comicLink = $settings->getSetting('urlToXcomic') . '/';
	$comicTotal = $settings->getSetting('rssNumComics');
	//$comicID = $xcomic->comicDisplay->getLatestComicID();
	
	//Array of the comics to be syndicated
	$comics = $xcomic->dbc->getAll('SELECT * FROM '.XCOMIC_COMICS_TABLE.' ORDER BY cid DESC LIMIT '.$comicTotal);
	
	//There were no comics retrieved for some reason (including none posted in the first place)
	if (sizeOf($comics) == '0') { die; }
	
	$comicDate = $xcomic->comicDisplay->getDate();

	header('Content-type: text/xml');
	echo '<?xml version="1.0"?>
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