<div class="span6">
    <div class="grid simple">
        <div class="grid-title no-border">
            <h4><?php echo lang ( "settings" ) ?></h4>
        </div>
        <div class="grid-body no-border"><br>
            <?php echo form_open ( "settings" , array ( 'class' => 'form-login form-horizontal' ) ); ?>
            <div class="row-fluid">
                <div class="span12">
                    <?php if ( isset( $message ) ): ?>
                        <div class="alert alert-error"><?php echo $message; ?></div>
                    <?php endif ?>

                    <?php
                    $timezone_identifiers_list = timezone_identifiers_list ();
                    foreach ( $timezone_identifiers_list as $key => $value ) {
                        $zones[ $value ] = $value;
                    }
                    ?>
                    <div class="control-group">
                        <label class="control-label" for="time_zone"><?php echo lang ( "time_zone" ) ?></label>
                        <div class="controls">
                            <?php echo form_dropdown ( 'time_zone' , $zones , $settings->time_zone ,
                                array ( "class" => "span12" , "id" => "time_zone" ) ); ?>
                        </div>
                    </div>

                    <?php $langs = array ( 'english' => 'English' , 'arabic' => 'العربية' ); ?>
                    <div class="control-group">
                        <label class="control-label" for="language"><?php echo lang ( "language" ) ?></label>
                        <div class="controls">
                            <?php echo form_dropdown ( 'language' , $langs , $settings->language ,
                                array ( "class" => "span12" , "id" => "language" ) ); ?>
                        </div>
                    </div>

                    <?php $days = lang ( "days_array" ); ?>
                    <div class="control-group">
                        <label class="control-label" for="weekstart"><?php echo lang ( "weekstart" ) ?></label>
                        <div class="controls">
                            <?php echo form_dropdown ( 'weekstart' , $days , $settings->weekstart ,
                                array ( "class" => "span12" , "id" => "weekstart" ) ); ?>
                        </div>
                    </div>

                </div>
            </div>
            <div class="form-actions" style="margin-bottom: -31px !important;">
                <div class="pull-right flip">
                    <button type="submit" class="btn btn-success btn-cons"><?php echo lang ( 'save' ) ?></button>
                    <button type="button" class="btn btn-white btn-cons"><?php echo lang ( 'cancel' ) ?></button>
                </div>
            </div>
            <?php echo form_close (); ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("select").select2();
    });
</script>