<?php
/**
 * Name:         My Radio Station
 * Version :     1.0
 * Author:       Zitouni Bessem
 * Requirements: PHP5 or above
 *
 */
defined ( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

class Track extends MY_Controller
{

    /**
     * Track constructor.
     */
    public function __construct ()
    {
        parent::__construct ();
        // Load Track Model
        $this->load->model ( 'track_model' );
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
        $data[ 'tracks' ] = $this->track_model->getAll ();
        $meta[ 'datatables' ] = true;
        $meta[ 'page_title' ] = $this->lang->line ( "tracks" );
        $data[ 'tracks_full' ] = true;
        $this->load->view ( 'templates/head' , $meta );
        $this->load->view ( 'templates/header' );
        $this->load->view ( 'list_tracks' , $data );
        $this->load->view ( 'templates/footer' , $meta );
    }

    public function play ()
    {
        if ( $this->input->get ( 'id' ) ) {
            $id = $this->input->get ( 'id' );
        }
        if ( $this->input->get ( 'id' ) && $track = $this->track_model->getByID ( $id ) ) {
            $data[ 'track' ] = $track;
            $this->load->view ( 'play_track' , $data );
        }
    }

    public function upload ()
    {
        $this->load->view ( 'templates/head' );
        $this->load->view ( 'templates/header' );
        $this->load->view ( 'upload_tracks' );
        $this->load->view ( 'templates/footer' );
    }

    public function do_upload ()
    {
        $this->load->library ( 'upload' );
        $config[ 'upload_path' ] = "uploads/";
        $config[ 'allowed_types' ] = 'mp3|wav';
        $config[ 'max_size' ] = 1024 * 1024 * 512;
        $config[ 'encrypt_name' ] = true;

        $this->upload->initialize ( $config );
        if ( !$this->upload->do_upload ( 'file' ) ) {
            $error = $this->upload->display_errors ();
            $error = array ( 'error' => $error );
            $this->output->set_output ( json_encode ( $error ) );
            return;
        }
        $upload_data = $this->upload->data ();
        $output_data = array ( 'upload_data' => $upload_data );


        $this->load->helper ( 'getid3' );
        $getID3 = loadgetid3 ();
        $id3 = $getID3->analyze ( $config[ 'upload_path' ] . $upload_data[ 'file_name' ] );

        $database_data = array (
            'title' => isset( $id3[ 'tags' ][ 'id3v2' ][ 'title' ] ) ? $id3[ 'tags' ][ 'id3v2' ][ 'title' ][ 0 ] : substr ( $upload_data[ 'client_name' ] ,
                0 , strripos ( $upload_data[ 'client_name' ] , "." ) ) ,
            'url' => $config[ 'upload_path' ] . $upload_data[ 'file_name' ] ,
            'artist' => isset( $id3[ 'tags' ][ 'id3v2' ][ 'artist' ] ) ? $id3[ 'tags' ][ 'id3v2' ][ 'artist' ][ 0 ] : "" ,
            'album' => isset( $id3[ 'tags' ][ 'id3v2' ][ 'album' ] ) ? $id3[ 'tags' ][ 'id3v2' ][ 'album' ][ 0 ] : "" ,
            'length' => $id3[ 'playtime_seconds' ] ,
            'mime' => $id3[ 'mime_type' ] ,
            'genre' => isset( $id3[ 'tags' ][ 'id3v2' ][ 'genre' ] ) ? $id3[ 'tags' ][ 'id3v2' ][ 'genre' ][ 0 ] : "" ,
            'bitrate' => $id3[ "audio" ][ "bitrate" ] / 1000 ,
            'samplerate' => $id3[ "audio" ][ "sample_rate" ] ,
            'channels' => $id3[ "audio" ][ "channels" ] ,
            'audiostart' => $id3[ "avdataoffset" ] ,
            'audioend' => $id3[ "avdataend" ] ,
            'audiolength' => $id3[ "avdataend" ] - $id3[ "avdataoffset" ] ,
            'filename' => $upload_data[ 'client_name' ] ,
            'filesize' => $upload_data[ 'file_size' ] ,
            'upload_date' => date ( "Y-m-d H:i:s" )
        );
        $this->track_model->add ( $database_data );

        //output to json format
        $this->output->set_output ( json_encode ( $output_data ) );
    }


    function delete ()
    {
        if ( $this->input->post ( 'id' ) ) {
            $id = $this->input->post ( 'id' );
        }
        if ( $this->input->post ( 'id' ) && $this->track_model->delete ( $id ) ) {
            //output to json format
            echo json_encode ( array ( "status" => "success" ) );
        } else {
            //output to json format
            echo json_encode ( array ( "status" => "error" , "message" => "AJAX Error" ) );
        }
    }

    public function ajax_list ()
    {
        $list = $this->track_model->get_datatables ();
        $data = array ();
        $no = isset( $_POST[ 'start' ] ) ? $_POST[ 'start' ] : 0;
        foreach ( $list as $track ) {
            $no ++;
            $row = array ();
            $row[] = $track->id;
            $row[] = "track";
            $row[] = $track->title;
            $row[] = $track->artist;
            $row[] = $track->album;
            $row[] = $track->length;
            $row[] = $track->bitrate;
            $row[] = $track->mime;
            $row[] = $track->genre;
            $row[] = $track->samplerate;
            $row[] = $track->channels;
            $row[] = $track->filesize;
            $row[] = $track->upload_date;
            $data[] = $row;
        }

        $output = array (
            "draw" => isset( $_POST[ 'start' ] ) ? $_POST[ 'draw' ] : "" ,
            "recordsTotal" => $this->track_model->count_all () ,
            "recordsFiltered" => $this->track_model->count_filtered () ,
            "data" => $data ,
        );
        //output to json format
        echo json_encode ( $output );
    }

}

/* End of file track.php */
/* Location: ./application/controllers/track.php */
