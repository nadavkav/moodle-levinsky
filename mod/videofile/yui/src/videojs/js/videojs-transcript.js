M.mod_videofile = M.mod_videofile || {};
M.mod_videofile.videojs_transcript = {
  init: function (videoId) {
    var myPlayer = videojs('videofile-' + videoId);

    myPlayer.ready(function(){

        var options = {
            showTitle: false,
            showTrackSelector: false

        };

        // fire up the plugin
        var transcript = this.transcript(options);

        // attach the widget to the page
        var transcriptContainer = document.querySelector('.videotranscript');
        transcriptContainer.appendChild(transcript.el());
    });

  }
};
