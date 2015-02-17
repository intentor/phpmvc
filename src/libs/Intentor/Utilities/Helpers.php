<?php

//==PUBLIC VARIABLES==
//Current language.
$phpmvc_lang = null;

//==ARRAYS==

/**
 * Implode an array with the key and value pair giving
 * a glue, a separator between pairs and the array
 * to implode.
 * @param string $glue The glue between key and value
 * @param string $separator Separator between pairs
 * @param array $array 	The array to implode
 * @return string The imploded array
 */
function array_implode($glue, $separator, $array) {
	if (!is_array($array)) return $array;
	
	$string = array();
	foreach ($array as $key => $val) {
		if (is_array($val) ) {
			$val = implode(',', $val);
		}
		$string[] = "{$key}{$glue}{$val}";
	}
	
	return implode($separator, $string);	 
}

/**
 * Creates an array from an objet with public properties.
 * @param object $from Object from which the data will be read.
 * @return array
 */
function array_create($from) {
	$a = array();
	foreach ($from as $key => $value) {
		//Do not insert array objects.
		if (is_array($value)) continue;
		
		$r = new \ReflectionAnnotatedProperty($from, $key);
		if (!$r->hasAnnotation('IgnoreOnArrayCreate')) {
			$a[$key] = $value;
		}
	}

	return $a;
}

/**
 * Gets a list that can be used on dropdowns.
 * @param object $arr Array from which the data will be extracted.
 * @param string $value_field Value field.
 * @param string $text_field Text field.
 * @return array
 */
function get_list($arr, $value_field, $text_field) {
	$list = array(); $i = 0;
	foreach ($arr as $obj) {
		$list[$i++] = array($obj[$value_field] => $obj[$text_field]);
	}

	return $list;
}

//==DATE/TIME==
/**
 * Formate date to MySql
 * @param string[data]
 * @return string[data]
 */
function date_to_mysql($data){
	$year = substr($data,6,4);
	$month = substr($data,3,2);
	$day = substr($data,0,2);
	return $year."-".$month."-".$day;
}

/**
 * Formate date to brazilian mode
 * @param string[data]
 * @return string[data]
 */
function date_to_brazilian($data){
	$year = substr($data,0,4);
	$month = substr($data,5,2);
	$day = substr($data,8,2);
	return $day."/".$month."/".$year;
}

/**
 * Gets the current date and time.
 * @returns date
 */
function cur_date() {
	return date('Y-m-d H:i:s');
}

/**
 * Gets a date string.
 * @param date $date Date to be converted.
 * @return string
 */
function to_date($date) {
	$d = new DateTime($date);
	return $d->format("d/m/Y");
}

/**
 * Gets a date/time string.
 * @param date $date Date to be converted.
 * @return string
 */
function to_date_time($date) {
	$d = new DateTime($date);
	return $d->format("d/m/Y H:i");
}

/**
 * Gets a date/time string in UTC format.
 * @param date $date Date to be converted.
 * @return string
 */
function to_date_time_utc($date) {
	$d = new DateTime($date);
	return $d->format("Y-m-d\TH:i:s\Z");
}

/**
 * Creates a mask for the string that was sent
 * @example mask("12345678911","###.###.###-##")
 * @param string $value string receiving the mascara.
 * @param string $mask string mask that is applied.
 * @return string
 */
function mask($value, $mask) {
	$maskared = '';
	$k = 0;
	for($i = 0; $i <= strlen($mask)-1; $i++)
	{
		if($mask[$i] == '#')
		{
			if(isset($value[$k]))
				$maskared .= $value[$k++];
		}
		else
		{
			if(isset($mask[$i]))
				$maskared .= $mask[$i];
		}
	}
	return $maskared;
}

/**
 * Gets a timestamp from a date.
 * @param date $date Date to be converted.
 * @return string
 */
function to_timestamp($date) {
	$d = new DateTime($date);
	return $d->getTimestamp();
}


/**
 * Tranforma Segundos em tempo formato 00:00:00
 * @param $seconds segundos
 * @return string 00:00:00
 * */
function seconds_to_hours($seconds) {	
	$hours = floor($seconds / 3600);
	$seconds -= $hours * 3600;
	$minutes = floor($seconds / 60);
	$seconds -= $minutes * 60;
	$hours = ($hours < 10)?'0'.$hours:$hours;
	$minutes = ($minutes < 10)?'0'.$minutes:$minutes;
	$seconds = ($seconds < 10)?'0'.$seconds:$seconds;
	
	return $hours.':'.$minutes.':'.$seconds;
}

