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
<title>Tokei Mizuro - a web comic by Dave Kerkeslager</title>
<meta name="description" content="Tokei Mizuro, a web comic about [insert description]" />
<meta name="keywords" content="tokei, mizuro, web, comic, dave, kerkeslager" />
<meta name="author" content="David Kerkeslager" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link rel="stylesheet" type="text/css" href="<?php echo $xcomicRootPath.'templates/'.$configInfo['usingTheme'].'/'; ?>main.css" title="standard layout" media="screen" />
</head>

<body>
<div id="page-container">

<div id="left-side">

<div id="header">
<a href="/index.html"><img src="images/header.gif" height="120" width="600" border="0" alt="Tokei Mizuro Banner"></a>
</div>

<div id="content">
	
	<div id="top-text">
	</div>
	
	<?php $xcomic->getImageCode() ?>
	
	
	<?php $xcomic->getPrevNextCode() ?>
	
	<?php $xcomic->getComicStatusCode('White') ?>

	<div id="rantbox">
		<div class="leftRantbox">
			<?php $xcomic->getNewsCode('default') ?>
		</div>
		
		<div class="rightRantbox">
			<?php $xcomic->getNewsCode('right') ?>
		</div>
	</div>

<!-- End div for id content -->
</div>

<!-- End div for id left-side -->
</div>

<div id="right-side">
<div id="navigation">
<span class="largeText">Navigation:</span>
	<ul class="menu">
		<li class="menu-item">
			<ul>
				<li class="nav-image">
				<a href=""><img src="" width="60" height="60"></a>
				</li>
				<li class="nav-text">
				<a href="">Characters</a>
				</li>
			</ul>
		</li>
		<li class="menu-item">
			<ul>
				<li class="nav-image">
				<a href=""><img src="" width="60" height="60"></a>
				</li>
				<li class="nav-text">
				<a href="">Info</a>
				</li>
			</ul>
		</li>
		<li class="menu-item">
			<ul>
				<li class="nav-image">
				<a href=""><img src="" width="60" height="60"></a>
				</li>
				<li class="nav-text">
				<a href="">Other works</a>
				</li>
			</ul>
		</li>
		<li class="menu-item">
			<ul>
				<li class="nav-image">
				<a href=""><img src="" width="60" height="60"></a>
				</li>
				<li class="nav-text">
				<a href="">Forum</a>
				</li>
			</ul>
		</li>
		<li class="menu-item">
			<ul>
				<li class="nav-image">
				<a href=""><img src="" width="60" height="60"></a>
				</li>
				<li class="nav-text">
				<a href="">Links</a>
				</li>
			</ul>
		</li>
		<li class="menu-item">
			<ul>
				<li class="nav-image">
				<a href=""><img src="" width="60" height="60"></a>
				</li>
				<li class="nav-text">
				<a href="">About</a>
				</li>
			</ul>
		</li>
	</ul>
</div>

<!-- End div for id right-side -->
</div>

<!-- Ending div for class wrap -->
</div>
</body>
</html>