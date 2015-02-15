<?php
ini_set('memory_limit', '128M');
require_once '../inc/Midi/bootstrap.php';

use \Midi\Emit\File;
use \Midi\Emit\Track;
use \Midi\Event\TimeSignatureEvent;
use \Midi\Event\SetTempoEvent;
use \Midi\Delta;
use \Midi\Event\NoteOnEvent;
use \Midi\Event\NoteOffEvent;
use \Midi\Event\EndOfTrackEvent;
use \Midi\Event\TrackNameEvent;
use \Midi\Event\ChannelPrefixEvent;
use \Midi\Event\ProgramChangeEvent;
function eventTime($time)
{
	global $lastTime;
	$diff = $time - $lastTime;
	$lastTime = $time;
	return new Delta(round($diff * 96));
}
function addInstrumentTrack($instrument)
{
	global $settings, $id, $f, $noteMap, $parts;

	$song = new Track();
    if($instrument == 2)
        $channel = 9;
    else
        $channel = $instrument;
    $name = new TrackNameEvent($settings['instruments'][$instrument]);
    $program = new ProgramChangeEvent($channel, $settings['midiInstrumentMap'][$instrument] - 1);
    $song->appendEvent($name);
    $song->appendEvent($program);
	$notes = array();
	$maxTime = -1;
	foreach($parts as $p)
	{
        if(!empty($p)) {
            $arr = explode(' ', $p);
            if($arr[3] == $instrument)
            {
                $note = $arr[1];
                $time = floor($arr[0]);
                $fracTime = $arr[0] - $time;
                if(!isset($notes[$time]))
                    $notes[$time] = array();
                $notes[$time][] = array($note, $arr[2], $fracTime);
                if($time > $maxTime)
                    $maxTime = $time;
            }
        }
	}
    $events = array();
	for($t = 0; $t <= $maxTime; $t++)
	{
		if(isset($notes[$t]))
		{
			for($i = 0; $i < count($notes[$t]); $i++)
			{
				$note = $notes[$t][$i][0];
                if(!isset($events[$t]))
                    $events[$t] = array();
                $events[$t][] = array(0, $channel, $noteMap[$note], 1, $notes[$t][$i][2]);
                if(!isset($events[$t+$notes[$t][$i][1]]))
                    $events[$t+$notes[$t][$i][1]] = array();
                $events[$t+$notes[$t][$i][1]][] = array(0, $channel, $noteMap[$note], 0, $notes[$t][$i][2]);
			}
		}
	}
    ksort($events);
    foreach($events as $t => $arr) {
        foreach($arr as $v) { 
            $delta = eventTime($t+$v[4]);
            if($v[3] == 1)
                $event = new NoteOnEvent($v[1], $v[2], 50);
            else
                $event = new NoteOffEvent($v[1] , $v[2], 0);
            $song->appendEvent($event, $delta);
        }
    }
	$song->appendEvent(new EndOfTrackEvent());

	$f->addTrack($song);
}

if($_GET['id'] && is_numeric($_GET['id']))
{

	$id = $_GET['id'];
	$filename = 'm/'.$id.'.mid';
    require('../inc/init.php');
	if(TEST || !file_exists($filename))
	{
		require('midi_notes.php');		
		list($data) = mysqli_fetch_array(db_query('SELECT data FROM sequences WHERE id="'.$id.'"'));
		$data = explode(':', $data);
		$bpm = $data[0];
		$mpqn = 1/(($bpm)/60) * 1000000;
		$parts = explode(";", $data[1]);
		
		$f = new File(384);		
		$first = new Track();
		$first->appendEvent(new TimeSignatureEvent(4, 4));
		$first->appendEvent(new SetTempoEvent($mpqn));
		$first->appendEvent(new EndOfTrackEvent());
		$f->addTrack($first);
		
		for($i=0; $i < $settings['numInstruments']; $i++) {
            $GLOBALS['lastTime'] = 0;
			addInstrumentTrack($i);
        }
		
		$f->save($filename);
	}
	header('Location: /'.$filename);
}
?>
