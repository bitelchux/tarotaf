<?php
session_start();
include "../system/lib.php";

if($_SESSION["joined"]) {
	echo "Error. Faut arrêter maintenant.";
} else {
	$id = $_GET["id"];
	$nom = $_SESSION["nom"];

	connect();

	$query = "SELECT `id`, `full`, `partie` FROM `parties` WHERE `id`=$id AND `full`<>4";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	$line = mysql_fetch_array($result) or die('Partie deja prise (OU BIEN tricheur !)');

	$new_full = $line["full"] + 1;
	$query = "UPDATE `parties` SET `full` = '$new_full' WHERE `id` = $id";
	mysql_query($query) or die('Query failed: ' . mysql_error());


	$partie = load_partie($id);

	$id_player = add_player(&$partie, $nom);

	$_SESSION["id_player"] = $id_player;
	$_SESSION["id"] = $id;

	save_partie($id, $partie);
	
	echo "Success : partie rejointe avec succès.";
	
	$_SESSION["joined"] = true;
}

?>
