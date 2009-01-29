<?php

include "interface.php";

///////////////////////////////////////////////
// FONCTIONS ADDITIONNELLES SUR LES TABLEAUX //
///////////////////////////////////////////////

function cyclic_permutation($tab, $i=1) {
	for($j=1; $j<=$i; $j++)
		array_push($tab, array_shift($tab));
	return $tab;
}

function remove_index($tab, $indice) {
	//passer $tab en référence : remove_indice(&$tab, 4);
	$debut = array_slice($tab, 0, $indice+0);
	$fin = array_slice($tab, $indice+1);
	$tab = array_merge($debut, $fin);
}

function remove($tab, $valeur) {
	//passer $tab en référence : remove(&$tab, 4);
	$indice = array_search($valeur, $tab);
	$debut = array_slice($tab, 0, $indice+0);
	$fin = array_slice($tab, $indice+1);
	$tab = array_merge($debut, $fin);
}



/////////////////////////////////
// FONCTIONS DE GESTION DU JEU //
/////////////////////////////////

function random_deck() {
	$a = range(1, 22);
	shuffle($a);
	return $a;
}

function distribue($p) {
	$manche = $p["manche"];
	if($manche>0) {
		$p["numtour"] = $manche;
		foreach(range(1, $manche) as $jj) {
			foreach(range(0,3) as $i) {
				$carte = array_pop($p["deck"]);
				$p["cards"][$i][] = $carte;
			}
		}
		foreach(range(0,3) as $i) {
			sort($p["cards"][$i]);
		}
	} else {
		$p["numtour"] = 1;
		foreach(range(0,3) as $i) {
			$carte = array_pop($p["deck"]);
			$p["cards"][$i][] = $carte;
		}
	}
}


function faire_le_pli($p) {
	$cartemax = max($p["pli_actuel"]);
	$gagnant = (array_search($cartemax."", $p["pli_actuel"]) + $p["beginner"] + 4) % 4;
	comment(&$p, "Dernier pli : " . implode($p["pli_actuel"], "-") . " (". $p["name"][$gagnant] .")");
	$p["dones"][$gagnant]++;
	$p["beginner"] = $gagnant;
	fin_de_pli(&$p);
}

function debut_de_manche($p) {
	if($p["manche"]==0) {
		comment(&$p, "Manche avec ta carte sur ton front.");
	} else {
		comment(&$p, "Manche avec " . $p['manche'] . " carte(s).");
	}
	$p["deck"] = random_deck();
	$p["beginner"] = ($p["croupier"] + 1)%4;
	$p["annonce"] = false;
	$p["contract"] = array("-", "-", "-", "-");
	$p["dones"] = array(0, 0, 0, 0);
	distribue(&$p);
	debut_de_pli(&$p);
}

function fin_de_manche($p) {
	for($i=0; $i<4; $i++) {
		$p["score"][$i] -= abs($p["contract"][$i] - $p["dones"][$i]);
	}
	$p["manche"]--;
	if($p["manche"]==-1) {
		comment(&$p, "Fini. On recommence ?");
		$p["manche"] = 5;
		$p["croupier"] ++; 
		$p["croupier"] %= 4;
		debut_de_manche(&$p);
	} else {
		debut_de_manche(&$p);
	}
}

function debut_de_pli($p) {
	$p["pli_actuel"] = array();
	$p["next"] = $p["beginner"];
}

function fin_de_pli($p) {
	$p["numtour"] -= 1;
	if($p["numtour"]==0) {
		fin_de_manche(&$p);
	} else {
		debut_de_pli(&$p);
	}
}

function debut_de_partie($p) {
	$p["manche"] = 5;
	debut_de_manche(&$p);
}

function start_partie($p) {
	$p["comments"] = array();
	$p["croupier"] = rand(0, 3);
	$p["score"] = array(0, 0, 0, 0);
	debut_de_partie(&$p);
}

