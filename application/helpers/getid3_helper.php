<?php
if (! function_exists('loadgetid3'))
{
	function loadgetid3()
	{
		define("GETID3_HELPERAPPSDIR", 'helperapps');
		set_time_limit(0);
		require_once("getid3/getid3.php");
		$getID3 = new getID3();
		return $getID3;
	}
}