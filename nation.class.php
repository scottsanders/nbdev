<?php
require_once('OAuth2/Client.php');
require_once('OAuth2/GrantType/IGrantType.php');
require_once('OAuth2/GrantType/AuthorizationCode.php');

class Nation {

	protected $client;

	protected $client_id 		= "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx";
	protected $client_secret 	= "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx";
	protected $redirect_url 	= "http://nbdev.s143255.gridserver.com/authorise";

	protected $slug;
	protected $base_url;
	protected $auth_url;
	protected $access_token_url;

	protected $code;
	public $access_token;

	function __construct($slug) {

		$this->slug = $slug;

		$this->base_url = "https://$slug.nationbuilder.com";
		$this->auth_url = "https://$slug.nationbuilder.com/oauth/authorize";
		$this->access_token_url = "https://$slug.nationbuilder.com/oauth/token";

		$this->client = new OAuth2\Client($this->client_id, $this->client_secret);
		
		if (!empty($_COOKIE["NBDevAccessToken"])){

			$this->access_token = $_COOKIE["NBDevAccessToken"];
			$this->client->setAccessToken($_COOKIE["NBDevAccessToken"]);

			if($_GET['code'])
				header("Location: /admin"); //redirect to take "code" out of url
			
		} else { //No access token yet

			$this->code = isset($_GET['code']) ? $_GET['code'] : "";
			
			if(empty($this->code)) //if no code, generate code from NB
				header("Location: ".$this->client->getAuthenticationUrl($this->auth_url, $this->redirect_url));
			
			if($this->get_access_token()){
				$this->client->setAccessToken($this->access_token);
				return;
			}

		}

		return false;

	}

	protected function get_access_token() {

		if (empty($this->code)) return false;
		
		if (empty($this->access_token)) {

			if (!empty($_COOKIE["NBDevAccessToken"])) {

				$this->access_token = $_COOKIE["NBDevAccessToken"];

			} else {

				$params = array('code' => $this->code, 'redirect_uri' => $this->redirect_url);

				$response = $this->client->getAccessToken($this->access_token_url, 'authorization_code', $params);

				$this->access_token = $response['result']['access_token'];
				$this->client->setAccessToken($this->access_token);
				
				setcookie("NBDevAccessToken", $this->access_token);
				
			}

			return $this->access_token;
		} else {
		
			$this->client->setAccessToken($this->access_token);
			return true;
		}
		
		//TODO: Cache this access token to speed up API transactions

	}

	function hasAccessToken() {
		return !empty($this->access_token);

	}

	function output($output, $format="json", $exit=true) {

		if ($format == "json")
			header('Content-type: application/json');

		print_r ($output);
		
		if($exit) exit;

	}


	function findSites($args=array(),$range=array(10,0)) {

		$args = array_merge($args, array("per_page"=>$range[0], "page"=>$range[1]));

		$response = $this->client->fetch($this->base_url . '/api/v1/sites', $args);
		$sites = array();

		foreach ($response['result']['results'] as $result) {
			$sites[] = $result;
		}
		
		return $sites;

	}

	function findPeople($args=array(),$range=array(10,0)) {

		$args = array_merge($args, array("per_page"=>$range[0], "page"=>$range[1]));

		$response = $this->client->fetch($this->base_url . '/api/v1/people/search', $args);
		$people = array();
		return $response;
		foreach ($response['result']['results'] as $result) {

			$person = array();
			$person['first_name'] = $result['first_name'];
			$person['last_name'] = $result['last_name'];
			$person['url'] = $this->base_url . "/" . $result['id'];

			$people[] = $person;
		}
		
		return $people;

	}

	function findPeopleInList($list_id,$limit=10) {

		$args = array("limit"=>$limit);

		$response = $this->client->fetch($this->base_url . '/api/v1/lists/'.$list_id.'/people', $args);
		$people = array();

		foreach ($response['result']['results'] as $result) {

			$person = array();
			$person['id'] = $result['id'];
			$person['postcode'] = $result['primary_address']['zip'];
			$person['federal_district'] = $result['federal_district'];
			// $person['response'] = $result;

			$people[] = $person;
		}
		
		return $people;

	}

