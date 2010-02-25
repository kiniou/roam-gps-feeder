<?php

include "./config.inc.php";

$error_query = "";
$error_sql = "";
$result = array();

$query=NULL;
if (array_key_exists('query',$_POST)) {

	$query = json_decode(stripslashes($_POST['query']),TRUE);

	if ($query == NULL) {
		$error_query = 'Bad encoded query.';
	} else {

		$good_query = array_key_exists('fromdate',$query);

		if( !$good_query ) {
			$error_query .= 'Not valid query';
			$query=NULL;
		}
	}
} else {
	$error_query = 'no query sended';
}

if ( !$sql_con = mysql_pconnect('localhost',$db_user,$db_password,MYSQL_CLIENT_COMPRESS) ) {
		$error_sql = 'Database connection problem'. mysql_error();
}

$sql_query  = "SELECT t.`timestamp` , t.`longitude` , t.`latitude` , t.`accuracy` , t.`status` ";
$sql_query .= "FROM trail t ";
if (!is_null($query)) {
	$sql_query .= "WHERE t.`timestamp` > ".$query['fromdate']." ";
	$sql_query .= "ORDER BY t.`timestamp` ASC ";
} else {
	$sql_query .= "ORDER BY t.`timestamp` DESC ";
	$sql_query .= "LIMIT 0,1 ";
}

mysql_select_db($db_base,$sql_con);
$sql_result = mysql_query($sql_query,$sql_con);
$sql_error = mysql_error();
mysql_close($sql_con);

$rows = array();
while ($row = mysql_fetch_array($sql_result,MYSQL_ASSOC)) {
$rows[] = $row;
}
$sql_error .= mysql_error();
mysql_free_result($sql_result);

$result['result']=$rows;
if ("" != $error_query) $result['error_query'] = $error_query;
if ("" != $sql_error) $result['error_sql'] = $error_sql;

die(json_encode($result));
