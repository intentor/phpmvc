<?php
class ErrorController extends Intentor\PhpMVC\Controller {	
	/**
	 * /error/index/code
	 */
	function index($code) {
		switch ($code) {
			case '500':
				$this->view('index', TITLE_INDEX);
				break;
			case '400':
				$this->view('http400', TITLE_HTTP400);
				break;
			case '401':
				$this->view('http401', TITLE_HTTP401);
				break;
			case '403':
				$this->view('http403', TITLE_HTTP403);
				break;
			case '404':
				$this->view('http404', TITLE_HTTP404);
				break;
			case 'signup_confirmation':
				$this->view('signup_confirmation', TITLE_SIGNUP_CONFIRMATION);
				break;
			case 'recover_sent':
				$this->view('recover_sent', TITLE_EMAIL_CONFIRMATION);
				break;
		}
	}
}