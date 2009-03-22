<?php
require_once("main.php");
require_once("libs/json.php");

$id =     $_GET['gameid'] or $_POST['gameid']; //rhooo
$action = $_GET['action'] or $_POST['action']; //scandale !

echo json_encode(action($id, $action));
?>