function forbidden_bet($p, $joueur) {
	if($joueur==$p["croupier"]) {
		$croup=$p["croupier"];
		$veinard1=($croup+1)%4;
		$veinard2=($croup+2)%4;
		$veinard3=($croup+3)%4;
		$paris=$p["contract"];
		$sum = $paris[$veinard1]+$paris[$veinard2]+$paris[$veinard3];
		return max(1, $p["manche"]) - $sum;
	} else {
		return -1;
	}
}

function available_bets($p, $joueur) {
	$bets = range(0, max(1,$p["manche"]));
	$forbid = forbidden_bet($p, $joueur);
	if(in_array($forbid, $bets))
		remove(&$bets, $forbid);
	return $bets;
}







////////////////////////
// FONCTION D'ACTIONS //
////////////////////////

function bet($p, $joueur, $pari) {
	if($p["annonce"]) die("T'as deja parie, nanar$joueur");
	if($joueur!=$p["next"]) die("attends avant de parier");
	if($pari == forbidden_bet($p, $joueur)) die("pari interdit !");
	$p["contract"][$joueur] = $pari;
	$p["next"] = ($p["next"]+1) % 4;
	$p["timestamp"] = time();
	if($p["next"] == $p["beginner"])
		$p["annonce"] = true;
}

function play($p, $joueur, $carte) {
	if($joueur!=$p["next"]) die("attends avant de jouer");
	if($carte==0 or $carte==22) 
		$vcard = 22; 
	else
		$vcard = $carte;
	$rang = array_search($vcard, $p["cards"][$joueur]);
	if($rang === FALSE) die("joue une carte que t'as, banane");
	remove_index(&$p["cards"][$joueur], $rang);
	array_push($p["pli_actuel"], $carte+0);
	$p["next"] = ($p["next"]+1) % 4;
	$p["timestamp"] = time();
	if($p["next"] == $p["beginner"]) {
		faire_le_pli(&$p);
	}
}










//////////////////////////////////////
// FONCTION DE GESTION DES JOUEURS  //
//////////////////////////////////////


function add_player($p, $nom, $bot = false) {
	if(!isset($nom) && strlen($nom)>0)
		$nom = "Player " . (count($p["name"])+1);
	while(in_array($nom, $p["name"]))
		$nom .= "'";
	$p["name"][] = $nom;
	$p["idg"][] = strtoupper(md5(serialize($p)));  //n'imprt nawak
	$p["bot"][] = $bot;
	$id_player = count($p["name"])-1;
	if(count($p["name"])==4) {
		start_partie(&$p);
	}
	$p["timestamp"] = time();
	return $id_player;
}

function new_partie() {
	$p["name"] = array();
	$p["bot"] = array();
	$p["idg"] = array();
	$p["cards"] = array(array(), array(), array(), array());
	$p["plis"] = 0; //"plis effectués au cours d'une manche";
	$p["deck"] = 0; // "array du jeu de cartes";
	$p["next"] = 0; // "celui qui doit jouer, là, maintenant";
	$p["beginner"] = 0; // "celui qui commence le pli";
	$p["annonce"] = false; // "si les annonces sont terminées";
	$p["contract"] = array(); // "Tableau des contrats";
	$p["dones"] = array(); //"Tableau du nombre de plis réalisés";
	$p["score"] = array(); //"Tableau des scores";
	$p["pli_actuel"] =  array(); //"Tableau du pli en cours";
	$p["numtour"] = 0; // "numero du tour au cours d'une manche. varie de 5 à 1";
	$p["manche"] = 0; // "Numero de manche. varie de 5 à 0";
	$p["croupier"] = 0; // "Numero du croupier. A initialiser et à incrémenter";
	$p["timestamp"] = time(); // "Pas encore utilisé : le timestamp du dernier ajout de joueur";
	// autre truc pour l'ajax ?
	return $p;
}



///////////////////////////////
// FONCTIONS DE MAINTENANCE  //
///////////////////////////////

