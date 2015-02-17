<?php
/**
 * Authentication Manager.
 */
class Auth {
	/** Key for user ID. */
	const KEY_USER_ID = 'uid';
	/** Key for username. */
	const KEY_USER_NAME = 'uname';
	/** Key for username. */
	const KEY_PERMISSIONS = 'udata';
	
	/**
	 * Checks if there's an authenticated user.
	 * @return boolean
	 */
	public static function is_auth() {
		return isset($_SESSION[self::KEY_USER_ID]);
	}
	
	/**
	 * Check's if there was a user connected before (related to "remember me" during login).
	 */
	public static function check_login() {
		if (!self::is_auth() && isset($_COOKIE[self::KEY_USER_ID])) {
			$_SESSION[self::KEY_USER_ID] = $_COOKIE[self::KEY_USER_ID];
			$_SESSION[self::KEY_USER_NAME] = $_COOKIE[self::KEY_USER_NAME];
			if (isset($_COOKIE[self::KEY_PERMISSIONS])) $_SESSION[self::KEY_PERMISSIONS] = $_COOKIE[self::KEY_PERMISSIONS];
		}
	}
	
	/**
	 * Logs in a user.
	 * @param int $user_id User ID.
	 * @param string $user_name Name of the user.
	 * @param boolean $remember Indicates if the login needs to be remembered.
	 * @param array $permissions IDs of every permission the user has.
	 */
	public static function login($user_id, $user_name, $remember, $permissions = null) {
		$_SESSION[self::KEY_USER_ID] = $user_id;
		$_SESSION[self::KEY_USER_NAME] = $user_name;
		if ($permissions != null) self::permissions($permissions);
	
		if ($remember) {
			setcookie(self::KEY_USER_ID, $_SESSION[self::KEY_USER_ID], time()+60*60*24*100, '/');
			setcookie(self::KEY_USER_NAME, $_SESSION[self::KEY_USER_NAME], time()+60*60*24*100, '/');
			if (isset($_SESSION[self::KEY_PERMISSIONS])) setcookie(self::KEY_PERMISSIONS, $_SESSION[self::KEY_PERMISSIONS], time()+60*60*24*100, '/');
		}
	}
	
	/**
	 * Logs out the current user.
	 */
	public static function logout() {
		unset($_SESSION[self::KEY_USER_ID]);
		unset($_SESSION[self::KEY_USER_NAME]);
		unset($_SESSION[self::KEY_PERMISSIONS]);
		setcookie(self::KEY_USER_ID, '', time()-60*60*24*100, '/');
		setcookie(self::KEY_USER_NAME, '', time()-60*60*24*100, '/');
		setcookie(self::KEY_PERMISSIONS, '', time()-60*60*24*100, '/');
		session_destroy();
	}
	
	/**
	 * Gets the current user's ID.
	 * @return int
	 */
	public static function user_id() {
		if (self::is_auth()) {
			return $_SESSION[self::KEY_USER_ID];
		} else {
			return -1;
		}
	}
	
	/**
	 * Gets the current user's name.
	 * @return string
	 */
	public static function username() {
		if (self::is_auth()) {
			return $_SESSION[self::KEY_USER_NAME];
		} else {
			return '';
		}
	}
	
	/**
	 * Gets or sets the current user's permissions.
	 * @param array $permissions Permissions list.
	 * @return array
	 */
	public static function permissions($permissions = null) {
		if (self::is_auth()) {
			if ($permissions != null) {
				$_SESSION[self::KEY_PERMISSIONS] = implode(',', $permissions);
				setcookie(self::KEY_PERMISSIONS, $_SESSION[self::KEY_PERMISSIONS], time()+60*60*24*100, '/');
			}
			
			if (isset($_SESSION[self::KEY_PERMISSIONS])) {
				return explode(',', $_SESSION[self::KEY_PERMISSIONS]);
			} else {
				return array();
			}
		} else {
			return array();
		}
	}
	
	/**
	 * Checks if the current user has a certain permission.
	 * @param $perm ID of the permission.
	 * @return boolean
	 */
	public static function has_permission($perm) {
		if (self::is_auth()) {
			$permissions = self::permissions();		
			if(gettype($perm) == "array") {
				return (count(array_intersect($perm, $permissions)) > 0);
			} else {					
				return in_array($perm, $permissions);
			}
		} else {
			return false;
		}
	}
}