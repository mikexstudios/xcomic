<?php
	//Include Xcomic core operation files
	$xcomicRootPath = './';
	include($xcomicRootPath.'Xcomic.php');
	
	//Create Xcomic object
	$xcomic = new Xcomic();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<title>Xcomic - description</title>
<meta name="description" content="Tokei Mizuro, a web comic about [insert description]" />
<meta name="keywords" content="tokei, mizuro, web, comic, dave, kerkeslager" />
<meta name="author" content="David Kerkeslager" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link rel="stylesheet" type="text/css" href="styles/kubrick/layout.css" title="standard layout" media="screen" />

<style type="text/css" media="screen">

		/* BEGIN IMAGE CSS */
			/*	To accomodate differing install paths of WordPress, images are referred only here,
				and not in the wp-layout.css file. If you prefer to use only CSS for colors and what
				not, then go right ahead and delete the following lines, and the image files. */
			
			body	 	{ background: url("styles/kubrick/images/kubrickbgcolor.jpg"); }				
			#page		{ background: url("styles/kubrick/images/kubrickbg.jpg") repeat-y top; border: none; } 			
			#header 	{ background: url("styles/kubrick/images/kubrickheader.jpg") no-repeat bottom center; }
			#footer 	{ background: url("styles/kubrick/images/kubrickfooter.jpg") no-repeat bottom; border: none;}
			
			
			/*	Because the template is slightly different, size-wise, with images, this needs to be set here
				If you don't want to use the template's images, you can also delete the following two lines. */
			
			#header 	{ margin: 0 !important; margin: 0 0 0 1px; padding: 1px; height: 198px; width: 758px; }
			#headerimg 	{ margin: 7px 9px 0; height: 192px; width: 740px; } 
		/* END IMAGE CSS */
		

		/* 	To ease the insertion of a personal header image, I have done it in such a way,
			that you simply drop in an image called 'personalheader.jpg' into your /images/
			directory. Dimensions should be at least 760px x 200px. Anything above that will
			get cropped off of the image. */
		
		#headerimg 	{ background: url('styles/kubrick/images/personalheader.jpg') no-repeat top;}
		
	</style>

</head>

<body>
<div id="page">

<div id="header">
	<div id="headerimg">
		<h1><a href="asdf" title="asdf">Xcomic</a></h1>
		<div class="description">a webcomic about stuff</div>
	</div>
</div>

<div id="navigation" class="middlecolumn">
	<ul id="nav-menu">
	
		<li><a href=''>Characters</a></li>
		<li><a href=''>Information</a></li>
		<li><a href=''>Other Works</a></li>
		<li><a href=''>Forum</a></li>
		<li><a href=''>Links</a></li>
		<li><a href=''>About</a></li>
		<li><a href=''>Blog</a></li>
	
	</ul>
</div>

<div id="content" class="middlecolumn">

	<?php $xcomic->getImageCode(); ?>
	
	<br />
	
	<div id="comic-functions">
		<?php $xcomic->getComicNavCode(); ?>
	</div>
	
	<?php $xcomic->getNewsCode('default'); ?>

<!-- End div for id content -->
</div>



<div id="footer">
<p>
<a href="./admin">Administration Panel</a>
<br />
Page generated in <?php $xcomic->getExecutionTime(); ?> seconds <cite>Powered by <a href="http://www.mikexstudios.com" title="Powered by Xcomic, state-of-the-art web comic publishing platform"><strong>Xcomic</strong></a></cite> &copy; 2004 mikeXstudios. <a href="http://binarybonsai.com/kubrick/">Kubrick Design by Michael Heilemann</a>.
</p>
</div>

<!-- Ending div for page-container -->
</div>
</body>
</html>