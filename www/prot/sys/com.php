<?php

session_start(); //should be only here

class query {
	var $type;
	var $status;
	var $message;
	
	function query($type="no type specified", $status="no status specified", $message="no message specified") {
		$this -> type = $type;
		$this -> status = $status;
		$this -> message = $message;
	}
}

class answer {
	var $query;
	var $username;
	var $sid;

	var $available_games;
	var $used_games;
	var $rooms;

	function answer($username="not specified", $query=null) {
		$this -> sid = SID;
		if($query==null) $query=new query;
		$this -> query = $query;
		$this -> username = $username;
	}

	function login_success($username) {
		$this -> username = $username;
		$this -> query = new query("login", "success", "Successfully logged in as $username.");
	}

	function login_failure($message) {
		$this -> query = new query("login", "error", $message);
	}

	function register_success($username) {
		$this -> username = $username;
		$this -> query = new query("register", "success", "Register successfull. Logged in as $username");
	}

	function register_failure($message) {
		$this -> query = new query("register", "error", $message);
	}
}

function session_init($username) {
	$_SESSION['user'] = $username;
	$_SESSION['games'] = array();
}

function is_logged_in() {
	return !empty($_SESSION['user']);
}

function fail($type, $message) {
	$answer = new answer(null, new query($type, "error", $message));
	die(json_encode($answer));
}


?>



