//==TEXT==
/**
 * Limits the text size.
 * @prama String $string Text to be limited.
 * @prama Int $width Number of characters.
 * */
function limit_text($string, $width) {
	if (strlen($string) > $width)
		$string = substr($string, 0, $width - 3) . '...';

	return $string;
}

//==E-MAIL==

/**
 * Sends an e-mail.
 * @param string $to E-mail to which the message will be sent.
 * @param string $from E-mail from which the message will be sent.
 * @param string $subject Subject of the message.
 * @param string $body Message to be sent.
 * @param string $reply_to Indicates if the message needs to be replied to another e-mail.
 */
function send_mail($to, $from, $subject, $body, $reply_to = '') {
	$err = '';
	
	try {
		//first try sending mail using mail.
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		$headers .= 'From: Shopitos <'.$from.'>' . "\r\n";
		if (isset($reply_to)) $headers .= 'Reply-To: '.$reply_to . "\r\n";
		$headers .= 'X-Mailer: PHP/' . phpversion();
		
		if (!mail($to, $subject , $body, $headers)) {
			//If the mail can't be sent using the built in mail function, uses PHPMailer.
			$mail = new PHPMailer();
			
			//SMTP server data.
			$mail->IsSMTP();
			$mail->Host = SMTP_HOST;
			$mail->Port = SMTP_PORT;
			
			//Checks for debug.
			if (SMTP_DEBUG) $mail->SMTPDebug = 1;
			
			//Only enables authentication if there's a username and password.
			if (defined('SMTP_USER') && SMTP_USER != '' &&
				defined('SMTP_PASSWORD') && SMTP_PASSWORD != '') {
				$mail->SMTPAuth = true;
				$mail->Username = SMTP_USER;
				$mail->Password = SMTP_PASSWORD;
				if (defined('SMTP_SECURITY') && SMTP_SECURITY != '') {
					$mail->SMTPSecure = SMTP_SECURITY;
				}
			}
			
			//Message data.
			if (isset($reply_to)) $mail->AddReplyTo($reply_to, $reply_to);
			$mail->setFrom($from, $from);
			$mail->addAddress($to, $to);
			$mail->isHTML(true);
			$mail->Subject = $subject;
			$mail->Body = $body;
			$mail->AltBody = "To view the message, please use an HTML compatible email viewer.";
			
			//Sends the mail.
			if(!$mail->send()) {
				$err = $mail->ErrorInfo;
			}
		}
	} catch (Exception $e) {
		$err = $e->getMessage();
	}
	
	return $err;
}

//==HASHING==

/**
 * Creates a MD5 hash for password purposes.
 * @param string $p String to be hashed.
 * @return string
 */
function hash_password($p) {
	return hash('md5', $p);
}


//==STRING==

/**
 * Replaces the ending of a string.
 * @param string $string String to be analysed.
 * @param string $old Value to be replaced.
 * @param string $new Value to replace.
 * @return string
 */
function replace_end($string, $old, $new) {
	$old = preg_quote($old);
	return preg_replace("/$old$/", $new, $string);
}

/**
 * Checks if a given string starts with a certain value.
 * @param string $haystack String to be analysed.
 * @param string $needle Value to look for.
 * @return boolean
 */
function starts_with($haystack, $needle) {
	$needle = preg_quote($needle);
	return preg_match("/^$needle/", $haystack);
}

/**
 * Checks if a given string ends with a certain value.
 * @param string $haystack String to be analysed.
 * @param string $needle Value to look for.
 * @return boolean
 */
function ends_with($haystack, $needle) {
	$needle = preg_quote($needle);
	return preg_match("/$needle$/", $haystack);
}

//==PASSWORDS==

/**
 * Generates a random password from a set of possible characters.
 * Original code in http://www.laughing-buddha.net/php/password.
 * @param int $length Size of the password.
 * @param int $possible Set of possible characters.
 * @return string
 */
function generate_password($length = 8, $possible = "0123456789") {
	$password = "";
	$maxlength = strlen($possible);

	//Check for length overflow and truncate if necessary.
	if ($length > $maxlength) {
		$length = $maxlength;
	}

	//Counter for how many characters are in the password so far
	$i = 0;

	//Add random characters to $password until $length is reached.
	while ($i < $length) {
		//Pick a random character from the possible ones.
		$char = substr($possible, mt_rand(0, $maxlength-1), 1);

		//Checks if the character have already been used.
		if (!strstr($password, $char)) {
			$password .= $char;
			$i++;
		}
	}

	return $password;
}

//==URL==

/**
 * Resolves an URL, writing it on the current page. Always starts it from the initial path ('/').
 * @param string $url URL to be resolved.
 * @param string $lang Language code of the language used in the URL.
 */
