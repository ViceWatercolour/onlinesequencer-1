<?php
function preview($id, $title="Untitled") {
	$filename = 't/'.$id.'.gif';
	if(!file_exists($filename)) {
		$piano = array("C3", "C#3", "D3", "D#3", "E3", "F3", "F#3", "G3", "G#3", "A3", "A#3", "B3", "C4", "C#4", "D4", "D#4", "E4", "F4", "F#4", "G4", "G#4", "A4", "A#4", "B4", "C5", "C#5", "D5", "D#5", "E5", "F5", "F#5", "G5", "G#5", "A5", "A#5", "B5");
        
		$im = imagecreatetruecolor(64, 64);
		
        $orange = imagecolorallocate($im, 255, 165, 0);
		$white = imagecolorallocate($im, 255, 255, 255);
		$black = imagecolorallocate($im, 0, 0, 0);
		
        $row = mysqli_fetch_array(db_query('SELECT title, data, date FROM sequences WHERE id="'.$id.'" LIMIT 1'));
		$data = $row['data'];
		$data = explode(':', $data);
		$data = $data[1];
		$parts = explode(";", $data);
		$notes = 0;
		
        foreach($parts as $p) {
            if(empty($p))
                continue;
			$arr = explode(' ', $p);
            if(!isset($arr[3]))
                $arr[3] = 0;
			$note = $arr[1];
			$time = $arr[0];
			$x = $time;
			$y = (count($piano) - array_search($note, $piano))*2;
			if($x < 64 && $y < 64) {
				imagefilledrectangle($im, $x, $y, $x+1, $y+1, $orange);
			}
			$notes++;
		}
		$date = @date("m/d/y", $row['date']);
		imagettftext($im, 6, 0, 5, 50, $black, "resources/slkscre.ttf", $date."\n".$notes.' notes');
		imagettftext($im, 6, 0, 4, 49, $white, "resources/slkscre.ttf", $date."\n".$notes.' notes');
		imagegif($im, $filename);
	}
    return '<img class="preview" src="'.$filename.'" alt="'.$id.'" title="'.$title.'" width="64" height="64" />';
}

function randomSequences($count) {
    $maxID = db_result(db_query('SELECT id FROM sequences ORDER by id DESC LIMIT 1'), 0);
    $sql = 'id IN(';
    for($i = 0; $i < $count; $i++) {
        $sql .= rand(6, $maxID);
        if($i != $count - 1)
            $sql .= ',';
    }
    $sql .= ')';
    return $sql;
}

function isAdmin() {
    global $settings;
    return isset($_COOKIE['adminpass']) && $_COOKIE['adminpass'] == $settings['adminpass'];
}

function formatSequenceTitle($row) {
    if($row['title'] != 'Untitled')
        return e_html($row['title']).' (#'.$row['id'].')';
    else
        return 'Sequence #'.$row['id'];
}

function formatSequenceInfo($row) {
	$date = date('Y-m-d', $row['date']);
    if($row['basedon'] != 0)
        $basedon = ', based on <a href="/'.$row['basedon'].'" onclick="return onSequenceLinkClick(event, '.$row['id'].')">#'.$row['basedon'].'</a>';
    else
        $basedon = '';
    $result = db_query('SELECT id FROM sequences WHERE basedon="'.$row['id'].'" ORDER BY date DESC');
    $count = mysqli_num_rows($result);
    if($count > 0) {
        $inspired = ', inspired ';
        $i = 0;
        while(($row2 = mysqli_fetch_array($result)) && $i < 3) {
            $inspired .= '<a href="/'.$row2['id'].'" onclick="return onSequenceLinkClick(event, '.$row2['id'].')">#'.$row2['id'].'</a>';
            if($i < $count-1)
                $inspired .= ', ';
            $i++;
        }
        if($count-3 > 0) {
            $inspired .= '<a href="/sequences?basedon='.$row['id'].'&date=4">+'.($count - 3).'</a>';
        }
    } else {
        $inspired = '';
    }
     if(isAdmin()) {
        $adminlink = ' / <a href="/delete/'.$row['id'].'">Delete</a>';
     } else {
        $adminlink = '';
     }
     
     return $row['accesscount'].' plays / created '.$date.$basedon.$inspired.' / <a href="/'.$row['id'].'">Permanent link</a> / <a href="/app/midi.php?id='.$row['id'].'">Download MIDI</a>'.$adminlink;
}
?>
