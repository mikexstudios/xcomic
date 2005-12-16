<?php
//Calculate the time needed to execute the script
$xcomicEndTime = strtok(microtime(), ' ') + strtok(' ');
$executionTime = $xcomicEndTime-$xcomicStartTime;
?>
   <div id="footer">
	Page generated in <?php echo sprintf('%01.3f', $executionTime); ?> seconds <cite>Powered by <a href="http://xcomic.sourceforge.net" title="Powered by Xcomic, a web comic publishing platform"><strong>Xcomic</strong></a> <?php echo $settings->getSetting('version'); ?></cite> &copy; 2004-2005 Xcomic. Using <a href="http://www.wordpress.org">Wordpress' Administration Style</a>.
   </div>

<!-- End div for page-container -->
  </div>
 </body>
</html>
<?php
//End the script
exit;
?>