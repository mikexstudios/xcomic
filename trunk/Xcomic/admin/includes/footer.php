<?php
//Calculate the time needed to execute the script
$xcomicEndTime = strtok(microtime(), " ") + strtok(" ");
$executionTime = $xcomicEndTime-$xcomicStartTime;
?>
<div id="footer">
	Page generated in <?php echo sprintf('%01.3f', $executionTime); ?> seconds <cite>Powered by <a href="http://www.mikexstudios.com" title="Powered by Xcomic, state-of-the-art web comic publishing platform"><strong>Xcomic</strong></a></cite> &copy; 2004 mikeXstudios.
</div>

<!-- End div for page-container -->
</div>
</body>
</html>
<?php
//
// Close our DB connection.
//
//$xcomicDb->sql_close();

//End the script
exit;
?>