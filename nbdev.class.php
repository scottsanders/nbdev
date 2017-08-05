<?php

require('nation.class.php');

class NBDev {

	protected $slug;
	protected $nation = false;
	protected $domain = "http://nb.dev";

	protected $site = false;
	protected $sites = array();

	protected $events = array();
	protected $event;

	protected $error = false;
	protected $success = false;

	function __construct($slug=false) { 
			
		if(!empty($_COOKIE["NBDevSlug"])) {
			$this->slug = $_COOKIE["NBDevSlug"];
			$this->nation = new Nation($this->slug);
		} else if(!empty($_GET["slug"])) {
			$this->slug = $_GET["slug"];
		}


	}

	public function render($template, $layout=true) {

		if($layout) include("templates/header.php");

		if (file_exists("templates/$template.php")) {
			include("templates/".$template.".php");
		}

		if($layout) include("templates/footer.php");

		exit();

	}

	public function isAuthorised() {
		return $this->nation->hasAccessToken();
	}

	public function authorise() {
		
		if (isset($_GET['code'])) { //if code in url, redirect

			header("Location: $this->domain/admin?code=" . $_GET['code']);
		
		} else if (!empty($_COOKIE["NBDevSlug"])) { //if code in url, redirect

			$this->nation = new Nation($this->slug);

			header("Location: $this->domain/admin");

		} else if (isset($this->slug)) { //else save cookie then get code

			setcookie("NBDevSlug", $this->slug);

			$this->nation = new Nation($this->slug);

			header("Location: $this->domain/admin");

		} else {
	
			header("Location: $this->domain");			

		}

	}

	public function loadEvents() {
		
		$this->events = $this->nation->getEvents($this->site);
		
	}

	public function loadEvent($id) {
		
		if(intval($id) > 0)	
			$this->event = $this->nation->getEvent($this->site, $id);

		//cleanup dates
		$st = explode("+",$this->event['start_time']);
		$et = explode("+",$this->event['end_time']);
		$this->event['start_time'] = $st[0];
		$this->event['end_time'] = $et[0];

		if(!empty($_POST['name'])) $this->event['name'] = $_POST['name'];
		if(!empty($_POST['intro'])) $this->event['intro'] = $_POST['intro'];
		if(!empty($_POST['status'])) $this->event['status'] = $_POST['status'];
		if(!empty($_POST['start_time'])) $this->event['start_time'] = $_POST['start_time'];
		if(!empty($_POST['end_time'])) $this->event['end_time'] = $_POST['end_time'];

	}

	public function setSite($site) {
		if(!empty($site))
			$this->site = $site;
	}

	public function loadSites() {

		$this->sites = $this->nation->findSites();

		if(count($this->sites) == 1) {

			$this->redirect("/admin/sites/".$this->sites[0]['slug']."/events");

		} 

	}

	public function saveEvent($id) {
		
		if(!isset($this->event)) return $this->error[] = "No event selected";
		
		//Check for required fields
		if(empty($_POST['name'])) $this->error['name'] = "Event Name is required";
		if(empty($_POST['intro'])) $this->error['intro'] = "Event Details are required";
		if(empty($_POST['status'])) $this->error['status'] = "Status is required";
		if(empty($_POST['start_time'])) $this->error['start_time'] = "Start time is required";
		if(empty($_POST['end_time'])) $this->error['end_time'] = "End time is required";

		if(!$this->error){

			$st = $this->event['start_time'];
			$et = $this->event['end_time'];
			$this->event['start_time'] .= ":00+10:00"; //Melbourne (NB required)
			$this->event['end_time'] .= ":00+10:00"; //Melbourne  (NB required)

			if(!empty($this->event['id'])){
				$event = array(
					"id" => $this->event['id'],
					"name" => $this->event['name'],
					"intro" => $this->event['intro'],
					"status" => $this->event['status'],
					"start_time" => $this->event['start_time'],
					"end_time" => $this->event['end_time']
				);
				$response = $this->nation->updateEvent($this->site, $event);

			} else {
				$response = $this->nation->createEvent($this->site, $this->event);;
				if(!empty($response['result']['event']['id'])){
					//redirect to events
					$this->redirect("/admin/sites/$this->site/events/".$response['result']['event']['id']."/edit");
				}
			}

			$this->event['start_time'] = $st;
			$this->event['end_time'] = $et;
			
			$this->success = "Event saved";
		}

	}

	public function redirect ($path) {
		header("Location: $path");
	}
}