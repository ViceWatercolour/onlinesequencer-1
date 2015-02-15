<?php
require('inc/init.php');
function preview2($id, $title=null, $caption=null) {
    header('Content-type: image/gif');
	$filename = 't/'.$id.$caption.'b.gif';
	if(!file_exists($filename)) {
		$piano = array("C2", "C#2", "D2", "D#2", "E2", "F2", "F#2", "G2", "G#2", "A2", "A#2", "B2", "C3", "C#3", "D3", "D#3", "E3", "F3", "F#3", "G3", "G#3", "A3", "A#3", "B3", "C4", "C#4", "D4", "D#4", "E4", "F4", "F#4", "G4", "G#4", "A4", "A#4", "B4", "C5", "C#5", "D5", "D#5", "E5", "F5", "F#5", "G5", "G#5", "A5", "A#5", "B5", "C6", "C#6", "D6", "D#6", "E6", "F6", "F#6", "G6", "G#6", "A6", "A#6", "B6", "C7", "C#7", "D7", "D#7", "E7", "F7", "F#7", "G7", "G#7", "A7", "A#7", "B7");
		$im = imagecreatetruecolor(200, 200);
		$orange = imagecolorallocate($im, 27, 200, 224);
        $instrument_colors = array(
            imagecolorallocate($im, 27, 158, 224),
            imagecolorallocate($im, 255, 165, 0),
            imagecolorallocate($im, 153, 20, 20),
            imagecolorallocate($im, 204, 143, 198),
            imagecolorallocate($im, 54, 204, 43),
            imagecolorallocate($im, 96, 96, 96),
            imagecolorallocate($im, 81, 43, 204),
            imagecolorallocate($im, 168, 204, 43),
            imagecolorallocate($im, 21, 108, 176),
            imagecolorallocate($im, 116, 107, 0),
            imagecolorallocate($im, 241, 170, 0),
            imagecolorallocate($im, 145, 105, 42),
            imagecolorallocate($im, 83, 53, 7),
        );
		$white = imagecolorallocate($im, 255, 255, 255);
		$black = imagecolorallocate($im, 0, 0, 0);
		$row = mysqli_fetch_array(db_query('SELECT title, data, date FROM sequences WHERE id="'.$id.'" LIMIT 1'));
		$data = $row['data'];
		$data = explode(':', $data);
		$data = $data[1];
		$parts = explode(";", $data);
		$notes = 0;
        imagefilledrectangle($im, 0, 0, 200, 200, $black);
		foreach($parts as $p) {
            if(empty($p))
                continue;
			$arr = explode(' ', $p);
            if(!isset($arr[3]))
                $arr[3] = 0;
            if(!isset($arr[1]))
                continue;
			$note = $arr[1];
			$time = $arr[0];
			$x = $time*2;
			$y = (count($piano) - array_search($note, $piano) - 15)*4;
			if($x < 200 && $y < 200)
			{
				imagefilledrectangle($im, $x, $y, $x+2, $y+2, $instrument_colors[$arr[3]]);
			}
			$notes++;
		}
		$date = @date("m/d/y", $row['date']);
        if(!isset($title)) {
            if($row['title'] != 'Untitled')
                $title = e_html($row['title']);
            else
                $title = 'Sequence #'.$id;
        }
        if(strlen($title) > 22)
            $title = substr($title, 0, 22).'...';
		imagettftext($im, 12, 0, 10, 23, $black, "resources/Prototype.ttf", $title);
		imagettftext($im, 12, 0, 9, 22, $white, "resources/Prototype.ttf", $title);
		imagettftext($im, 12, 0, 10, 187, $black, "resources/Prototype.ttf", isset($caption) ? $caption : $date);
		imagettftext($im, 12, 0, 9, 186, $white, "resources/Prototype.ttf", isset($caption) ? $caption : $date);
        if(TEST) {
            imagegif($im);
            exit;
        } else 
            imagegif($im, $filename);
	}
    echo file_get_contents($filename);
}
preview2(intval($_GET['id']), $_GET['title']);
?>