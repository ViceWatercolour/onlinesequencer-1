<?php
set_time_limit(0);

require('inc/init.php');
$result = mysql_query('SELECT * FROM sequences');
while($row = mysql_fetch_array($result)) {
$data = explode(':', $row['data']);
if(count($data) != 2) {
    echo $row['id']."\n";
    continue;
}
$tempo = $data[0];
$notedata = $data[1];
$notes = explode(';', $notedata);

$scaleFrequency = array();
for($i = 0; $i < count($settings['scales']); $i++) {
	$scaleFrequency[$i] = 0;
}
$totalNotes = 0;
foreach($notes as $note) {
	if($note == '')
		continue;
	$note = explode(' ', $note);
	$time = $note[0];
	$type = $note[1];
	$instrument = count($note) == 3 ? $note[2] : 1;	
	$type = substr($type, 0, strlen($type)-1);
	if($instrument != 2) {
		$totalNotes++;
		for($j = 0; $j < count($settings['scales']); $j++) {
			if(array_search($type, $settings['scales'][$j]) > -1) {
				$scaleFrequency[$j]++;
			}
		}
	}
}
if($totalNotes == 0)
    continue;
$maxScale = 0;
$maxFrequency = 0;
for($i = 0; $i < count($settings['scales']); $i++) {
	if($scaleFrequency[$i] > $maxFrequency) {
		$maxFrequency = $scaleFrequency[$i];
		$maxScale = $i;
	}
}
$f = $maxFrequency/$totalNotes;
if($f >= 0.9) {
    $scale = $settings['scales'][$maxScale];
    db_query('UPDATE sequences SET scale="'.$maxScale.'" WHERE id="'.$row['id'].'"');
}
}
?>