<!DOCTYPE html>
<html class="no-js">
<head>
	
	<meta charset="utf-8">

	<title>Nation Builder Developer Excercises</title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="shortcut icon" href="/favicon.png" type="image/png">

	<link rel="stylesheet" href="/base/dist/css/site.min.css?<?php print time(); ?>">

	<link href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" type="text/css">

</head>

<body>

	<?php //if($this->site): ?>

		<header class="primary">
			
			<nav class="container">
				<ul class="menu-primary">
					<li><a href="/">Developer Excercises</a></li>
				</ul>
			</nav>
		
		</header>

	<?php //endif; ?>


	<section class="main">

		<div class="container">
			
			<div class="main-content">

				<?php if($this->error): ?>
					<div class="error"><?php print implode(", ",$this->error); ?></div>
				<?php endif; ?>
				<?php if($this->success): ?>
					<div class="success"><?php print $this->success; ?></div>
				<?php endif; ?>