function make_bot_play($p) {
	if(count($p["name"]) < 4) {
		$attente_max = rand(5,10)/5; //temps (secondes) que les bots mettent à joindre la partie
		$bots = array("R2-D2", "C3PO", "R4-P17", "Eto Demerzel", "Marvin", "Dors Venabili");
		if(count($p["name"]) < 4 && $p["timestamp"] + $attente_max < time()) {
			add_player(&$p, $bots[rand(0, count($bots)-1)], true);
			return true;
		}
		return false;
	} else {
		$attente_max = rand(1, 4)/4; //temps (secondes) que les bots mettent à jouer
		if($p["bot"][$p["next"]] && $p["timestamp"] + $attente_max < time()) {
			if(!$p["annonce"]) {
				$bot = $p["next"];
				bet(&$p, $bot, ia_pari($p["cards"][$bot], $p["contract"], max(1,$p["manche"])));
			} else {
				play(&$p, $p["next"], ia_jouer($p, $p["next"]));
			}
			return true;
		}
	}
}

function update($p) {
	$compteur = 0;
	while(make_bot_play(&$p)) $compteur++;
	return $compteur>0;
}








///////////////////////////////////////
// FONCTIONS D'AFFICHAGE / DEBUGGAGE //
///////////////////////////////////////

function comment($p, $texte) {
	$p["comments"][] = $texte;
	while (count($p["comments"])>3) 
		array_shift($p["comments"]);
}


function disp_for_player($p, $joueur) {
	page_player($p, $joueur);
}


//////////////////////////////////////////
// FONCTION D'INTELLIGENCE ARTIFICIELLE //
//////////////////////////////////////////

function ia_pari($cartes, $paris, $tour) {
	$x = rand()/getrandmax();
	$c = $x * $x * $x;
	return intval($c * ($tour+0.9));
}

function ia_jouer($p, $joueur) {
	$x = rand()/getrandmax();
	$c = $x * $x * $x;
	$ncards = max(1, $p["manche"]);
	//return $p["cards"][$joueur][$c * ($ncards+0.3)];
	return max($p["cards"][$joueur]);
}





////////////////////////////////
// FONCTIONS DE GESTION MYSQL //
////////////////////////////////

include "login.php";

function connect() {
	list($host, $user, $pass, $base) = get_login();
	mysql_connect($host, $user, $pass);
	mysql_select_db($base) or die('Could not select database');
}

function mysql_fetch_arrays($result) {
	$i=0;
	$tab=array();
	while($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$tab[$i] = $line;
		$i++;
	}
	return $tab;
}

function get_free_parties() {
	$serial = addslashes(serialize(new_partie()));
	$query = 'SELECT `id`, `full` FROM `parties` WHERE `full`<4';
	$result = mysql_query($query) or die('Query failed1: ' . mysql_error());
	$tab = mysql_fetch_arrays($result);
	if(count($tab)==0) {
		$query = "INSERT INTO `parties` ";
		$query.= "(`id` ,`partie` ,`next` ,`mod` ,`full`) ";
		$query.= "VALUES (NULL , '$serial', '-1', '0', '0');";
		mysql_query($query) or die('Query failed2: ' . mysql_error());
		$query = 'SELECT `id`, `full` FROM `parties` WHERE `full`< 4';
		$result = mysql_query($query) or die('Query failed3: ' . mysql_error());
		$tab = mysql_fetch_arrays($result);
	}
	return $tab;
}

function load_partie($id) {
	$query = "SELECT `partie` FROM `parties` WHERE `id`=$id";
	$result = mysql_query($query) or die('Query failed lp: ' . mysql_error());
	$line = mysql_fetch_array($result, MYSQL_ASSOC);
	$partie = unserialize($line["partie"]);
	return $partie;
}

function save_partie($id, $partie) {
	$serial = addslashes(serialize($partie));
	$full = count($partie["name"]);
	$query = "UPDATE `parties` SET `partie` = '$serial', `full` = '$full' WHERE `id` = $id";
	$result = mysql_query($query) or die('Query failed sp: ' . mysql_error());
}












//
//  makeJavaScriptArray - Returns a JavaScript array constant created from the provided PHP nested array.
//                        this function is typically called to create a response to an Ajax request. 
//
//  Note: While this function will protect the JavaScript code from special characters, the calling code
//        still needs to encode any HTML entities before sending the result to the browser.
//

