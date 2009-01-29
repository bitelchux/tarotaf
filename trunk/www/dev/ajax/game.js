var DEBUG = 0;




var LOGIN = false;
var OK = false;
var NOM = false;
var PARTIES = false;


function post_name() {
	LOGIN = $("login");
	OK = $("login_OK");
	NOM = $("login_nom");
	PARTIES = $("parties");
	NOM.disable();
	OK.disable();
	OK.blur();
	callback=function(t) {
		parties=eval(t);
		for(i=0; i<parties.length; i++) {
			var item = document.createElement("li");
			texte = "Partie n°" + parties[i][0] + " : nombre de joueurs : ";
			texte += parties[i][1] + " <a href='#' onclick='return join_game(";
			texte += parties[i][0] + ")'>rejoindre</a>";
			item.innerHTML = texte;
			PARTIES.empty();
			PARTIES.appendChild(item);
			PARTIES.flush();
			PARTIES.show();
		}
		if(DEBUG) join_game(parties[0][0]); // simulation du click.  DEBUG
		LOGIN.hide();
	};
	error_fun = function (t) {
		OK.enable();
		NOM.enable();
		NOM.focus();
	};
	request("start.php", callback, "nom="+NOM.value, error_fun);
	return false;
}

function join_game(id) {
	callback = function(t) {
		if(t.match(/Error/) != null) {
			alert("impossible de rejoindre la partie.");
		} else {
			PARTIES.hide();
			wait_for_players([]);
			update();
		}
	};
	request("join.php?id="+id, callback);
	return false;
}


// penser à la construction suivante :  a={cons : 3, let : 4}  // JSON ?

// "prototype" de création d'élément (fade, roll, ...)



Object.prototype.add_card = function(valeur, playable) {
	c = getCarte(valeur, playable);
	this.innerHTML += "\n\t\t\t";
	this.appendChild(c);
	this.innerHTML += "";	
}

Object.prototype.addElement = function(tag, content) {
	el = document.createElement(tag);
	el.innerHTML = content;
	this.appendChild(el);
	this.flush();
	return el;
}

last_waiters = [];
function wait_for_players(waiters) {
	$("waiting_room").show();
	if(waiters !== last_waiters) {
		$("present_players").empty();
		for(var i=0; i<waiters.length; i++) {
			$("present_players").addElement("li", waiters[i]);
		}
		last_waiters = waiters;
	}
}

function new_mail(text, duration) {
	box = document.createElement("div");
	box.style.padding = "10px";
	box.style.backgroundColor = "#889";
	box.style.border = "1px solid #555";
	box.addText(text);
	box.style.position = "fixed";
	box.style.width = "200px";
	box.style.height = "80px";
	box.style.top = "200px";
	box.style.left = "300px";
	box.style.textAlign = "center";
	id=identify(fond);
	setTimeout("$('" + id + "').hide(); $('waiting_room').hide()", duration*1000);
	return box;
}

function new_intrusive(text, duration) {
	fond = document.createElement("div");
	fond.style.position = "fixed";
	fond.style.top = "0px";
	fond.style.left = "0px";
	fond.style.width = "100%";
	fond.style.height = "100%";
	fond.style.backgroundColor = "#888";
	fond.set_opacity(0.3);
	box = document.createElement("div");
	box.style.padding = "10px";
	box.style.backgroundColor = "#889";
	box.style.border = "1px solid #555";
	box.addText(text);
	box.style.position = "fixed";
	box.style.width = "200px";
	box.style.height = "80px";
	box.style.top = "200px";
	box.style.left = "300px";
	box.style.textAlign = "center";
	document.body.appendChild(box);
	document.body.appendChild(fond);
	idbox=identify(box);
	idfond=identify(fond);
	document.body.appendChild(box);
	setTimeout("$('"+idbox+"').hide();$('"+idfond+"').hide();$('waiting_room').hide()", duration*1000);
	return box;
}

var ids = ["jb", "jg", "jh", "jd"];

var already_prepared = false;
function prepare_game(game, force) {
	if(!already_prepared || force) {
		already_prepared = true;
		$("main").hide();
		$("plateau").show();
		for(var i=0; i<4; i++) {
			$(ids[i]+'_name').innerHTML = game.names[i];
		}
	}
}

var last_handle_playable = false;
function build_handle(handle, playable) {
	if(last_handle_playable != [handle, playable]) {
		last_handle_playable = [handle, playable];
		for(var i=0; i<5; i++) {
			$("handle"+i).empty();
		}
		for(var i=0; i<handle.length; i++) {
			//$("handle"+i).empty();
			if(handle[i]<22) {
				$("handle"+i).appendChild(getCarte(handle[i]));
				if(playable) {
					todo = "play_card("+handle[i]+"); pulse($('handle"+i+"'));";
					$("handle"+i).firstChild.setAttribute("onclick", todo);
				}
			} else {
				$("handle"+i).appendChild(getCarte("Excuse"));
				if(playable) {
					todo = "pulse($('handle"+i+"')); ask_excuse()";
					$("handle"+i).firstChild.setAttribute("onclick", todo);
				}
				
			}
		}
	}
}

