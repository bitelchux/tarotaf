<?php
session_start();
include "system/lib.php";

$id_player = $_SESSION["id_player"];
$id = $_SESSION["id"];

connect();
$partie = load_partie($id);

$modif = update(&$partie);
if($modif)
	save_partie($id, $partie);

disp_for_player($partie, $id_player);

?>
