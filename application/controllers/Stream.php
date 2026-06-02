<?php
/**
 * Name:         My Radio Station
 * Version :     1.0
 * Author:       Zitouni Bessem
 * Requirements: PHP5 or above
 *
 */
defined ( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

class Stream extends MY_Controller
{

    /**
     * Stream constructor.
     */
    public function __construct ()
    {
        parent::__construct ();
        // Load PlayList Model
        $this->load->model ( 'playlist_model' );
        // Load Calendar Model
        $this->load->model ( 'calendar_model' );
        // Remove temp files
        $this->clean_old ();
    }

    /**
     * Start streaming
     */
    public function index ()
    {
        $settings = $this->settings_model->_Settings;
        $start = date ( "Y-m-d H:i:s" );
        $end = date ( "Y-m-d H:i:s" , strtotime ( $start . ' +1 hour' ) );
        $playfiles = $this->playlist_model->play ( $start , $end );

        ignore_user_abort ( true );
        $now = time ();
        $cache = "webstream/";
        $instance = $cache . "$now.php";
        $data = var_export ( $playfiles , true );
        $data = str_replace ( "stdClass::__set_state" , "(object)" , $data );
        $data = str_replace ( "die();" , "unlink(__FILE__);" , $data );
        $content = file_get_contents ( "application/libraries/Webstream.php" ) . "\n";
        $content .= "\$playfiles = " . $data . ";\n";
        $content .= "\$base_url = \"../\";\n";
        if ( isset( $_GET[ 'debug' ] ) ) {
            $content .= "\$debug = 1;\n";
        } else {
            $content .= "\$debug = 0;\n";
        }
        $content .= "date_default_timezone_set('" . $settings->time_zone . "');\n";
        $content .= "\$wp = new Webstream(\$playfiles, \$base_url, \$debug);\n";
        $content .= "\$wp->startStream();\n";
        file_put_contents ( $instance , $content );
        $instance = base_url () . $instance;
        //Redirect to stream file
        header ( "location: $instance" );
        return;
    }


    /**
     * Remove unused stream files
     */
    private function clean_old ()
    {
        $webstreams = "webstream/";
        $files = glob ( $webstreams . '*' ); // get all webstream file names
        foreach ( $files as $file ) { // iterate files
            if ( is_file ( $file ) ) {
                if ( $file != $webstreams . "index.php" ) {
                    $filename = substr ( $file , strlen ( $webstreams ) , strlen ( $file ) - 4 ); // remove webstreams
                    $filename = substr ( $filename , 0 , strlen ( $filename ) - 4 ); // remove extension
                    if ( time () - intval ( $filename ) > 3600 ) { // after 1 hour
                        unlink ( $file ); // delete file
                    }
                }
            }
        }
    }

    /**
      * load streaming data for 1 hour
      */
    public function data ()
    {
        $start = date ( "Y-m-d H:i:s" );
        $end = date ( "Y-m-d H:i:s" , strtotime ( $start . ' +1 hour' ) );
        $playfiles = $this->playlist_model->play ( $start , $end );
        $now = (count ( $playfiles ) > 0) ? $playfiles[ 0 ]->title : "";
        $next = (count ( $playfiles ) > 1) ? $playfiles[ 1 ]->title : "";
        $progress = array ( "percent" => 0 );
        if ( count ( $playfiles ) > 0 ) {
            $progress = array (
                "start_str" => date ( "Y-m-d H:i:s" , $playfiles[ 0 ]->start ) ,
                "end_str" => date ( "Y-m-d H:i:s" , $playfiles[ 0 ]->end ) ,
                "on_str" => date ( "Y-m-d H:i:s" , $playfiles[ 0 ]->on ) ,
                "next" => $playfiles[ 0 ]->end - $playfiles[ 0 ]->on ,
                "start" => $playfiles[ 0 ]->start ,
                "end" => $playfiles[ 0 ]->end ,
                "on" => $playfiles[ 0 ]->on ,
                "percent" => (($playfiles[ 0 ]->on - $playfiles[ 0 ]->start) * 100) / ($playfiles[ 0 ]->end - $playfiles[ 0 ]->start) ,
            );
        }
        $output = array (
            "now" => $now ,
            "next" => $next ,
            "progress" => $progress
        );
        header ( 'Access-Control-Allow-Headers:Origin, Accept, X-Requested-With, Content-Type' );
        header ( 'Access-Control-Allow-Origin: *' );
        header ( 'Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS, HEAD' );
        header ( 'Content-Type: application/json' );
        echo json_encode ( $output );
    }

    /**
      * live player (widget)
      */
    public function player ($is_widget = false)
    {
        $data[ 'is_widget' ] = $is_widget;
        if ( $is_widget ) {
            $this->load->view ( 'stream' , $data );
        } else {
            $meta[ 'code_copy' ] = true;
            $this->load->view ( 'templates/head' , $meta );
            $this->load->view ( 'templates/header' );
            $this->load->view ( 'stream_view' , $data );
            $this->load->view ( 'templates/footer' , $meta );
        }
    }


    /**
      * Week calendar events/tracks (widget)
      */
    public function week_calendar ($type = "events" , $is_widget = false)
    {
        $settings = $this->settings_model->_Settings;
        $dowMap = array ( 'Sunday' , 'Monday' , 'Tuesday' , 'Wednesday' , 'Thursday' , 'Friday' , 'Saturday' );
        $today = date ( "w" );
        if ( $today == $settings->weekstart ) {
            $start = date ( "Y-m-d 00:00:00" );
        } else {
            $start = date ( "Y-m-d H:i:s" , strtotime ( 'previous ' . $dowMap[ $settings->weekstart ] ) );
        }
        $end = date ( "Y-m-d H:i:s" , strtotime ( $start . ' +1 week' ) );
        if ( $type == "events" ) {
            $data[ 'rows' ] = $this->calendar_model->events_feed ( $start , $end );
        } else {
            $data[ 'rows' ] = $this->playlist_model->play ( $start , $end , false );
        }
        $data[ 'start' ] = $start;
        $data[ 'end' ] = $end;
        $data[ 'is_widget' ] = $is_widget;
        $meta[ 'code_copy' ] = true;
        $data[ 'type' ] = $type;
        if ( $is_widget ) {
            $this->load->view ( 'week_calendar' , $data );
        } else {
            $this->load->view ( 'templates/head' , $meta );
            $this->load->view ( 'templates/header' );
            $this->load->view ( 'week_calendar' , $data );
            $this->load->view ( 'templates/footer' , $meta );
        }
    }


    /**
      * Week calendar events/tracks data
      * Output JSON FORMAT
      */
    public function get_week_calendar ($type = "events")
    {
        $settings = $this->settings_model->_Settings;
        $dowMap = array ( 'Sunday' , 'Monday' , 'Tuesday' , 'Wednesday' , 'Thursday' , 'Friday' , 'Saturday' );
        $today = date ( "w" );
        if ( $today == $settings->weekstart ) {
            $start = date ( "Y-m-d 00:00:00" );
        } else {
            $start = date ( "Y-m-d H:i:s" , strtotime ( 'previous ' . $dowMap[ $settings->weekstart ] ) );
        }
        $end = date ( "Y-m-d H:i:s" , strtotime ( $start . ' +1 week' ) );
        if ( $type == "events" ) {
            $rows = $this->calendar_model->events_feed ( $start , $end );
        } else {
            $rows = $this->playlist_model->play ( $start , $end , false );
        }
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

        $date = strtotime ( $start );
        $output = array ();
        while ($date < strtotime ( $end )) {
            $da = date ( "Y-m-d" , $date );
            $active = $da == date ( "Y-m-d" );
            $day = date ( "w" , $date );
            $value = "";
            if ( isset( $js_data[ $da ] ) ) {
                foreach ( $js_data[ $da ] as $key => $data ) {
                    $value .= "<h5>" .
                        "<small>" . $data[ 'start' ] . " - " . $data[ 'end' ] . " " . "</small>" .
                        $data[ 'title' ] .
                        "</h5>";
                }
            }
            $output[] = array (
                "date" => $da ,
                "day" => $day ,
                "active" => $active ,
                "value" => htmlentities ( $value )
            );
            $date += 3600 * 24;
        }
        header ( 'Access-Control-Allow-Headers:Origin, Accept, X-Requested-With, Content-Type' );
        header ( 'Access-Control-Allow-Origin: *' );
        header ( 'Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS, HEAD' );
        header ( 'Content-Type: application/json' );
        $callback = isset( $_GET[ 'callback' ] ) ? $_GET[ 'callback' ] : false;
        if ( $callback ) {
            //output to json format
            echo $callback . "(" . json_encode ( $output ) . ")";
        } else {
            //output to json format
            echo json_encode ( $output );
        }
    }
}
