<?php
/**
 * Name:         My Radio Station
 * Version :     1.0
 * Author:       Zitouni Bessem
 * Requirements: PHP5 or above
 *
 */
defined ( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

class Playlist extends MY_Controller
{

    /**
     * Playlist constructor.
     */
    public function __construct ()
    {
        parent::__construct ();
        // Load Playlist Model
        $this->load->model ( 'playlist_model' );
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
        $data[ 'playlists' ] = $this->playlist_model->getAll ();
        $meta[ 'datatables' ] = true;
        $meta[ 'page_title' ] = $this->lang->line ( "playlists" );
        $data[ 'tracks_full' ] = false;
        $this->load->view ( 'templates/head' , $meta );
        $this->load->view ( 'templates/header' );
        $this->load->view ( 'list_tracks' , $data );
        $this->load->view ( 'playlists' , $data );
        $this->load->view ( 'templates/footer' , $meta );
    }

    public function play ()
    {
        if ( $this->input->get ( 'id' ) ) {
            $id = $this->input->get ( 'id' );
        }
        if ( $this->input->get ( 'id' ) && $tracks = $this->playlist_model->getTracksListByID ( $id ) ) {
            $data[ 'tracks' ] = $tracks;
            $this->load->view ( 'play_playlist' , $data );
        }
    }

    public function select ()
    {
        $data[ 'playlists' ] = $this->playlist_model->getAll ();
        $this->load->view ( 'select_playlists' , $data );
    }

    function add ()
    {
        $this->form_validation->set_rules ( 'name' , $this->lang->line ( "name" ) , 'required|xss_clean' );
        if ( $this->form_validation->run () == true ) {
            $data = array (
                'id' => null ,
                'name' => $this->input->post ( 'name' ) ,
                'description' => $this->input->post ( 'description' ) ,
                'audiolength' => $this->input->post ( 'audiolength' )
            );
            $data_tracks = array ();
            if( $this->input->post ( 'track_id' ) ){
                $time_starts = $this->input->post ( 'time_start' );
                $time_ends = $this->input->post ( 'time_end' );
                foreach ( $this->input->post ( 'track_id' ) as $key => $value ) {
                    $data_tracks[] = array (
                        'id' => null ,
                        'track_id' => $value ,
                        'time_start' => $time_starts[ $key ] ,
                        'time_end' => $time_ends[ $key ] ,
                    );
                }
            }
        }
        if ( $this->form_validation->run () == true && $id = $this->playlist_model->add ( $data , $data_tracks ) ) {
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
        if ( $this->form_validation->run () == true ) {
            $data = array (
                'name' => $this->input->post ( 'name' ) ,
                'description' => $this->input->post ( 'description' ) ,
                'audiolength' => $this->input->post ( 'audiolength' )
            );
            $data_tracks = array ();
            $time_starts = $this->input->post ( 'time_start' );
            $time_ends = $this->input->post ( 'time_end' );
            foreach ( $this->input->post ( 'track_id' ) as $key => $value ) {
                $data_tracks[] = array (
                    'id' => null ,
                    'track_id' => $value ,
                    'playlist_id' => $id ,
                    'time_start' => $time_starts[ $key ] ,
                    'time_end' => $time_ends[ $key ] ,
                );
            }
        }
        if ( $this->form_validation->run () == true && $this->playlist_model->update ( $id , $data , $data_tracks ) ) {
            //output to json format
            echo json_encode ( array ( "status" => "success" , "message" => $id ) );
        } else {
            //output to json format
            echo json_encode ( array ( "status" => "error" , "message" => "AJAX Error" ) );
        }
    }

    function delete ()
    {
        if ( $this->input->post ( 'id' ) ) {
            $id = $this->input->post ( 'id' );
        }
        if ( $this->input->post ( 'id' ) && $this->playlist_model->delete ( $id ) ) {
            //output to json format
            echo json_encode ( array ( "status" => "success" ) );
        } else {
            //output to json format
            echo json_encode ( array ( "status" => "error" , "message" => "AJAX Error" ) );
        }
    }

    public function ajaxList ()
    {
        $list = $this->playlist_model->get_datatables ();
        $data = array ();
        $no = isset( $_POST[ 'start' ] ) ? $_POST[ 'start' ] : 0;
        foreach ( $list as $playlist ) {
            $no ++;
            $row = array ();
            $row[] = $playlist->id;
            $row[] = "playlist";
            $row[] = $playlist->name;
            $row[] = $playlist->description;
            $row[] = $playlist->audiolength;
            $data[] = $row;
        }

        $output = array (
            "draw" => isset( $_POST[ 'start' ] ) ? $_POST[ 'draw' ] : "" ,
            "recordsTotal" => $this->playlist_model->count_all () ,
            "recordsFiltered" => $this->playlist_model->count_filtered () ,
            "data" => $data ,
        );
        //output to json format
        echo json_encode ( $output );
    }

    public function ajaxElement ()
    {
        if ( isset( $_POST[ 'id' ] ) ) {
            $output = $this->playlist_model->getByID ( $_POST[ 'id' ] );
            $output->tracks = $this->playlist_model->getTracksByID ( $_POST[ 'id' ] );;
        } else {
            $output = array ( "status" => "error" , "message" => "AJAX Error" );
        }
        //output to json format
        echo json_encode ( $output );
    }


}