function url($url = '', $lang = '') {
	echo(get_url($url, $lang));
}

/**
 * Resolves an URL. Always starts it from the initial path ('/').
 * @param string $url URL to be resolved.
 * @param string $lang Language code of the language used in the URL.
 * @return string
 */
function get_url($url = '', $lang = '') {
	if (starts_with($url, "http")) return $url;
	else return translate_uri($url, $lang);
}

/**
 * Function to convert an object in the session. in order to be used.
 * @param string $session_name Name of the session.
 * @return object
 * */
function get_object_from_session($session_name) {
	if(isset($_SESSION[$session_name])){
		$obj = $_SESSION[$session_name];
		
		if (!is_object ($obj) && gettype ($obj) == 'object') {
			return ($obj = unserialize (serialize ($obj)));
		} else { 
			return $obj;
		}
	}
}


/**
 * Writes the current page URL.
 * @param string $lang Language code of the language used in the URI.
 */
function cur_url($lang = '') {
	echo(get_cur_url($lang));
}

/**
 * Gets the current page URL.
 * @param string $lang Language code of the language used in the URI.
 * @return string
 */
function get_cur_url($lang = '') {
	return translate_uri($_SERVER['REQUEST_URI'], $lang);
}

/**
 * Gets the current page URL from root.
 * @param string $lang Language code of the language used in the URI.
 * @return string
 */
function get_cur_url_from_root($lang = '') {
	$uri = $_SERVER['REQUEST_URI'];
	
	return translate_uri($uri, $lang);
}

//==REFLECTION==

/**
 * Copies data from a list of objects to another list based on its properties.
 * @param string $toClassName Name of the class of the array item objects.
 * @param array $from Array from which the objects will be read.
 * @param array $to Array to which the objects will be copied.
 * @return array
 */
function copy_to_array($toClassName, $from, &$to = null) {
	if ($to == null) $to = array();
	
	$i = 0;
	foreach ($from as $obj) {
		$to[$i] = new $toClassName;
		copy_to($obj, $to[$i++]);
	}
	
	return $to;
}

/**
 * Copies data from one object to another based on its properties.
 * @param object $from Object from which the data will be read.
 * @param object $to Object to which the data will be copied.
 * @return object
 */
function copy_to($from, &$to) {
	foreach ($from as $key => $value) {
		if ($to instanceof ArrayAccess && $to->offsetExists($key)) {
			$to->offsetSet($key, $value);
		} else if (property_exists($to, $key)) {
			$to->$key = $value;
		}
	}
	
	return $to;
}

//==CHARSET==

/**
 * Converts from UTF-8 to ISO-8859-1.
 * @param string $s String to be converted.
 */
function to_iso($s) {
	return mb_convert_encoding($s, 'ISO-8859-1', 'UTF-8');
}

/**
 * Converts from ISO-8859-1 to UTF-8.
 * @param string $s String to be converted.
 */
function to_uft8($s) {
	return mb_convert_encoding($s, 'UTF-8', 'ISO-8859-1');
}

/**
 * Converts all strings in an array or object to ISO-8859-1.
 * @param object $obj
 */
function object_to_iso($obj) {
	if ($obj) {
		foreach ($obj as $key => $value) {
			$tvalue = gettype($value);
	
			if ($tvalue == 'string') {
				$obj->$key = to_iso($value);
			} else if ($tvalue == 'object') {
				$obj[$key] = object_to_iso($value);
			}
		}
	}

	return $obj;
}

//==OPERATIONAL SYSTEM==

/**
 * Gets the request operation system.
 * @param $userAgent User agent data (usually value of $_SERVER['HTTP_USER_AGENT']).
 */
