<?php
class HomeController extends Intentor\PhpMVC\Controller {
	/**
	 * /home/index
	 */
	function index() {
		$this->model->user_name = 'test';
		
		$this->view('index', TITLE_INDEX);
	}
}