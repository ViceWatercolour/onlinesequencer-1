<?php
$settings = array(
    'domain' => 'http://onlinesequencer.net',
	'template' => 'default',
    
    'instruments' => array('Electric Piano', 'Acoustic Guitar', 'Percussion', 'Smooth Synth', 'Electric Guitar', 'Bass', 'Synth Pluck', 'Scifi', 'Grand Piano', 'French Horn', 'Trombone', 'Violin', 'Cello'),
    'min' =>        array(  0,  0,  3,  0,  0,  0, 12, 12,  0, 16, 16, 31, 12),
    'max' =>        array( 71, 59, 63, 59, 47, 23, 59, 71, 71, 47, 55, 71, 48),
    'offset' =>     array(  0,  0,  0,  0,  0,  0, 11, 12,  0,  0,  0,  0,  0),
    'originalBpm' =>array( 30, 15, 10, 30, 30, 30, 30, 30, 15, 30, 30, 40, 40),
    'volume' =>     array(0.25,  1,  1,  1,  1,  1,  1,  1,0.3,0.45,0.4,array(0.55, 0.85), array(0.1,0.2)),
    'midiInstrumentMap' => array(1, 25, 1, 81, 30, 33, 88, 100, 1, 61, 58, 41, 43),
    'audioVersion' => 2,
    
    'typingKeyboard' => array(
        "zxcvbnm,./",
        "asdfghjkl;'",
        "qwertyuiop[]"
    ),
    
    'piano' => array('C2', 'C#2', 'D2', 'D#2', 'E2', 'F2', 'F#2', 'G2', 'G#2', 'A2', 'A#2', 'B2', 'C3', 'C#3', 'D3', 'D#3', 'E3', 'F3', 'F#3', 'G3', 'G#3', 'A3', 'A#3', 'B3', 'C4', 'C#4', 'D4', 'D#4', 'E4', 'F4', 'F#4', 'G4', 'G#4', 'A4', 'A#4', 'B4', 'C5', 'C#5', 'D5', 'D#5', 'E5', 'F5', 'F#5', 'G5', 'G#5', 'A5', 'A#5', 'B5', 'C6', 'C#6', 'D6', 'D#6', 'E6', 'F6', 'F#6', 'G6', 'G#6', 'A6', 'A#6', 'B6', 'C7', 'C#7', 'D7', 'D#7', 'E7', 'F7', 'F#7', 'G7', 'G#7', 'A7', 'A#7', 'B7'),
    'pianoNotes' => array('C', 'C#', 'D', 'D#', 'E', 'F', 'F#', 'G', 'G#', 'A', 'A#', 'B'), 
    'scaleNames' => array("No Key Guide", "C Major","C# Major","D Major","D# Major","E Major","F Major","F# Major","G Major","G# Major","A Major","A# Major","B Major", "C Minor","C# Minor","D Minor","D# Minor","E Minor","F Minor","F# Minor","G Minor","G# Minor","A Minor","A# Minor","B Minor"),
    'scaleOctaves' => array(0, 6, 5, 5, 4, 4, 3, 3, 2, 1, 1, 0, 0, 6, 6, 5, 5, 4, 3, 3, 2, 2, 1, 0, 0),
    'scales' => array(
        array(),
        array('C', 'D', 'E', 'F', 'G', 'A', 'B', 'C', 'D', 'E', 'F', 'G'),
        array('C#', 'D#', 'F', 'F#', 'G#', 'A#', 'C', 'C#', 'D#', 'F', 'F#', 'G#'),
        array('D', 'E', 'F#', 'G', 'A', 'B', 'C#', 'D', 'E', 'F#', 'G', 'A'),
        array('D#', 'F', 'G', 'G#', 'A#', 'C', 'D', 'D#', 'F', 'G', 'G#', 'A#'),
        array('E', 'F#', 'G#', 'A', 'B', 'C#', 'D#', 'E', 'F#', 'G#', 'A', 'B'),
        array('F', 'G', 'A', 'A#', 'C', 'D', 'E', 'F', 'G', 'A', 'A#', 'C'),
        array('F#', 'G#', 'A#', 'B', 'C#', 'D#', 'F', 'F#', 'G#', 'A#', 'B', 'C#'),
        array('G', 'A', 'B', 'C', 'D', 'E', 'F#', 'G', 'A', 'B', 'C', 'D'),
        array('G#', 'A#', 'C', 'C#', 'D#', 'F', 'G', 'G#', 'A#', 'C', 'C#', 'D#'),
        array('A', 'B', 'C#', 'D', 'E', 'F#', 'G#', 'A', 'B', 'C#', 'D', 'E'),
        array('A#', 'C', 'D', 'D#', 'F', 'G', 'A', 'A#', 'C', 'D', 'D#', 'F'),
        array('B', 'C#', 'D#', 'E', 'F#', 'G#', 'A#', 'B', 'C#', 'D#', 'E', 'F#'),
        array('C', 'D', 'D#', 'F', 'G', 'G#', 'A#', 'C', 'D', 'D#', 'F', 'G'),
        array('C#', 'D#', 'E', 'F#', 'G#', 'A', 'B', 'C#', 'D#', 'E', 'F#', 'G#'),
        array('D', 'E', 'F', 'G', 'A', 'A#', 'C', 'D', 'E', 'F', 'G', 'A'),
        array('D#', 'F', 'F#', 'G#', 'A#', 'B', 'C#', 'D#', 'F', 'F#', 'G#', 'A#'),
        array('E', 'F#', 'G', 'A', 'B', 'C', 'D', 'E', 'F#', 'G', 'A', 'B'),
        array('F', 'G', 'G#', 'A#', 'C', 'C#', 'D#', 'F', 'G', 'G#', 'A#', 'C'),
        array('F#', 'G#', 'A', 'B', 'C#', 'D', 'E', 'F#', 'G#', 'A', 'B', 'C#'),
        array('G', 'A', 'A#', 'C', 'D', 'D#', 'F', 'G', 'A', 'A#', 'C', 'D'),
        array('G#', 'A#', 'B', 'C#', 'D#', 'E', 'F#', 'G#', 'A#', 'B', 'C#', 'D#'),
        array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'A', 'B', 'C', 'D', 'E'),
        array('A#', 'C', 'C#', 'D#', 'F', 'F#', 'G#', 'A#', 'C', 'C#', 'D#', 'F'),
        array('B', 'C#', 'D', 'E', 'F#', 'G', 'A', 'B', 'C#', 'D', 'E', 'F#')
    ),
    'percussion' => array('Open Surdo', 'Closed Surdo', 'Castanets', 'Belltree', 'Jingle Bell', 'Shaker', 'Open Triangle', 'Mute Triangle', 'Open Cuica', 'Mute Cuica', 'Low Woodblock', 'High Woodblock', 'Claves', 'Long Guiro', 'Short Guiro', 'Long Lo Whistle', 'Short Hi Whistle', 'Maracas', 'Cabasa', 'Low Agogo', 'High Agogo', 'Low Timbale', 'High Timbale', 'Low Conga', 'Open Hi Conga', 'Mute Hi Conga', 'Low Bongo', 'High Bongo', 'Ride Cymbal 2', 'Vibra-Slap', 'Crash Cymbal 2', 'Cowbell', 'Splash Cymbal', 'Tambourine', 'Ride Bell', 'Chinese Cymbal', 'Ride Cymbal', 'High Tom 1', 'Crash Cymbal', 'High Tom 2', 'Mid Tom 1', 'Open Hi-Hat', 'Mid Tom 2', 'Pedal Hi-Hat', 'Low Tom 1', 'Closed Hi-Hat', 'Low Tom 2', 'Snare Drum 2', 'Hand Clap', 'Snare Drum 1', 'Side Stick', 'Kick Drum 1', 'Kick Drum 2', 'Metronome Bell', 'Metronome Click', 'Square Click', 'Sticks', 'Scratch Pull', 'Scratch Push', 'Slap', 'High Q')
);
$settings['numNotes'] = count($settings['piano']);
$settings['numInstruments'] = count($settings['instruments']);
$publicSettings = $settings;

$settings = array_merge($settings, require('settings_private.php'));

function settings_js() {
    global $publicSettings;
    return 'var settings = '.json_encode($publicSettings);
}
?>