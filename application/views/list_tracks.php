<div class="span6" id="tracks_list" style="display: none">
    <ul class="nav nav-tabs" id="tab-tracks">
        <li class="active <?php echo lang ( "is_rtl" ) ? "pull-right" : "" ?>"><a href="#tablisttraks">
                <h5 class="no-margin"><?php echo lang ( "list" ) ?> <span
                            class="semi-bold"><?php echo lang ( "tracks" ) ?></span></h5>
            </a></li>
        <li class=" <?php echo lang ( "is_rtl" ) ? "pull-right" : "" ?>"><a href="#tabuploadtrack">
                <h5 class="no-margin"><?php echo lang ( "upload" ) ?> <span
                            class="semi-bold"><?php echo lang ( "track" ) ?></span></h5>
            </a></li>
    </ul>
    <div class="tab-content minheight500">
        <div class="tab-pane active" id="tablisttraks">
            <table class="table table-hover table-condensed minheight400 checkable_datatable" id="tracks_datatable">
                <thead>
                <tr>
                    <th valign="middle" align="center" style="min-width: 16px;">
                        <input type="checkbox" id="select_all" name="select_all"/>
                    </th>
                    <th style="min-width: 16px;"></th>
                    <th style="min-width: 170px; width: 100%;"><?php echo lang ( "title" ) ?></th>
                    <th style="min-width: 160px;"><?php echo lang ( "artist" ) ?></th>
                    <th style="min-width: 150px;"><?php echo lang ( "album" ) ?></th>
                    <th style="min-width: 80px;"><?php echo lang ( "length" ) ?></th>
                    <th style="min-width: 80px;"><?php echo lang ( "bitrate" ) ?></th>
                    <th style="min-width: 80px;"><?php echo lang ( "mime" ) ?></th>
                    <th style="min-width: 100px;"><?php echo lang ( "genre" ) ?></th>
                    <th style="min-width: 125px;"><?php echo lang ( "samplerate" ) ?></th>
                    <th style="min-width: 125px;"><?php echo lang ( "channels" ) ?></th>
                    <th style="min-width: 125px;"><?php echo lang ( "filesize" ) ?></th>
                    <th style="min-width: 155px;"><?php echo lang ( "upload_date" ) ?></th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <div class="tab-pane" id="tabuploadtrack">
            <div class="row-fluid">
                <form id="my-awesome-dropzone" style="height: 500px;" action="<?= site_url ( '/track/do_upload' ); ?>"
                      class="dropzone no-margin"/>
                    <input type="hidden" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" />
                <div class="fallback minheight400">
                    <input name="file" type="file" multiple=""/>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>


<link href="<?= base_url (); ?>assets/plugins/dropzone/css/dropzone.css" rel="stylesheet" type="text/css"/>
<script src="<?= base_url (); ?>assets/js/tracks.js" type="text/javascript"></script>
<script src="<?= base_url (); ?>assets/plugins/dropzone/dropzone.js" type="text/javascript"></script>
<script type="text/javascript">
    Dropzone.autoDiscover = false;
</script>
<?php if ( $tracks_full ): ?>
    <script type="text/javascript">
        $('#tracks_list').removeClass('span6').addClass('span12');
        $('#tracks_list .tab-content .tab-pane').wrapInner("<div class='grid simple'></div>");
        $('#tracks_list .tab-content .tab-pane .grid').wrapInner("<div class='grid-body no-border'></div>");
        $('#tracks_list .tab-content .tab-pane .grid').prepend("<div class='grid-title no-border'></div>");
        $('#tracks_list .tab-content .tab-pane').removeClass('tab-pane').addClass('span6');
        $('#tracks_list .tab-content .grid:eq(0) .grid-title').append("<h4>" + $('#tracks_list #tab-tracks li:eq(0) h5').html() + "</h4>");
        $('#tracks_list .tab-content .grid:eq(1) .grid-title').append("<h4>" + $('#tracks_list #tab-tracks li:eq(1) h5').html() + "</h4>");
        $('#tracks_list #tab-tracks').remove();
        $('#tracks_list .tab-content').removeClass('tab-content');//.addClass('row-fluid');
    </script>
<?php endif ?>
<script type="text/javascript">
    $('#tracks_list').show();
</script>
