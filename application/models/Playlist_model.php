<?php defined ( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

/**
 * Name:         My Radio Station
 * Version :     1.0
 * Author:       Zitouni Bessem
 * Requirements: PHP5 or above
 *
 */
class Playlist_model extends CI_Model
{
    var $table = "playlist";
    var $column_order = array ( null , null , 'name' , 'description' , 'audiolength' );
    var $column_search = array ( 'name' , 'description' , 'audiolength' );
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

    public function getTracksListByID ($id)
    {
        $q = $this->db->select ( 'track.*' )
            ->from ( 'track_playlist' )
            ->join ( 'track' , 'track.id=track_playlist.track_id' , 'LEFT' )
            ->where ( 'track_playlist.playlist_id' , $id )
            ->get ();
        if ( $q->num_rows () > 0 ) {
            return $q->result ();
        }
        return array ();
    }


    public function getTracksByID ($id)
    {
        $q = $this->db->select ( 'track_playlist.*, track.title' )
            ->from ( 'track_playlist' )
            ->join ( 'track' , 'track.id=track_playlist.track_id' , 'LEFT' )
            ->where ( 'playlist_id' , $id )
            ->get ();
        if ( $q->num_rows () > 0 ) {
            return $q->result ();
        }
        return array ();
    }

    public function getByColumn ($column , $value)
    {
        $q = $this->db->get_where ( $this->table , array ( $column => $value ) );
        if ( $q->num_rows () > 0 ) {
            return $q->result ();
        }
        return false;
    }

    public function add ($data = array () , $data_tracks = array ())
    {
        if ( $this->db->insert ( $this->table , $data ) ) {
            $id = $this->db->insert_id ();
            foreach ( $data_tracks as $i => $row ) {
                $data_tracks[ $i ][ 'playlist_id' ] = $id;
            }
            if( !empty($data_tracks) )
                $this->db->insert_batch ( 'track_playlist' , $data_tracks );
            return $id;
        }
        return false;
    }

    public function update ($id , $data = array () , $data_tracks = array ())
    {
        if ( $this->db->where ( 'id' , $id )->update ( $this->table , $data ) ) {
            $this->db->where ( 'playlist_id' , $id )->delete ( 'track_playlist' );
            if( !empty($data_tracks) )
                $this->db->insert_batch ( 'track_playlist' , $data_tracks );
            return $id;
        }
        return false;
    }

    public function delete ($id)
    {
        if ( $this->db->where ( 'id' , $id )->delete ( $this->table ) ) {
            $this->db->where ( 'playlist_id' , $id )->delete ( 'track_playlist' );
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


    public function event_to_playlists ($event)
    {

        $this->db->from ( $this->table );
        return $this->db->count_all_results ();
    }

    public function play ($start , $end , $repeat_off = true)
    {
        $this->load->model ( 'playlist_model' );
        $this->load->model ( 'calendar_model' );
        $output = array ();
        if ( $start && $end ) {
            $start_sql = "(UNIX_TIMESTAMP(si1.starts) + playlist_calendar.time_start + track_playlist.time_start)";
            $end_sql = "(UNIX_TIMESTAMP(si1.starts) + playlist_calendar.time_start + track_playlist.time_end)";
            $subQuery = $this->calendar_model->select_events ( $start , $end );
            $selection = "track.id                        AS `id`,
						  track.title                     AS `title`,
						  track.url                       AS `url`,
						  track.artist                    AS `artist`,
						  track.album                     AS `album`,
						  track.length                    AS `length`,
						  track.mime                      AS `mime`,
						  track.genre                     AS `genre`,
						  track.bitrate                   AS `bitrate`,
						  track.samplerate                AS `samplerate`,
						  track.channels                  AS `channels`,
						  track.audiostart                AS `audiostart`,
						  track.audioend                  AS `audioend`,
						  track.audiolength               AS `audiolength`,
						  track.filename                  AS `filename`,
						  track.filesize                  AS `filesize`,
						  track.upload_date               AS `upload_date`,
						  track.length                    AS `playtime`,
						  $start_sql                      AS `start`,
						  $end_sql                        AS `end`,
						  UNIX_TIMESTAMP('" . $start . "')    AS `on`,
						  '" . $start . "'                    AS `on_str`,
						  FROM_UNIXTIME($start_sql)       AS `start_str`,
						  FROM_UNIXTIME($end_sql)         AS `end_str`,
						  calendar.name                   AS `event`,
						  playlist.name                   AS `playlist`, ";
            $where_clause = "(
				(si1.starts >= '$start' AND si1.starts < '$end')
			    OR (si1.ends > '$start' AND si1.ends <= '$end')
			    OR (si1.starts <= '$start' AND si1.ends >= '$end')
		    )";
            $where_clause_1 = "(
				($start_sql >= UNIX_TIMESTAMP('$start') AND $start_sql < UNIX_TIMESTAMP('$end'))
			    OR ($end_sql > UNIX_TIMESTAMP('$start') AND $end_sql <= UNIX_TIMESTAMP('$end'))
			    OR ($start_sql <= UNIX_TIMESTAMP('$start') AND $end_sql >= UNIX_TIMESTAMP('$end'))
		    )";
            $q = $this->db->select ( $selection )
                ->from ( "($subQuery) AS si1" )
                ->join ( 'calendar' , 'calendar.id=si1.id_calendar' , 'left' )
                ->join ( 'playlist_calendar' , 'playlist_calendar.calendar_id=calendar.id' , 'left' )
                ->join ( 'playlist' , 'playlist.id=playlist_calendar.playlist_id' , 'left' )
                ->join ( 'track_playlist' , 'track_playlist.playlist_id=playlist.id' , 'left' )
                ->join ( 'track' , 'track.id=track_playlist.track_id' , 'left' )
                ->where ( $where_clause )
                ->where ( $where_clause_1 )
                ->order_by ( 'start' )
                //->limit(5)
                ->get ();
            //echo $this->db->last_query();die();
            if ( $q->num_rows () > 0 ) {
                $output = $q->result ();
            }
        }
        return $this->fill_gabs ( $output , $start , $end , $repeat_off );
    }

    function create_off_gab ($start , $end)
    {
        $track = new StdClass();
        $track->title = "OFF";
        $track->url = "assets/sounds/blank.mp3";//blank
        $track->start = $start;
        $track->end = $end;
        $track->playtime = 10.031020408163;// $track->end - $track->start;
        $track->audiostart = 29;//0;
        $track->audioend = 40173;
        $track->playtime * 16000;
        $track->audiolength = 40144;
        $track->playtime * 16000;
        $track->on = $start;
        $track->playtime_str = date ( 'H:i:s' , $track->playtime );
        $track->on_str = date ( 'Y-m-d H:i:s' , $track->on );
        $track->start_str = date ( 'Y-m-d H:i:s' , $track->start );
        $track->end_str = date ( 'Y-m-d H:i:s' , $track->end );
        return $track;
    }

    function fill_gabs ($playfiles , $start , $end , $repeat_off)
    {
        $output = array ();
        if ( empty( $playfiles ) ) {
            $track = $this->create_off_gab ( strtotime ( $start ) , strtotime ( $end ) );
            $output = array ( $track );
        } else {
            $last_start = strtotime ( $start );
            foreach ( $playfiles as $key => $playfile ) {
                if ( strtotime ( $playfile->start_str ) > $last_start ) {
                    $track = $this->create_off_gab ( $last_start , $playfile->start );
                    $output[] = $track;
                }
                $last_start = strtotime ( $playfile->end_str );
                $output[] = $playfile;
            }
            if ( $playfile->end < strtotime ( $end ) ) {
                $track = $this->create_off_gab ( $playfile->end , strtotime ( $end ) );
                $output[] = $track;
            }
        }
        if ( $repeat_off ) {
            return $this->deplicate_off ( $output );
        }
        return $output;
    }

    function deplicate_off ($playfiles)
    {
        $output = array ();
        foreach ( $playfiles as $key => $playfile ) {
            if ( $playfile->playtime < (strtotime ( $playfile->end_str ) - strtotime ( $playfile->start_str )) ) {
                $times = floor ( (strtotime ( $playfile->end_str ) - strtotime ( $playfile->start_str )) / $playfile->playtime );
                for ( $i = 0; $i < $times; $i ++ ) {
                    $output[] = $playfile;
                }
            } else {
                $output[] = $playfile;
            }
        }
        return $output;
    }
}

/* End of file track_model.php */
/* Location: ./application/models/track_model.php */
