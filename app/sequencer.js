var $ = jQuery;

var mainStylesheet;
var zoomLevel = 1;
var grid = 1;
var autoScroll = 0;
var instrument = 0;
var keyElements = [];
var keyRowElements = [];
var keyTextElements = [];

var scale = [];
var clipboard = [];
var clipboardTime;
var clickedButton = false;
var mouseButton = 0;
var mode = "draw"
var maxCells;
var bpm;
var lastStepTime;
var selectOpen = false;

var selectedNotes = [];
var dragNotes = [];
var clickedNote = false;
var sequencer;
var mouseX, mouseY;
var mouseClickX, mouseClickY;
var playhead;
var playTimeoutId = 0;
var lastPlayTime = 0;
var autoplay = false;
var onLoop = null;
var scrollLeft = 0;
var targetScrollLeft = 0;

function createKey(t, addHighlight) {
    var div = document.createElement('div');
    var div_t = document.createElement('div');
    div_t.innerHTML = t;
    div_t.className = 'key_text';
    if(addHighlight) {
        var div_h = document.createElement('div');
        div_h.id = 'key_highlight_'+t;
        div.appendChild(div_h);
    }
    div.appendChild(div_t);
    keyTextElements.push(div_t);
    div.className = addHighlight ? (t.length == 3 ? "key_sharp" : "key") : "sequencer_key";
    if(addHighlight) {
        div.onmousedown = function() {
            var btn = getButton(event);
            mouseButton = btn;
            playNote(instrument, t, 1);
        };
        div.onmouseover = function() {
            if(mouseButton == 1 && dragNotes.length == 0) {
                playNote(instrument, t, 1);
            }
        };
    }
    return div;
}
function displayKeys() {
    for(var i = 0; i < piano.length; i++) {
        var leftElement = createKey(piano[i], true);
        keyboard.appendChild(leftElement);
        keyElements.push(leftElement);
    }
    for(var i = 0; i < keyElements.length; i++) {
        var rowElement = createKey(piano[i], false);
        keyboard_table.appendChild(rowElement);
        keyRowElements.push(rowElement);
    }
}
function updateKeys() {
    for(var i = 0; i < keyTextElements.length; i++) {
        if(settings['numNotes'] - i - 1 >= settings['min'][instrument] && settings['numNotes'] - i - 1 <= settings['max'][instrument]) {
            if(instrument == 2)
                keyTextElements[i].innerHTML = settings['percussion'][i-8];
            else
                keyTextElements[i].innerHTML = piano[i];
            if(scale.indexOf(settings['pianoNotes'][(keyTextElements.length-i-1)%12]) > -1)
                keyRowElements[i].className = "sequencer_key highlight";
            else
                keyRowElements[i].className = "sequencer_key";
        }
        else
            keyTextElements[i].innerHTML = "";
    }
}

function playNote(instrument, name, length, delay) {
    if(window.enableSynth && instrument != 2) {
        playSynthNote(audioSystem.audioContext, name, length * song.sleepTime, 2, delay);
        return;
    }
    idx = pianoToIndex[name];
    if(settings['numNotes'] - idx - 1>= settings['min'][instrument] && settings['numNotes'] - idx - 1<= settings['max'][instrument])
        audioSystem.play(instrument, (settings['numNotes']-(idx+settings['offset'][instrument])-1), delay);
    document.getElementById('key_highlight_'+name).className='instrument'+instrument;
    window.setTimeout(function(){
        document.getElementById('key_highlight_'+name).className='';
    }, song.sleepTime);
}

Song.prototype.update = function(note)
{
    var noteIndex = pianoToIndex[note.type];
    var x = noteWidth * note.time;
    var y = noteHeight * noteIndex;
    var width = noteWidth * note.length;
    note.element.style.width = (width-4)+"px";
    note.element.style.top=y+"px";
    note.element.style.left=x+"px";
}

