<?php
require_once dirname(__FILE__) . '/Intentor/Intentor.php';

foreach (glob(dirname(__FILE__).'/Utils/*.php') as $filename) {
	include $filename;
}