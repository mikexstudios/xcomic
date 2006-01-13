    <?php //News display loop
     do {
       if (get('getNewsContent') != '') { //If news exists, then display
    ?>
         <div class="post">
     	<h2><?php out('getNewsTitle'); ?></h2>
     
     	<small>On <?php out('getNewsDate'); ?> <?php out('getNewsTime'); ?> by <a href="mailto:<?php out('getNewsAuthorEmail'); ?>"><?php out('getNewsAuthor'); ?></a></small>
     	<div class="entry">
     	<?php out('getNewsContent'); ?>
     	</div>
         </div>
     <?php 
        } //End empty news if check
      } while(get('hasNextNews')); //End of News display loop 
     ?>