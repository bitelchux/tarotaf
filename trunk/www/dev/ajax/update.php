<?php
session_start();
include "../system/lib.php";

$id_player = $_SESSION["id_player"];
$id = $_SESSION["id"];

connect();
$partie = load_partie($id);

$modif = update(&$partie);
if($modif)
	save_partie($id, $partie);

//disp_for_player($partie, $id_player);

ajax_for_player($partie, $id_player); ?>

<?
/*
<? print_r($partie); ?>
*/?>


<?/*données initiales
tout est indexé par le joueur. (le joueur 0 est toi)
{
	names = noms des joueurs
	scores = scores des joueurs
	beginnner = numero du premier joueur qui a joué
	next = numero du prochain joueur
	goals = n_plis à faire,
	dones = n_plis faits,
	you = cartes du joueur,
	all = cartes jouées par tous les joueurs
}*/?>

