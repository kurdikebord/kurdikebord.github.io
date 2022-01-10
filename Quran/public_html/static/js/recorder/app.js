//webkitURL is deprecated but nevertheless
URL = window.URL || window.webkitURL;

var gumStream; 						//stream from getUserMedia()
var recorder; 						//WebAudioRecorder object
var input; 							//MediaStreamAudioSourceNode  we'll be recording
var encodingType; 					//holds selected encoding for resulting audio (file)
var encodeAfterRecord = true;       // when to encode

// shim for AudioContext when it's not avb.
var AudioContext = window.AudioContext || window.webkitAudioContext;
var audioContext; //new audio context to help us record

// var encodingTypeSelect = document.getElementById("encodingTypeSelect");
var encodingTypeSelect = 'mp3';
var recordButton = document.getElementById("recordBtn");
// var stopButton = document.getElementById("stopButton");

//add events to those 2 buttons
// recordButton.addEventListener("click", startRecording);
// stopButton.addEventListener("click", stopRecording);

function startRecording() {
	// // console.log("startRecording() called");
	// if(recordBtn.classList.contains("start"))
	// {
	// 	// stop it
	// 	console.log('stop recorder');
	// 	stopRecording();
	// 	return true;
	// }

	/*
		Simple constraints object, for more advanced features see
		https://addpipe.com/blog/audio-constraints-getusermedia/
	*/

    var constraints = { audio: true, video:false }

    /*
    	We're using the standard promise based getUserMedia()
    	https://developer.mozilla.org/en-US/docs/Web/API/MediaDevices/getUserMedia
	*/
	if(navigator.mediaDevices === undefined)
	{
		notifAlerty('error', 'لطفا دسترسی به میکروفون را به سلام قرآن بدهید', 'خطا در ضبط صدا');
		console.log('navigator.mediaDevices is undefined!');
		stopRecordingJob();
		return false;
	}
	navigator.mediaDevices.getUserMedia(constraints).then(function(stream) {
		__log("getUserMedia() success, stream created, initializing WebAudioRecorder...");

		/*
			create an audio context after getUserMedia is called
			sampleRate might change after getUserMedia is called, like it does on macOS when recording through AirPods
			the sampleRate defaults to the one set in your OS for your playback device

		*/
		audioContext = new AudioContext();

		//update the format
		// document.getElementById("formats").innerHTML="Format: 2 channel "+encodingTypeSelect.options[encodingTypeSelect.selectedIndex].value+" @ "+audioContext.sampleRate/1000+"kHz"
		//assign to gumStream for later use
		gumStream = stream;
		/* use the stream */
		input = audioContext.createMediaStreamSource(stream);

		//stop the input from playing back through the speakers
		//input.connect(audioContext.destination)

		//get the encoding
		// encodingType = encodingTypeSelect.options[encodingTypeSelect.selectedIndex].value;
		encodingType = 'mp3';

		//disable the encoding selector
		encodingTypeSelect.disabled = true;

		recorder = new WebAudioRecorder(input, {
		  workerDir: "static/js/recorder/", // must end with slash
		  encoding: encodingType,
		  numChannels:2, //2 is the default, mp3 encoding supports only 2
		  onEncoderLoading: function(recorder, encoding) {
		    // show "loading encoder..." display
		    __log("Loading "+encoding+" encoder...");
		  },
		  onEncoderLoaded: function(recorder, encoding) {
		    // hide "loading encoder..." display
		    __log(encoding+" encoder loaded");
		  }
		});

		recorder.onComplete = function(recorder, blob) {
			__log("Encoding complete");
			createDownloadLink(blob,recorder.encoding);
			encodingTypeSelect.disabled = false;
		}

		recorder.setOptions({
		  timeLimit:120,
		  encodeAfterRecord:encodeAfterRecord,
	      ogg: {quality: 0.5},
	      mp3: {bitRate: 160}
	    });

		//start the recording process
		recorder.startRecording();

		 __log("Recording started");

	}).catch(function(err) {
	  	//enable the record button if getUSerMedia() fails
    	// recordButton.disabled = false;
    	// stopButton.disabled = true;
    	console.log('error on start recorder!');
    	console.log(err);
	});

	//disable the record button
    // recordButton.disabled = true;
    // stopButton.disabled = false;
}

function stopRecording() {
	// console.log("stopRecording() called");

	if(gumStream === undefined)
	{
		notifAlerty('error', 'خطا در ذخیره صوت', 'خطا');
		console.log("gumStream is undefined!");
		return false;
	}

	//stop microphone access
	gumStream.getAudioTracks()[0].stop();

	//disable the stop button
	// stopButton.disabled = true;
	// recordButton.disabled = false;

	//tell the recorder to finish the recording (stop recording + encode the recorded audio)
	recorder.finishRecording();

	__log('Recording stopped');
}

function createDownloadLink(blob,encoding) {

	var url = URL.createObjectURL(blob);
	// var au = document.createElement('audio');
	// var div = document.createElement('div');
	// var link = document.createElement('a');

	//add controls to the <audio> element
	// au.controls = true;
	// au.src = url;
	// au.classList = 'block';

	// //link the a element to the blob
	// link.href = url;
	// link.download = 'SalamQuran-lms-'+  new Date().toISOString() + '.'+encoding;
	// link.innerHTML = link.download;

	//add the new audio and a elements to the div element
	// div.classList = 'cbox';
	// div.appendChild(au);
	// recordingsList.replaceWith(div);

	// div.appendChild(link);
	//add the div element to the ordered list
	// recordingsList.replaceWith(div);


	var myAudioPlayer = document.getElementById("recordedAudio");
	myAudioPlayer.src = url;
	// hide waiting sound
	document.getElementById("waitingMsg").style.display = 'none';
	// show audio element
	document.getElementById("recordingsList").style.display = 'block';


	lastAudioBlob = blob;
	lastAudioURL  = url;
}



//helper function
function __log(e, data) {
	console.log(e + " " + (data || ''));
	// log.innerHTML += "\n" + e + " " + (data || '');
}