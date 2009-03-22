<?php


/*
petite histoire

enregistrement de l'utilisateur
    rem/register.php + POST

connexion de l'utilisateur
    rem/login.php + POST

demande de la liste des parties
    rem/list.php
    --> retourne le json adapté

rejoindre une partie
    rem/join.php?gameid=4621
    --> retourne l'objet réponse (comme update.php)

(re)afficher le jeu
    rem/update.php
    --> retourne un objet à propos de toutes les parties ouvertes

jouer
    rem/act.php?gameid=138&action=ACTIONOBJECT
    --> retourne le résultat dans l'objet query (->type = 'play'  ...)
    --> retourne aussi les données de update.php

quitter une partie
    rem/close.php?gameid=123

chat
    rem/talk.php?chatid=132127 & message=mlkqsd (POST)
    rem/listen.php

CHAT
le chat est indépendant; il faudra être connecté (avant) et les salles
pourront être réliées aux parties mais elles sont indépendantes a priori.




LOGIN
rem/login.php
    POST :
        user = raccoon
        pass = mybone

rem/register.php
    POST :
        user = raccoon
        pass = mybone


PLAY

/rem/update.php

/rem/act.php
    GET and otherwise POST :
        action = ACTIONOBJECT
        gameid = INTEGER



ABOUT THE SESSIONS
the session is based on cookies.
But the server provides something like that :
  PHPSESSID=ff2f3a96697547eff93f09b62889c41b
and putting it in the url as the *first* GET argument
will do the trick :
  play.php?PHPSESSID=ff2f3a96697547eff93f09b62889c41b

variable de session :

$_SESSION['user'] : username
$_SESSION['games'] : open games ids list


NOTE : YOU CAN'T OPEN TWICE THE SAME GAME WITH ONE SINGLE USER


DATABASE

users table :

+------+
| user |  username USED AS AN ID
+------+
| pass |  pass (hashed or whatever)
+------+
| stat |  stat_object will be used one day..
+------+

games table :

+-----------+
| id        |  # of the game. Useful
+-----------+
| obj       |  "object" game. MUST NOT contain any username (but 0,1,2,...)
+-----------+
| type      |  string giving the type of the game. e.g. "whist22"
+-----------+
| remaining |  number of players who can join.*
+-----------+
| room      |  NAME of the room. (room is used as an id)
+-----------+
| players   |  "kevin2000, johndoe, xjm_" **
+-----------+

* all that matters :
  remaining == 0 while players can join.
  remaining > 0 since players can't join.

** implode(", ", array("kevin2000", "johndoe", "xjm_"))
   there is no redundancy in the line : username are only available here.


NORME DES FICHIERS DE JEU :

les fichiers de jeu devront contenir une classe
du même nom que le fichier, et pas d'autres fonctions
hors de cette classe.

Par exemple, dans le fichier 
	sys/games/poker.php

class poker {
  function new_game()
  function action($numplayer, $action)
  function relativize($numplayer)
}

$game -> action(..) modifie l'instance $game.
$game -> relativize(..) renvoie un tableau prêt à se faire JSONer.
$game -> new_game(..) normalement, inutile ?

sqlline_to_relative_object :
  string -> obj -> relativize -> robj > string




reste à faire

	toutes les fonctions début de jeu, début de partie, ... fin de partie, fin de jeu

	trouver un moyen d'enregistrer un score quand la fonction est appelée




infos qui vont avec une instance de jeu
  le type de jeu               "whist22"
  le nom des joueurs           "bob2000, jmjm, elie, poiz"
  le numero de l'utilisateur   1                             // in [0..3]
*/

array(
	"id" => 47,
	"type" => "whist22",
	"players" => "bob2000, jmjm, elie, poiz",
	"numplayer" => 1,
	"room" => "teuf",
	"object" =>
		array(
			"bets" => array(-1, -1, -1, -1),
			"hands" => array("invisible", array(5, 11, 16, 20), "invisible", "invisible"),
			"dealer" => 0,
			"starter" => 1,
			"toplay" => 3,
			"current_trick" => array(2, 12),
			"tricks" => array(array(3, 1, 7, 15))
		)
);

/*


*/



/*

req/connect?user=user&pass=passmd5
req/displaygames
req/displaygameswaiting
req/selectgame?id=1234

req/updategame?id=1234
req/playcard?card=12
req/bet?amount=3

ou alors :
req/act?action=play&value=12


req/closegameandfucktheotherplayers
req/playforme


*/

//phpinfo();

?>
