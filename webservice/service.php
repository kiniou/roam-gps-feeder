<?php

include "./config.inc.php";


if (array_key_exists("data",$_POST)) {

	$data = stripslashes($_POST["data"])."\n";

	if (!is_null($data) ) {

		if ( $con = mysql_pconnect('localhost',$db_user,$db_password,MYSQL_CLIENT_COMPRESS) ) {
			$data_list = json_decode($data,true);

			$values = array();
			$keys = array();

			if(!empty($data_list["timestamp"])) {
				$keys[]="`timestamp`";
				$values[]=strftime("%Y%m%d%H%M%S",$data_list["timestamp"]);
			}

			if(!empty($data_list["longitude"])) {
				$keys[]="`longitude`";
				$values[]=sprintf("%10.6f",$data_list["longitude"]);
			}

			if(!empty($data_list["latitude"])) {
				$keys[]="`latitude`";
				$values[]=sprintf("%10.6f",$data_list["latitude"]);
			}
			if(!empty($data_list["accuracy"])) {
				$keys[]="`accuracy`";
				$values[]=sprintf("%10.6f",$data_list["accuracy"]);
			}
			if(!empty($data_list["status"])) {
				$keys[]="`status`";
				$values[]=sprintf("%d",$data_list["status"]);
			}

			$query = sprintf("INSERT INTO %s (%s) VALUES (%s)", $db_table, implode(',',$keys) , implode(",",$values));

			mysql_select_db($db_base,$con);
			mysql_query($query,$con);
			mysql_close($con);
			$ret.=$query;
		} else {
			die("<span class='error'>Database connection error</span>");
		}

		$log = $data;

		fclose($f);
		$last_status = "";
		switch($data_list["status"]) {
			case 0:
				$laststatus="STOPPED";
				break;
			case 1:
				$laststatus="IN MOVEMENT";
				break;
			case 2:
				$laststatus="LISTENING TO MUSIC";
				break;
			case 3:
				$laststatus="PANIC";
				break;	
		}
		$ret = "<span>Update Coordinates OK!</span><br/>Last Status : ".$laststatus."<br/>";
		die($ret);

	} else {
		die("<span class='error'>Bad Posted Data!</span>");
	}
	
} else {
	die("<span class='error'>No Posted Data!</span>");
}
?>