Song.prototype.setBPM = function(v)
{
    if(!isNumber(v))
        v = 110;
    v = Math.max(v, 10);
    bpm = v;
    var nps = (v*4)/60;
    this.sleepTime = (1/nps)*1000;
}
Song.prototype.play = function(start) {
    button.style.backgroundImage="url(/app/stop.gif)";
    this.playPos = start;

    this.playing = true;
    playhead.style.display = "block";
    lastStepTime = new Date().getTime();
    this.playColumn(start);
    lastPlayTime = start;
}
Song.prototype.stop = function() {
    button.style.backgroundImage="url(/app/play.gif)";
    this.stopping = true;
    playhead.style.display = "none";
}
var scrollIntervalId = 0;
function setScrollDelta(x) {
    if(targetScrollLeft)
        setScrollLeft(container.scrollLeft + x);
    else
        setScrollLeft(targetScrollLeft + x);
}
function setScrollLeft(x) {
    if(autoScroll == 0) {
        targetScrollLeft = Math.min(Math.max(x, 0), container.scrollWidth - container.clientWidth);
        if(scrollIntervalId != 0)
            window.clearInterval(scrollIntervalId);
        scrollIntervalId = window.setInterval(function() {
            var d = targetScrollLeft - scrollLeft;
            if(Math.abs(d) < 2) {
                scrollLeft = container.scrollLeft = targetScrollLeft;
                window.clearInterval(scrollIntervalId);
            }
            else {
                scrollLeft += d/2;
                container.scrollLeft = Math.round(scrollLeft);
            }
        }, 66);
    } else if(autoScroll == 1) {
        scrollLeft = container.scrollLeft = Math.round(x);
    }
}
Song.prototype.playColumn = function(idx) {
    playhead.style.left = idx*noteWidth+"px";
    if(idx*noteWidth > scrollLeft + 7*clientWidth/8) {
        setScrollDelta(3*clientWidth/4);
    }
    if(idx == maxCells || idx >= song.loopTime) {
        if(onLoop == null) {
            idx = 0;
            if(idx*noteWidth < scrollLeft) {
                setScrollLeft(0);
            }
        }
        else {
            onLoop();
            song.stop();
        }
    }
    if(this.noteColumns[idx] != undefined)
        for(var i in this.noteColumns[idx]) {
            var note = song.noteColumns[idx][i];
            playNote(note.instrument, note.type, note.length, song.sleepTime*note.fracTime/1000);
        }
    var elapsed = new Date().getTime() - lastStepTime;
    var diff = Math.min(song.sleepTime/2, Math.max(0, elapsed - song.sleepTime));
    playTimeoutId = window.setTimeout(function() {
        if(!song.stopping)
            song.playColumn(idx+1);
        else {
            song.stopping = false;
            song.playing = false;
        }
    }, this.sleepTime - diff);
    lastStepTime = new Date().getTime();
}

function loadInstrument(id) {
    loading = true;
    if(!loadedInstruments[id])
        document.getElementById("loading_container").style.display="block";    
    loadedInstruments[id] = true;
    
    if(enableSynth && id != 2) {
        for(var i = settings['min'][id]; i <= settings['max'][id]; i++) {
            document.onLoadSound();
        }
    } else {
        audioSystem.loadInstrument(id);
    }
}

