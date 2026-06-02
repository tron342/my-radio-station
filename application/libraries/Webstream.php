<?php
/**
* Name:  WEB STREAM AUDIO
* Author: Bessem ZIOTUNI
* Created:  01.01.2017
*/
class Webstream{
	private $playlist;
	private $base_url;
	private $debug = 1;

	public function __construct($playlist, $base_url = "", $debug = 0)
    {
        $this->playlist = $playlist;
        $this->base_url = $base_url;
        $this->debug    = $debug;
    }

	private function setHeader(){
		ignore_user_abort(true);
        if (isset($_SERVER['HTTP_RANGE'])) {
            header('HTTP/1.1 206 Partial Content');
        } else {
            header('HTTP/1.1 200 OK');
        }
		if( $this->debug ){
        	header("Content-Type: text/plein");
		}else{
        	header("Content-Type: audio/mp3");
		}
        header("Content-Language: en-US");
        header("Connection: close");
        header('HTTP/1.0 200 OK');
        header('Cache-Control: public, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        header('Accept-Ranges: none');
        header("Content-Transfer-Encoding: binary");
		header('Access-Control-Allow-Headers:Origin, Accept, X-Requested-With, Content-Type');
		header('Access-Control-Allow-Methods:GET, OPTIONS, HEAD');
		header('Access-Control-Allow-Origin:*');
	}

	private function write_file($track, $startFrom = 0){
		$location = $this->base_url.$track->url;
		if( $this->debug ){
			echo "=========================\n";
			echo "$location\n";
			echo date("H:i:s", $track->start)." - ".date("H:i:s", $track->end)."\t";
			echo intval($track->percent)." %\t";
			echo "startFrom : ".$startFrom."/".$track->audioend."\t";
			echo $track->title."\n";
			return;
		}
        $fm = @fopen($location, 'rb');
        if ($fm) {
        	$buffer_size = 1024 * 16;
        	$size= $track->audioend;
	        $begin = $startFrom == 0?$track->audiostart:$startFrom;
	        $end   = $size - 1;
            $cur = $begin;
        	while (@ob_end_flush());
	        fseek($fm, $begin, 0);
	        while (!feof($fm) && $cur <= $end && (connection_status() == 0)) {
				if( connection_aborted() ){
					die();
				}
	            echo  fread($fm, min($buffer_size, ($end - $cur) + 1));
	            $cur += $buffer_size;
	        }
        }
	}

	public function startStream(){
		$this->setHeader();
		if( $this->debug ){
			echo "Time NOW : ".date("H:i:s")."\n";
		}
		foreach ($this->playlist as $index => $track) {
			if( connection_status() != 0){
				die();
			}
			if( connection_aborted() ){
				die();
			}
			$startFrom = 0;
			if( $index == 0 ){
				if( $this->debug ){
					echo "time : ".time()."\n";
					echo "on : ".intval($track->on)." ---> ".date("Y-m-d H:i:s", intval($track->on))."\n";
					echo "start : ".intval($track->start)." ---> ".date("Y-m-d H:i:s", intval($track->start))."\n";
					echo "end : ".intval($track->end)." ---> ".date("Y-m-d H:i:s", intval($track->end))."\n";
					echo "(time-start)*100 : ".((intval($track->on)-intval($track->start))*100)."\n";
					echo "end-start : ".(intval($track->end)-intval($track->start))."\n";
				}
				$track->percent = ((intval($track->on)-intval($track->start))*100)/(intval($track->end)-intval($track->start));
				$startFrom = ($track->audiolength*$track->percent)/100;
			}else{
				$track->percent = 0;
			}
			$this->write_file($track, $startFrom);
		}
	}
}
//http://radio.almarfah.com/radiostation/index.php/stream?debug