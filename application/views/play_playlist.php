<?php
$jj = array ();
foreach ( $tracks as $track ) {
    $jj[] = array (
        "title" => $track->title ,
        "mp3" => base_url ( $track->url ) ,
    );
}
?>
<div id="jquery_jplayer_1" class="jp-jplayer"></div>
<div id="jp_container_1" class="jp-audio" role="application" aria-label="media player">
    <div class="jp-type-playlist">
        <div class="jp-gui jp-interface">
            <div class="jp-controls">
                <button class="jp-previous" role="button" tabindex="0"><?php echo lang ( "previous" ) ?></button>
                <button class="jp-play" role="button" tabindex="0"><?php echo lang ( "play" ) ?></button>
                <button class="jp-next" role="button" tabindex="0"><?php echo lang ( "next" ) ?></button>
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
            </div>
            <div class="jp-toggles">
                <button class="jp-repeat" role="button" tabindex="0"><?php echo lang ( "repeat" ) ?></button>
                <button class="jp-shuffle" role="button" tabindex="0"><?php echo lang ( "shuffle" ) ?></button>
            </div>
        </div>
        <div class="jp-playlist maxheight300 vertical-scroll">
            <ul>
                <li>&nbsp;</li>
            </ul>
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
<script src="<?= base_url (); ?>assets/plugins/jPlayer/dist/jplayer/jquery.jplayer.js" type="text/javascript"></script>
<script src="<?= base_url (); ?>assets/plugins/jPlayer/dist/add-on/jplayer.playlist.min.js"
        type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function () {
        new jPlayerPlaylist({
            jPlayer: "#jquery_jplayer_1",
            cssSelectorAncestor: "#jp_container_1"
        }, <?php echo json_encode ( $jj ); ?>, {
            ready: function () {
                $(this).jPlayer("play");
            },
            swfPath: "<?=base_url (); ?>assets/plugins/jPlayer/dist/jplayer",
            supplied: "mp3",
            wmode: "window",
            useStateClassSkin: true,
            autoBlur: false,
            smoothPlayBar: true,
            keyEnabled: true
        });
    });
</script>
