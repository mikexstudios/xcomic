  <meta name="description" content="Xcomic, a web comic about [insert description]" />
  <meta name="keywords" content="xcomic, web comic, web manga, publishing platform" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link rel="shortcut icon" href="<?php out('theme_path'); ?>/images/favicon.gif" type="image/gif" />
  <link rel="stylesheet" type="text/css" href="<?php out('theme_path'); ?>/style.css" title="standard layout" media="screen" />
  <?php // Check for RSS feed
    if (get('rss_enabled')) { ?>
      <link rel="alternate" type="application/rss+xml" title="<?php out('site_title'); ?> RSS Feed" href="rss.php" />
  <?php } ?>
  <style type="text/css" media="screen">
   /* BEGIN IMAGE CSS */
   /*To accomodate differing install paths of Xcomic, images are referred only here,
     and not in the layout.css file. If you prefer to use only CSS for colors and what
	not, then go right ahead and delete the following lines, and the image files. */
     body	{ background: url("<?php out('theme_path'); ?>/images/kubrickbgcolor.jpg"); }
	#page { background: url("<?php out('theme_path'); ?>/images/kubrickbg.jpg") repeat-y top; border: none; }
	#header { background: url("<?php out('theme_path'); ?>/images/kubrickheader.jpg") no-repeat bottom center; }
	#footer { background: url("<?php out('theme_path'); ?>/images/kubrickfooter.jpg") no-repeat bottom; border: none;}

   /*Because the template is slightly different, size-wise, with images, this needs to be set here
	If you don't want to use the template's images, you can also delete the following two lines. */
	#header { margin: 0 !important; margin: 0 0 0 1px; padding: 1px; height: 198px; width: 758px; }
	#headerimg { margin: 7px 9px 0; height: 192px; width: 740px; } 
   /* END IMAGE CSS */

   /*To ease the insertion of a personal header image, I have done it in such a way,
	that you simply drop in an image called 'personalheader.jpg' into your /images/
	directory. Dimensions should be at least 760px x 200px. Anything above that will
	get cropped off of the image. */

   /*
	#headerimg 	{ background: url('<?php out('theme_path'); ?>/images/personalheader.jpg') no-repeat top;}
    */
  </style>