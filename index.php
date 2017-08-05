<?php
date_default_timezone_set('Australia/Sydney');

require('nbdev.class.php');

//Split endpoints
$endpoints = explode("/", $_GET['endpoint']);

if(empty($_GET['endpoint'])){
	setcookie("NBDevSlug");
	setcookie("NBDevAccessToken");
	setcookie("NBDevSlugscottsanders");
	setcookie("NBDevSlugcommongrace");
}

$app = new NBDev();

if ($endpoints[0] == "authorise") {

	$app->authorise();

} else if ($endpoints[0] == "admin"){

	if ($app->isAuthorised()) {

		if ($endpoints[1] == "sites"){
	
			$app->setSite($endpoints[2]);			

			if ($endpoints[3] == "events"){

				if ($endpoints[4] == "new"){

					$app->loadEvent($_POST);

					if($_POST['form_event'] == "save")
						$app->saveEvent($endpoints[4]);

					$app->render("eventForm");

				} else if ($endpoints[5] == "edit") {

					if(isset($_POST['slug']))
						$app->loadEvent($_POST);
					else
						$app->loadEvent($endpoints[4]);

					if($_POST['form_event'] == "save")
						$app->saveEvent($endpoints[4]);

					$app->render("eventForm");

				}

				$app->loadEvents();
				$app->render("events");

			} else {

				if($_POST['form_event'] == "select_site")
					$app->redirect("/admin/sites/".$_POST['site']."/events");

				$app->loadSites("sites");
				$app->render("sites");

			}

		} else {

			$app->redirect("/admin/sites");

		}

	} else {

		$app->redirect("/");
	
	}

} else {

	$app->render("nationForm");

}

