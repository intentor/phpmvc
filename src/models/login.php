<?php	
class LoginIndexModel  {	
	/**
	 * @Display(label = LABEL_LOGIN_EMAIL)
	 * @Required(VAL_REQUIRED)
	 * @Regex(pattern = REGEX_EMAIL, message = EMAIL_INVALID)
	 * @StringLength(255)
	 */
	public $login_email;
	/**
	 * @Display(label = LABEL_LOGIN_PASSWORD)
	 * @Required(VAL_REQUIRED)
	 * @StringLength(8)
	 */
	public $login_password;
}

class loginRecoverModel {
	/**
	 * @Display(label = LABEL_LOGIN_EMAIL)
	 * @Required(VAL_REQUIRED)
	 * @Regex(pattern = REGEX_EMAIL, message = EMAIL_INVALID)
	 * @StringLength(255)
	 */
	public $user_email;
	
	/**
	 * @Display(label = LABEL_LOGIN_PASSWORD)
	 * @Required(VAL_REQUIRED)
	 * @StringLength(8)
	 */
	public $login_password;
}

class LoginRecoverSentModel {
	/**
	 * @Display(label = LABEL_LOGIN_EMAIL)
	 * @Required(VAL_REQUIRED)
	 * @Regex(pattern = REGEX_EMAIL, message = EMAIL_INVALID)
	 * @StringLength(255)
	 */
	public $user_email;
}

class LoginConfirmModel {
	/**
	 * @Display(label = LABEL_USER_PASSWORD)
	 * @Required(VAL_REQUIRED)
	 * @StringLength(8)
	 */
	public $user_password;
	/**
	 * @Display(label = LABEL_USER_PASSWORD_CONFIRM)
	 * @Required(VAL_REQUIRED)
	 * @Compare(to = 'user_password', message = COMPARE_PASSWORD_INVALID)
	 * @StringLength(8)
	 */
	public $user_password_confirm;
}