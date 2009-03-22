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

if($user) {
  if(! login($user, $pass)) {
    $answer -> login_failure("wrong username/password combination");
  } else {
    $answer -> login_success($user);
    session_init($user);
  }
} else {
  $answer -> login_failure("you must specify a username");
}

echo json_encode($answer);

?>
