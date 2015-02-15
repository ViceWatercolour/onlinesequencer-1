<?php
function l($page, $params) {
	global $pages;
	$url = '/'.$page;
	
	$required_params = $pages[$page]['params'];
	foreach($required_params as $k=>$v)
	{
		if(array_key_exists($k, $params))
		{
			$url .= '/'.urlencode($params[$k]);
		}
		else
		{
			error('Missing link parameter: '.$k);
		}
	}
	
	$qs = array();
	foreach($params as $k => $v)
	{
		if(!array_key_exists($k, $required_params))
		{
			$qs[$k] = $v;
		}
	}
	if(count($qs) > 0)
		$url .= '?'.http_build_query($qs);
		
	return $url;
}
function self($params) {
	global $pages, $page_name;
	$url = '/'.$page_name;
	
	$required_params = $pages[$page_name]['params'];
	foreach($required_params as $k=>$v)
	{
		if(array_key_exists($k, $params))
			$url .= '/'.urlencode($params[$k]);
		else
			$url .= '/'.urlencode($GLOBALS[$k]);
	}
	
	$qs = array();
	foreach($_GET as $k => $v)
	{
		if(should_persist_param($k))
			$qs[$k] = $v;
	}
	foreach($params as $k => $v)
	{
		if(!array_key_exists($k, $required_params))
		{
			$qs[$k] = $v;
		}
	}
	if(count($qs) > 0)
		$url .= '?'.http_build_query($qs);
		
	return $url;
}
?>