function setNoteSize() {
    mainStylesheet.cssRules[0].style.height = (noteHeight-4)+"px";
    mainStylesheet.cssRules[0].style.lineHeight = (noteHeight-4)+"px";
    mainStylesheet.cssRules[0].style.fontSize = (noteHeight*0.625)+"px";
    mainStylesheet.cssRules[1].style.height = noteHeight+"px";
    mainStylesheet.cssRules[1].style.lineHeight = noteHeight+"px";
    mainStylesheet.cssRules[1].style.fontSize = (noteHeight*0.625)+"px";
    sequencer.style.height = (piano.length*noteHeight+2)+"px"
}
function zoom(v) {
    oldZoom = zoomLevel
    zoomLevel = v;
    document.getElementById('sequencer_background').style.zoom = v;
    noteWidth = 25 * v;
    noteHeight = 16 * v;
    setNoteSize();
    for(var note in song.notes) {
        song.update(song.notes[note]);
    }
    container.scrollTop = scrollTop = scrollTop*(zoomLevel/oldZoom);
    zoomLevel = v;
}
function zoomIn() {
    zoom(zoomLevel*1.25);
}
function zoomOut() {
    zoom(zoomLevel/1.25);
}
function create() {
    mainStylesheet = document.getElementById('css_vars').sheet;
    
    scrollLeft = 0;
    scrollTop = 0;
    loadedInstruments = [];
    for(var i = 0; i < settings['instruments'].length; i++) {
        loadedInstruments.push(false);
    }
    container = document.getElementById("sequencer_main");
    clientWidth = container.clientWidth;
    sequencer = document.getElementById("sequencer_inner");
    sequencerRect = sequencer.getBoundingClientRect();
    keyboard = document.getElementById("keyboard_element");
    keyboard_wrapper = document.getElementById("keyboard_wrapper_element");
    keyboard_table = document.getElementById("sequencer_keyboard");
    instrumentSelect = document.getElementById('instrument_select');
    keySelect = document.getElementById('key_select');
    gridSelect = document.getElementById('grid_select');
    scrollSelect = document.getElementById('scroll_select');
    selectionRect = document.getElementById('selection_rect');
    
    setNoteSize();
    displayKeys();
    
    document.onLoadSound = function() {
        document.getElementById("loading").style.width=Math.round(audioSystem.getProgress()*100)+"%";
        if(audioSystem.getProgress() >= 1) {
            document.getElementById("loading_container").style.display="none";
            loading = false;
            if(autoplay && !song.playing) {
                song.play(0);
                autoplay = false;
            }
        }
    }
    audioSystem.init({force:"audioTag", audioTagTimeToLive:1000});
    if(window.enableSynth)
        initSynth(audioSystem.audioContext);
    if(audioSystem.unsupportedBrowser)
        message("Audio is not supported in your browser.");
   
    var option = document.createElement("option"); //Electric Piano
    option.value=0;
    option.innerHTML = settings['instruments'][0];
    instrumentSelect.appendChild(option);
    var option = document.createElement("option"); //Grand Piano
    option.value=8;
    option.innerHTML = settings['instruments'][8];
    instrumentSelect.appendChild(option);
    for(var i=1; i < 8; i++) {
        var option = document.createElement("option"); //Everything else
        option.value=i;
        option.innerHTML = settings['instruments'][i];
        instrumentSelect.appendChild(option);
    }
    for(var i=9; i < settings['instruments'].length; i++) {
        var option = document.createElement("option");
        option.value=i;
        option.innerHTML = settings['instruments'][i];
        instrumentSelect.appendChild(option);
    }
    instrumentSelect.onchange = function()
    {
        instrument = parseInt(instrumentSelect.value);
        if(!loadedInstruments[instrument])
            loadInstrument(instrument);
        updateKeys();
        for(var i = 0; i < selectedNotes.length; i++) {
            song.moveNote(selectedNotes[i], selectedNotes[i].instrument, instrument, selectedNotes[i].time, selectedNotes[i].time, selectedNotes[i].type, selectedNotes[i].type);
            select(selectedNotes[i]);
        }
        selectOpen = false;
    }
    
    for(var i = 0; i < settings['scales'].length; i++) {
        var option = document.createElement("option");
        option.innerHTML = settings['scaleNames'][i];
        keySelect.appendChild(option);
    }
    keySelect.onchange = function() {
        scale = settings['scales'][keySelect.selectedIndex];
        updateKeys();
        selectOpen = false;
    }
    instrumentSelect.onmousedown = keySelect.onmousedown = function() {selectOpen = true};
    
    gridSelect.onchange = function() {
        grid = gridSelect.value;
        //mainStylesheet.cssRules[2].style.backgroundImage="url(/app/sequencer"+grid+".gif)";
    }
    
    scrollSelect.onchange = function() {
        autoScroll = scrollSelect.value;
    }
    
    container.onscroll = function() {
        keyboard.style.top = (-container.scrollTop+1)+"px";
        scrollLeft = container.scrollLeft;
        scrollTop = container.scrollTop;
        sequencerRect = sequencer.getBoundingClientRect();
    };
    
    document.getElementById("sequencer_inner").style.width = length*100;
    
    document.onmouseup = onmouseup;
    sequencer.onmousedown = onmousedown;
    document.onmousemove = onmousemove;
    container.onkeypress = onkeypress;
    container.onkeydown = onkeydown;
    container.onmouseover = function() {
        if(!selectOpen)
            container.focus();
        document.oncontextmenu = function(){return false;};
    }
    container.onmouseout = function() {
        document.oncontextmenu = function(){return true;};
    }
    
    button = document.getElementById('play_small');
    button.onmousedown = function() {
        if(song.playing) {
            song.stop();
        }
        else if(!song.stopping) {
            song.play(0);
        }
    };
    keyboardIcon = document.getElementById('keyboard_icon');
    keyboardOptions = document.getElementById('keyboard_options')
    keyboardIcon.onmouseover = function() {
        keyboardOptions.style.display = 'block'
    };
    keyboardIcon.onmouseout = function() {
        keyboardOptions.style.display = 'none'
    };
    playhead = document.createElement('div');
    playhead.className="playhead";
    sequencer.appendChild(playhead);
    setMode('draw');
    updateKeys();
    
    mainInit();
}

