<?php if ( $is_widget ): ?>
    <?php $this->load->view ( 'templates/head' ); ?>
<?php endif ?>
<div id="jquery_jplayer_1" class="jp-jplayer"></div>
<div id="jp_container_1" class="jp-audio-stream" role="application" aria-label="media player">
    <div class="jp-type-single">
        <div class="jp-gui jp-interface">
            <div class="jp-controls">
                <button class="jp-play" role="button" tabindex="0"><?php echo lang ( "play" ) ?></button>
            </div>
            <div class="jp-volume-controls">
                <button class="jp-mute" role="button" tabindex="0"><?php echo lang ( "mute" ) ?></button>
                <button class="jp-volume-max" role="button" tabindex="0"><?php echo lang ( "max_volume" ) ?></button>
                <div class="jp-volume-bar">
                    <div class="jp-volume-bar-value"></div>
                </div>
            </div>
            <div class="jp-progress">
                <div class="jp-seek-bar">
                    <div class="jp-play-bar1 jp-seeking-bg" style="width: 100%"></div>
                </div>
            </div>
        </div>
        <div class="jp-details">
            <div class="jp-title" aria-label="title">&nbsp;</div>
        </div>
    </div>
</div>
<link rel="stylesheet" type="text/css"
      href="<?= base_url (); ?>assets/plugins/jPlayer/dist/skin/blue.monday/css/jplayer.blue.monday.css" title="style"
      media="screen"/>
<script src="<?= base_url (); ?>assets/plugins/jPlayer/dist/jplayer/jquery.jplayer.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        function setjPlayer(item, url, type, serverType) {
            var obj = new Object();
            obj[type] = url;

            if (serverType == 'shoutcast') {
                obj[type] = url + ";stream/1";
            }

            $(item).jPlayer("destroy");
            $(item).jPlayer({
                ready: function () {
                    $(this).jPlayer("setMedia", obj).jPlayer("play");
                },
                ended: function (event) {
                    $(this).jPlayer("play");
                },
                swfPath: "<?=base_url (); ?>assets/plugins/jPlayer/dist/jplayer",
                supplied: type,
                wmode: "window",
                useStateClassSkin: true,
                autoBlur: false,
                keyEnabled: true
            });
        }

        setjPlayer('#jquery_jplayer_1', SITE_URL + '/stream', 'mp3', 'icecast');

        var ServerDataUpdate = function () {
            $.getJSON(SITE_URL + '/stream/data', function (response) {
                $('#jp_container_1 .jp-title').html("");
                $('#jp_container_1 .jp-title').append("<span class='now'><b>" + globalLang['now'] + ":</b>" + response.now + "</span>");
                if (response.next != null && response.next != "") {
                    $('#jp_container_1 .jp-title').append("<span class='next'><b>" + globalLang['next'] + ":</b>" + response.next + "</span>");
                }
                setTimeout(ServerDataUpdate, response.progress.next * 1000);
            });
        };
        ServerDataUpdate();
    });
</script>
