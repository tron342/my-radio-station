<?php defined ( 'BASEPATH' ) OR exit( 'No direct script access allowed' );
/**
 * Name:         My Radio Station
 * Version :     1.0
 * Author:       Zitouni Bessem
 * Requirements: PHP5 or above
 *
 */

class Track_model extends CI_Model
{
    var $table = "track";
    var $column_order = array (
        null ,
        null ,
        'title' ,
        'artist' ,
        'album' ,
        'length' ,
        'bitrate' ,
        'mime' ,
        'genre' ,
        'samplerate' ,
        'channels' ,
        'filesize' ,
        'upload_date'
    );
    var $column_search = array (
        'title' ,
        'artist' ,
        'album' ,
        'length' ,
        'bitrate' ,
        'mime' ,
        'genre' ,
        'samplerate' ,
        'channels' ,
        'audiostart' ,
        'audioend' ,
        'audiolength' ,
        'filename' ,
        'filesize' ,
        'upload_date'
    );
    var $order = array ( 'id' => 'asc' );

    public function __construct ()
    {
        parent::__construct ();
    }

    public function getAll ()
    {
        $q = $this->db->get ( $this->table );
        if ( $q->num_rows () > 0 ) {
            return $q->result_array ();
        }
        return array ();
    }

    public function count ()
    {
        return $this->db->count_all ( $this->table );
    }

    public function getByID ($id)
    {
        $q = $this->db->get_where ( $this->table , array ( 'id' => $id ) , 1 );
        if ( $q->num_rows () > 0 ) {
            return $q->row ();
        }
        return false;
    }

    public function getByColumn ($column , $value)
    {
        $q = $this->db->get_where ( $this->table , array ( $column => $value ) );
        if ( $q->num_rows () > 0 ) {
            return $q->result ();
        }
        return false;
    }

    public function add ($data = array ())
    {
        if ( $this->db->insert ( $this->table , $data ) ) {
            $id = $this->db->insert_id ();
            return $id;
        }
        return false;
    }

    public function update ($id , $data = array ())
    {
        if ( $this->db->where ( 'id' , $id )->update ( $this->table , $data ) ) {
            return $id;
        }
        return false;
    }

    public function delete ($id)
    {
        $track = $this->getByID ( $id );

        if ( $this->db->where ( 'id' , $id )->delete ( $this->table ) ) {
            unlink ( $track->url );
            return true;
        }
        return false;
    }

    private function _get_datatables_query ()
    {
        $this->db->from ( $this->table );
        $i = 0;
        foreach ( $this->column_search as $item ) // loop column
        {
            if ( isset( $_POST[ 'search' ] ) && $_POST[ 'search' ][ 'value' ] ) // if datatable send POST for search
            {
                if ( $i === 0 ) // first loop
                {
                    $this->db->group_start (); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like ( $item , $_POST[ 'search' ][ 'value' ] );
                } else {
                    $this->db->or_like ( $item , $_POST[ 'search' ][ 'value' ] );
                }
                if ( count ( $this->column_search ) - 1 == $i ) //last loop
                {
                    $this->db->group_end ();
                } //close bracket
            }
            $i ++;
        }
        if ( isset( $_POST[ 'order' ] ) ) // here order processing
        {
            $this->db->order_by ( $this->column_order[ $_POST[ 'order' ][ '0' ][ 'column' ] ] ,
                $_POST[ 'order' ][ '0' ][ 'dir' ] );
        } else {
            if ( isset( $this->order ) ) {
                $order = $this->order;
                $this->db->order_by ( key ( $order ) , $order[ key ( $order ) ] );
            }
        }
    }

    function get_datatables ()
    {
        $this->_get_datatables_query ();
        if ( isset( $_POST[ 'length' ] ) && $_POST[ 'length' ] != - 1 ) {
            $this->db->limit ( $_POST[ 'length' ] , $_POST[ 'start' ] );
        }
        $query = $this->db->get ();
        return $query->result ();
    }

    function count_filtered ()
    {
        $this->_get_datatables_query ();
        $query = $this->db->get ();
        return $query->num_rows ();
    }

    public function count_all ()
    {
        $this->db->from ( $this->table );
        return $this->db->count_all_results ();
    }
}

/* End of file track_model.php */
/* Location: ./application/models/track_model.php */