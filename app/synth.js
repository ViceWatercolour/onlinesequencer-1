function getFrequency(n) {
    return Math.round(440 * Math.pow(2, (piano.length - piano.indexOf(n) - 1 + 3 - 36) / 12));
}

var poly = 64;
var oscillators = {};
var synthTime = 0;
var oscillatorType = "triangle";

function initSynth(context) {
    for(var i = 0; i < piano.length; i++) {
        var n = piano[i];
        oscillators[n] = context.createOscillator();
        var o = oscillators[n];
        o.type = "triangle";
        o.frequency.value = getFrequency(n);
        
        var gain = context.createGain();
        gain.gain.value = 0;
        o.connect(gain);
        gain.connect(context.destination);
        
        o.gain = gain;
        o.inUse = false;
        o.inUseTime = 0;
        
        o.start ? o.start(0) : o.noteOn(0);
    }
}

function playSynthNote(context, note, time, type) {
    synthTime++;
    var oscillator = oscillators[note];
    if(oscillator) {
        (function(synthTime) {
            if(oscillatorType)
                oscillator.type = oscillatorType;
            oscillator.inUseTime = synthTime;
            oscillator.inUse = true;
            oscillator.gain.gain.value = 0.1;
            window.setTimeout(function() {
                if(oscillator.inUseTime == synthTime) {
                    oscillator.gain.gain.value = 0;
                    oscillator.inUse = false;
                }
            }, time);
        })(synthTime);
    }
}