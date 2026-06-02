<?php defined ( 'BASEPATH' ) OR exit( 'No direct script access allowed' );
/**
 * Name:         My Radio Station
 * Version :     1.0
 * Author:       Zitouni Bessem
 * Requirements: PHP5 or above
 *
 */

class Calendar_model extends CI_Model
{
    var $table = "calendar";

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

    public function getPlaylistsByID ($id)
    {
        $q = $this->db->select ( 'playlist_calendar.*, playlist.name, playlist.audiolength as durration' )
            ->from ( 'playlist_calendar' )
            ->join ( 'playlist' , 'playlist.id=playlist_calendar.playlist_id' , 'LEFT' )
            ->where ( 'calendar_id' , $id )
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

    public function add ($data = array () , $data_playlists = array ())
    {
        if ( $this->db->insert ( $this->table , $data ) ) {
            $id = $this->db->insert_id ();
            $data[ 'id' ] = $id;
            $this->insert_instances ( $data );
            if ( count ( $data_playlists ) > 0 ) {
                foreach ( $data_playlists as $i => $row ) {
                    $data_playlists[ $i ][ 'calendar_id' ] = $id;
                }
                $this->db->insert_batch ( 'playlist_calendar' , $data_playlists );
            }
            return $id;
        }
        return false;
    }

    public function update ($id , $data = array () , $data_playlists = array ())
    {
        if ( $this->db->where ( 'id' , $id )->update ( $this->table , $data ) ) {
            $data[ 'id' ] = $id;
            $this->insert_instances ( $data );
            $this->db->where ( 'calendar_id' , $id )->delete ( 'playlist_calendar' );
            if ( count ( $data_playlists ) > 0 ) {
                $this->db->insert_batch ( 'playlist_calendar' , $data_playlists );
            }
            return $id;
        }
        return false;
    }

    public function delete ($id)
    {
        if ( $this->db->where ( 'id' , $id )->delete ( $this->table ) ) {
            $this->db->where ( 'id_calendar' , $id )->delete ( 'calendar_instance' );
            $this->db->where ( 'calendar_id' , $id )->delete ( 'playlist_calendar' );
            return true;
        }
        return false;
    }

    public function moveshow ($id , $day , $min)
    {
        $this->db->set ( 'starts' ,
            'DATE_ADD(DATE_ADD(`starts`, INTERVAL ' . $min . ' MINUTE), INTERVAL ' . $day . ' DAY)' , false );
        $this->db->set ( 'ends' ,
            'DATE_ADD(DATE_ADD(`ends`, INTERVAL ' . $min . ' MINUTE), INTERVAL ' . $day . ' DAY)' , false );
        $this->db->where ( 'id' , $id );
        if ( $this->db->update ( $this->table ) ) {
            $calendar = (array)$this->getByID ( $id );
            $this->insert_instances ( $calendar );
            return $id;
        }
        return false;
    }

    public function resizeshow ($id , $day , $min)
    {
        $this->db->set ( 'ends' ,
            'DATE_ADD(DATE_ADD(`ends`, INTERVAL ' . $min . ' MINUTE), INTERVAL ' . $day . ' DAY)' , false );
        $this->db->set ( 'duration' , "`duration`+" . ((intval ( $min ) * 60) + (intval ( $day ) * DAY_VALUE)) ,
            false );
        $this->db->where ( 'id' , $id );
        if ( $this->db->update ( $this->table ) ) {
            $calendar = (array)$this->getByID ( $id );
            $this->insert_instances ( $calendar );
            return $id;
        }
        return false;
    }

    public function insert_instances ($data_row)
    {
        $result = array ();
        if ( $data_row[ 'repeat_type' ] == - 1 ) {
            $result[] = array (
                "id" => null ,
                "id_calendar" => $data_row[ 'id' ] ,
                "starts" => $data_row[ 'starts' ] ,
                "ends" => $data_row[ 'ends' ] ,
                "repeat_type" => "0" ,
                "one-time" => true ,
            );
        } elseif ( $data_row[ 'repeat_type' ] == 2 ) { // monthly
            $s = strtotime ( $data_row[ 'starts' ] );
            if ( $data_row[ 'end_date' ] == null ) {
                $e = null;
            } else {
                $e = strtotime ( $data_row[ 'end_date' ] );
            }
            $check_date = $s;
            if ( $e != null ) {
                while ($check_date <= $e) {
                    if ( date ( "d" , $check_date ) == date ( "d" , strtotime ( $data_row[ 'starts' ] ) ) ) {
                        $show_start = date ( 'Y-m-d ' , $check_date ) . date ( 'H:i:s' ,
                                strtotime ( $data_row[ 'starts' ] ) );
                        $show_end = date ( 'Y-m-d H:i:s' , strtotime ( $show_start ) + $data_row[ 'duration' ] );
                        $result[] = array (
                            "id" => null ,
                            "id_calendar" => $data_row[ 'id' ] ,
                            "starts" => $show_start ,
                            "ends" => $show_end ,
                            "repeat_type" => "1" ,
                            "one-time" => true ,
                        );
                    }
                    $check_date += DAY_VALUE;
                }
            } else {
                $result[] = array (
                    "id" => null ,
                    "id_calendar" => $data_row[ 'id' ] ,
                    "starts" => $data_row[ 'starts' ] ,
                    "ends" => $data_row[ 'ends' ] ,
                    "repeat_type" => "1" ,
                    "one-time" => false ,
                );
            }
        } else { // weekly
            $repeat_days = explode ( "," , $data_row[ 'repeat_days' ] );
            $onetime = true;
            $s = strtotime ( $data_row[ 'starts' ] );
            if ( $data_row[ 'end_date' ] == null ) {
                $e = strtotime ( $data_row[ 'starts' ] . "+1 months" );
                $onetime = false;
            } else {
                $e = strtotime ( $data_row[ 'end_date' ] );
            }
            $check_date = $s;
            switch ($data_row[ 'repeat_type' ]) {
                case 0:
                    $m = 7;
                    break;
                case 1:
                    $m = 14;
                    break;
                case 4:
                    $m = 21;
                    break;
                case 5:
                    $m = 28;
                    break;
            }
            while ($check_date <= $e) {
                $d = (($check_date - $s) / DAY_VALUE) % $m;
                $add = true;
                if ( $d >= 7 ) {
                    $add = false;
                }
                if ( $add && in_array ( date ( "w" , $check_date ) , $repeat_days ) ) {
                    $show_start = date ( 'Y-m-d ' , $check_date ) . date ( 'H:i:s' ,
                            strtotime ( $data_row[ 'starts' ] ) );
                    $show_end = date ( 'Y-m-d H:i:s' , strtotime ( $show_start ) + $data_row[ 'duration' ] );
                    $result[] = array (
                        "id" => null ,
                        "id_calendar" => $data_row[ 'id' ] ,
                        "starts" => $show_start ,
                        "ends" => $show_end ,
                        "repeat_type" => "2" ,
                        "one-time" => $onetime ,
                    );
                }
                $check_date += DAY_VALUE;
            }
        }
        if ( count ( $result ) > 0 ) {
            $this->db->where ( 'id_calendar' , $data_row[ 'id' ] )->delete ( 'calendar_instance' );
            $this->db->insert_batch ( "calendar_instance" , $result );
        }
        //echo json_encode($result);
    }

    public function select_events ($start_timestamp , $end_timestamp)
    {

        $weekQuery = "SELECT 
					@nbrDays := ABS(FLOOR(DATEDIFF(`starts`, '$start_timestamp')/28)) as `nbrDays`, 
					`calendar_instance`.`id`                                          as `id`, 
					`calendar_instance`.`id_calendar`                                 as `id_calendar`, 
					TIMESTAMP(DATE_ADD(`starts`, INTERVAL @nbrDays*28 DAY))           as `starts`, 
					TIMESTAMP(DATE_ADD(`ends`, INTERVAL @nbrDays*28 DAY))             as `ends`, 
					`calendar_instance`.`one-time`                                    as `one-time`,  
					`calendar_instance`.`repeat_type`                                 as `repeat_type`
				FROM `calendar_instance`
				WHERE `one-time` = 0 AND `repeat_type` = 2";

        $monthQuery = "SELECT 
					@nbrDays := ABS(FLOOR(DATEDIFF(`starts`, '$start_timestamp')/30)) as `nbrDays`, 
					`calendar_instance`.`id`                                          as `id`, 
					`calendar_instance`.`id_calendar`                                 as `id_calendar`, 
					TIMESTAMP(DATE_ADD(`starts`, INTERVAL @nbrDays MONTH))            as `starts`, 
					TIMESTAMP(DATE_ADD(`ends`, INTERVAL @nbrDays MONTH))              as `ends`, 
					`calendar_instance`.`one-time`                                    as `one-time`,  
					`calendar_instance`.`repeat_type`                                 as `repeat_type`
				FROM `calendar_instance`
				WHERE `one-time` = 0 AND `repeat_type` = 1";

        $subQueries = array ();
        $subQueries[] = "SELECT 0 as nbrDays, `calendar_instance`.* FROM `calendar_instance`";
        $subQueries[] = $weekQuery;
        $subQueries[] = $monthQuery;
        return "((" . implode ( ") UNION (" , $subQueries ) . "))";
    }


    public function events_feed ($start_timestamp , $end_timestamp)
    {
        $selection = "si1.id_calendar             AS id,
					  si1.starts                  AS start,
					  si1.ends                    AS end,
					  calendar.duration           AS duration,
					  calendar.name               AS title,
					  calendar.background_color   AS color,
					  calendar.color              AS textColor,
					  calendar.name               AS title";
        $where_clause = "( 
			(si1.starts >= '$start_timestamp' AND si1.starts < '$end_timestamp') 
		    OR (si1.ends > '$start_timestamp' AND si1.ends <= '$end_timestamp') 
		    OR (si1.starts <= '$start_timestamp' AND si1.ends >= '$end_timestamp') 
	    )";
        $subQuery = $this->select_events ( $start_timestamp , $end_timestamp );

        $q = $this->db->select ( $selection )
            ->from ( "($subQuery) AS si1" )
            ->join ( 'calendar' , 'calendar.id=si1.id_calendar' , 'left' )
            ->where ( $where_clause )
            ->order_by ( 'si1.starts' )
            ->get ();
        //echo $this->db->last_query();die();

        if ( $q->num_rows () > 0 ) {
            $rows = $q->result ();
            $result = array ();
            foreach ( $rows as $key => $row ) {
                $show = array (
                    "id" => intval ( $row->id ) ,
                    "title" => $row->title ,
                    "start" => $row->start ,
                    "end" => $row->end ,
                    "duration" => $row->duration ,
                    "editable" => true ,
                    "allDay" => false ,
                    "showId" => intval ( $row->id ) ,
                    "linked" => 0 ,
                    "record" => 0 ,
                    "rebroadcast" => 0 ,
                    "instance_rotation" => - 1 ,
                    "rotation" => 0 ,
                    "rotation_scheduled" => false ,
                    "soundcloud_id" => - 1 ,
                    "auto_dj" => false ,
                    "nowPlaying" => false ,
                    "color" => $row->color ,
                    "textColor" => $row->textColor ,
                    "percent" => 0 ,
                    "show_empty" => 0 ,
                    "show_partial_filled" => true
                );
                if ( strtotime ( $row->end ) > time () ) {
                    $show[ "editable" ] = true;
                }
                $result[] = $show;
            }
            return $result;
        }
        return array ();
    }

}

/* End of file track_model.php */
/* Location: ./application/models/track_model.php */