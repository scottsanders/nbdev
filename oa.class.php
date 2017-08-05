<?php
require('oaapi.php');

/**
 * Manages profile API calls
 * 
 * @class CampaignActions_OA
 * @version 1.0
 * @package CampaignActions
 * @category Class
 */

class OpenAustralia {

	protected $oaapi;
	
	function __construct() {

		$this->oaapi = new OAAPI('AUi5noAfaeQ3CG3Ti4FiWs6y');

	}

	public function find($postcode) {

		$mps = $this->oaapi->query('getRepresentatives', array('output' => 'js', 'postcode' => $postcode));
		return $mps;

	}

	public function electorate($postcode) {

		$response = $this->oaapi->query('getDivisions', array('output' => 'js', 'postcode' => $postcode));
		return $response;

	}

	public function person($id) {

		$mps = $this->oaapi->query('getRepresentative', array('output' => 'js', 'id' => intval($id)));
		return $mps;

	}

	public function build_email($id) {

		$mp = json_decode($this->person($id));
		return array(
			'name' => $mp[0]->full_name,
			'email' => $this->clean($mp[0]->first_name . '.' . $mp[0]->last_name . '.MP').'@aph.gov.au'
			// 'email' => 'scott+' . $this->clean($mp[0]->first_name . $mp[0]->last_name) . '@commongrace.org.au' 
		);

	}

	public function clean($input){
		$input = strtolower($input);
		$input = str_replace("'","",$input);
		return $input;

	}

}