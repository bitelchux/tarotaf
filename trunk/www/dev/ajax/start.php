<?php
session_start();
include "../system/lib.php";
$_SESSION["joined"] = false;
if($_POST["nom"])
	$_SESSION["nom"] = $_POST["nom"];


connect();
$parties = get_free_parties();

	for($i=0; $i<=0*1000; $i++) {
		for($j=0; $j<=1000; $j++) {
			cos($i+$j);
		}
	}

//header('HTTP/1.1 403 Forbidden');

echo makeJavaScriptArray($parties);

