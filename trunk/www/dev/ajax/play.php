<?php
include "../system/lib.php";
session_start();

$card = $_GET["card"];

$id_player = $_SESSION["id_player"];
$id = $_SESSION["id"];

connect();
$partie = load_partie($id);

play(&$partie, $id_player, $card);
save_partie($id, $partie);

//disp_for_player($partie, $id_player);

?>
