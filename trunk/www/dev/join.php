<?php
session_start();
include "system/lib.php";

if($_SESSION["joined"]) {
	echo "Ne pas actualiser avec ca tu vas tout peter, andouille";
} else {
	header("Location: update.php");
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
	
	//disp_for_player($partie, $id_player);
	
	
	/*if($new_full==4) {
		disp_for_player($partie, $id_player);
	} else {
		echo "<p><a href='update.php'>Update</a></p>\n";
	}*/

	$_SESSION["joined"] = true;
}

?>
