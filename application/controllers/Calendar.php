<?php
/**
 * Name:         My Radio Station
 * Version :     1.0
 * Author:       Zitouni Bessem
 * Requirements: PHP5 or above
 *
 */
defined ( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

class Calendar extends MY_Controller
{

    /**
     * Calendar constructor.
     */
    public function __construct ()
    {
        parent::__construct ();
        // Load Calendar Model
        $this->load->model ( 'calendar_model' );
        // Load Form Validation Library
        $this->load->library ( 'form_validation' );
        // Load Ion Auth Library
        $this->load->library ( 'ion_auth' );
        // Check user is logged in ?
        if ( !$this->ion_auth->logged_in () ) {
            redirect ( 'auth/login' , 'refresh' );
        }
    }

    public function index ()
    {
        $data = array ();
        $meta[ 'datatables' ] = true;
        $meta[ 'calendar_plugin' ] = true;
        $meta[ 'datepicker' ] = true;
        $data[ 'settings' ] = $this->settings_model->_Settings;
        $meta[ 'page_title' ] = $this->lang->line ( "calendars" );
        $this->load->view ( 'templates/head' , $meta );
        $this->load->view ( 'templates/header' );
        $this->load->view ( 'calendar' , $data );
        $this->load->view ( 'select_playlists' , $data );
        $this->load->view ( 'templates/footer' , $meta );
    }

    function add ()
    {
        $this->form_validation->set_rules('name', $this->lang->line ( "name" ), 'required|xss_clean');
        $this->form_validation->set_rules('startdate', $this->lang->line ( "startdate" ), 'required|xss_clean');
        $this->form_validation->set_rules('starttime', $this->lang->line ( "starttime" ), 'required|xss_clean');
        $this->form_validation->set_rules('enddate', $this->lang->line ( "enddate" ), 'required|xss_clean');
        $this->form_validation->set_rules('endtime', $this->lang->line ( "endtime" ), 'required|xss_clean');
        if ( $this->form_validation->run () == true ) {
            $data = array (
                "id" => null ,
                "name" => $this->input->post ( "name" ) ,
                "starts" => $this->input->post ( "startdate" ) . " " . $this->input->post ( "starttime" ) ,
                "ends" => $this->input->post ( "enddate" ) . " " . $this->input->post ( "endtime" ) ,
                "duration" => $this->input->post ( "add_show_duration" ) ,
            );
            if ( isset( $_POST[ 'repeat_type' ] ) ) {
                $data[ "repeat_type" ] = $this->input->post ( "repeat_type" );
            } else {
                $data[ "repeat_type" ] = "-1";
            }
            if ( $this->input->post ( "background_color" ) ) {
                $data[ "background_color" ] = $this->input->post ( "background_color" );
            } else {
                $data[ "background_color" ] = "#ffcc00";
            }
            if ( $this->input->post ( "color" ) ) {
                $data[ "color" ] = $this->input->post ( "color" );
            } else {
                $data[ "color" ] = "#000000";
            }

            if ( $data[ "repeat_type" ] == "-1" ) { // no repeat
                $data[ "repeat_days" ] = null;
                $data[ "no_end" ] = null;
                $data[ "end_date" ] = null;
            } elseif ( $data[ "repeat_type" ] == "2" ) { // monthly
                $data[ "repeat_days" ] = null;
                if ( !isset( $_POST[ 'add_show_no_end' ] ) ) {
                    $data[ "end_date" ] = $this->input->post ( "add_show_end_date" );
                    $data[ "no_end" ] = false;
                } else {
                    $data[ "no_end" ] = true;
                    $data[ "end_date" ] = null;
                }
            } else { // week
                if ( isset( $_POST[ 'add_show_day_check' ] ) ) {
                    $data[ "repeat_days" ] = implode ( "," , $this->input->post ( "add_show_day_check" ) );
                } else {
                    $data[ "repeat_days" ] = null;
                }
                if ( !isset( $_POST[ 'add_show_no_end' ] ) ) {
                    $data[ "end_date" ] = $this->input->post ( "add_show_end_date" );
                    $data[ "no_end" ] = false;
                } else {
                    $data[ "no_end" ] = true;
                    $data[ "end_date" ] = null;
                }
            }

            $data_playlists = array ();
            $playlist_ids = isset( $_POST[ 'playlist_id' ] ) ? $this->input->post ( 'playlist_id' ) : array ();
            $time_starts = $this->input->post ( 'time_start' );
            $time_ends = $this->input->post ( 'time_end' );
            foreach ( $playlist_ids as $key => $value ) {
                $data_playlists[] = array (
                    'id' => null ,
                    'playlist_id' => $value ,
                    'time_start' => $time_starts[ $key ] ,
                    'time_end' => $time_ends[ $key ] ,
                );
            }
        }
        if ( $this->form_validation->run () == true && $id = $this->calendar_model->add ( $data , $data_playlists ) ) {
            //output to json format
            echo json_encode ( array ( "status" => "success" , "message" => $id ) );
        } else {
            //output to json format
            echo json_encode ( array ( "status" => "error" , "message" => validation_errors () ) );
        }
    }

    function edit ()
    {
        if ( $this->input->get ( 'id' ) ) {
            $id = $this->input->get ( 'id' );
        }
        $this->form_validation->set_rules ( 'name' , $this->lang->line ( "name" ) , 'required|xss_clean' );
        $this->form_validation->set_rules ( 'startdate' , $this->lang->line ( "startdate" ) , 'required|xss_clean' );
        $this->form_validation->set_rules ( 'starttime' , $this->lang->line ( "starttime" ) , 'required|xss_clean' );
        $this->form_validation->set_rules ( 'enddate' , $this->lang->line ( "enddate" ) , 'required|xss_clean' );
        $this->form_validation->set_rules ( 'endtime' , $this->lang->line ( "endtime" ) , 'required|xss_clean' );
        if ( $this->input->post ( "repeat_type" ) != - 1 && !isset( $_POST[ 'add_show_no_end' ] ) ) {
            $this->form_validation->set_rules ( 'add_show_end_date' , $this->lang->line ( "enddate" ) ,
                'required|xss_clean' );
        }
        if ( $this->form_validation->run () == true ) {

            $data = array (
                "name" => $this->input->post ( "name" ) ,
                "starts" => $this->input->post ( "startdate" ) . " " . $this->input->post ( "starttime" ) ,
                "ends" => $this->input->post ( "enddate" ) . " " . $this->input->post ( "endtime" ) ,
                "duration" => $this->input->post ( "add_show_duration" ) ,
            );
            if ( isset( $_POST[ 'repeat_type' ] ) ) {
                $data[ "repeat_type" ] = $this->input->post ( "repeat_type" );
            } else {
                $data[ "repeat_type" ] = "-1";
            }
            if ( $this->input->post ( "background_color" ) ) {
                $data[ "background_color" ] = $this->input->post ( "background_color" );
            } else {
                $data[ "background_color" ] = "#ffcc00";
            }
            if ( $this->input->post ( "color" ) ) {
                $data[ "color" ] = $this->input->post ( "color" );
            } else {
                $data[ "color" ] = "#000000";
            }

            if ( $data[ "repeat_type" ] == "-1" ) { // no repeat
                $data[ "repeat_days" ] = null;
                $data[ "no_end" ] = null;
                $data[ "end_date" ] = null;
            } elseif ( $data[ "repeat_type" ] == "2" ) { // monthly
                $data[ "repeat_days" ] = null;
                if ( !isset( $_POST[ 'add_show_no_end' ] ) ) {
                    $data[ "end_date" ] = $this->input->post ( "add_show_end_date" );
                    $data[ "no_end" ] = false;
                } else {
                    $data[ "no_end" ] = true;
                    $data[ "end_date" ] = null;
                }
            } else { // week
                if ( isset( $_POST[ 'add_show_day_check' ] ) ) {
                    $data[ "repeat_days" ] = implode ( "," , $this->input->post ( "add_show_day_check" ) );
                } else {
                    $data[ "repeat_days" ] = date ( "w" , strtotime ( $this->input->post ( "startdate" ) ) );
                }
                if ( !isset( $_POST[ 'add_show_no_end' ] ) ) {
                    $data[ "end_date" ] = $this->input->post ( "add_show_end_date" );
                    $data[ "no_end" ] = false;
                } else {
                    $data[ "no_end" ] = true;
                    $data[ "end_date" ] = null;
                }
            }
            $data_playlists = array ();
            $playlist_ids = isset( $_POST[ 'playlist_id' ] ) ? $this->input->post ( 'playlist_id' ) : array ();
            $time_starts = $this->input->post ( 'time_start' );
            $time_ends = $this->input->post ( 'time_end' );
            foreach ( $playlist_ids as $key => $value ) {
                $data_playlists[] = array (
                    'id' => null ,
                    'playlist_id' => $value ,
                    'calendar_id' => $id ,
                    'time_start' => $time_starts[ $key ] ,
                    'time_end' => $time_ends[ $key ] ,
                );
            }
        }
        if ( $this->form_validation->run () == true && $this->calendar_model->update ( $id , $data ,
                $data_playlists )
        ) {
            //output to json format
            echo json_encode ( array ( "status" => "success" , "message" => $id ) );
        } else {
            //output to json format
            echo json_encode ( array ( "status" => "error" , "message" => validation_errors () ) );
        }
    }

    function delete ()
    {
        if ( $this->input->post ( 'id' ) ) {
            $id = $this->input->post ( 'id' );
        }
        if ( $this->input->post ( 'id' ) && $this->calendar_model->delete ( $id ) ) {
            //output to json format
            echo json_encode ( array ( "status" => "success" ) );
        } else {
            //output to json format
            echo json_encode ( array ( "status" => "error" , "message" => "AJAX Error" ) );
        }
    }

    public function eventsFeed ()
    {
        $start = $this->input->post ( "start" );
        $end = $this->input->post ( "end" );
        if ( $start && $end ) {
            //output to json format
            echo json_encode ( $this->calendar_model->events_feed ( $start , $end ) );
        } else {
            //output to json format
            echo json_encode ( array () );
        }
    }

    public function ajaxElement ()
    {
        if ( isset( $_POST[ 'id' ] ) ) {
            $output = $this->calendar_model->getByID ( $_POST[ 'id' ] );
            $output->playlists = $this->calendar_model->getPlaylistsByID ( $_POST[ 'id' ] );;
        } else {
            $output = array ( "status" => "error" , "message" => "AJAX Error" );
        }
        //output to json format
        echo json_encode ( $output );
    }

    public function moveShow ()
    {
        $day = $this->input->post ( "day" );
        $min = $this->input->post ( "min" );
        $id = $this->input->post ( "id" );

        $condition = isset( $_POST[ 'day' ] )
                    && isset( $_POST[ 'min' ] )
                    && isset( $_POST[ 'id' ] )
                    && is_int(intval($id))
                    && is_int(intval($min))
                    && is_int(intval($day));

        if ( $condition && $this->calendar_model->moveshow ( $id , $day , $min ) ) {
            //output to json format
            echo json_encode ( array ( "status" => "success" , "message" => $id ) );
        } else {
            //output to json format
            echo json_encode ( array ( "status" => "error" , "message" => "AJAX Error" ) );
        }
    }

    public function resizeShow ()
    {
        $day = $this->input->post ( "day" );
        $min = $this->input->post ( "min" );
        $id = $this->input->post ( "id" );

        $condition = isset( $_POST[ 'day' ] )
                    && isset( $_POST[ 'min' ] )
                    && isset( $_POST[ 'id' ] )
                    && is_int(intval($id))
                    && is_int(intval($min))
                    && is_int(intval($day));

        if ( $condition && $this->calendar_model->resizeshow( $id , $day , $min ) ) {
            //output to json format
            echo json_encode ( array ( "status" => "success" , "message" => $id ) );
        } else {
            //output to json format
            echo json_encode ( array ( "status" => "error" , "message" => "AJAX Error" ) );
        }
    }

}
