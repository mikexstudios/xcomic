	<form method="POST" action="{ACTION_URL}" enctype="multipart/form-data">
	<input type="hidden" name="mode" value="{MODE}">
	
	<p>
	Next comic date and time (MM/DD/YYYY HH:MM):<br /><input type="text" name="{NEXT_COMIC_MONTH}" size="1" value="{CURR_COMIC_MONTH}" /> / <input type="text" name="{NEXT_COMIC_DAY}" size="1" value="{CURR_COMIC_DAY}" /> / <input type="text" name="{NEXT_COMIC_YEAR}" size="2" value="{CURR_COMIC_YEAR}" /> &nbsp; <input type="text" name="{NEXT_COMIC_HOUR}" size="1" value="{CURR_COMIC_HOUR}" /> : <input type="text" name="{NEXT_COMIC_MINUTES}" size="2" value="{CURR_COMIC_MINUTES}" />
	</p>
	
	<p>
	Next comic status:<br /><input type="text" name="{NEXT_COMIC_PERCENT}" size="1" value="{CURR_COMIC_PERCENT}" />%
	</p>
		
	<p>
	Comments on status:<br />
	<textarea wrap="soft" name="{NEXT_COMIC_COMMENT}" rows="5" cols="50">{CURR_COMIC_COMMENT}</textarea>
	</p>

	<p>
	<input type="submit" name="submit" value="Change Status" />
	</p>
	</form>
