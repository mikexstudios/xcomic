<div id="comicarea">
	<div id="comic">
		<img src="<?php out('getComicImageUrl'); ?>" alt="<?php out('getComicTitle'); ?>" />
	</div>
	
	<div id="comicnavigation">
		<div id="comic-navigation-dropdown">
			<form>
		        <script language="javascript">
		        <!--
		          function goToComic(inCid) {
		               if (inCid != '') {
		                    top.location.href = "<?php out('getThisScriptFilename'); ?>" + "?cid=" + inCid;
		               }
		          }
		        //-->
		        </script>
				<select name="cid" onchange="goToComic(this.options[selectedIndex].value);">
				<option value="">MegaTokyo Archives</option>
		        <?php //Get the rest of the comics
		          out('getComicArchiveOptionList');
		        ?>
				</select>
			</form>
		</div>
		
		<div id="comic-navigation-buttons">
			<a href="?cid=1"><img src="<?php out('theme_path'); ?>/mt-bottom-start.gif" /></a>
			<?php //If there is no previous comic URL, do not display a link
				if(get('getPreviousComicUrl') != '') { ?>
				<a href="<?php out('getPreviousComicUrl'); ?>"><img src="<?php out('theme_path'); ?>/mt-bottom-prev.gif" /></a>
			<?php } //End if ?>
           
			<?php //If there is no next comic URL, do not display a link
				if(get('getNextComicUrl') != '') { ?>
				<a href="<?php out('getNextComicUrl'); ?>"><img src="<?php out('theme_path'); ?>/mt-bottom-next.gif" /></a>
			<?php } //End if ?>
			
		</div>
	</div>	
</div>
