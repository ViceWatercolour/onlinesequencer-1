var endpoint = "/app/api";

function isNumber(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}

if (!Array.prototype.indexOf) {
	Array.prototype.indexOf = function(obj, start) {
		 for (var i = (start || 0), j = this.length; i < j; i++) {
			 if (this[i] === obj) { return i; }
		 }
		 return -1;
	}
}

piano = [];
pianoToIndex={};
var k = 0;
for(var i = 7; i >= 2; i--)
{
	for(var j = settings['pianoNotes'].length-1; j >= 0; j--)
	{
        var name = settings['pianoNotes'][j]+i;
		piano.push(name);
        pianoToIndex[name] = k;
        k++;
	}
}

function detectScale(notes) {
	var scaleFrequency = {};
	for(var i = 0; i < settings['scales'].length; i++) {
		scaleFrequency[i] = 0;
	}
	var totalNotes = 0;
	for(var i = 0; i < notes.length; i++) {
		var note = notes[i].type.substring(0, notes[i].type.length-1);
		if(note.instrument == 2)
			continue;
		totalNotes++;
		for(var j = 0; j < settings['scales'].length; j++) {
			if(settings['scales'][j].indexOf(note) > -1) {
				scaleFrequency[j]++;
			}
		}
	}
	var maxScale;
	var maxFrequency = 0;
	for(var i = 0; i < settings['scales'].length; i++) {
		if(scaleFrequency[i] > maxFrequency) {
			maxFrequency = scaleFrequency[i];
			maxScale = i;
		}
	}
	var f = maxFrequency/totalNotes;
	if(f > 0.9)
		return maxScale;
	else
		return -1;
}
function updateScale(notes) {
	var scaleIndex = detectScale(notes);
	if(scaleIndex > 0) {
		scale = settings['scales'][scaleIndex];
		keySelect.selectedIndex = scaleIndex;
		updateKeys();
	}
}

var id=0;
function Note(song, type, time, length, instrument) {
	this.song = song;
	this.time = time;
    this.intTime = Math.floor(time);
    this.fracTime = this.time - this.intTime;
	this.type = type;
	this.length = length;
	this.instrument = instrument;
    this.selected = false;
    this.id = id++;
}

function Song(data) {
	this.playing = false;
	this.stopping = false;
	this.notes = [];
	this.loopTime = 0;
	this.noteColumns = [];
    this.basedon = 0;
	data = data.split(";");
	var maxTime = 0;
	for(var i = 0; i < data.length; i++) {
		noteArray = data[i].split(' ');
		if(noteArray[1] != undefined) {
			var time = parseFloat(noteArray[0]);
			noteInstrument = noteArray.length > 3 ? parseInt(noteArray[3]) : 0;
			if(loadedInstruments[noteInstrument] == false) {
				loadInstrument(noteInstrument);
			}
			var note = new Note(this, noteArray[1], time, noteArray[2], noteInstrument);
			this.addNote(note);
			if(note.intTime > maxTime) {
				maxTime = note.intTime;
			}
		}
	}
	if(maxTime < 64)
		maxTime = 64;
	updateLength(maxTime+16);
	updateScale(this.notes);
}
Song.prototype.addNote = function(note) {
	var noteIndex = piano.indexOf(note.type);
    if(noteIndex != -1) {
        this.notes.push(note);
        note.element = document.createElement('div');
        note.element.noteData = note;
        sequencer.appendChild(note.element);
        note.element.className = "note instrument"+note.instrument;
        this.update(note);
        this.moveNote(note, undefined, note.instrument, undefined, note.time, undefined, note.type);
        this.updateLoopTime();
    }
}

Song.prototype.removeNote = function(note) {
    note.deleted = true;
	this.notes.splice(this.notes.indexOf(note), 1);
	delete this.noteColumns[note.intTime][note.id];
	sequencer.removeChild(note.element);
	this.updateLoopTime();
}

Song.prototype.moveNote = function(note, oldInstrument, newInstrument, oldTime, newTime, oldType, newType) {
    if(note.deleted)
        return;
	note.time = newTime;
    note.intTime = Math.floor(note.time);
    note.fracTime = note.time - note.intTime;
	note.type = newType;
    note.instrument = newInstrument;
	if(oldTime != undefined) {
		delete this.noteColumns[Math.floor(oldTime)][note.id]
	}
	if(this.noteColumns[note.intTime] == null)
		this.noteColumns[note.intTime] = {};
	this.noteColumns[note.intTime][note.id] = note;
    if(note.length >= 0.75)
          note.element.innerHTML = newType;
	return true;
}
Song.prototype.updateLoopTime = function() {
	this.loopTime = 0;
	for(var i = 0; i < this.notes.length; i++) {
		if(this.notes[i].intTime+Math.ceil(this.notes[i].length) >= this.loopTime)
			this.loopTime = this.notes[i].intTime;
	}
	this.loopTime = Math.floor(this.loopTime/16)*16+16;
        if(this.loopTime >= maxCells)
            updateLength(this.loopTime + 16);
}

noteWidth = 25;
noteHeight = 16;

function getButton (event) {
    if ('which' in event) {
        return event.which;
    }
    else {
        if ('button' in event) {
            var buttons = "";
            if (event.button & 1) {
                return 1;
            }
            if (event.button & 2) {
                return 2;
            }
            if (event.button & 4) {
                return 3;
            }
        }
    }
}

function updateLength(length)   {
	length = Math.floor(length/16)*16+16;
	maxCells = length;
	document.getElementById("sequencer_inner").style.width = (length*noteWidth)+"px";
}