	function findPeopleByTag($tag,$range=array(10,0)) {

		$tag = rawurlencode($tag);

		$response = $this->client->fetch($this->base_url . '/api/v1/tags/'.$tag.'/people');
		$people = array();

		if (empty($response['result']['results'])) return $people;

		foreach ($response['result']['results'] as $result) {

			$person = array();
			$person['first_name'] = $result['first_name'];
			$person['last_name'] = $result['last_name'];
			$person['url'] = $this->base_url."/".$result['id'];

			$people[] = $person;
		}
		
		return $people;

	}

	function matchPerson($email) {

		$response = $this->client->fetch($this->base_url . '/api/v1/people/match', array('email' => $email));
		return $response;

	}

	function getPerson($id) {

		$response = $this->client->fetch($this->base_url . '/api/v1/people/' . $id);
		return $response;

	}

	function updatePerson($id, $person) {

		$params = array('person' => $person);
		$headers = array('Content-Type' => 'application/json');

		$response = $this->client->fetch($this->base_url . '/api/v1/people/' . $id, $params, 'PUT', $headers);
		
		return $response;

	}

	function pushPerson($person) {

		$params = array('person' => $person);
		$headers = array('Content-Type' => 'application/json');

		$response = $this->client->fetch($this->base_url . '/api/v1/people/push', $params, 'PUT', $headers);
		
		return $response;

	}

	function tagPerson($id, $tag) {

		$params = array(
			'tagging' => array(
				'tag' => $tag,
			),
		);
		$headers = array('Content-Type' => 'application/json');

		$response = $this->client->fetch($this->base_url . '/api/v1/people/' . $id . '/taggings', $params, 'PUT', $headers);
		
		return $response;

	}

	function removeTag($id, $tag) {

		$tag = rawurlencode($tag);

		//$tag = 'test%20tag';

		$params = '';
		$headers = array('Content-Type' => 'application/json');
		$response = $this->client->fetch($this->base_url . '/api/v1/people/' . $id . '/taggings/' . $tag, $params, 'DELETE', $headers);

		return $response;

	}

	function createDonation($donation) {

		$params = array('donation' => $donation);
		$headers = array('Content-Type' => 'application/json');

		$response = $this->client->fetch($this->base_url . '/api/v1/donations', $params, 'POST', $headers);
		
		return $response;

	}

	function updateDonation($id, $donation) {

		$params = array('donation' => $donation);
		$headers = array('Content-Type' => 'application/json');

		$response = $this->client->fetch($this->base_url . '/api/v1/donations/' . $id, $params, 'PUT', $headers);
		
		return $response;

	}

	function getEvents($site, $range=array(10,0)) {

		$response = $this->client->fetch($this->base_url . '/api/v1/sites/'.$site.'/pages/events');
		$events = array();

		if (empty($response['result']['results'])) return $events;

		foreach ($response['result']['results'] as $result) {

			$event= $result;
			$events[] = $event;

		}
		
		return $events;

	}

	function getEvent($site, $id, $range=array(10,0)) {

		$response = $this->client->fetch($this->base_url . '/api/v1/sites/'.$site.'/pages/events/'.$id);
		$events = array();

		if (empty($response['result']['event'])) return $events;

		return $response['result']['event'];

	}

	function createEvent($site, $event, $range=array(10,0)) {

		$params = array("event"=>$event);
		$headers = array('Content-Type' => 'application/json');


		$response = $this->client->fetch($this->base_url . '/api/v1/sites/'.$site.'/pages/events', json_encode($params), 'POST', $headers);
		
		return $response;

	}

	function updateEvent($site, $event, $range=array(10,0)) {

		$params = array("event"=>$event);
		$headers = array('Content-Type' => 'application/json');

		$response = $this->client->fetch($this->base_url . '/api/v1/sites/'.$site.'/pages/events/'.$event['id'], json_encode($params), 'PUT', $headers);
		
		return $response;

	}

}