function xPosition(x) {
    return x - sequencerRect.left;
}
function yPosition(y) {
    return y - sequencerRect.top;
}

function timeIndex(x) {
    return Math.floor(x/noteWidth/(1/grid))/grid;
}
function noteIndex(y) {
    return Math.floor(y/noteHeight);
}
function timeIndexRound(x) {
    return Math.round(x/noteWidth/(1/grid))/grid;
}
function noteIndexRound(y) {
    return Math.round(y/noteHeight);
}

function onNoteClick(event) {
    var element = document.elementFromPoint(event.clientX, event.clientY);
    if(element != undefined && element.noteData != undefined) {
        var note = element.noteData;
        if(note.selected == false) {
            clearSelection();
            select(note);
        }
        dragSelection();

        playNote(note.instrument, note.type, note.length);
        window.top.confirmExit = true;
        return clickedNote = true;
    }
    else {
        clearSelection();
        return clickedNote = false;
    }
}
function mouseDownDraw(event) {
    if(!onNoteClick(event)) {
        x = xPosition(event.clientX);
        y = yPosition(event.clientY);
        if(x > 0 && y > 0) {
            type = piano[noteIndex(y)];
            playNote(instrument, type, 1);
            time = timeIndex(x);
            var note = new Note(song, type, time, 1/grid, instrument);
            if(time <= maxCells && type != undefined) {
                song.addNote(note);
                dragNotes = [];
                dragNotes.push(note);
            }
            window.top.confirmExit = true;
        }
    }
}
function mouseDownEdit(event) {
    onNoteClick(event);
}
function mouseDownPlay(event) {
    x = xPosition(event.clientX);
    time = Math.floor(timeIndex(x));
    if(!song.playing)
        song.play(time);
    else
        song.stop();
}
function mouseDownErase(event) {
    var element = document.elementFromPoint(event.clientX, event.clientY);
    if(element.noteData != undefined) {
        song.removeNote(element.noteData);
        window.top.confirmExit = true;
    }
}
function onmousedown(event) {
    if(clickedButton) {
        clickedButton = false;
        return;
    }
    var btn = getButton(event);
    mouseButton = btn;
    if(btn == 1) {
        if(mode == "draw") {
            mouseDownDraw(event);
        }
        else if(mode == "edit") {
            mouseDownEdit(event);
        }
        else if(mode == "play") {
            mouseDownPlay(event);
        }
        else if(mode == "erase") {
            mouseDownErase(event);
        }
        mouseClickX = event.clientX;
        mouseClickY = event.clientY;
    }
    else if(btn == 2) {
        mouseDownPlay(event);
        event.stopPropagation()
        event.preventDefault();
    }
    else if(btn == 3) {
        mouseDownErase(event);
    }
}
function setMode(m) {
    document.getElementById("mode_"+mode).className="btn tooltip";
    mode = m;
    document.getElementById("mode_"+mode).className="active tooltip";
}

function select(note) {
    note.element.className = 'note instrument'+note.instrument+' selected';
    if(note.selected)
        return;
    note.selected = true;
    selectedNotes.push(note);
}

