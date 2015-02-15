<?php
function should_persist_param($param) {
	return $param != 'form_id' && $param != 'x' && !stristr($param, 'start_') && !stristr($param, 'stop_');
}
function validate($param, $type_string, $key='') {
	$typearr = explode(';', $type_string);
	$type = $typearr[0];
	switch($type) {
		case 'int':
			if(!is_numeric($param))
				return $key.' must be a number';
			return true;
		break;
		case 'textarea':
			$valid = !empty($param);
			if(empty($param))
				return $key.' must not be blank.';
			return true;
		break;
		case 'string':
			$valid = !empty($param);
			if(empty($param))
				return $key.' must not be blank.';
			return true;
		break;
		case 'enum':
			$values = explode(',', $typearr[1]);
			return in_array($param, $values);
		break;
		case 'file':
			$file = $param;
			if($file['error'] == 4)
				return 'A file is required.';
			$name = $file['name'];
			$valid_ext = array();
			$filetype = $typearr[1];
			switch($filetype) {
				case 'image':
					$valid_ext = array('gif', 'jpg');
				break;
				default: 
					$valid_ext = array($filetype);
				break;
			}
			$ext = strtolower(substr($name, strrpos($name, '.')+1));
			if(!in_array($ext, $valid_ext))
				return 'The file extension '.$ext.' is not allowed.';
			if(filesize($file['tmp_name']) > $typearr[2])
				return 'File size must be less than '.$typearr[2].'B';
			return true;
		break;
	}
}
function p_int($name, $default=false) {
	if(isset($_GET[$name]) && is_numeric($_GET[$name]))
		return $_GET[$name];
	else
		return $default;
}
function p_string($name, $default=false) {
	if(isset($_GET[$name]))
		return $_GET[$name];
	else
		return $default;
}
function p_bool($name) {
	return isset($_GET[$name]);
}
function e_mysql($s) {
	global $link;
	return mysqli_real_escape_string($link, $s);
}
function e_html($s) {
	return htmlspecialchars($s);
}
function e_url($s) {
	return urlencode($s);
}

?>