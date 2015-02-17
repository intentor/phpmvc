<?php
class AccountController extends Intentor\PhpMVC\Controller {
	/**
	 * /account/index
	 * @Authorize
	 */
	function index($params) {
		$user_db = $this->db->orm->sp_user [Auth::user_id ()];
		
		if ($this->is_post()) {
			// Updates the user.
			$user_db ['user_email'] = $this->model->user_email;
			$user_db ['user_name'] = $this->model->user_name;
			$user_db ['user_document'] = $this->model->user_document;
			$user_db ['user_address_line1'] = $this->model->user_address_line1;
			$user_db ['user_address_line2'] = $this->model->user_address_line2;
			$user_db ['user_city'] = $this->model->user_city;
			$user_db ['user_district'] = $this->model->user_district;
			$user_db ['user_state'] = $this->model->user_state;
			$user_db ['user_country'] = $this->model->user_country;
			$user_db ['user_zip'] = $this->model->user_zip;
			$user_db ['user_phone'] = $this->model->user_phone;
			$user_db ['user_cellphone'] = $this->model->user_cellphone;
			
			$user_db->update();
			
			$this->redirect('account/');
		} else {
			copy_to ($user_db, $this->model);
		}
		
		$this->model->user_password = '';
		$this->view('index', TITLE_ACCOUNT_CHANGE_DATA);
	}
	
	/**
	 * /account/password
	 * @Authorize
	 */
	function password() {
		$user_db = $this->db->orm->sp_user [Auth::user_id ()];
		
		if ($this->is_post ()) {
			if ($this->model->user_password != $this->model->user_password_confirm) {
				$this->add_message_error(MESSAGE_CONFIRMATION_ERROR);
			} else {
				$user_db ['user_password'] = hash_password ( $this->model->user_password );
				$user_db->update ();
				
				$user_stores = $this->db->orm->sp_store('user_id', $user_db['user_id']);
				
				foreach ($user_stores as $store) {
					$this->db->orm->oc_user('store_id', $store['store_id'])->update(array(
						'`password`' => $user_db['user_password']
					));
				}
			}
		}
		
		$this->redirect ( 'account/index/' );
	}
}