<div class=" tiles grey row-fluid" style="max-heights:600px;">
    <div class="span4" id="calendar-editor" style="display: none">

        <!-- FORM EDITING SHOW -->
        <form id="add-show-form" action="<?= site_url ( '/calendar/add' ); ?>" method="POST">
            <input type="hidden" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" />
            <div class="tiles-body tiles red">
                <div class="pull-right flip">
                    <button type="submit"
                            class="btn btn-mini btn-white add-show-submit"><?php echo lang ( "save" ) ?></button>
                    <button id="cancel-add-show" type="button"
                            class="btn btn-mini btn-white"><?php echo lang ( "cancel" ) ?></button>
                </div>
                <h4 class="no-margin text-white"><?php echo lang ( "create" ) ?> <span
                            class="semi-bold"><?php echo lang ( "show" ) ?></span></h4>
            </div>

            <ul class="nav nav-tabs" id="tab-calendar">
                <li class="active <?php echo lang ( "is_rtl" ) ? "pull-right" : "" ?>"><a href="#tabeditingshow">
                        <h5 class="no-margin"><?php echo lang ( "edit" ) ?></h5>
                    </a></li>
                <li class="<?php echo lang ( "is_rtl" ) ? "pull-right" : "" ?>"><a href="#tabshowplaylists">
                        <h5 class="no-margin"><?php echo lang ( "playlists" ) ?></h5>
                    </a></li>
            </ul>
            <div class="tab-content vertical-scroll">
                <div class="tab-pane no-padding active" id="tabeditingshow">
                    <div class="tiles-body">

                        <h5><?php echo lang ( "general" ) ?> <span
                                    class="semi-bold"><?php echo lang ( "information" ) ?></span></h5>
                        <div class="control-group">
                            <div class="controls">
                                <input type="text" name="name" value="Untitled event" id="add_show_name" class="span12"
                                       placeholder="Name">
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <h5 class="pull-right flip"><?php echo lang ( "duration" ) ?> <span
                                    class="semi-bold">00:00:00</span></h5>
                        <input type="hidden" name="add_show_duration" id="add_show_duration" value="0">
                        <h5><?php echo lang ( "time" ) ?> <span
                                    class="semi-bold"><?php echo lang ( "information" ) ?></span></h5>
                        <div class="control-group no-margin">
                            <div class="controls">
                                <input type="text" name="startdate" id="add_show_start_date" class="span7"
                                       placeholder="Start Date"/>
                                <div class="bootstrap-timepicker timepicker span5 no-margin no-float"
                                     style="display: inline-block;">
                                    <input type="text" name="starttime" id="add_show_start_time"
                                           class="timepicker-24 span12" placeholder="Start Time"/>
                                </div>
                            </div>
                        </div>
                        <div class="control-group no-margin">
                            <div class="controls">
                                <input type="text" name="enddate" id="add_show_end_date_no_repeat" class="span7"
                                       placeholder="End Date"/>
                                <div class="bootstrap-timepicker timepicker span5 no-margin no-float"
                                     style="display: inline-block;">
                                    <input type="text" name="endtime" id="add_show_end_time"
                                           class="timepicker-24 span12" placeholder="End Time"/>
                                </div>
                            </div>
                        </div>


                        <h5><?php echo lang ( "repeat" ) ?> <span
                                    class="semi-bold"><?php echo lang ( "information" ) ?></span></h5>
                        <div class="control-group no-margin">
                            <div class="controls">
                                <select name="repeat_type" class="span12 input_select">
                                    <option value="-1"><?php echo lang ( "none" ) ?></option>
                                    <option value="0"><?php echo lang ( "weekly" ) ?></option>
                                    <option value="1"><?php echo lang ( "every_2_weeks" ) ?></option>
                                    <option value="4"><?php echo lang ( "every_3_weeks" ) ?></option>
                                    <option value="5"><?php echo lang ( "every_4_weeks" ) ?></option>
                                    <option value="2"><?php echo lang ( "monthly" ) ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group no-margin" id="add_show_day_check" style="display: none">
                            <div class="controls">
                                <?php $days = lang ( "days_short_array" ); ?>
                                <?php foreach ( $days as $key => $day ): ?>
                                    <div class="checkbox check-primary no-margin">
                                        <input type="checkbox" id="chbx_d_0<?php echo $key ?>"
                                               name="add_show_day_check[]" value="<?php echo $key ?>">
                                        <label class=" no-margin"
                                               for="chbx_d_0<?php echo $key ?>"><?php echo $day ?></label>
                                    </div>
                                <?php endforeach ?>
                            </div>
                        </div>

                        <div class="control-group no-margin" id="add_show_end" style="display: none">
                            <div class="controls row-fluid">
                                <div class="span6">
                                    <input type="text" disabled="disabled" id="add_show_end_date"
                                           name="add_show_end_date" class="span12" placeholder="Date End"/>
                                </div>
                                <div class="span6">
                                    <div class="checkbox check-primary" style="line-height: 40px;">
                                        <input type="checkbox" id="chbx_d_no_end" name="add_show_no_end" value="0"
                                               checked>
                                        <label class=" no-margin" for="chbx_d_no_end"><?php echo lang ( "no_end" ) ?>
                                            ?</label>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <h5><?php echo lang ( "style" ) ?> <span
                                    class="semi-bold"><?php echo lang ( "information" ) ?></span></h5>
                        <div class="control-group no-margin">
                            <div class="controls">
                                <label class="span6 text-label"
                                       for="add_show_background_color"><?php echo lang ( "background_color" ) ?></label>
                                <div class="input-append span5 colorpicker-component colorchooser" data-color="#ffcc00"
                                     data-colorpicker-guid="8">
                                    <input name="background_color" type="text" id="add_show_background_color"
                                           class="span10 no-margin" value="#ffcc00"/>
                                    <span class="add-on span2 no-margin no-float" style="height: 34px;"><i
                                                class="icon-tint"></i></span>
                                </div>
                            </div>
                            <div class="controls">
                                <label class="span6 text-label"
                                       for="add_show_color"><?php echo lang ( "text_color" ) ?></label>
                                <div class="input-append span5 colorpicker-component colorchooser" data-color="#000000"
                                     data-colorpicker-guid="8">
                                    <input name="color" type="text" id="add_show_color" class="span10 no-margin"
                                           value="#000000"/>
                                    <span class="add-on span2 no-margin no-float" style="height: 34px;"><i
                                                class="icon-tint"></i></span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="tab-pane" id="tabshowplaylists" style="height: 100%; box-sizing: border-box;">
                    <!-- TAP PLAYLISTS -->
                    <button type="button" class="btn btn-small btn-primary tip "
                            data-placement="<?php echo lang ( "is_rtl" ) ? "left" : "right" ?>" id="select-playlist"
                            style="margin-left:5px" title="<?php echo lang ( "select_playlist" ) ?>"><i
                                class="icon-plus"></i></button>
                    <table id="calendar_playlists" class="table table-hover table-condensed table-small minheight300"
                           style="max-height: inherit;">
                        <thead>
                        <tr>
                            <th valign="middle" class="text-center" style="min-width: 16px;">
                                <i class="icon-move"></i>
                            </th>
                            <th width="100%"><?php echo lang ( "title" ) ?></th>
                            <th><?php echo lang ( "start" ) ?></th>
                            <th><?php echo lang ( "end" ) ?></th>
                            <th><?php echo lang ( "duration" ) ?></th>
                            <th valign="middle" class="text-center" style="min-width: 16px;">
                                <i class="icon-trash delete-all-calpl"></i>
                            </th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                        <tr>
                            <td><i class="icon-info"></i></td>
                            <td colspan="5"></td>
                        </tr>
                        </tfoot>
                    </table>
                    <!-- TAP PLAYLISTS -->
                </div>
            </div>

        </form>
        <!-- FORM EDITING SHOW -->
    </div>

    <div class="span12 tiles white no-margin" id="calendar-tile">
        <div class="tiles-body">
            <div id='calendar'></div>
        </div>
    </div>
