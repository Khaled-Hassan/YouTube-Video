var player, done = false, currentTime = 0, maxTime = 0, playStatus = -1, wrong = false, getTime;

var tag = document.createElement('script');
tag.src = "https://www.youtube.com/iframe_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

/**
 * The youtube API function.
 */
function onYouTubeIframeAPIReady() {
    player = new YT.Player('player', {
        height: '360',
        width: '640',
        videoId: '?showinfo=0',
        playerVars: {
            autoplay: 0,
            controls: 0,
            rel: 0,
            showinfo: 0,
            disablekb: 1,
            enablejsapi: 1,
            iv_load_policy: 3,
            playsinline: 0,
            modestbranding: 1,
            ecver: 2
        },
        events: {
            'onReady': onPlayerReady,
            'onStateChange': onPlayerStateChange
        }
    });

}

/**
 * The youtube API function.
 */
function onPlayerReady(event) {
    done = true;
    if (player.isMuted()) {
        document.getElementById('mute').setAttribute('disabled', '');
        document.getElementById('unmute').removeAttribute('disabled');
    } else {
        document.getElementById('unmute').setAttribute('disabled', '');
        document.getElementById('mute').removeAttribute('disabled');
    }
}

/**
 * The youtube API function.
 */
function onPlayerStateChange(event) {
    var temp = '', currentTime = 0;
    playStatus = parseInt(event.data);
    switch (event.data) {
        case 0:
            temp = ('end');
            break;
        case 1:
            getTime = setInterval(function () {
                currentTime = parseInt(player.getCurrentTime()) + 1;
                var max = currentTime - 3;
                if (max < maxTime) {
                    if (!wrong) {
                        if (currentTime > maxTime) {
                            maxTime = currentTime;
                        }
                    } else {
                        wrong = false;
                        temp = 'return';
                    }
                } else {
                    temp = 'wait';
                    wrong = true;
                }

                video.showTime(currentTime);
            }, 1000);
            video.playStatus = 1;
            video.checkPlayButtonStatus();
            temp = ('play');
            video.showDuration();
            break;
        case 2:
            window.clearInterval(getTime);
            video.playStatus = 0;
            video.checkPlayButtonStatus();
            temp = ('pause');
            break;
        case 3:
            temp = ('puffer');
            break;
        case 4:
            temp = 'wait';
            break;
        case 5:
            temp = 'wait';
    }
    document.getElementById('status').innerHTML = temp;
}

/**
 * The youtube API function.
 */
function visibilityChcek() {
    var hidden, visibilityChange = '', pauseStatus = false;
    if (typeof document.hidden !== "undefined") { // Opera 12.10 and Firefox 18 and later support 
        hidden = "hidden";
        visibilityChange = "visibilitychange";
    } else if (typeof document.mozHidden !== "undefined") {
        hidden = "mozHidden";
        visibilityChange = "mozvisibilitychange";
    } else if (typeof document.msHidden !== "undefined") {
        hidden = "msHidden";
        visibilityChange = "msvisibilitychange";
    } else if (typeof document.webkitHidden !== "undefined") {
        hidden = "webkitHidden";
        visibilityChange = "webkitvisibilitychange";
    }

    function handleVisibilityChange() {
        if (document[hidden]) {
            video.pauseVideo();
            if (playStatus === 1) {
                pauseStatus = true;
            }
        } else {
            if (pauseStatus === true) {
                video.playVideo();
                pauseStatus = false;
            }
        }
    }

    if (typeof document.addEventListener === "undefined" || typeof document[hidden] === "undefined") {
        alert("This demo requires a browser, such as Google Chrome or Firefox, that supports the Page Visibility API.");
    } else {
        document.addEventListener(visibilityChange, handleVisibilityChange, false);
        window.addEventListener('blur', handleVisibilityChange, false);
    }
}

/**
 * youtube video functions to control a playing video.<p></p>
 * @author Khaled Hassan
 * @category youtube video
 * @link khaled.h.developer@gmail.com
 */
