<?php
include "system/lib.php";
session_start();

$bet = $_GET["bet"];

$id_player = $_SESSION["id_player"];
$id = $_SESSION["id"];

connect();
$partie = load_partie($id);

bet(&$partie, $id_player, $bet);
save_partie($id, $partie);

//disp_for_player($partie, $id_player);

header("Location: update.php");

?>
