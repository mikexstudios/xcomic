<ul id="comicnav" class="comicnav-left">
	<li class="comicnav-link"><a href="{PREV_COMIC_LINK}">{PREV_COMIC_TEXT}</a></li>
</ul>

<ul id="comicnav" class="comicnav-right">
	<li class="comicnav-link"><a href="{NEXT_COMIC_LINK}">{NEXT_COMIC_TEXT}</a></li>
</ul>

<form class="comicdropdown-form">
<script language="javascript">
<!--
	//From MegaTokyo (http://www.megatokyo.com)
        function StripJump(cid)        {
                if (cid != '')  {
                        top.location.href = "{THIS_FILE}" + "?cid=" + cid;
                }
        }
//-->
</script>
<select onchange="StripJump(this.options[selectedIndex].value);" name="cid"> 
<option value="" selected>{COMIC_DROPDOWN_HEADER}</option> 
{COMIC_OPTION_LIST}
</select>
</form>