var video = {
    player: 'player',
    playStatus: 0,
    videoDuration: 0,
    videoId: '',
    quality: 'medium',
    flag: false,
    
    /**
     * Creat vidoe control seek bar and button (play, puse, volume up, volume down, mute and full screen).
     * @param control string <br> The div id to creat control elements inside it <p></p>
     * @param addSeekBar bool [optional] <br> Add the seek bar to can control the video playing or not <br> <b>true</b> is default value <p></p>
     * @param addFullScreen bool [optional] <br> Add the full screen button or not <br> <b>true</b> is default value <p></p>
     * @return null.
     */
    creatWatchElement: function (control, addSeekBar, addFullScreen) {
        if(addSeekBar === undefined){
            addSeekBar = true;
        }
        if(addFullScreen === undefined){
            addFullScreen = true;
        }
        var temp = '';
        if(addSeekBar === true){
            temp = '<div id="postionContainer"><div id="postion"></div></div>';
        }else{
            temp = '<div id="postionContainer" style="visibility:hidden"><div id="postion"></div></div>';
        }
        temp += '<button id="play" onclick="video.playPause()" disabled=""><i class="fa fa-play"></i></button>';
        temp += '<span id="timer"><span id="timerShow"></span><span id="sperator">|</span><span id="duratrion"></span></span>';
        temp += '<button id="volumeLess" onclick="video.volumeLess()" disabled=""><i class="fa fa-minus"></i></button>';
        temp += '<div id="volumeContainer"><div id="volume"></div></div>';
        temp += '<button id="volumeAdd" onclick="video.volumeAdd()" disabled=""><i class="fa fa-plus"></i></button>';
        temp += '<button id="volumeMute" onclick="video.checkMuteStatus()" disabled=""><i class="fa fa-bell"></i></button>';
        if(addFullScreen === true){
            temp += '<button id="fullScreen" onclick="video.playFullscreen()" disabled=""><i class="fa fa-square-o"></i></button>';
        }else{
            temp += '<button id="fullScreen" style="visibility:hidden" onclick="video.playFullscreen()" disabled=""><i class="fa fa-square-o"></i></button>';
        }
        temp += '<span id="status"></span>';

        document.getElementById(control).innerHTML = temp;
        visibilityChcek();
        loadYoutubeObject();
        getTimeClicked();
    },
    
    /**
     * Load youtube video with specific video id.
     * @param videoId string <br> The The youtube video ID <p></p>
     * @param quality string <br> The youtube video quality format. <br> <b>small</b> Small video size 320 * 240 <br> <b>medium</b> Medium video size 640 * 360 <br> <b>large</b> Large video size 853 * 480 <br> <b>hd720</b> HD720 video size 1280 * 720 <br> <b>hd1080</b> HD1080 video size 1920 * 1080 <br> <p></p>
     * @param start int [optional] <br> The start second <b>0</b> is default value. <p></p>
     * @param end int [optional] <br> The end second. <p></p>
     * @return null.
     */
    loadVideo: function (videoId, quality, start, end) {
        if(quality === undefined){
            quality = this.quality;
        }
        if(start === undefined){
            start = 0;
        }
        
        this.setPlayerQuality(quality);
        this.flag = true;
        this.videoId = videoId;
        
        if(end !== undefined && end > 0){
            player.loadVideoById({'videoId': videoId, 'startSeconds': start, 'endSeconds': end, 'suggestedQuality': quality});
        }else{
            player.loadVideoById({'videoId': videoId, 'startSeconds': start, 'suggestedQuality': quality});
        }
        document.getElementById('timerShow').innerHTML = '00:00:00';
        document.getElementById('duratrion').innerHTML = '00:00:00';

        document.getElementById('play').removeAttribute('disabled');
        document.getElementById('volumeLess').removeAttribute('disabled');
        document.getElementById('volumeAdd').removeAttribute('disabled');
        document.getElementById('volumeMute').removeAttribute('disabled');
        document.getElementById('fullScreen').removeAttribute('disabled');
        document.getElementById('postionContainer').setAttribute('onclick', 'showCoords(event)');
        document.getElementById('videoWatch').style.pointerEvents  = 'auto';
        this.checkPlayButtonStatus();
        CheckVolume();
    },
    
    /**
     * Set youtube video quality.
     * @param quality string <br> The youtube video quality format. <br> <b>small</b> Small video size 320 * 240 <br> <b>medium</b> Medium video size 640 * 360 <br> <b>large</b> Large video size 853 * 480 <br> <b>hd720</b> HD720 video size 1280 * 720 <br> <b>hd1080</b> HD1080 video size 1920 * 1080 <br> <p></p>
     * @return string <b>Email</b> on success or <b>FALSE</b> on failure.
     */
    setPlayerQuality: function (quality) {// small - medium - large - hd720 - hd1080
        this.quality = quality;
        player.setPlaybackQuality({'suggestedQuality': quality});
    },
    
    /**
     * Play or Pause youtube vido.
     * @return null.
     */
    playPause: function () {
        if(this.videoId === ''){
            return;
        }
        if (this.playStatus === 0) {
            if (this.flag === false) {
                this.loadVideo(this.videoId);
            } else {
                this.playVideo();
            }
        } else if (this.playStatus === 1 && this.flag) {
            this.pauseVideo();
        }
        this.checkPlayButtonStatus();
    },
    
    /**
     * Check the play button status between play or pause.
     * @return null.
     */
    checkPlayButtonStatus: function () {
        if (this.playStatus === 0) {
            document.getElementById('play').innerHTML = '<i class="fa fa-play"></i>';
            document.getElementById('play').setAttribute('title', 'Play');
        } else if (this.playStatus === 1) {
            document.getElementById('play').innerHTML = '<i class="fa fa-pause"></i>';
            document.getElementById('play').setAttribute('title', 'Pause');
        }
    },
    
    /**
     * Play the youtube video.
     * @return null.
     */
    playVideo: function () {
        if (done === true) {
            player.playVideo();
        }
    },
    
    /**
     * Pause the youtube video.
     * @return null.
     */
    pauseVideo: function () {
        player.pauseVideo();
    },
    
    /**
     * Stop the youtube video.
     * @return null.
     */
    stopVideo: function () {
        player.stopVideo();
    },
    
    /**
     * play youtube video from specific second.
     * @param second int <br> The second number to play from it. <p></p>
     * @return null.
     */
    goToTime: function (second) {
        player.seekTo(second);
    },
    
    /**
     * Get the youtube video current playing second .
     * @return int <b>Second</b>.
     */
    getCurrentTime: function () {
        var time = parseInt(player.getCurrentTime()) + 1;
        return time;
    },
    
    /**
     * Increase youtube video volume level.
     * @return null.
     */
    volumeAdd: function () {
        var volume = parseInt(player.getVolume()) + 4;
        player.setVolume(volume);
        this.checkVolumeButtonStatus();
    },
    
    /**
     * Decrease youtube video volume level.
     * @return null.
     */
    volumeLess: function () {
        var volume = parseInt(player.getVolume()) - 4;
        player.setVolume(volume);
        this.checkVolumeButtonStatus();

    },
    
    /**
     * Check the volume buttons (volume up '+' & volume down '-') status (enable or disable).
     * @return null.
     */
    checkVolumeButtonStatus: function () {
        var volume = parseInt(player.getVolume());
        if (player.isMuted()) {
            document.getElementById('volumeLess').setAttribute('disabled', '');
            document.getElementById('volumeAdd').setAttribute('disabled', '');
        } else {
            document.getElementById('volumeAdd').removeAttribute('disabled');
            document.getElementById('volumeLess').removeAttribute('disabled');
            if (volume === 0) {
                document.getElementById('volumeLess').setAttribute('disabled', '');
            } else if (volume === 100) {
                document.getElementById('volumeAdd').setAttribute('disabled', '');
            } else {
                document.getElementById('volumeAdd').removeAttribute('disabled');
                document.getElementById('volumeLess').removeAttribute('disabled');
            }
            document.getElementById('volume').style.width = volume + 'px';
        }
    },
    
    /**
     * Mute youtube video sounde.
     * @return null.
     */
    volumeMute: function () {
        player.mute();
        document.getElementById('volumeMute').innerHTML = '<i class="fa fa-bell"></i>';
        document.getElementById('volumeMute').setAttribute('title', 'Mute');
        this.checkVolumeButtonStatus();
    },
    
    /**
     * Unmute youtube video sounde.
     * @return null.
     */
    volumeUnmute: function () {
        player.unMute();
        document.getElementById('volumeMute').innerHTML = '<i class="fa fa-bell-slash"></i>';
        document.getElementById('volumeMute').setAttribute('title', 'Unmute');
        this.checkVolumeButtonStatus();
    },
        
    /**
     * Check the mute button status (enable or disable).
     * @return null.
     */
    checkMuteStatus: function () {
        if (player.isMuted()) {
            player.unMute();
            document.getElementById('volumeMute').innerHTML = '<i class="fa fa-bell"></i>';
            document.getElementById('volumeMute').setAttribute('title', 'Mute');
        } else {
            player.mute();
            document.getElementById('volumeMute').innerHTML = '<i class="fa fa-bell-slash"></i>';
            document.getElementById('volumeMute').setAttribute('title', 'Unmute');
        }
        CheckVolume();
    },
    
    /**
     * Play the youtube video in full screen.
     * @return null.
     */
    playFullscreen: function () {
        var thisVid = document.getElementById("player");

        if (thisVid.requestFullscreen) {
            thisVid.requestFullscreen();
        } else if (thisVid.msRequestFullscreen) {
            thisVid.msRequestFullscreen();
        } else if (thisVid.mozRequestFullScreen) {
            thisVid.mozRequestFullScreen();
        } else if (thisVid.webkitRequestFullScreen) {
            thisVid.webkitRequestFullScreen();
        }
        player.playVideo();
    },
    
    /**
     * Show youtube video current second in time format 'H:M:S'.
     * @param times int <br> The current second number. <p></p>
     * @return null.
     */
    showTime: function (times) {
        var cur = times;
        var hour = String(Math.floor(times / 3600));
        if (parseInt(hour) > 0) {
            times = times - (hour * 3600);
        }
        var minute = String(Math.floor(times / 60)), second = String(times % 60);
        if (hour.length === 1) {
            hour = '0' + hour;
        }
        if (minute.length === 1) {
            minute = '0' + minute;
        }
        if (second.length === 1) {
            second = '0' + second;
        }
        var formatedTime = hour + ':' + minute + ':' + second;
        document.getElementById('timerShow').innerHTML = formatedTime;
        var width = (cur / this.videoDuration) * 100;
        document.getElementById('postion').style.width = width + '%';
    },
    
    /**
     * Show youtube video duration in time format 'H:M:S'.
     * @return null.
     */
    showDuration: function () {
        var times = parseInt(player.getDuration());
        this.videoDuration = times;
        var hour = String(Math.floor(times / 3600));
        if (parseInt(hour) > 0) {
            times = times - (hour * 3600);
        }
        var minute = String(Math.floor(times / 60)), second = String(times % 60);
        if (hour.length === 1) {
            hour = '0' + hour;
        }
        if (minute.length === 1) {
            minute = '0' + minute;
        }
        if (second.length === 1) {
            second = '0' + second;
        }
        var formatedTime = hour + ':' + minute + ':' + second;
        document.getElementById('duratrion').innerHTML = formatedTime;
    }
};

function CheckVolume() {
    var volume = setTimeout(function () {
        clearTimeout(volume);
        video.checkVolumeButtonStatus();
    }, 500);
}
function showCoords(event) {
    var l = document.getElementById("postionContainer").offsetLeft, x = event.clientX - l,
            width = document.getElementById("postionContainer").offsetWidth,
            percent = x / width, time = parseInt(video.videoDuration * percent) - 1;

    if (time < 0) {
        time = 0;
    } else if (time > video.videoDuration) {
        time = video.videoDuration;
    }
    video.goToTime(time);
}