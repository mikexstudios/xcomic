    <div id="comictitle"><?php out('getComicTitle'); ?></div>
    <div id="comic"><img src="<?php out('getComicImageUrl'); ?>" alt="<?php out('getComicTitle'); ?>" /></div>
    <br />
    <div id="comic-functions">
        <ul class="comicnav comicnav-left">
           <?php //If there is no previous comic URL, do not display a link
             if(get('getPreviousComicUrl') != '') { ?>
            <li class="comicnav-link"><a href="<?php out('getPreviousComicUrl'); ?>">&lt; Previous</a></li>
           <?php } //End if ?>
        </ul>

        <ul class="comicnav comicnav-right">
           <?php //If there is no next comic URL, do not display a link
             if(get('getNextComicUrl') != '') { ?>
            <li class="comicnav-link"><a href="<?php out('getNextComicUrl'); ?>">Next &gt;</a></li>
           <?php } //End if ?>
        </ul>

        <form class="comicdropdown-form" action="" method="post">
        <script language="javascript">
        <!--
          function goToComic(inCid) {
               if (inCid != '') {
                    top.location.href = "<?php out('getThisScriptFilename'); ?>" + "?cid=" + inCid;
               }
          }
        //-->
        </script>
        <select onchange="goToComic(this.options[selectedIndex].value);" name="cid">
        <option value="" selected="selected">Archives</option>
        <?php //Get the rest of the comics
          out('getComicArchiveOptionList');
        ?>
        </select>
        </form>
    </div>