function deselect(note) {
    if(!note.selected)
        return;
    note.selected = false;
    selectedNotes.splice(selectedNotes.indexOf(note), 1);
    note.element.className = 'note instrument'+note.instrument;
}
function clearSelection() {
    for(var i = 0; i < selectedNotes.length; i++) {
        selectedNotes[i].selected = false;
        selectedNotes[i].element.className = 'note instrument'+selectedNotes[i].instrument;
    }
    selectedNotes = [];
}
function deleteSelection() {
    for(var i = 0; i < selectedNotes.length; i++) {
        selectedNotes[i].selected = false;
        selectedNotes[i].element.className = 'note instrument'+selectedNotes[i].instrument;
        song.removeNote(selectedNotes[i]);
    }
    selectedNotes = [];
}
function dragSelection() {
    dragNotes = [];
    for(var i = 0; i < selectedNotes.length; i++) {
        dragNotes.push(selectedNotes[i]);
    }
}
function selectAll() {
    if(selectedNotes.length == song.notes.length)
        clearSelection();
    else {
        for(var i = 0; i < song.notes.length; i++) {
            select(song.notes[i]);
        }
        if(selectedNotes.length > 0) {
            message(selectedNotes.length+" note"+(selectedNotes.length == 1 ? "" : "s")+" selected");
        }
    }
}
function cut() {
    copy(false);
    if(selectedNotes.length == 0) {
        message("Select some notes to cut first.");
    }
    else {
        message(clipboard.length+" note"+(selectedNotes.length == 1 ? "" : "s")+" copied.");
    }
    deleteSelection();
}
function cloneNote(note) {
    return new Note(song, note.type, note.time, 1, note.instrument);
}
function copy(showMessage) {
    clipboard = [];
    for(var i = 0; i < selectedNotes.length; i++) {
        clipboard.push(cloneNote(selectedNotes[i]));
    }
    if(showMessage) {
        if(selectedNotes.length == 0) {
            message("Select some notes to copy first.");
        }
        else {
            message(clipboard.length+" note"+(selectedNotes.length == 1 ? "" : "s")+" copied.");
        }
    }
}
function paste() {
    clearSelection();
    if(clipboard.length > 0)
        message(clipboard.length+" note"+(selectedNotes.length == 1 ? "" : "s")+" pasted. Drag them to change their position.");
    for(var i = 0; i < clipboard.length; i++) {
        var note = clipboard[i];
        song.addNote(note);
        select(note);
    }
    copy(false);
}
function onmousemove(event) {
    if((mouseButton == 1 && mode == "erase") || mouseButton == 3)
        mouseDownErase(event);
    else if(mouseButton == 1 && mode == "edit" && !clickedNote) {
        selectionRect.style.display = "block";
        
        if(mouseClickX < event.clientX) {
            selectionRect.style.left = xPosition(mouseClickX)+"px";
            selectionRect.style.width = (event.clientX - mouseClickX)+"px";
        }
        else {
            selectionRect.style.left = xPosition(event.clientX)+"px";
            selectionRect.style.width = (mouseClickX - event.clientX)+"px";
        }
        
        if(mouseClickY < event.clientY) {
            selectionRect.style.top = yPosition(mouseClickY)+"px";
            selectionRect.style.height = (event.clientY - mouseClickY)+"px";
        }
        else {
            selectionRect.style.top = yPosition(event.clientY)+"px";
            selectionRect.style.height = (mouseClickY - event.clientY)+"px";
        }
    }
    for(var i = 0; i < dragNotes.length; i++) {
        var dragNote = dragNotes[i];
        dragNote.element.style.left = (parseInt(dragNote.element.style.left) + event.clientX - mouseX)+"px";
        dragNote.element.style.top = (parseInt(dragNote.element.style.top) + event.clientY - mouseY)+"px";
    }
    mouseX = event.clientX;
    mouseY = event.clientY;
    time = timeIndex(xPosition(event.clientX));
    time = (Math.floor(time/4))
}
function onmouseup(event) {
    if(mode == "edit" && !clickedNote && mouseButton == 1) {
        selectionRect.style.display = "none";
        var xPos = xPosition(event.clientX);
        var yPos = yPosition(event.clientY);
        var xClickPos = xPosition(mouseClickX);
        var yClickPos = yPosition(mouseClickY);
        
        var startX, stopX, startY, stopY
        if(xClickPos < xPos) {
            startX = timeIndex(xClickPos);
            stopX = timeIndex(xPos);
        }
        else {
            stopX = timeIndex(xClickPos);
            startX = timeIndex(xPos);
        }
        
        if(yClickPos < yPos) {
            startY = noteIndex(yClickPos);
            stopY = noteIndex(yPos);
        }
        else {
            stopY = noteIndex(yClickPos);
            startY = noteIndex(yPos);
        }
        
        clearSelection();
        for(var i = 0; i < song.notes.length; i++) {
            var note = song.notes[i];
            if(note.time >= startX && note.time <= stopX) {
                var index = pianoToIndex[note.type];
                if(index >= startY && index <= stopY) {
                    select(note);
                }            
            }
        }
    }
    for(var index in dragNotes) {
        var dragNote = dragNotes[index];
        x = parseInt(dragNote.element.style.left);
        y = parseInt(dragNote.element.style.top);
        note = piano[noteIndexRound(y)];
        time = timeIndexRound(x);
        if(note != undefined && time < maxCells && time >= 0) {
            song.updateLoopTime();
            song.moveNote(dragNote, dragNote.instrument, dragNote.instrument, dragNote.time, time, dragNote.type, note);
        }
        song.update(dragNote);
    }
    dragNotes = [];
    mouseButton = 0;
}
function onkeydown(event) {
    var code = event.keyCode;
    if(code == 8 || code == 46) {
        deleteSelection();
        return false;
    }
    else if(code == 32) {
        if(!song.playing)
            song.play(lastPlayTime);
        else
            song.stop();
        return false;
    }
}
function onkeypress(event)
{
    var scaleId = keySelect.selectedIndex == 0 ? 1 : keySelect.selectedIndex;
    var scale = settings['scales'][scaleId];
    var chr = String.fromCharCode(event.keyCode).toLowerCase();
    for(var i = 0; i < settings['typingKeyboard'].length; i++) {
        var idx = settings['typingKeyboard'][i].indexOf(chr);
        if(idx != -1) {
            var n;
            switch(instrument) {
                case 0:
                case 4:
                case 3:
                case 6:
                case 7:
                case 8:
                case 9:
                case 10:
                case 12:
                    n=i+3;
                break;
                case 2:
                case 5:
                    n=i+2;
                break;
                case 1:
                case 11:
                    n=i+4;
                break;
            }
            if(idx > settings['scaleOctaves'][scaleId] + 7) {
                n += 2;
            }
            else if(idx > settings['scaleOctaves'][scaleId]) {
                n++;
            }
            var note = scale[idx]+n;
            playNote(instrument, note, 1);
        }
    }
};

