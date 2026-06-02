<?php if ( $is_widget ): ?>
    <?php $this->load->view ( 'templates/head' ); ?>
<?php endif ?>
<?php
$js_data = array ();
foreach ( $rows as $key => $row_1 ) {
    $row = (array)$row_1;
    $row_start = strripos ( $row[ 'start' ] , "-" ) ? strtotime ( $row[ 'start' ] ) : $row[ 'start' ];
    $row_end = strripos ( $row[ 'end' ] , "-" ) ? strtotime ( $row[ 'end' ] ) : $row[ 'end' ];
    $js_data[ date ( 'Y-m-d' , $row_start ) ][] = array (
        "start" => date ( 'H:i' , $row_start ) ,
        "end" => date ( 'H:i' , $row_end ) ,
        "title" => $row[ 'title' ]
    );
}
?>
<?php if (!$is_widget): ?>
<div class="row-fluid">
    <div class="span6">
        <?php endif ?>
        <div class="week_calendar">
            <ul class="week_days">
                <?php
                $date = strtotime ( $start );
                while ($date < strtotime ( $end )) {
                    $is_active = date ( "Y-m-d" , $date ) == date ( "Y-m-d" ) ? "active" : "";
                    $text = date ( "w d" , $date );
                    $text = explode ( " " , $text );
                    $days_short = lang ( "days_short_array" );
                    $text = "<h3><small>" . $days_short[ $text[ 0 ] ] . "</small>" . $text[ 1 ] . "</h3>";
                    $id = date ( "D" , $date );
                    echo '<li class="' . $is_active . ' pull-left flip"><a href="#tab_' . $id . '">' . $text . '</a></li>';
                    $date += 3600 * 24;
                }
                ?>
            </ul>
            <div class="days_content">
                <?php
                $date = strtotime ( $start );
                while ($date < strtotime ( $end )) {
                    $da = date ( "Y-m-d" , $date );
                    $is_active = $da == date ( "Y-m-d" ) ? "active" : "";
                    $id = date ( "D" , $date );
                    ?>
                    <div class="tab-pane <?php echo $is_active; ?> minheight300 maxheight300 vertical-scroll"
                         id="<?php echo "tab_" . $id ?>">
                        <?php
                        if ( isset( $js_data[ $da ] ) ) {
                            foreach ( $js_data[ $da ] as $key => $data ) {
                                echo "<h5>" .
                                    "<small>" .
                                    $data[ 'start' ] . " - " . $data[ 'end' ] . " " .
                                    "</small>" .
                                    $data[ 'title' ] .
                                    "</h5>";
                            }
                        }
                        ?>
                    </div>
                    <?php $date += 3600 * 24; ?>
                <?php } ?>
            </div>
        </div>
        <?php if (!$is_widget): ?>
    </div>
    <div class="span6">
        <div class="grid simple">
            <div class="grid-title no-border text-center">
                <h4><?php echo lang ( "widget_code" ) ?></h4>
            </div>
            <div class="grid-body no-border minheight300">
                <p><?php echo lang ( "widget_description" ) ?></p>
                <div class="code-copy">
                    <button id="copy-button" class="btn btn-mini btn-danger"><i
                                class="icon-copy"></i> <?php echo lang ( "copy" ) ?></button>
                    <pre><code class="html minheight200"><?php
                            $url = site_url ( "/stream/week_calendar/" . $type . "/widget/" );
                            echo htmlentities ( '<iframe frameborder="0" width="100%" height="422" src="' . $url . '"></iframe>' );
                            ?></code></pre>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif ?>
<script type="text/javascript">
    $(document).ready(function () {
        $('.week_calendar .week_days a').on('click', function (e) {
            $('.week_calendar .week_days li.active').removeClass("active");
            $('.week_calendar .days_content .active').removeClass("active");
            $('.week_calendar .days_content').find(this.href.substr(this.href.lastIndexOf("#"))).addClass('active');
            $(this).parent('li').addClass('active');
            e.preventDefault();
            return false;
        });
    });
</script>