<?php
class SignupController extends Intentor\PhpMVC\Controller {
	/**
	 * /signup/index
	 */
	function index($param) {
		if (!isset($_SESSION['terms_accepted'])) {
			$this->redirect('signup/terms/');
		}
				
		if ($this->is_post()) {
			//First verifies if the user already exists based on its e-mail.
			$user_db = $this->db->orm->sp_user->where('user_email', $this->model->user_email)->fetch();
			if ($user_db)
				$this->add_message_error(MESSAGE_USER_EXISTS);
			else{
				//Checks if the passwords are confirmed.
				if ($this->model->user_password != $this->model->user_password_confirm) {
					$this->add_message_error(MESSAGE_CONFIRMATION_ERROR);
				} else {
					//Creates the action key for access confirmation.
					$action_key = hash_password($this->model->user_email.$this->model->user_password.cur_date());
				
					$user_document = preg_replace('/[^\d]/', '', $this->model->user_document);
					
					//Creates the user.
					$insert = array(
						'user_email' => $this->model->user_email,
						'user_password' => hash_password($this->model->user_password),
						'user_name' => $this->model->user_name,
						'user_document' => $user_document,
						'user_address_line1' => $this->model->user_address_line1,
						'user_address_line2' => $this->model->user_address_line2,
						'user_city' => $this->model->user_city,
						'user_district' => $this->model->user_district,
						'user_state' => $this->model->user_state,
						'user_country' => $this->model->user_country,
						'user_zip' => $this->model->user_zip,
						'user_phone' => $this->model->user_phone,
						'user_cellphone' => $this->model->user_cellphone,
						'date_creation' => cur_date(),
						'user_action_key' => $action_key
					);
					
					try {
						$user_db = $this->db->orm->sp_user->insert($insert);
					
						$this->send_mail_signup($this->model->user_email, $this->model->user_name, $action_key);
						
						//On signup, every user is not an admin user.
						LoginHelper::login($user_db['user_id'], $user_db['user_email'], false);
	
						$this->view('success', TITLE_SUCCESS);
					} catch (PDOException $e) {
						if ($e->getCode() == 23000) {
							$user_db = $this->db->orm->sp_user('user_document', $user_document)->fetch();
							
							if ($user_db) {
								$this->add_message_error(sprintf(MESSAGE_DOCUMENT_EXISTS, get_url('login/recover')));
							}
						} else {
							throw $e;
						}
					}
				}
			}
		} else {
			//verifica se o usuario esta tentando se conectar via facebook;
			$facebook_token = sizeof($param) ? $param[0] : null;
			$data =  $facebook_token ? json_decode(file_get_contents('https://graph.facebook.com/me?access_token='.$facebook_token)) : null;

			if ($data){
				$this->model->name 			= $data->name;
				$this->model->email		 	= $data->email;
				$this->model->facebook_id 	= $data->id;
			}
		}
		
		$this->view('index', TITLE_INDEX);
	}
	
	/**
	 * /signup/terms
	 */
	function terms() {
		if ($this->is_post()) {
			$_SESSION['terms_accepted'] = true;
			$this->redirect('signup/');
		} else {
			unset($_SESSION['terms_accepted']);
			$this->view('terms', TITLE_TERMS);
		}
	}
	
	/**
	 * /signup/success
	 */
	function success() {
		$this->view('success', TITLE_SUCCESS);
	}

	/**
	 * /signup/confirm/action_key
	 */
	function confirm($param) {
		if (sizeof($param) == 1) {
			//Removes the action key from the user and logs it.
			$user_db = $this->db->orm->sp_user()
				->select('user_id, user_email, user_is_admin, user_action_key')
				->where('user_action_key', $param[0])
				->fetch();
			
			if ($user_db) {
				//Removes the action key and do user login.
				$user_db['user_action_key'] = '';
				$user_db->update();
				LoginHelper::login($user_db['user_id'], $user_db['user_email'], ($user_db['user_is_admin'] == 1));
				
				$this->view('confirmed', TITLE_CONFIRMED);
			} else {
				$this->redirect('err/signup_confirmation/', TITLE_CONFIRM_ERROR);
			}
		} else {
			$this->redirect('err/http403/');
		}
	}
	 
	//==SUPPORT==
	
	/**
	 * Sends e-mail of sign up.
	 * @param string $user_email User's e-mail.
	 * @param string $user_name User's name.
	 * @param string $action_key Action key for access confirmation.
	 */
	private function send_mail_signup($user_email, $user_name, $action_key) {	
		$message = file_get_contents(URL_BASE.'lang/'.cur_lang().'/mail/user_created.htm');
		$message = str_replace('[USER_NAME]', $user_name, $message);
		$message = str_replace('[LINK_CONFIRMATION]', URL_BASE.cur_lang().'/signup/confirm/'.$action_key, $message);
		$message = str_replace('[URL_BASE]', URL_BASE, $message);
	
		send_mail($user_email, EMAIL_FROM, SUBJECT_EMAIL_SIGNUP, $message);
	}
}