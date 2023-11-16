function onYouTubeIframeAPIReady() {
    if (typeof playerInfoList === 'undefined') {
        return;
    }

    for (var i = 0; i < playerInfoList.length; i++) {
        var curplayer = createYoutubePlayer(playerInfoList[i]);
        players[i] = curplayer;
    }
}

var players = new Array();

function createYoutubePlayer(playerInfo) {
    return new YT.Player(playerInfo.id, {
        videoId: playerInfo.videoId,
        width: '100%',
        height: '100%',
        pauseOnScroll: false,
        fitToBackground: true,
        playerVars: {
            ratio: 16 / 9,
            fitToBackground: true,
            autoplay: playerInfo.autoplay,
            loop: 1,
            controls: 0,
            enablejsapi: 1,
            wmode: 'opaque',
            branding: 0,
            rel: 0,
            iv_load_policy: 0,
            showinfo: 0,
            autohide: 0
        },
        events: {
            onReady: function (e) {
                e.target.mute();
            },
            onStateChange: function (e) {
                if (e.data === 0) {
                    e.target.playVideo();
                }
            }
        }
    });
}

function onVimeoReady() {
    for (var i = 0; i < playerInfoList.length; i++) {
        var curplayer = createVimeoPlayer(playerInfoList[i]);
        players[i] = curplayer;
    }
}

function createVimeoPlayer(playerInfo) {
    return new Vimeo.Player(playerInfo.id, {
        id: playerInfo.videoId,
        autoplay: playerInfo.autoplay,
        background: true,
        portrait: false,
    })
}
