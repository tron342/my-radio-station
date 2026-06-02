<div id="jquery_jplayer_1" class="jp-jplayer"></div>
<div id="jp_container_1" class="jp-audio" role="application" aria-label="media player">
    <div class="jp-type-single">
        <div class="jp-gui jp-interface">
            <div class="jp-controls">
                <button class="jp-play" role="button" tabindex="0"><?php echo lang ( "play" ) ?></button>
                <button class="jp-stop" role="button" tabindex="0"><?php echo lang ( "stop" ) ?></button>
            </div>
            <div class="jp-progress">
                <div class="jp-seek-bar">
                    <div class="jp-play-bar"></div>
                </div>
            </div>
            <div class="jp-volume-controls">
                <button class="jp-mute" role="button" tabindex="0"><?php echo lang ( "mute" ) ?></button>
                <button class="jp-volume-max" role="button" tabindex="0"><?php echo lang ( "max_volume" ) ?></button>
                <div class="jp-volume-bar">
                    <div class="jp-volume-bar-value"></div>
                </div>
            </div>
            <div class="jp-time-holder">
                <div class="jp-current-time" role="timer" aria-label="time">&nbsp;</div>
                <div class="jp-duration" role="timer" aria-label="duration">&nbsp;</div>
                <div class="jp-toggles">
                    <button class="jp-repeat" role="button" tabindex="0"><?php echo lang ( "repeat" ) ?></button>
                </div>
            </div>
        </div>
        <div class="jp-details">
            <div class="jp-title" aria-label="title">&nbsp;</div>
        </div>
        <div class="jp-no-solution">
            <span>Update Required</span>
            To play the media you will need to either update your browser to a recent version or update your <a
                    href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
        </div>
    </div>
</div>

<link rel="stylesheet" type="text/css"
      href="<?= base_url (); ?>assets/plugins/jPlayer/dist/skin/blue.monday/css/jplayer.blue.monday.css" title="style"
      media="screen"/>
<script src="<?= base_url (); ?>assets/plugins/jPlayer/dist/jplayer/jquery.jplayer.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#jquery_jplayer_1").jPlayer({
            ready: function () {
                $(this).jPlayer("setMedia", {
                    title: "<?php echo $track->title ?>",
                    mp3: "<?php echo base_url ( $track->url ) ?>"
                }).jPlayer("play");
            },
            swfPath: "<?=base_url (); ?>assets/plugins/jPlayer/dist/jplayer",
            supplied: "mp3",
            wmode: "window",
            useStateClassSkin: true,
            autoBlur: false,
            smoothPlayBar: true,
            keyEnabled: true,
            remainingDuration: true,
            toggleDuration: true
        });
    });
</script>
