	<form method="POST" action="{ACTION_URL}" enctype="multipart/form-data">
	<input type="hidden" name="mode" value="{MODE}">
	<p>
		Base Url:<br /><input type="text" name="{OPT_BASE_URL}" size="20" value="{CURR_BASE_URL}" />
	</p>
	<p>
		Url to Xcomic:<br /><input type="text" name="{OPT_URL_TO_XCOMIC}" size="20" value="{CURR_URL_TO_XCOMIC}" />
	</p>
	<p>
		Using theme:<br /><input type="text" name="{OPT_USING_THEME}" size="10" value="{CURR_USING_THEME}" />
	</p>
	<p>
	<input type="submit" name="submit" value="Change options" />
	</p>
	</form>