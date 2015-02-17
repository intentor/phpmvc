<?php
class AccountIndexModel  {
	public $user_id;
	/**
	 * @Display(label = LABEL_USER_EMAIL)
	 * @Required(VAL_REQUIRED)
	 * @Regex(pattern = REGEX_EMAIL, message = EMAIL_INVALID)
	 * @StringLength(255)
	 */
	public $user_email;
	/**
	 * @Display(label = LABEL_USER_PASSWORD)
	 * @Required(VAL_REQUIRED)
	 * @StringLength(8)
	 */
	public $user_password;
	/**
	 * @Display(label = LABEL_USER_PASSWORD_CONFIRM)
	 * @StringLength(8)
	 */
	public $user_password_confirm;
	/**
	 * @Display(label = LABEL_USER_NAME)
	 * @Required(VAL_REQUIRED)
	 * @StringLength(255)
	 */
	public $user_name;
}

class AccountPasswordModel extends AccountIndexModel {
}