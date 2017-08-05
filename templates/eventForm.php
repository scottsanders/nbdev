	
	<?php global $endpoints; ?>

	<nav class="breadcrumbs">
		<a href="/admin/sites"><?php print $this->slug; ?></a><span class="icon-right-open"></span>
		<a href="/admin/sites">Sites</a><span class="icon-right-open"></span>
		<a href="/admin/sites/<?php print $this->site; ?>/events"><?php print $this->site; ?></a><span class="icon-right-open"></span> 
		Developer Excercises
	</nav>	
	<h1>Events</h1>	
	
	
	<ul class="menu-tabs">
		<li><a href="/admin/sites/<?php print $this->site; ?>/events/new" <?php if ($endpoints[4] == "new") print 'class="active"'; ?>>+ New Event</a></li>
		<li><a href="/admin/sites/<?php print $this->site; ?>/events" <?php if ($endpoints[5] == "edit") print 'class="active"'; ?>>Events</a></li>
	</ul>

	<form method="post" event="">

		<div class="field">
			<label>Event Name*</label>
			<input <?php if(isset($this->error['name'])) print 'class="field-error"'; ?> type="text" name="name" value="<?php print isset($this->event) ? $this->event['name'] : ""; ?>" size="40">
		</div>

		<div class="field">
			<label>Status*</label>
			<select name="status" class="<?php if(isset($this->error['status'])) print 'field-error'; ?>">
				<option value="published" <?php if(isset($this->event)) print $this->event['status'] == 'published' ? "selected" : ""; ?>>Published</option>
				<option value="unlisted" <?php if(isset($this->event)) print in_array($this->event['status'],array('unlisted','expired')) ? "selected" : ""; ?>>Unlisted</option>
				
			</select>
		</div>

		<div class="field">
			<label>Start/end time*</label>
			<input <?php if(isset($this->error['start_time'])) print 'class="field-error"'; ?> type="datetime-local" name="start_time" value="<?php print isset($this->event) ? $this->event['start_time'] : ""; ?>" size="40">
			<input <?php if(isset($this->error['end_time'])) print 'class="field-error"'; ?> type="datetime-local" name="end_time" value="<?php print isset($this->event) ? $this->event['end_time'] : ""; ?>" size="40">
			<div class="field-description">Time is relative to Melbourne, Australia.</div>

		</div>

		<div class="field">
			<label>Event Details*</label>
			<textarea name="intro" <?php if(isset($this->error['intro'])) print 'class="field-error"'; ?> rows="15" cols="75"><?php print isset($this->event) ? $this->event['intro'] : ""; ?></textarea>
			<div class="field-description">HTML is allowed.</div>
		</div>


		<div class="field">
			<input type="hidden" name="form_event" value="save">
			<button type="submit" class="button-action"><?php print $endpoints[4] == "new" ? "Create" : "Update"; ?> event</button>
			<?php if ($endpoints[5] == "edit"): ?>
				<!-- <a href="/admin/events/<?php print $endpoints[4]; ?>/delete" class="button" style="float: right" onclick="return confirm('Are you sure you want to delete this event?')">Delete event</a> -->
			<?php endif; ?>
		</div>

	</form>
