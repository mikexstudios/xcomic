<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <title><?php out('site_title'); ?></title>
  <?php theme_include('page_head.php'); ?>
 </head>
 <body>
  <div id="page">
   <div id="header">
    <div id="headerimg">
     <h1><a href="./index.php" title="<?php out('site_title'); ?>"><?php out('site_title'); ?></a></h1>
     <div class="description">Powered by <a href="http://xcomic.sourceforge.net" title="Xcomic">Xcomic</a></div>
    </div>
   </div>

   <?php theme_include('page_nav.php'); ?>

   <div id="content" class="middlecolumn">
    <?php theme_include('comic_display.php'); ?>
    
    <?php theme_include('news_display.php'); ?>

    <!-- End div for id content -->
   </div>

   <?php theme_include('page_footer.php'); ?>
   <!-- Ending div for page-container -->
  </div>
 </body>
</html>
