<?php

function echoxml() {
	echo '<?xml version="1.0" encoding="UTF-8"?> ' . "\n";
}

function link_update() {
	echo "<div class='update'><p><a href='update.php'>MAJ</a></p></div>\n";
}

function graphic_disp_card($card, $classe_suppl="", $playable=false) {
	if($card=="-") {
		$card="&nbsp;";
		$classe_suppl = "carte_table carte_cachee";
	}
	$texte = $card;
	if($classe_suppl=="carte_poignee") $blanc="	";
	if($playable) {
		$classe_suppl .= " carte_jouable";
		$texte = "<a href='play.php?card=$card'>$card</a>";
	}
	if($card==22 && $playable) {
		$classe_suppl .= " carte_excuse";
		$texte = "<a class='excuse0' href='play.php?card=0'>0</a> <a class='excuse22' href='play.php?card=22'>22</a>";
	}
	echo "		$blanc	<div class='carte $classe_suppl'>$texte</div>\n";
}

function graphic_disp_handle($cards, $playable=false) {
	echo "			<div class='poignee'>\n";
	foreach($cards as $card) {
		graphic_disp_card($card, "carte_poignee", $playable);
	}
	echo "			<hr />\n";
	echo "			</div>\n";
}


function graphic_disp_player($infos, $i, $cards, $paris, $playable) {
	echo "\n";
	list($noms, $contrat, $realises, $ids, $score, $dealer, $manche, $pli_ec) = $infos;
	$orientation=array("bas", "gauche", "haut", "droite");
	echo "		<div class='joueur $orientation[$i]'>\n";
	graphic_disp_card($pli_ec[$i], "carte_table");
	echo "			<h3>$noms[$i]</h3>\n";
	if($dealer==$i)
		echo "			<p class='info_player'>Dealer</p>\n";
	//echo "			<p class='id'>$ids[$i]</p>\n";
	echo "			<p class='contrat'>Contrat : $realises[$i]/$contrat[$i]</p>\n";
	echo "			<p class='score'>Score : $score[$i]</p>\n";
	//echo "			<hr />\n";
	if($i==0) {
		if(!($manche==0 && $pli_ec[$i] == "-" && in_array("-", $contrat)) || $playable) graphic_disp_handle($cards, $playable);
		if(isset($paris)) {
			$ncartes = max($manche, 1);
			echo "			<div class='pari'>\n";
			echo "				<h4>Combien de plis pensez-vous faire ?</h4>\n";
			for($c=0; $c<=$ncartes; $c++) {
				if(in_array($c, $paris))
					echo "				<a href='bet.php?bet=$c'>$c</a>\n";
				else {
					//echo "				<span class='forbidden'>$c<span>La somme ferait $ncartes. Trouve autre chose.</span></span>\n";
					echo "				<span class='forbidden'>$c</span>\n";
				}
			}
			//echo "				<hr />\n";
			echo "			</div>\n";
		//echo "			<hr />\n";
		}
	}
	echo "		</div>\n";
}


function player($data, $position) {
	list($noms, $contrat, $realises, $ids, $score, $dealer, $manche, $pli_ec, $cartes, $joueur, $p) = $data;
	$num=array("bas"=>0, "gauche"=>1, "haut"=>2, "droite"=>3);
	$i = $num[$position];
	if((!$p["annonce"] and true) && $p["next"]==$joueur) /////////// enlever le or true
		$paris = available_bets($p, $joueur);
	else
		$paris=NULL;
	$infos = array($noms, $contrat, $realises, $ids, $score, $dealer, $manche, $pli_ec);
	$playable = ($p["annonce"] && $joueur == $p["next"]);
	graphic_disp_player($infos, $i, $cartes, $paris, $playable);
}



function disp_etat($data) {
	list($noms, $contrat, $realises, $ids, $score, $dealer, $manche, $pli_ec, $cartes, $joueur, $p) = $data;
	$prochain = $p["name"][$p["next"]];
	if($p["annonce"])
		echo "$prochain est en train de jouer ...<br />\n";
	else
		echo "$prochain est en train de parier ...<br />\n";
	foreach($p["comments"] as $ligne)
		echo "				$ligne<br />\n";
	// ON POURRAIT AJOUTER DES MESSAGES AVEC LES VARIABLES DE SESSION (du genre, machin a fait le pli)
	// DIRE QUE bidule EST LE CROUPIER
}



function page_player($p, $joueur) {
	if(count($p["name"]) != 4) {
		$nom = $p["name"][$joueur];
		
		echoxml();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" />
	<meta http-equiv="refresh" content="2" /> 
	<link href="defaut.css" rel="stylesheet" type="text/css" />
	<title>Tarotaf - attente des joueurs</title>
</head>

<body>
	<div class="main">
		<h2>Attente des joueurs</h2>
		<?php echo "";
		//link_update();
		echo "		<p>Joueurs presents :</p>\n";
		echo "		<ul>\n";
		foreach($p["name"] as $nom)
			echo "			<li>$nom</li>\n";
		echo "		</ul>\n";

		?>
	</div>
</body>
</html>
<?php 


	} else {
	 	$cartes = $p["cards"][$joueur];
		$contrat   = cyclic_permutation($p["contract"]  , $joueur);
		$realises  = cyclic_permutation($p["dones"]     , $joueur);
		$noms      = cyclic_permutation($p["name"]      , $joueur);
		$ids       = cyclic_permutation($p["idg"]       , $joueur);
		$score     = cyclic_permutation($p["score"]     , $joueur);
		$dealer = ($p["croupier"] - $joueur + 4)%4;
		
		$pli_ec = $p["pli_actuel"];
		$pli_ec = array_pad($pli_ec, 4, "-");
		$pli_ec = cyclic_permutation($pli_ec, 4-$p["beginner"]);
		$pli_ec = cyclic_permutation($pli_ec, $joueur);
		// $pli_ec est le pli en cours, mais si c'est la dernière manche, c'est juste le pli VISIBLE
		if($p["manche"]==0 && !$p["annonce"]) {
			$pli_ec = array('-');
			$pli_ec[] = $p["cards"][($joueur+1)%4][0];
			$pli_ec[] = $p["cards"][($joueur+2)%4][0];
			$pli_ec[] = $p["cards"][($joueur+3)%4][0];
		}
		$manche = $p["manche"];
	
		$data = array($noms, $contrat, $realises, $ids, $score, $dealer, $manche, $pli_ec, $cartes, $joueur, $p);
	
		
		echo '<?xml version="1.0" encoding="ISO-8859-1"?>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" />
	<meta http-equiv="refresh" content="1.5" />
	<link href="defaut.css" rel="stylesheet" type="text/css" />
	<title>Tarotaf - partie en cours</title>
</head>

<body>
	<div class="plateau">
		<?php player($data, 'haut'); ?>
		<?php player($data, 'gauche'); ?>
		
		<div class="etat">
			<p>
				<?php disp_etat($data); ?>
			</p>
		</div>
		<?php player($data, 'droite'); ?>
		<hr />
		<?php player($data, 'bas'); ?>
	</div>
</body>
</html>
<?php 
	}
}
?>
