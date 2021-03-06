<?php
/**
Xcomic

//pageHeader-
//the beginning of every page

$Id$
*/

//
// gzip_compression
//

if ($settings->getSetting('gzipcompress') && !@ini_get('zlib.output_compression')) {
	$phpver = phpversion();

	$useragent = (isset($_SERVER["HTTP_USER_AGENT"]) ) ? $_SERVER["HTTP_USER_AGENT"] : $HTTP_USER_AGENT;

    $do_gzip_compress = false;
	if ($phpver >= '4.0.4pl1' && (strstr($useragent,'compatible') || strstr($useragent,'Gecko'))) {
		if (extension_loaded('zlib')) {
			ob_start('ob_gzhandler');
		}
	} elseif ($phpver > '4.0') {
		if (strstr($HTTP_SERVER_VARS['HTTP_ACCEPT_ENCODING'], 'gzip')) {
			if (extension_loaded('zlib')) {
				$do_gzip_compress = true;
				ob_start();
				ob_implicit_flush(0);

				header('Content-Encoding: gzip');
			}
		}
	}
	unset($phpver);
}

?>