<div class="modal fade" id="playlist_select_modal">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title"><?php echo lang ( "select_playlist" ) ?></h4>
        </div>
        <div class="modal-body">
            <table class="table table-hover table-condensed checkable_datatable" id="playlist_select">
                <thead>
                <tr>
                    <th valign="middle" align="center" style="min-width: 16px;">
                        <input type="checkbox" id="select_all" name="select_all"/>
                    </th>
                    <th style="min-width: 16px;"></th>
                    <th style="min-width: 170px; width: 100%;"><?php echo lang ( "title" ) ?></th>
                    <th style="min-width: 160px;"><?php echo lang ( "description" ) ?></th>
                    <th style="min-width: 125px;"><?php echo lang ( "audiolength" ) ?></th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang ( "cancel" ) ?></button>
            <button type="button" id="select" disabled="disabled"
                    class="btn btn-primary"><?php echo lang ( "select" ) ?></button>
        </div>
    </div>
</div>
<script src="<?= base_url (); ?>assets/js/select_playlists.js" type="text/javascript"></script>