<?php

require_once("com.php");
require_once("sqlconfig.php");
require_once("login.php");
require_once("libs/json.php");

$user = $_POST["user"];
$pass = $_POST["pass"];

if($_GET["debug"]=="get") {
  $user = $_GET["user"];
  $pass = $_GET["pass"];
}

$answer = new answer;

$v_user = validate_username($user);
$v_pass = validate_password($pass);

if($v_user[0] && $v_pass[0]) {
  // user & pass valid
  if(! register($user, $pass) ) {
    $answer -> register_failure("Username already taken.");
  } else {
    $answer -> register_success($user);
    session_init($id);
  }
} else if(!$v_user[0]) {
  $answer -> register_failure($v_user[1]);
} else if(!$v_pass[0]) {
  $answer -> register_failure($v_pass[1]);
}

echo json_encode($answer);

?>