var messageTimeoutId = 0;
function message(text) {
    if(messageTimeoutId != 0)
        window.clearTimeout(messageTimeoutId);
    var messageWrapper = document.getElementById('message_wrapper');
    var messageText = document.getElementById('message_text');
    messageText.innerHTML = text;
    messageWrapper.style.display="block";
    messageTimeoutId = window.setTimeout(function() {
        messageWrapper.style.display="none";
    }, 2000);
}

function save() {
    if(navigator.cookieEnabled) {
        var captcha = document.cookie.indexOf('captcha') > -1;
        if(!captcha) {
            document.getElementById('captcha_frame').src="/captcha.php";
            document.getElementById('captcha_container').style.display="block";
            return;
        }
        document.getElementById('captcha_frame').src="about:blank";
        document.getElementById('captcha_container').style.display="none";
    }
    if(song.notes.length > 0) {
        document.getElementById('share').style.display="inline";
        document.getElementById('sharelink').innerHTML = 'Saving...';
        data = bpm+":";
        for(var i = 0; i < song.notes.length; i++) {
            var note = song.notes[i];
            data = data+note.time+" "+note.type+" "+note.length+" "+note.instrument+";";
        }
        $.ajax({
            type: "POST",
            url: endpoint+='/save.php', 
            data: 'title='+encodeURIComponent(document.getElementById('title').value)+'&basedon='+song.basedon+'&data='+encodeURIComponent(data),
            success: function(r) {
                if(r && r.indexOf('http://') > -1) {
                    document.getElementById('sharelink').innerHTML = r;
                }
                else {
                    document.getElementById('sharelink').innerHTML = '<span style="color:red">Error saving, check your connection and try again.</span>';
                }
                document.getElementById('share').style.display="inline";
            },
            error: function(r) {
                document.getElementById('sharelink').innerHTML = '<span style="color:red">Error saving, check your connection and try again.</span>';
                document.getElementById('share').style.display="inline";
            }
        });
    }
}

function clearSong() {
    song.stop();
    song.playing = false;
    if(playTimeoutId)
        window.clearTimeout(playTimeoutId);
	while(song.notes.length > 0) {
		song.removeNote(song.notes[0]);
	}
	song.updateLoopTime();

    lastPlayTime = 0;
    selectedNotes = [];
    dragNotes = [];
    clickedNote = false;
    scrollLeft = 0;
    targetScrollLeft = 0;
}

function loadData(data) {
	var parts = data.split(":");
	bpm = parts[0];
	var notes = parts[1];
	if(typeof bpm == 'undefined' || bpm == "")
		bpm = "110";
	if(typeof notes == 'undefined')
		notes = '';
	if(typeof song != 'undefined')
		clearSong();
	song = new Song(notes);
    document.getElementById('bpm').value = bpm;
	song.setBPM(bpm);
}