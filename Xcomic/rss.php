<?php
//Do necessary things
$xcomicRootPath = './';
include $xcomicRootPath.'Xcomic.php';
$xcomic = new Xcomic($db);

//Include RSSCreator
include $xcomicRootPath.'/includes/RSS.class.php';
$rss = new XML_RSSCreator('RSS 2.0');

//Include newsDisplay for news syndication
include $xcomicRootPath.'/includes/NewsDisplay.class.php';
$newsDisplay = new NewsDisplay($dbc);
//Check if RSS is enabled
$rssEnabled = (bool) $settings->getSetting('enableRSS');
if ($rssEnabled === FALSE) {
	die;
}
elseif ($rssEnabled === TRUE) {
	//For execution time
	$start = microtime();
	
	//Check what $_GET['feed'] is; if null, assume 'both', and if 'news' or 'comics', get just the news/comics
	$feed = $_GET['feed'];
	if ((!$feed) || (($feed != 'news') && ($feed != 'comics') && ($feed != 'both'))) {
		$feed = 'both';
	}

	$rss->title = $xcomic->security->removeMagicQuotes($settings->getSetting('title'));
	$rss->link = $xcomic->security->removeMagicQuotes($settings->getSetting('urlToXcomic'));
	//$rss->desc = $xcomic->security->removeMagicQuotes($settings->getSetting('title'));
	$rss->desc = $rss->title . ', powered by Xcomic';
	//Only fetch this many rows, since we'll only return this many to the user anyway
	$maxSyndicate = $settings->getSetting('rssNumComics');
	switch ($feed) {
		case 'news':
			$data = $xcomic->dbc->getAll('SELECT * FROM '.XCOMIC_NEWS_TABLE.' ORDER BY id DESC LIMIT '.$maxSyndicate);
			//No news to syndicate, or an error
			if (sizeOf($data) == '0') {
				die;
			}

			$rss->pubDate = $rss->rfc822Date($newsDisplay->getDate());
			$rss->buildDate = $rss->rfc822Date(time());
			$rss->docs = 'http://blogs.law.harvard.edu/tech/rss';
			
			$newsItems = sizeOf($data);
			$i = 0;
			while ($i < $newsItems) {
				$item = array(
					'title' => $data[$i]['title'],
					'description' => $data[$i]['username'] . ' writes, "' . $data[$i]['content'] . '"',
					'pubDate' => $rss->rfc822date($data[$i]['date'])
				);
				$rss->addItem($item);
				$i++;
			}

			//Now to echo it
			$finish = microtime();
			$rss->generator = 'Xcomic, http://xcomic.sourceforge.net, generated in ' . $finish - $start . 's';
			$rssData = $rss->save();
			header('Content-type: text/xml');
			echo $rssData;
		break;
		case 'comics':
			$data = $xcomic->dbc->getAll('SELECT * FROM '.XCOMIC_COMICS_TABLE.' ORDER BY cid DESC LIMIT '.$maxSyndicate);
			//No comics to syndicate, or an error
			if (sizeOf($data) == '0') {
				die;
			}

			$rss->pubDate = $rss->rfc822Date($xcomic->comicDisplay->getDate());
			$rss->buildDate = $rss->rfc822Date(time());
			$rss->docs = 'http://blogs.law.harvard.edu/tech/rss';
			
			$comicItems = sizeOf($data);
			$i = 0;
			while ($i < $comicItems) {
				$item = array(
					'title' => $data[$i]['title'],
					'description' => '<img src="' . $rss->link . '/comics/' . $data[$i]['filename'] . '" alt="' . $data[$i]['title'] . '" />',
					'pubDate' => $rss->rfc822date($data[$i]['date']),
					'guid' => $rss->link . '/index.php?cid=' . $data[$i]['cid']
				);
				$rss->addItem($item);
				$i++;
			}

			//Now to echo it
			$finish = microtime();
			$rss->generator = 'Xcomic, http://xcomic.sourceforge.net, generated in ' . $finish - $start . 's';
			$rssData = $rss->save();
			header('Content-type: text/xml');
			echo $rssData;
		break;
		case 'both':
		default:
		$data = $xcomic->dbc->getAll('SELECT * FROM '.XCOMIC_NEWS_TABLE.' ORDER BY id DESC LIMIT '.$maxSyndicate);
			//No news to syndicate, or an error
			if (sizeOf($data) == '0') {
				die;
			}

			$rss->pubDate = $rss->rfc822Date($newsDisplay->getDate());
			$rss->buildDate = $rss->rfc822Date(time());
			$rss->docs = 'http://blogs.law.harvard.edu/tech/rss';
			
			$newsItems = sizeOf($data);
			$i = 0;
			while ($i < $newsItems) {
				$item = array(
					'title' => $data[$i]['title'],
					'description' => $data[$i]['username'] . ' writes, "' . $data[$i]['content'] . '"',
					'pubDate' => $rss->rfc822date($data[$i]['date'])
				);
				$rss->addItem($item);
				$i++;
			}

			$comicdata = $xcomic->dbc->getAll('SELECT * FROM '.XCOMIC_COMICS_TABLE.' ORDER BY cid DESC LIMIT '.$maxSyndicate);
			$comicItems = sizeOf($data);
			$i = 0;
			while ($i < $comicItems) {
				$item = array(
					'title' => $comicdata[$i]['title'],
					'description' => '<img src="' . $rss->link . '/comics/' . $comicdata[$i]['filename'] . '" alt="' . $comicdata[$i]['title'] . '" />',
					'pubDate' => $rss->rfc822date($comicdata[$i]['date']),
					'guid' => $rss->link . '/index.php?cid=' . $comicdata[$i]['cid']
				);
				$rss->addItem($item);
				$i++;
			}

			//Now to echo it
			$finish = microtime();
			$rss->generator = 'Xcomic, http://xcomic.sourceforge.net, generated in ' . $finish - $start . 's';
			$rssData = $rss->save();
			header('Content-type: text/xml');
			echo $rssData;			
		break;
	}
}
?>