function makeJavaScriptObject( $phpArray ) {
	$arrayConstant = '{';
	$delimiter = '';

	foreach ($phpArray as $fieldName => $fieldValue) {
		if (is_bool( $fieldValue ))                                    // Boolean data type
			if ($fieldValue) $fieldConstant = 'true';
			else $fieldConstant = 'false';

		elseif (is_numeric( $fieldValue ))                            // Numeric data type
			$fieldConstant = $fieldValue;

		elseif (is_string( $fieldValue ))                            // String data type
			$fieldConstant = "'" . addSlashes( $fieldValue ) . "'";

		elseif (is_array( $fieldValue ))                            // Array data type
			$fieldConstant = makeJavaScriptObject( $fieldValue );

		else                                                        // Unknown data type
			$fieldConstant = '';

		if ($fieldConstant > '') {
			$arrayConstant .= $delimiter . " '$fieldName': $fieldConstant";
			$delimiter = ',';
		}
	}
	$arrayConstant .= ' }';
	return $arrayConstant;
}

function makeJavaScriptArray( $phpArray ) {
	$arrayConstant = '[';
	$delimiter = '';
	$i=0;
	foreach ($phpArray as $fieldName => $fieldValue) {
		if (is_bool( $fieldValue ))                                    // Boolean data type
			if ($fieldValue) $fieldConstant = 'true';
			else $fieldConstant = 'false';

		elseif (is_numeric( $fieldValue ))                            // Numeric data type
			$fieldConstant = $fieldValue;

		elseif (is_string( $fieldValue ))                            // String data type
			$fieldConstant = "'" . addSlashes( $fieldValue ) . "'";

		elseif (is_array( $fieldValue ))                            // Array data type
			$fieldConstant = makeJavaScriptArray( $fieldValue );

		else                                                        // Unknown data type
			$fieldConstant = '';

		if ($fieldConstant > '') {
			$arrayConstant .= $delimiter . "$fieldConstant";
			$delimiter = ', ';
		}
	}
	$arrayConstant .= ']';
	return $arrayConstant;
}






////////////////////////////////
// AFFICHAGE DES DONNEES AJAX //
////////////////////////////////
function dump($val) {
	if(gettype($val) == "string") {
		return '"' . $val . '"';
	} elseif (gettype($val) == "integer") {
		return '' . $val;
	} elseif (gettype($val) == "array") {
		$array = array();
		foreach($val as $elem) {
			$array[]=dump($elem);
		}
		return '[' . implode(', ', $array) . ']';
	}
}

function ajax_for_player($p, $joueur) {
	$names = cyclic_permutation($p["name"]      , $joueur);
	$scores= cyclic_permutation($p["score"]     , $joueur);
	$goals = cyclic_permutation($p["contract"]  , $joueur);
	$dones = cyclic_permutation($p["dones"]     , $joueur);
		$pli_ec = $p["pli_actuel"];
		$pli_ec = array_pad($pli_ec, 4, "-");
		$pli_ec = cyclic_permutation($pli_ec, 4-$p["beginner"]);
		$pli_ec = cyclic_permutation($pli_ec, $joueur);
	$all = $pli_ec;
	$you = $p["cards"][$joueur];
	$dealer   = ($p["croupier"] - $joueur + 4)%4;
	$beginner = ($p["beginner"] - $joueur + 4)%4;
	$next     = ($p["next"]     - $joueur + 4)%4;
	$forbidden = forbidden_bet($p, $joueur);
	echo "{\n";
		$ser=dump($names);    echo "	names : $ser,\n";
		$ser=dump($scores);   echo "	scores : $ser,\n";
		$ser=dump($goals);    echo "	goals : $ser,\n";
		$ser=dump($dones);    echo "	dones : $ser,\n";
		$ser=dump($you);      echo "	you : $ser,\n";
		$ser=dump($all);      echo "	all : $ser,\n";
		$ser=dump($beginner); echo "	beginner : $ser,\n";
		$ser=dump($next);     echo "	next : $ser,\n";
		$ser=dump($dealer);   echo "	dealer : $ser,\n";
		$ser=dump($forbidden);echo "	forbidden : $ser\n";
	echo "}";
}













?>
