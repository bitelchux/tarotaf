<?php

require_once("sqlconfig.php");

function login_debug() {return 0;}

function debug($txt) {
    if(login_debug()) {
        echo "debug : $txt\n";
    }
}

function seed() {
    return "_QhS173s2ML5@";
}

// 2009.02.19 : save 'non hashed' in order to be able to improve later

function validate_username($username) {
	if(strlen($username) < 4) {
		return array(FALSE, "Username should contain at least four characters.");
	} if(strlen($username) > 30) {
		return array(FALSE, "Username should contain at most 30 characters.");
	} else if ( !preg_match('/^[a-z0-9_]*$/',$username) ) {
		return array(FALSE, "Username should contain only characters matching [a-z0-9_].");
	} else {
		return array(TRUE, "");
	}
}

function validate_password($password) {
	if(strlen($password) < 6) {
		return array(FALSE, "Password should contain at least 6 characters");
	} else {
		return array(TRUE, "");
	}
}

function login($user, $pass) {
	$tables = get_tables();
	$usertable = $tables[0];
	$others = get("SELECT * FROM `$usertable` WHERE `user` = '$user' AND `pass` = '$pass'");
	if(count($others) == 1) {
		debug("login : ok : Successfully signed in with username $user");
		return TRUE;
	} else {
		debug("login : nok : Wrong username/password combination");
		return FALSE;
	}
}

function register($user, $pass) {
	$tables = get_tables();
	$usertable = $tables[0];
	$others = get("SELECT * FROM `$usertable` WHERE `user` = '$user'");
	if(count($others) >= 1) {
		debug("register : nok : username ($user) already taken.");
		return FALSE;
	} else {
		debug("register : ok : username available");
		send("INSERT INTO `$usertable` (`user` ,`pass` ,`stat`) VALUES ('$user', '$pass', '');");
		debug("register : ok : user created");
		return TRUE;
	}
}



