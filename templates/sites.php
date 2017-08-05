	<nav class="breadcrumbs">
		<a href="/admin/sites"><?php print $this->slug; ?></a><span class="icon-right-open"></span> Select a site
	</nav>
	
	<p>We found multiple Sites in your Nation, please select one to continue.</p>

	<form method="post" action="">

		<?php foreach ($this->sites as $site): ?>
			<div class="field">
				<input type="radio" id="site-<?php print $site['slug']; ?>" name="site" value="<?php print $site['slug']; ?>"> <label style="display:inline-block;margin:.2em;font-weight: bold;" for="site-<?php print $site['slug']; ?>"><?php print $site['name']; ?></label>
			</div>
		<?php endforeach; ?>
	
		<div class="field">
			<input type="hidden" name="form_event" value="select_site">
			<input type="submit" class="button-action" value="Continue">
		</div>
	</form>
