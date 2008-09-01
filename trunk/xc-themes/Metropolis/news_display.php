<div id="newsarea">
	<div id="news-header">
		<h2><span class="orange">console</span></h2>
	</div>
	
<?php //News display loop
do {
	if (get('getNewsContent') != '') { //If news exists, then display
		//$doesHaveNext = get('hasNextNews');
?>
	<!-- Might be a good idea to generate this using tables -->
	<div id="news-entry<?php //echo ($doesHaveNext ? '' : '-one'); ?>">
	<!--
		<div id="news-console">
			<img src="742.jpg" />
		</div>
	-->
		<div id="news-title">
			<?php out('getNewsTitle'); ?>
		</div>
		<div id="news-body">
			<strong><?php out('getNewsDate'); ?><!--Wednesday - December 7, 2005--></strong><br />
			[<strong><a href="<?php out('getNewsAuthorEmail'); ?>"><?php out('getNewsAuthor'); ?></a></strong>] - <?php out('getNewsTime'); ?><!--17:59:00-->
			<?php out('getNewsContent'); ?>
		</div>
	</div>
<?php 
	} //End empty news if check
} while(get('hasNextNews')); //End of News display loop 
?>

</div>
