	
	<nav class="breadcrumbs">
		<a href=""><?php print $this->slug; ?></a><span class="icon-right-open"></span>
	</nav>
	
	<h1>Cron jobs</h1>	
	
	<ul class="menu-tabs">
		<li><a href="/admin/actions/new">+ New Action</a></li>
		<li><a href="/admin/actions">Actions</a></li>
		<li><a href="/admin/cron" class="active">Cron jobs</a></li>
	</ul>

	<p>Perform additional tasks when a <em>Signup</em> is created or updated.</p>

	<form action="">

		<table class="table-borderless">

			<tr>
				<th width="1%"></th>
				<th width="33%">Task</th>
				<th class="verbose">Description</th>
				<th width="10%" class="center">Last run</th>
			</tr>

			<tr>
				<td><a href="/admin/cron/auto-districting/edit" class="button small icon-pencil"></a></td>
				<td><strong>Auto-districting by postcode</strong></td>
				<td class="verbose">If federal electorate is not known, lookup by postcode through <a href="http://www.openaustralia.org.au" style="color:inherit;text-decoration:underline;">OpenAustralia</a>.</td>
				<td class="minor center">5 days ago</td>
			</tr>

			<tr>
				<td><a href="/admin/cron/gender/edit" class="button small icon-pencil"></a></td>
				<td><strong>Gender suggestion</strong></td>
				<td class="verbose">Suggest gender based on first name with <a href="http://www.openaustralia.org.au" style="color:inherit;text-decoration:underline;">GenderAPI</a>, when accuracy is at least 95%.</td>
				<td class="minor center">5 days ago</td>
			</tr>

		</table>
	
		<div class="field">
			<input type="hidden" name="form_action" value="save">
			<button type="submit" class="button-action">Save Tasks</button>
		</div>

	</form>
