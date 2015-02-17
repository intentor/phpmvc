<?php
class LoginController extends Intentor\PhpMVC\Controller {
	/**
	 * /login/index
	 */
	function index() {
		//If the user is authenticated, redirect him/her to the members area.
		if (Auth::is_auth()) {
			$this->redirect("members/");
		}
	 	
		if ($this->is_post()) {
			$user_db = $this->db->orm->sp_user()
				->select('user_id, user_email, user_action_key, user_is_admin')
				->where('user_email = ? AND user_password = ?', strtolower($this->model->login_email), hash_password($this->model->login_password))
				->fetch();
			
			//If the user exists, execute its login.
			if ($user_db) {
				LoginHelper::login($user_db['user_id'], $user_db['user_email'], ($user_db['user_is_admin'] == 1));					
				$this->redirect('mystore/');							
			} else {
				$this->add_message_error(MESSAGE_LOGIN_FAIL);
			}
		}
		
		$this->view('index', TITLE_INDEX);
	}
	
	/**
	 * /login/recover
	 */
	function recover() {
		if ($this->is_post()) {
			//Checks if there's an user for the provided e-mail.
			$user_db = $this->db->orm->sp_user()
				->select('user_id, user_email, user_name, user_action_key')
				->where('user_email', $this->model->user_email)
				->fetch();			
			if ($user_db) {
				//Creates the action key for password recovery.
				$action_key = hash_password($this->model->user_email.cur_date());

				$user_db['user_action_key'] = $action_key;
				$user_db->update();
				
				//Sends the recovery e-mail to the user.
				$this->send_mail_recover($user_db['user_email'], $user_db['user_name'], $action_key);
				
				$this->view('recover_sent', TITLE_RECOVER);
			} else {
				$this->add_message_error(MESSAGE_EMAIL_DONT_EXIST);
				$this->view('recover_index', TITLE_RECOVER);
			}
		} else {
			$this->view('recover_index', TITLE_RECOVER);
		}		
	}
	
	/**
	 * /login/confirm
	 */
	function confirm($param) {
		if (sizeof($param) == 1) {
			//Removes the action key and asks the user to change his/her password.
			$user_db = $this->db->orm->sp_user()
				->select('user_id, user_email, user_is_admin, user_action_key')
				->where('user_action_key', $param[0])
				->fetch();
			if ($user_db) {
				if ($this->is_post()) {
					if ($this->model->user_password != $this->model->user_password_confirm) {
						$this->add_message_error(MESSAGE_CONFIRMATION_ERROR);
					} else {
						$user_db['user_password'] = hash_password($this->model->user_password);
						$user_db['user_action_key'] = NULL;
						$user_db->update();
						
						$user_stores = $this->db->orm->sp_store('user_id', $user_db['user_id']);
						
						foreach ($user_stores as $store) {
							$this->db->orm->oc_user('store_id', $store['store_id'])->update(array(
									'`password`' => $user_db['user_password']
							));
						}
						
						LoginHelper::login($user_db['user_id'], $user_db['user_email'], ($user_db['user_is_admin'] == 1));
						
						$this->redirect('mystore/');
					}
				}
				
				$this->view('recover_confirm', TITLE_RECOVER);
			} else {
				$this->redirect('err/recover_sent/', TITLE_EMAIL_CONFIRMATION);
			}
		} else {
			$this->redirect('err/http403/');
		}
	}
	
	/**
	 * /login/out
	 */
	function out() {
		Auth::logout();
		$this->redirect('home/');
	}
	 
	//==SUPPORT==
	
	/**
	 * Sends e-mail of password recovery
	 * @param string $user_email User's e-mail.
	 * @param string $user_name User's name.
	 * @param string $action_key Action key for access confirmation.
	 */
	private function send_mail_recover($user_email, $user_name, $action_key) {	
		$message = file_get_contents(URL_BASE.'lang/'.cur_lang().'/mail/user_recover.htm');
		$message = str_replace('[USER_NAME]', $user_name, $message);
		$message = str_replace('[LINK_CONFIRMATION]', URL_BASE.cur_lang().'/login/confirm/'.$action_key, $message);
		$message = str_replace('[URL_BASE]', URL_BASE, $message);
	
		send_mail($user_email, EMAIL_FROM, SUBJECT_EMAIL_RECOVER, $message);
	}
}