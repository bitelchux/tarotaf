<?php
session_start();
include "system/lib.php";
$_SESSION["joined"] = false;
if($_POST["nom"])
	$_SESSION["nom"] = $_POST["nom"];


connect();
$parties = get_free_parties();
?>

<?php echoxml(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" />
	<meta http-equiv="refresh" content="10" /> 
	<link href="defaut.css" rel="stylesheet" type="text/css" />
	<title>Tarotaf - rejoindre une partie</title>
</head>

<body>
	<div class="main">
		<h1>Tarotaf</h1>
		<h2>Rejoindre une partie</h2>
		<p>Parties disponibles :</p>
		<ul>
<?php
		foreach($parties as $line) {
			echo "			<li>\n				Partie " . $line['id'] ." : nombre de joueurs deja presents : " . $line['full'] . " :\n";
			echo "				<a href='join.php?id=" . $line['id'] . "'>rejoindre</a>\n			</li>\n";
		}
		?>
		</ul>
	</div>
</body>
</html>

