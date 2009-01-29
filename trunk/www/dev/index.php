<?php include "system/lib.php"; echoxml(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" />
	<link href="defaut.css" rel="stylesheet" type="text/css" />
	<title>Tarotaf - rejoindre une partie</title>
</head>

<body>
	<div class="main">

		<h1>Tarotaf</h1>
		<h2>Envie de jouer ?</h2>
		<p>
			C'est tr&egrave;s simple ! Il suffit de choisir un pseudo :
			<form action="start.php" method="post">
				<input type="text" value="" name="nom" />
				<input type="submit" value="Jouer" />
			</form>
		</p>
	</div>
</body>
</html>



<?php
/*

-suivi.php : affiche les parties en cours (avec les noms ?)
-suivi.php?p=numerodepartie : affiche la partie en cours




  UN PEU D'AJAX ? (plus tard)
-ajax.php                     // charge le javascript (pas forcément le jeu)
-ajaxpari.php?pari=1
-ajaxjouer.php?carte=13
-ajaxnew.php?c=1082           //indique s'il y a eu changement. (si oui, avec toutes les infos ?)
-ajaxjeu.php                  //renvoie toutes les infos du jeu

- Fonctions nécessaires :
étant donné un coup (vérifier à qui est le tour), jouer
le cas échéant, passer le pli, le tour, la manche


  DEROULEMENT D'UN JEU
l'utilisateur arrive sur la page start.php, il sélectionne une partie (une partie est éventuellement crée s'il n'y a pas de place)
il attend les autres joueurs si besoin. (actualisation automatique)
le dernier joueur arrive, la partie est initialisée (au même moment !)
l'id de la partie est stocké dans une variable de session.

déroulement : actualisation ...
- tant que ce n'est pas au joueur de jouer, aucun lien n'est disponible
- au joueur de jouer/parier : liens pari.php/jouer.php disponibles : clic = jeu+actualisation


  RESTANT A FAIRE
//-dire qui est le croupier (choisi au hasard)
//-afficher 0/22 pour l'excuse lors du choix   ->  faisable avec des images
//-affichage du dernier pli, pour ne pas rester sur sa faim
//-canaliser/enfiler les messages à afficher au mileu
//-manche sur le front : jouer automatiquement ? (NOT !)
-gérer les joueurs enregistrés ?
-gérer les robots
	- développer un peu - donner un timestamp de jeu
-faire l'ia
-afficher le changement des scores
//-donner un nom
//-actualiser le full mysql lors d'un ajout d'un robot
//-vérifier l'état de la variable $p["annonce"]

-htaccess (pour le 404 moche notamment)
-proposer l'ouverture d'esprit pour les hackers ?

on a bien accès au htaccess.

-sémantique : la carte avant le nom du joueur ?

-faire une pause après le pli de quelqu'un d'autre --> seulement en ajax ?
	- ajout d'une variable autorisant les opérations de modif sur la partie

-ajax - cache de 1 ou 2 secondes ?

*/
?>
