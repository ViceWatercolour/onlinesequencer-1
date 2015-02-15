<?php
require('../../inc/init.php');
$title = $_REQUEST['title'];
$basedon = $_REQUEST['basedon'];
$data = $_REQUEST['data'];

function save($title, $basedon, $data, $html = false) {
    global $settings;
    if($title == 'Untitled')
        $hash = md5($data);
    else
        $hash = md5($title.$data);
	$result = db_query('SELECT id FROM sequences WHERE hash="'.$hash.'"');
	if(mysqli_num_rows($result) == 1) {
		$id = db_result($result, 0);
		return '<a href="'.$settings['domain'].'/'.$id.'" target="_blank">'.$settings['domain'].'/'.$id.'</a> &middot; <a href="/app/midi.php?id='.$id.'">Export MIDI file</a>';
	}
	else {
		db_query('INSERT INTO sequences(date, title, basedon, data, datalength, hash) VALUES("'.time().'", "'.db_escape_string($title).'", "'.intval($basedon).'", "'.db_escape_string($data).'", "'.strlen($data).'", "'.$hash.'")');
		return '<a href="'.$settings['domain'].'/'.db_insert_id().'" target="_blank">'.$settings['domain'].'/'.db_insert_id().'</a> &middot; <a href="/app/midi.php?id='.db_insert_id().'">Export MIDI file</a>';
	}
}

header('Content-type: text/plain');
echo save($title, $basedon, $data);
?>