function get_os($userAgent) {
	// Create list of operating systems with operating system name as array key
	$oses = array (
			'iPhone' => '(iPhone)',
			'iPad' => '(iPad)',
			'Android' => '(Android)',
			'Windows 3.11' => 'Win16',
			'Windows 95' => '(Windows 95)|(Win95)|(Windows_95)', // Use regular expressions as value to identify operating system
			'Windows 98' => '(Windows 98)|(Win98)',
			'Windows 2000' => '(Windows NT 5.0)|(Windows 2000)',
			'Windows XP' => '(Windows NT 5.1)|(Windows XP)',
			'Windows 2003' => '(Windows NT 5.2)',
			'Windows Vista' => '(Windows NT 6.0)|(Windows Vista)',
			'Windows 7' => '(Windows NT 6.1)|(Windows 7)',
			'Windows 8' => '(Windows NT 6.2)|(Windows 8)',
			'Windows NT 4.0' => '(Windows NT 4.0)|(WinNT4.0)|(WinNT)|(Windows NT)',
			'Windows ME' => 'Windows ME',
			'Open BSD'=>'OpenBSD',
			'Sun OS'=>'SunOS',
			'Linux'=>'(Linux)|(X11)',
			'Safari' => '(Safari)',
			'Macintosh'=>'(Mac_PowerPC)|(Macintosh)',
			'QNX'=>'QNX',
			'BeOS'=>'BeOS',
			'OS/2'=>'OS/2',
			'Search Bot'=>'(nuhk)|(Googlebot)|(Yammybot)|(Openbot)|(Slurp/cat)|(msnbot)|(ia_archiver)'
			);

	foreach($oses as $os=>$pattern){ 
		if(eregi($pattern, $userAgent)) { 
			return $os;
		}
	}
	return 'Unknown';
}

//==LANGUAGE==

/**
 * Gets the current language ISO code.
 * <p>It can be different from <c>user_lang</c> because it takes into account all the available languages to the application.</p>
 * @param $lang Language ISO code to be verified. If the code does not exist, DEFAULT_LANGUAGE will be used.
 * return Current ISO code.
 */
function cur_lang($lang = '') {
	global $phpmvc_lang;
	
	if (empty($lang) && $phpmvc_lang == null) {
		$phpmvc_lang = user_lang();
		if (!is_lang_supported($phpmvc_lang)) {
			$phpmvc_lang = format_lang(DEFAULT_LANGUAGE);
		}
	} else if (!empty($lang)) {
		$phpmvc_lang = format_lang($lang);
		if (!is_lang_supported($phpmvc_lang)) {
			$phpmvc_lang = format_lang(DEFAULT_LANGUAGE);
		}
	}	
	
	return $phpmvc_lang;
}

/**
 * Checks if a given value is a language.
 * <p>Languages use the format xx-XX, like in pt-BR.</p>
 * @param string $value Value to be analysed.
 * @return boolean
 */
function is_lang($value) {
	return (preg_match("/^([a-zA-Z]{2})(-[a-zA-Z]{2})?$/", $value) == 1);
}

/**
 * Checks if a certain value is a language.
 * @param $lang Language ISO code to be verified.
 * @return boolean
 */
function is_lang_supported($lang) {
	return (is_lang($lang) && file_exists('lang/'.$lang));
}

/** 
 * Formats a certain language code.
 * <p>If the $lang is invalid, DEFAULT_LANGUAGE will be used instead.</p>
 * @param $lang Language ISO code to be formated.
 * @return string
 */
function format_lang($lang) {
	if (USE_SHORT_ISO_CODE) {
		$lang = strtolower(substr($lang, 0, 2));
	} else if (strlen($lang) == 5) {
		$lang = strtolower(substr($lang, 0, 2)).'-'.strtoupper(substr($lang, 3, 2));
	} else {
		$lang = DEFAULT_LANGUAGE;
	}
	
	return $lang;
}

/**
 * Gets the current language ISO Code for the user.
 * return Current ISO code.
 */
function user_lang() {
	$res = DEFAULT_LANGUAGE;
	
	if (isset($_REQUEST['lang'])) {
		$res = $_REQUEST['lang'];
	} else if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
		$res = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 5);
	}

	return format_lang($res);
}

/**
 * Translates a URI.
 * <p>The language code is appended to the URI.</p>
 * @param string $uri URI to be translated.
 * @param string $lang Language code of the language used in the URI.
 * @return string
 */
function translate_uri($uri, $lang = '') {
	if (!strncmp($uri, DIRECTORY_ROOT, strlen(DIRECTORY_ROOT))) $uri = substr($uri, strlen(DIRECTORY_ROOT));
	
	//First, checks if it's a URI that requires translation.
	if (!preg_match("/\.[a-z]{2,4}$/i", $uri)) {
		if ($lang == '') $lang = cur_lang();
		else $lang = format_lang($lang);
		
		//Checks if the first part is a language.
		$position = strpos($uri, '/');
		if (($position > 0 && is_lang(substr($uri, 0, $position)))) {
			$uri = substr($uri, $position + 1);
		} else if (is_lang($uri)) {
			$uri = '';
		}
		
		$uri = $lang.'/'.$uri;
	}
	
	return URL_BASE.$uri;
}


function relative_to_url($text){
	return str_replace(array('../../../../', '../../../'), URL_BASE, $text);
}

function url_to_relative($text){
	return str_replace(URL_BASE, '../../../../', $text);
}