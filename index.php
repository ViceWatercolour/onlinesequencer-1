<?php
define("NOT_IN_FORUM", 1);
include("inc/init.php");
include($local.'/pages.php');
mod("page");
include($templates_path.'/'.$settings['template'].'/main.php');

$form_id = 0;

if(!isset($_GET["x"]))
	$parts = array('view', '0');
else
	$parts = explode("/", $_GET['x']);
$page_name = $parts[0];
if(empty($page_name) || preg_match("/\W/", $page_name))
	error_page("No page was specified.");
else {
	$params = $pages[$page_name]['params'];
	if(count($parts) - 1 < count($params))
		error_page('Incorrect URL specified. Did you follow a broken link?');
	$i = 0;
	foreach($params as $param=>$value) {
		$p = urldecode($parts[$i+1]);
		if(validate($p, $value))
			$GLOBALS[$param] = $p;
		else
			error_page('Incorrect URL specified. Did you follow a broken link?');
		$i++;
	}
	
	$filename = $local.'/pages/'.$page_name.'.php';
	if(file_exists($filename)) {
		include($filename);
	}
	else {
		error_page("The page ".$page_name." does not exist.");
	}
}
?>
