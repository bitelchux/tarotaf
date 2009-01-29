<?php 
error_reporting(E_ALL);
session_start();
include "../system/lib.php"; 
echoxml();
?>

<?php function form_button($name) {
	echo "<form action=\"#\" onsubmit=\"javascript:$name; return false;\">
				<input type=\"submit\" value=\"$name\" />
			</form>
";
}?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<link href="ajax.css" rel="stylesheet" type="text/css" />
	<!--<script type="text/javascript" src="prototype.js"></script>
	<script type="text/javascript" src="src/scriptaculous.js"></script>-->
	<script type="text/javascript" src="lib.js"></script>
	<script type="text/javascript" src="game.js"></script>
	<title>Tarotaf - play</title>
</head>

<body onload="javascript:start();">
	<div id="commands">
			<?php form_button('stop_update()'); ?>
			<?php form_button('update()'); ?>
	</div>
	<div id="main">

		<h1>Tarotaf</h1>
		<h2>Asynchr application</h2>
		<p>
			Waiting for complete loading.
		</p>
		<div id="waiting_room">
			<h2>Waiting for players</h2>
			<p>Congratulations! Now you're a player. But you HAVE to wait. Life is unfair.</p>
			<p>Players :</p>
			<ul id="present_players">
				<li>You</li>
			</ul>
		</div>
		<ul id="parties"><li></li></ul>
		<div id="login">
			<form id="post_name" action="start.php" onsubmit="javascript:return post_name()"><p>
				<label>User input : 
				<input type="text" name="nom" id="login_nom" value="Name" /></label>
				<input type="submit" value="Send" id="login_OK" />
			</p></form>
		</div>
	</div>
	<div id="images"></div>
	<div id="barcompletes">
		<div id="bar"></div>
		<div id="completes"></div>
	</div>
	<!--<div><pre id="res"></pre></div>-->
	<!--<div id="busy"></div>-->
	<div id="plateau">
		<div class="joueur haut" id="jh">
			<div id="jh_card"></div>
			<h3 class="nom" id="jh_name"></h3>
			<p>Score : <span id="jh_score">?</span></p>
			<p>Contract : <span id="jh_dones">-</span>/<span id="jh_goal">-</span></p>
		</div>
		<div class="joueur gauche" id="jg">
			<div id="jg_card"></div>
			<h3 class="nom" id="jg_name"></h3>
			<p>Score : <span id="jg_score">?</span></p>
			<p>Contract : <span id="jg_dones">-</span>/<span id="jg_goal">-</span></p>
		</div>
		<div class="joueur droite" id="jd">
			<div id="jd_card"></div>
			<h3 class="nom" id="jd_name"></h3>
			<p>Score : <span id="jd_score">?</span></p>
			<p>Contract : <span id="jd_dones">-</span>/<span id="jd_goal">-</span></p>
		</div>
		<div class="joueur bas" id="jb">
			<div id="jb_card"></div>
			<h3 class="nom" id="jb_name"></h3>
			<p>Score : <span id="jb_score">?</span></p>
			<p>Contract : <span id="jb_dones">-</span>/<span id="jb_goal">-</span></p>
			<p id="annonces">How many ? <span id="bets"></span> </p>
			<div id="poignee">
				<div class="handleCard" id="handle0"></div>
				<div class="handleCard" id="handle1"></div>
				<div class="handleCard" id="handle2"></div>
				<div class="handleCard" id="handle3"></div>
				<div class="handleCard" id="handle4"></div>
			</div>
		</div>
	</div>
	<div id="cardtodisp"></div>
	<div id="ask_excuse">
		Valeur de l'excuse : 
		<a href='javascript:play_card(0)'>0</a> ou
		<a href='javascript:play_card(22)'>22</a> ?
	</div>
</body>
</html>




<?php
/*


RESTE PLEIN DE TRUCS A FAIRE.

En vrac :
- tapis continu
- attendre un certain temps à la fin du pli
- sauter le tour 1
- afficher sur le front des joueurs pour le tour 0
- dormir.








*/
?>







