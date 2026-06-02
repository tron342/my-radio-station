<div class="span6">
    <ul class="nav nav-tabs" id="tab-playlist">
        <li class="active <?php echo lang ( "is_rtl" ) ? "pull-right" : "" ?>"><a href="#tabplaylists">
                <h5 class="no-margin"><?php echo lang ( "playlists" ) ?></h5>
            </a></li>
        <li class="<?php echo lang ( "is_rtl" ) ? "pull-right" : "" ?>" style="display: none;"><a href="#tabediting">
                <h5 class="no-margin"><?php echo lang ( "edit" ) ?> <span
                            class="semi-bold"><?php echo lang ( "playlist" ) ?></span></h5>
            </a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tabplaylists">
            <table class="table table-hover table-condensed minheight400 checkable_datatable" id="playlist_datatable">
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
        <div class="tab-pane " id="tabediting">
            <form id="editPlayList" action="<?= site_url ( '/playlist/add' ); ?>" method="POST">
                <input type="hidden" name="audiolength" value="0">
                <input type="hidden" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" />
                <div class="tab-title">
                    <div class="pull-right flip">
                        <button type="submit" class="btn btn-mini btn-white"><?php echo lang ( "save" ) ?></button>
                        <button id="cancelediting" type="button"
                                class="btn btn-mini btn-white"><?php echo lang ( "cancel" ) ?></button>
                    </div>
                    <h4><?php echo lang ( "create" ) ?> <span class="semi-bold">"<?php echo lang ( "tracks" ) ?>"</span>
                    </h4>
                </div>
                <h4 id="playlist_duration" class="text-right flip"><?php echo lang ( "duration" ) ?>: <span
                            class="semi-bold text-success">00:00:00</span></h4>
                <div class="control-group">
                    <div class="controls">
                        <input type="text" name="name" class="span8" placeholder="<?php echo lang ( "name" ) ?>">
                    </div>
                </div>
                <div class="control-group no-margin">
                    <div class="controls">
                        <textarea name="description" class="span12" placeholder="<?php echo lang ( "description" ) ?>"
                                  rows="5"></textarea>
                    </div>
                </div>
                <table class="table table-hover table-condensed minheight300" id="playlist_tracks">
                    <thead>
                    <tr>
                        <th valign="middle" class="text-center" style="min-width: 16px;">
                            <i class="icon-move"></i>
                        </th>
                        <th style="min-width: 125px;" width="100%"><?php echo lang ( "title" ) ?></th>
                        <th style="min-width: 60px;"><?php echo lang ( "start" ) ?></th>
                        <th style="min-width: 60px;"><?php echo lang ( "end" ) ?></th>
                        <th style="min-width: 60px;"><?php echo lang ( "duration" ) ?></th>
                        <th valign="middle" class="text-center" style="min-width: 16px;">
                            <i class="icon-trash delete-all-pltr"></i>
                        </th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </form>
        </div>
    </div>
</div>
<script src="<?= base_url (); ?>assets/js/playlists.js" type="text/javascript"></script>
