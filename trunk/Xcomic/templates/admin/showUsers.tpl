	<div class="user-list">
		<table cellspacing="0" cellpadding="0" class="user-list">
		<tr class="each-user">
			<td class="list-username">Username:</td>
			<td class="list-userfunc" colspan="2">Functions:</td>
		</tr>
		{LIST_OF_USERS}
		<!--
		<tr class="each-user">
			<td class="list-username">Admin</td>
			<td class="list-userfunc"><a href="">Edit</a></td>
			<td class="list-userfunc"><a href="">Delete</a></td>
		</tr>
		-->
		</table>
	</div>
	
	<p class="sub-section-title">Add a new user:</p>
	<p>
		<form method="POST" action="{ACTION_URL}" enctype="multipart/form-data">
		<input type="hidden" name="mode" value="{MODE}">
		
		<p>
		Username:<br /><input type="text" name="{ADDIN_USERNAME}" size="20" />
		</p>
		
		<p>
		Password:<br /><input type="password" name="{ADDIN_PASSWORD}" size="20" />
		</p>
		
		<p>
		E-mail Address:<br /><input type="text" name="{ADDIN_EMAIL}" size="20" />
		</p>
	
		<p>
		<input type="submit" name="submit" value="Add New User" />
		</p>
		</form>
	</p>