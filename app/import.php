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
$filename = 'uploads/midi/'.str_replace('..', '', $_GET['import']);
$parser = new FileParser();
$parser->load($filename);
$time = 0;
$maxTime = -1;
$noteArray = array();
$noteOnTime = array();
$timeDivision = 0;
$currentTrack = 0;
$startTime = array();
while($chunk = $parser->parse())
{
    if ($chunk instanceof Delta) {
        $a = $chunk->getData();
        $time = $time + $a[0];
    } else if ($chunk instanceof SetTempoEvent) {
        $bpm = $chunk->bpm;
    }
    else if($chunk instanceof NoteOffEvent || ($chunk instanceof NoteOnEvent && $chunk->param2 == 0)) {
        if($currentTrack == -1) {
            $currentTrack++;
        }
        $noteTime = round($time/($timeDivision/4), 2);
        if(isset($_GET['trackInstruments'][$currentTrack]) && is_numeric($_GET['trackInstruments'][$currentTrack])) {
            $instrument = $_GET['trackInstruments'][$currentTrack];
        }
        else
            $instrument = 1;
        if(isset($_GET['trackTranspose'][$currentTrack]) && is_numeric($_GET['trackTranspose'][$currentTrack])) {
            $transpose = $_GET['trackTranspose'][$currentTrack];
        }
        else
            $transpose = 0;
        if($instrument != -1)
            $noteArray[] = array($startTime[$chunk->param1], str_replace(array("0", "1"), "2", str_replace(array("8", "9", "10"), "7", \Midi\Util\Note::getNoteName($chunk->param1+12+12*$transpose))), round($noteTime - $startTime[$chunk->param1]), $instrument);
    
    }
    else if($chunk instanceof NoteOnEvent) {
        $noteTime = round($time/($timeDivision/4), 2);
        $startTime[$chunk->param1] = $noteTime;
    }
    else if($chunk instanceof EndOfTrackEvent)
    {
        if($time > $maxTime)
            $maxTime = $time;
        $time = 0;
        $currentTrack++;
    }
    else if ($chunk instanceof FileHeader) {
        $a = $chunk->getData();
        $timeDivision = $a[2];
    }
}
$notes = '';
foreach($noteArray as $note)
{
    $notes .= implode(' ', $note).';';
}
$title = htmlspecialchars($_GET['title']);
$basedon = 0;
?>