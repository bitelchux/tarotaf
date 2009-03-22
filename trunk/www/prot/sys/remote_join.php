<?php
require_once("main.php");
require_once("libs/json.php");

echo json_encode(join_game($_GET['id']));
?>
