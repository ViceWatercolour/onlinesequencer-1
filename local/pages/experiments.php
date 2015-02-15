<?php
output_header("Experiments");
output_block_start("Random Sequences");
?>
<p>
Visit <a href="http://onlinesequencer.net/?random">http://onlinesequencer.net/?random</a> to autoplay random sequences that seem to be decent (with a lot of notes in the same key).
</p>
<?php
output_block_end();
output_block_start("Synthesis");
?>
<p>
Add <code>?synth</code> to any sequence's URL to generate the sounds in your browser, instead of using samples. This will also respect the note lengths. All instruments will sound the same and it doesn't work well in any browser yet, but it's still pretty cool!
</p>
<?php
output_block_end();
output_block_start("Console Tricks");
?>
<p>
You can do some hacky stuff using the Chrome JavaScript console. Type <code>grid=(1/4)/(1/3)</code> to change the grid size to whatever you want, where 1/3 is the new note length.
</p>
<p>
You can also add notes with JavaScript. This adds a bunch of random stuff and sounds terrible:
</p>
<pre>
for(var i = 0; i < 500; i++) {
    song.addNote(new Note(song,
    piano[Math.round(Math.random()*piano.length)] /*note (piano is an array of B7...C2) */,
    Math.round(Math.random()*64) /*time*/,
    1 /*length in 1/4 intervals*/,
    0 /*instrument*/));
}
</pre>
<p>This is slightly better:</p>
<pre>
for(var i = 0; i < 500; i++) {
    song.addNote(new Note(song,
    scales[1][Math.floor(Math.random()*scales[1].length)]+Math.floor(Math.random()*6+2) /*scales[1] is the C Major scale */,
    Math.round(Math.random()*64) /*time*/,
    1 /*length in 1/4 intervals*/,
    0 /*instrument*/));
}
</pre>
<?php
output_block_end();
output_footer();
?>