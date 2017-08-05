	
	<nav class="breadcrumbs">
		<a href="/admin/sites"><?php print $this->slug; ?></a><span class="icon-right-open"></span>
		<a href="/admin/sites">Sites</a><span class="icon-right-open"></span>
		<a href="/admin/sites/<?php print $this->site; ?>/events"><?php print $this->site; ?></a><span class="icon-right-open"></span> 
		Developer Excercises
	</nav>
	
	<h1>Events</h1>	
	
	<ul class="menu-tabs">
		<li><a href="/admin/sites/<?php print $this->site; ?>/events/new">+ New Event</a></li>
		<li><a href="/admin/sites/<?php print $this->site; ?>/events" class="active">Events</a></li>
	</ul>

	<?php if (count($this->events)>0): ?>
	
		<p>Displaying latest <strong><?php print count($this->events) == 1 ? count($this->events) . " event" : count($this->events) . " events"; ?></strong></p>

		<table class="table-borderless">

			<tr>
				<th width="2%"></th>
				<th>Slug</th>
				<th class="verbose">Name</th>
				<th class="verbose">Status</th>
			</tr>

			<?php foreach ($this->events as $event): ?>
				<tr>
					<td><a href="/admin/sites/<?php print $this->site; ?>/events/<?php print $event['id'] ?>/edit" class="button small icon-pencil"></a></td>
					<td><a href="/admin/sites/<?php print $this->site; ?>/events/<?php print $event['id'] ?>/edit"><?php print $event['slug']; ?></a></td>
					<td class="verbose"><?php print $event['name'] ?></td>
					<td class="verbose"><?php print $event['status'] ?></td>
				</tr>
			<?php endforeach; ?>
		</table>

	<?php else: ?>

		<p>There are no events to display.</p>

	<?php endif; ?>