function ask_excuse() {
	$("ask_excuse").show();
}

function update_game(game) {
	for(var i=0; i<4; i++) {
		$(ids[i]+'_score').innerHTML = game.scores[i];
		$(ids[i]+'_dones').innerHTML = game.dones[i];
		$(ids[i]+'_goal').innerHTML = game.goals[i];
	}
	if(game.goals.countElements("-") > 0) {
		for(var i=0; i<4; i++) {
			$(ids[i]).style.backgroundImage = "url(../minis/min99.png)";
		}
		build_handle(game.you, false);
		if(game.next!=0) {
			$("annonces").hide();
		} else {
			$("annonces").show();
			$("bets").innerHTML = "";
			for(var i=0; i<1+game.you.length; i++) {
				var bet=document.createElement("a");
				bet.innerHTML=i;
				bet.href="javascript:bet(" + i + ");";
				bet.className = "bet";
				if(i==game.forbidden) bet.style.color = "red";
				$("bets").appendChild(bet);
			}
		}
	} else {
		$("annonces").hide();
		build_handle(game.you, game.next==0);
		for(var i=0; i<4; i++) {
			num = game.all[i];
			if(num=="-") num=99;
			$(ids[i]).style.backgroundImage = "url(../minis/min"+zero(num)+".png)";
		}
	}
}

function bet(n) {
	request("bet.php?bet=" + n);
}

function play(n) {
	if(n==0 || n==22) {
		$("ask_excuse").hide();
	}
	request("play.php?card=" + n);
}

var already_joined = false;

function treat_response(text) {
	//text=text.replace(/\n/g, '');
	game = eval('('+text+')');   /// f**king parentheses
	if(game.names.length < 4) {
		wait_for_players(game.names);
	} else {
		if(!already_joined) {
			wait_for_players(game.names);
			already_joined = true;
			new_intrusive("Tout le monde il est là !", 1.2);
		} else {
			prepare_game(game);
			update_game(game);
		}
	}
}


/****************************************************/
/*******************  UPDATING  *********************/
/****************************************************/


function stop_update() {
	clearTimeout(idtimeout);
}

var idtimeout=false;
function update() {
	request("update.php", treat_response);
	idtimeout = setTimeout("update();", 1000);
}







/****************************************************/
/*******************  CARTES  ***********************/
/****************************************************/

function zero(n) {if(n<10)return ("0"+n);else return (""+n);}
function card(n, url) {return url.replace(/NUMBER/, zero(n));}

atouts  = "../minis/minNUMBER.png";
function atout(n) {return card(n, atouts);}

Atouts = range(0, 23).map(atout);
Excuse = atout("Excuse");
Rien = atout(99);




/*** Barre de chargement des images ***/
remaining = 0;total = 0;
function complete(IMG) {remaining--;update_complete();}
function add(IMG) {remaining++;total++;update_complete();}
function update_complete() {
	BAR = $("barcompletes");
	NUMBER = $("completes");
	PROG = $("bar");
	NUMBER.innerHTML= Math.round((total-remaining)/total*100) + "%";
	PROG.style.width = (total-remaining)/total*100 + "%";
	if(remaining) BAR.show(); else BAR.hide();
}



/*** CHARGER UNE LISTE D'IMAGES ***/
function load_images(urls) {
	IMG = new Array();
	for(i=0; i<urls.length; i++) {
		add();
		IMG[i] = new Image() //(20, 34);
		IMG[i].load(urls[i], complete);
		//IMG[i].src = urls[i];
		//IMG[i].onload = complete;   // Active la barre de chargement.
	}
	return IMG
}


/*** CONSTRUCTION DE L'OBJET CARTE ***/
function getCarte(valeur, playable) {
	if(playable===undefined) {playable = false};
	C = false;
	if(0 <= valeur && valeur <= 22) {
		C = ATOUTS[valeur];
	} else {
		C = EXCUSE;
	}
	carte = C.cloneNode(true);
	carte.className = "card";
	carte.alt = valeur;
	id=identify(carte);
	/*if(playable) {
		carte.setAttribute("onclick", "javascript:play_card("+valeur+")");
		carte.setAttribute("onclick", "javascript:pulse($('"+carte.id+"'))");
	}*/
	return carte;
}

var BAS=1; var HAUT=2; var GAUCHE=3; var DROITE=4;
function play_card(valeur) {
	joueur = $("jb");
	joueur.style.backgroundImage="url(../minis/min" + zero(valeur) + ".png)";
	play(valeur);
}

function start() {
	ATOUTS = load_images(Atouts);
	EXCUSE = load_images([Excuse])[0];
	RIEN = load_images([Rien])[0];
	if(DEBUG) $("login_OK").click();  //DEBUG
}




