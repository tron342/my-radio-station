<?php
/**
 * Name:         My Radio Station
 * Version :     1.0
 * Author:       Zitouni Bessem
 * Requirements: PHP5 or above
 *
 */
defined ( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

class Settings extends MY_Controller
{

    /**
     * Settings constructor.
     */
    public function __construct ()
    {
        parent::__construct ();
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
        $this->form_validation->set_rules ( 'time_zone' , $this->lang->line ( 'time_zone' ) , 'required|xss_clean' );
        $this->form_validation->set_rules ( 'language' , $this->lang->line ( 'language' ) , 'required|xss_clean' );
        $this->form_validation->set_rules ( 'weekstart' , $this->lang->line ( 'weekstart' ) , 'required|xss_clean' );

        if ( $this->form_validation->run () == true ) {
            $data = array (
                'time_zone' => $this->input->post ( 'time_zone' ) ,
                'language' => $this->input->post ( 'language' ) ,
                'weekstart' => $this->input->post ( 'weekstart' )
            );


        }
        if ( !($this->form_validation->run () == true && $this->settings_model->update ( $data )) ) {
            $data[ 'message' ] = (validation_errors ()) ? validation_errors () : $this->session->flashdata ( 'message' );
        }

        $data[ 'settings' ] = $this->settings_model->_Settings;
        $meta[ 'datepicker' ] = true;
        $meta[ 'page_title' ] = $this->lang->line ( "settings" );
        $this->load->view ( 'templates/head' , $meta );
        $this->load->view ( 'templates/header' );
        $this->load->view ( 'settings' , $data );
        $this->load->view ( 'templates/footer' , $meta );
    }

    public function sys_info ()
    {
        $meta[ 'page_title' ] = $this->lang->line ( "system_info" );
        $this->load->view ( 'templates/head' , $meta );
        $this->load->view ( 'templates/header' );
        $this->load->view ( 'sys_info' );
        $this->load->view ( 'templates/footer' , $meta );
    }
}
