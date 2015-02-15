<?php
$GLOBALS['forms'] = array();
function form_validate() {
	global $mybb;
	$t = form_token();
	if($t != $_POST['_t'])
		error_page('Sorry, the form you submitted was invalid. Please try again.');
}

function form_token() {
    if(isset($mybb))
        return md5($mybb->user['loginkey'].$mybb->user['lastvisit']);
    else
        return 0;
}

function form_create($submit, $style, $fname, $method) {
	global $forms, $form_id;
	$forms[$form_id] = array();
	$forms[$form_id]['style'] = $style;
	$forms[$form_id]['params'] = array();
	$forms[$form_id]['action'] = $fname;
	$forms[$form_id]['submit'] = $submit;
	$forms[$form_id]['errors'] = array();
	$forms[$form_id]['method'] = $method;
	$forms[$form_id]['fileupload'] = false;
	return $form_id;
}
function form_add_param($id, $fname, $name, $type, $default, $maxlength, $size) {
	global $forms;
	if(stristr($type, 'file'))
		$forms[$id]['fileupload'] = true;
	$forms[$id]['params'][$name] = array(
		'fname' => $fname,
		'type' => $type,
		'default' => $default,
		'value' => $default,
		'maxlength' => $maxlength,
		'size' => $size
	);
}
function form_add_error($f, $e) {
	global $forms;
	$forms[$f]['errors'][] = $e;
}
function form_display($f) {
	global $forms;
	$form = $forms[$f];
	$params = array();
	if($form['method'] == 'get')
	{
		foreach($_GET as $k=>$v)
		{
			if(should_persist_param($k))
				$params[$k] = urlencode($v);
		}
	}
	output_form_start($f, $form['action'] != null, $form['method'] == 'post', $form['errors'], $form['method'], $form['fileupload'], $params, $form['style']);
	foreach($form['params'] as $k=>$v)
	{
		if(isset($_REQUEST[$k]))
			$v['value'] = htmlspecialchars($_REQUEST[$k]);
		output_form_field($k, $v, $form['style']);
	}
	output_form_end($form['submit'], $form['style']);
}

function form_process() {
	global $forms;
	if(!isset($_REQUEST['form_id']))
		return;
	$f = $forms[$_REQUEST['form_id']];
	$valid = true;
	foreach($f['params'] as $k => $v)
	{
		$t = explode(';', $v['type']);
		$value = $t[0] == 'file' ? $_FILES[$k] : $_REQUEST[$k];
		$result = validate($value, $v['type'], $k);
		if($result === true)
		{
			$GLOBALS[$k] = $value;
		}
		else
		{
			form_add_error($_REQUEST['form_id'], $result);
			$valid = false;
		}
	}
	if($f['method'] == 'post')
		form_validate();
	if($valid && $f['action'])
		$f['action']();
}

?>