  <meta name="description" content="Xcomic, a web comic about [insert description]" />
  <meta name="keywords" content="xcomic, web comic, web manga, publishing platform" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link rel="shortcut icon" href="<?php out('theme_path'); ?>/images/favicon.gif" type="image/gif" />
  <link rel="stylesheet" type="text/css" href="<?php out('theme_path'); ?>/style.css" title="standard layout" media="screen" />
  <?php // Check for RSS feed
    if (get('rss_enabled')) { ?>
      <link rel="alternate" type="application/rss+xml" title="<?php out('site_title'); ?> RSS Feed" href="rss.php" />
  <?php } ?>
