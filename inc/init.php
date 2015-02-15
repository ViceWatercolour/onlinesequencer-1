<?php
define('IN_SITE', 1);
define('TEST', !isset($_SERVER['SERVER_NAME']) || $_SERVER['SERVER_NAME'] != 'onlinesequencer.net');
$GLOBALS['root'] = dirname(dirname(__FILE__));
$GLOBALS['local'] = $GLOBALS['root'] .'/local';
$GLOBALS['inc'] = $GLOBALS['root'] .'/inc';
$GLOBALS['templates_path'] = $GLOBALS['local'] .'/templates';
require($GLOBALS['local'] .'/settings.php');
if(TEST)
    error_reporting(E_ALL);
else
    error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);

chdir($GLOBALS['root']);
$link = mysqli_connect($settings['mysql_server'], $settings['mysql_user'], $settings['mysql_pass']);
mysqli_select_db($link, $settings['mysql_db']);

function mod($name) {
	global $inc, $root, $local, $templates_path, $settings, $userid;
	require_once($inc.'/functions.'.$name.'.php');
}

require($GLOBALS['inc']."/functions.core.php");
mod("input");
mod("error");
mod("database");
mod("data");
mod("js");
mod('sequencer');
date_default_timezone_set('America/New_York');
if(!TEST)
    start_buffer();
?>