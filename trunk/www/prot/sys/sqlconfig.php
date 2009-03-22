<?php

/*
 * DATABASE CONFIGURATION. USE THE loginsql.php FILE
 */

session_start();

// Database
require_once("loginsql.php");

// Data returned by the mysql_connect function.
$GLOBALS['sql_connection'] = false;

// Tables
function get_tables() {
    $users = "taf_users";
    $games = "taf_games"; // table de la liste des PARTIES en cours
    $rooms = "taf_rooms"; // table des SALLES existantes.
    $types = "taf_types"; // table de la liste des types de jeux disponibles.
    return array($users, $games, $rooms, $types);
}

function get_table_name($whichone) {
	$tables = get_tables();
	switch($whichone) {
		case 'users' : return $tables[0]; break;
		case 'games' : return $tables[1]; break;
		case 'rooms' : return $tables[2]; break;
		case 'types' : return $tables[3]; break;
		default : die("get_table(table) : Unkwown table !"); break;
	}
}

function connect() {
	if(!$GLOBALS['sql_connection']) {
		list($host, $user, $pass, $base) = get_login();
		$conn=mysql_connect($host, $user, $pass) or die("unable to mysql_connect");
		mysql_select_db($base, $conn) or die("unable to mysql_select_db");
		$GLOBALS['sql_connection']=$conn;
	}
	return "TEXT RETURNED BY THE connect() FUNCTION. TAKE CARE.";
}

function get($query) {
	connect();
	$result = mysql_query($query)
		or die("unable to mysql_query : $query");
	$array = array();
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$array[] = $row;
	}
	return $array;
}

function send($query) {
	connect();
	$result = mysql_query($query)
		or die("unable to mysql_query : $query");
}

?>

