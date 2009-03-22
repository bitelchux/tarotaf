<?php
require_once("main.php");
require_once("libs/json.php");

echo json_encode(create_game($_GET['type'], $_GET['room']));

//$_GET['room'] //ahaha
?>
