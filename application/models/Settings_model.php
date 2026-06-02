<?php defined ( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

/**
 * Name:         My Radio Station
 * Version :     1.0
 * Author:       Zitouni Bessem
 * Requirements: PHP5 or above
 *
 */
class Settings_model extends CI_Model
{
    var $table = "settings";
    public $_Settings = false;

    public function __construct ()
    {
        parent::__construct ();
        $this->_Settings = $this->getSettings ();
        $this->lang->load ( 'app' , $this->_Settings->language );
        date_default_timezone_set ( $this->_Settings->time_zone );
    }

    public function getSettings ()
    {
        $q = $this->db->get ( $this->table );
        if ( $q->num_rows () > 0 ) {
            return $q->row ();
        }
        return array ();
    }

    public function update ($data = array ())
    {
        if ( $this->db->update ( $this->table , $data ) ) {
            $this->_Settings = $this->getSettings ();
            $this->lang->load ( 'app' , $this->_Settings->language );
            date_default_timezone_set ( $this->_Settings->time_zone );
            return true;
        }
        return false;
    }
}

/* End of file track_model.php */
/* Location: ./application/models/track_model.php */