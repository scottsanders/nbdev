<?php

require('mailgun-api/vendor/autoload.php');

// deps
//$mandrill = new Mandrill('IemoCir3RC8rxNWCxfg7uw');

$mailgun = new \Mailgun\Mailgun('key-1ed372722b64f2e89c7f2a54ac5c40c6');
$domain = 'commongrace.org.au';

/**
 * Manages profile API calls
 * 
 * @class CampaignActions_Email
 * @version 1.0
 * @package CampaignActions
 * @category Class
 */

class Mailgun {

	/**
	 * Process the submission
	 * 
	 * @param $vars Form post data (after validation)
	 * @return bool success/failure
	 */
	public function process($vars) {

		// send the email
		try {
			$response = $this->sendEmail(
				$vars['recipients'],
				$vars['from'],
				$vars['subject'],
				nl2br($vars['message'])
			);
		
		} catch (Exception $e) {
    	
    		$response = array("error"=> $e->getMessage(), "vars"=>$vars);
		
		}

		

		// send email to user
		// $response = $this->emailUser($vars);

		return $response;

	}

	/**
	 * Process the confirmation email to send to user
	 * 
	 * @param $vars Form post data (after validation)
	 * @return bool success/failure
	 */
	public function emailUser($vars) {
		// get the to email templates
		$to_template = get_field('email_from', $vars['pid']);
		$to_name = $this->processTemplate($to_template[0]['name'], $vars);
		$to_email = $this->processTemplate($to_template[0]['email'], $vars);

		// get the from email templates
		$from_template = get_field('complete_email_from', $vars['pid']);
		$subject_template = get_field('complete_email_subject', $vars['pid']);
		$body_template = get_field('complete_email_body', $vars['pid']);

		// prep the email
		$from_name = $this->processTemplate($from_template[0]['name'], $vars);
		$from_email = $this->processTemplate($from_template[0]['email'], $vars);
		$subject = $this->processTemplate($subject_template, $vars);
		$body = $this->processTemplate($body_template, $vars);

		// prep recipients
		$recipient = array(
			array(
				'name' => $to_name,
				'email' => $to_email,
				//'type' => 'to'
			)
		);
				

		// send the email
		$response = $this->sendEmail(
			$recipient,
			array('email' => $from_email, 'name' => $from_name),
			$subject,
			$body
		);

		return $response;
	}


	/**
	 * Process the mail template, replace strings
	 * 
	 * @param $template The raw text/HTML template
	 * @param $vars $_POST data to replace strings with
	 * @return string Complete merged template
	 */
	public function processTemplate($template, $vars) {

		// replace strings 
		foreach ($vars as $token => $value) {
			if (!empty($value)) {
				$patterns[] = "/\[$token([,].*)?\]/";
				$replacements[] = $value;
			}
		}

		$output = preg_replace($patterns, $replacements, $template);

		// replace fallbacks
		foreach ($vars as $token => $value) {
			preg_match_all("/\[$token(?:(?:[,].*)=(.*))?\]/", $template, $matches);
			$output = str_replace($matches[0], $matches[1], $output);
		}

		return $output;

	}


	/**
	 * Send email through Mailgun
	 * 
	 * @param $to array of email addresses
	 * @param $from array of from senders
	 */
	public function sendEmail($recipients = array(), $from = array(), $subject, $body) {

		global $mailgun;
		global $domain;

		$to_formatted = '';
		$i = 0;
		$total = count($recipients);

		foreach ($recipients as $recipient) {

			$to_formatted .= $recipient['name'] . ' <' . $recipient['email'] . '>'; 

			if ($i < $total - 1) {

				$to_formatted .= ', ';
			}

			$i++;
		}
		$from_formatted = $from['name'] . ' <' . $from['email'] . '>';

		$message = array(
			'to'      => $to_formatted,
            'from'    => $from_formatted,
            'subject' => $subject,
            'html'    => $body
        );

        $result = $mailgun->sendMessage($domain, $message);

        return $result;

	}

}