<?php
$scales = array(array("C", "D", "E", "F", "G", "A", "B", "C", "D", "E", "F", "G"),
array("C#", "D#", "F", "F#", "G#", "A#", "C", "C#", "D#", "F", "F#", "G#"),
array("D", "E", "F#", "G", "A", "B", "C#", "D", "E", "F#", "G", "A"),
array("D#", "F", "G", "G#", "A#", "C", "D", "D#", "F", "G", "G#", "A#"),
array("E", "F#", "G#", "A", "B", "C#", "D#", "E", "F#", "G#", "A", "B"),
array("F", "G", "A", "A#", "C", "D", "E", "F", "G", "A", "A#", "C"),
array("F#", "G#", "A#", "B", "C#", "D#", "F", "F#", "G#", "A#", "B", "C#"),
array("G", "A", "B", "C", "D", "E", "F#", "G", "A", "B", "C", "D"),
array("G#", "A#", "C", "C#", "D#", "F", "G", "G#", "A#", "C", "C#", "D#"),
array("A", "B", "C#", "D", "E", "F#", "G#", "A", "B", "C#", "D", "E"),
array("A#", "C", "D", "D#", "F", "G", "A", "A#", "C", "D", "D#", "F"),
array("B", "C#", "D#", "E", "F#", "G#", "A#", "B", "C#", "D#", "E", "F#"),
array("C", "D", "D#", "F", "G", "G#", "A#", "C", "D", "D#", "F", "G"),
array("C#", "D#", "E", "F#", "G#", "A", "B", "C#", "D#", "E", "F#", "G#"),
array("D", "E", "F", "G", "A", "A#", "C", "D", "E", "F", "G", "A"),
array("D#", "F", "F#", "G#", "A#", "B", "C#", "D#", "F", "F#", "G#", "A#"),
array("E", "F#", "G", "A", "B", "C", "D", "E", "F#", "G", "A", "B"),
array("F", "G", "G#", "A#", "C", "C#", "D#", "F", "G", "G#", "A#", "C"),
array("F#", "G#", "A", "B", "C#", "D", "E", "F#", "G#", "A", "B", "C#"),
array("G", "A", "A#", "C", "D", "D#", "F", "G", "A", "A#", "C", "D"),
array("G#", "A#", "B", "C#", "D#", "E", "F#", "G#", "A#", "B", "C#", "D#"),
array("A", "B", "C", "D", "E", "F", "G", "A", "B", "C", "D", "E"),
array("A#", "C", "C#", "D#", "F", "F#", "G#", "A#", "C", "C#", "D#", "F"),
array("B", "C#", "D", "E", "F#", "G", "A", "B", "C#", "D", "E", "F#"));

chdir('../');
require('inc/init.php');
require('inc/Sajax.php');
mod('js');
function getPhrases($n, $index, $key=null, $id=null) {
    if($id == 0)
        $current = mysql_fetch_array(db_query('SELECT * FROM phrases ORDER BY RAND() LIMIT 1'));
    else
        $current = mysql_fetch_array(db_query('SELECT * FROM phrases WHERE hash="'.$id.'" LIMIT 1'));
    $key = $key || 0;
    $result = array();
    for($i = 1; $i < $n; $i++) {
        if($index%16 == 0) {
            $order = 'RAND()';
        }
        else {
            $order = 'count';
        }
        $current = mysql_fetch_array(db_query('SELECT hash,c0,c1,c2,c3, phrases_next.count FROM `phrases_next` LEFT JOIN (phrases) ON (phrases_next.hash2=phrases.hash) WHERE hash1='.$current['hash'].' ORDER BY '.$order.' LIMIT 1'));
        $notes = array();
        for($j = 0; $j < 4; $j++) {
            $notes[$j] = explode(' ', trim($current['c'.$j]));
        }
        $result[] = $notes;
        $index++;
    }
    return array($current['hash'], $result);
}
sajax_init();
sajax_export('getPhrases');
sajax_handle_client_request();
echo '<script>';
?>

<?php
sajax_show_javascript();
echo '</script>';
show_js('app/audioSystem');
show_js('app/lib');
$isIE = stristr($_SERVER['HTTP_USER_AGENT'], 'MSIE');
$format = $isIE ? 'mp3' : 'ogg';
?>
<script type="text/javascript">
window.audioFormat = '<?php echo $format; ?>';
loading = true;
totalNoteCount = 0;
noteCount = 0;
bufferSize = 10;
initialBuffer = bufferSize * 2;
buffer = [];
bufferTime = 0;
playTime = 0;
bpm = 100;
sleepTime = (1/((bpm*4)/60))*1000;
key = 4;
lastID = 0;
document.onLoadSound = function()
{
    noteCount++;
    if(noteCount >= totalNoteCount)
    {
        loading = false;
        load(initialBuffer);
    }
}
function loadInstrument(id) {
	for(var i = min[id]; i <= max[id]; i++)
	{
		totalNoteCount++;
		audioSystem.load("/app/sounds/"+id+"-"+(i-offset[id])+"."+window.audioFormat, id+"-"+(i-offset[id]));
	}
}
window.onload = function()
{
    loading = true;
	audioSystem.init({force:"audioTag", audioTagTimeToLive:1000});
	loadInstrument(0);
};

function play() {
    if(buffer[playTime] != undefined) {
        for(var i = 0; i < buffer[playTime].length; i++) {
            playNote(0, scales[key][buffer[playTime][i]]+'4');
        }
        playTime++;
        if(playTime%bufferSize == 0 && playTime > initialBuffer)
            load(bufferSize);
    }
    window.setTimeout(play, sleepTime);
};

function load(n) {
    var phrases = x_getPhrases(n, bufferTime, 4, lastID, function(result) {
        lastID = result[0];
        notes = result[1];
        for(var i = 0; i < n; i++) {
            for(var j = 0; j < 4; j++) {
                buffer[bufferTime+j] = [];
                for(var k in notes[i][j]) {
                    buffer[bufferTime+j].push(notes[i][j][k]);
                }
            }
            bufferTime = bufferTime + 4
        }
    });
}
</script>