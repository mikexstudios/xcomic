	<form method="POST" action="{ACTION_URL}" enctype="multipart/form-data">
	<input type="hidden" name="mode" value="{MODE}">
	
	<p>
	Title:<br /><input type="text" name="title" size="80" value="{TITLE}" />
	</p>
	
	<p>
	Category:<br />
	<select name="category">
	{CATEGORY_OPTIONS}
	</select>
	</p>
	
	<p>
	Content:<br />
	<textarea wrap="soft" name="content" rows="20" cols="70">{CONTENT}</textarea>
	</p>

	<p>
	<input type="submit" name="submit" value="Post!" />
	</p>
	</form>