</div>
<?php
$tz = timezone_open ( $settings->time_zone );
$dateTimeOslo = date_create ( "now" , timezone_open ( "Europe/Oslo" ) );
?>
<script type="text/javascript">
    var calendarPref = {};
    calendarPref.weekStart = <?php echo $settings->weekstart ?>;
    calendarPref.timestamp = <?php echo time () ?>;
    calendarPref.timezoneOffset = <?php echo timezone_offset_get ( $tz , $dateTimeOslo ) ?>;
    calendarPref.timeScale = 'month';
    calendarPref.timeInterval = 30;
    calendarPref.weekStartDay = <?php echo $settings->weekstart ?>;
    var currentTimezone = "<?php echo $settings->time_zone ?>";
    var timezoneOffset = <?php echo timezone_offset_get ( $tz , $dateTimeOslo ) ?>;
    var i18n_months = <?php echo json_encode ( lang ( "months_array" ) ) ?> ;
    var i18n_months_short = <?php echo json_encode ( lang ( "months_short_array" ) ) ?> ;
    var i18n_days_short = <?php echo json_encode ( lang ( "days_short_array" ) ) ?> ;
    var i18n_days_min = <?php echo json_encode ( lang ( "days_min_array" ) ) ?> ;
    var i18n_days = <?php echo json_encode ( lang ( "days_array" ) ) ?> ;
</script>
<script src="<?= base_url (); ?>assets/js/calender.js" type="text/javascript"></script>
