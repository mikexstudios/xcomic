	<form method="POST" action="{ACTION_URL}" enctype="multipart/form-data">
	<input type="hidden" name="mode" value="{MODE}">
	
	<p>
	Comic Title:<br /><input type="text" name="title" size="80" />
	</p>
	
	<p>
	Upload Comic:<br />
	<input type="file" size="35" name="comicFile">
	</p>

	<p>
	<input type="submit" name="submit" value="Post!" />
	</p>
	</form>
