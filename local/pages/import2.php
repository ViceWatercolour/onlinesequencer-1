<?php
require_once 'inc/Midi/bootstrap.php';
use \Midi\Emit\File;
use \Midi\Emit\Track;
use \Midi\FileHeader;
use \Midi\Event\TimeSignatureEvent;
use \Midi\Event\SetTempoEvent;
use \Midi\Delta;
use \Midi\Event\NoteOnEvent;
use \Midi\Event\NoteOffEvent;
use \Midi\Event\EndOfTrackEvent;
use \Midi\Event\TrackNameEvent;
use Midi\Parsing\FileParser;
output_header("Upload MIDI File");
$path = 'uploads/midi/'.$file;
$name = p_string('title');
try {
	$tracks = array();
	$trackNoteCount = array();
	$currentTrack = 0;
	$trackNoteCount[$currentTrack] = 0;
	$tracks[$currentTrack] = '[Untitled Track]';
	$parser = new FileParser();
	$parser->load($path);
	
	$time = 0;
	$maxTime = -1;
	$noteArray = array();
	$noteOnTime = array();
	$timeDivision = 0;

	while($chunk = $parser->parse()) {
		if ($chunk instanceof Delta) {
			$a = $chunk->getData();
			$time = $time + $a[0];
		} else if ($chunk instanceof SetTempoEvent) {
			$bpm = $chunk->bpm;
		}
		else if($chunk instanceof NoteOnEvent) {
			if($currentTrack == -1) {
				$currentTrack++;
				$tracks[$currentTrack] = '[Untitled Track]';
				$trackNoteCount[$currentTrack] = 0;
			}
			$noteTime = ($time/($timeDivision/4));
			$noteArray[] = array((int)$noteTime, str_replace(array("7", "8"), "6", \Midi\Util\Note::getNoteName($chunk->param1+12)), 1);
			$trackNoteCount[$currentTrack]++;
		}
		else if($chunk instanceof EndOfTrackEvent) {
			if($time > $maxTime)
				$maxTime = $time;
			$time = 0;
			$currentTrack++;
			$trackNoteCount[$currentTrack] = 0;
			$tracks[$currentTrack] = '[Untitled Track]';
		}
		else if($chunk instanceof TrackNameEvent) {
			$track = $chunk->getData();
			$track = $track[2];
			$tracks[$currentTrack] = $track;
		}
		else if ($chunk instanceof FileHeader) {
			$a = $chunk->getData();
			$timeDivision = $a[2];
		}
	}
	?>
	<script type="text/javascript">
	function preview() {
		var selects = document.getElementsByTagName('select');
		var url = "/app/sequencer.php?frame=1&title=<?php echo e_html($name); ?>&import=<?php echo $file; ?>&";
		for(var i = 0; i < selects.length; i++) {
			var trackID = selects[i].name;
			if(selects[i].className == "instrument") {
				url += "trackInstruments["+trackID+"]="+selects[i].value+"&";
			}
			else {
				url += "trackTranspose["+trackID+"]="+selects[i].value+"&";
			}
		}
		document.getElementById('sequencer_frame').src = url;
	}
	</script>
	<?php
	echo '
	<table width="100%"><tr><td style="vertical-align: top; width: 400px;"><p>Now choose which instruments to use for each track in the MIDI file.</p><table>';
	for($i = 0; $i < count($tracks); $i++) {
		echo '<tr><td>'.htmlspecialchars($tracks[$i]).' ('.$trackNoteCount[$i].' notes)</td>
		<td>
        
		<select class="instrument" name="'.$i.'">
			<option value="-1">None (don\'t use this track)</option>';
            for($j = 0; $j < $settings['numInstruments']; $j++) {
                echo '<option value="'.$j.'"'.($j == 0 ? ' selected="selected"' : '').'>'.$settings['instruments'][$j].'</option>';
            }
		echo '</select><br/>
            <select class="transpose" name="'.$i.'">
			<option value="-2">-2 octaves</option>
			<option value="-1">-1 octaves</option>
			<option value="0" selected="selected">--</option>
			<option value="1">+1 octaves</option>
			<option value="2">+2 octaves</option>
		</select>
		</td></tr>';
	}
	echo '<tr><td><input type="submit" value="Preview/Import" onclick="preview();"/></td></tr>';
	echo '</table></td><td style="vertical-align: top;">
<iframe id="sequencer_frame" style="border: 1px solid black;" src="about:blank" frameborder="no" scrolling="no" width="100%" height="500px"></iframe>
</td></tr></table>';
}
catch(Exception $ex) {
	output_message('Error', 'Could not parse MIDI file: '.$ex);
}
output_footer();
?>