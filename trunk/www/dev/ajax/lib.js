/****************************************************/
/*****************  AJAX  ***************************/
/****************************************************/


function get_XMLHttpRequest() {
	var instance = false;
	/* Internet Explorer.
	@if (@_jscript_version >= 5)
		try {instance = new ActiveXObject("Msxml2.XMLHTTP");}
		catch (e){try {instance = new ActiveXObject("Microsoft.XMLHTTP");} 
					catch (E) {instance = false;}}
	@else
		instance = false;
	@end @*/
	if (!instance && typeof XMLHttpRequest != 'undefined') {
		try {instance = new XMLHttpRequest();}
		catch (e) {instance = false;}
	}
	return instance;
}

// Fonction perso "request" :
// Pour une requête GET, faire request("get.php?arg=val", callback_function);
// Pour une requête POST, faire request("post.php", callback_function, "arg=val");
// (les arguments passés dans l'url seront transmis en "GET" aussi)
// retourne l'instance de la requête pour un traitement éventuel ..

// faire une fonction d'erreur

function request(url, callback, vars, error_fun) {
	var method = false;

	if(vars===undefined) {
		method = "GET";
		vars = null;
	} else {
		method = "POST";
	}
	
	var req = get_XMLHttpRequest();
	
	var fun_exists = callback !== undefined;

	if(method == "GET") {
		req.open("GET", url, fun_exists);
	} else {
		req.open("POST", url, callback !== null);
		req.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	}

	req.onreadystatechange = function () {
		if(req.readyState == 4) {
			if(req.status == 200) {
				if(fun_exists) {callback(req.responseText);}
			} else {
				if(error_fun !== undefined)
					error_fun(req.status);
				else alert("Problème avec la requête en statut " + req.status + " URL : "+url);
			}
		}
	}

	req.send(vars);
	return req;
}

function req(url) {return !request(url, alert);}








/****************************************************/
/******************   DOM   *************************/
/****************************************************/


// SI DESACTIVE LA CAUSE EST LA REDONDANCE AVEC prototype.js
function $(id) {return document.getElementById(id);}









/****************************************************/
/*******************  ROUTINES  *********************/
/****************************************************/

var subs = [];
function Sub(command_, condition_, fin_, interval_) {
	this.command = command_;
	this.condition = condition_;
	this.fin = (fin_==null) ? function() {} : fin_;
	this.interval = (interval_===null) ? 10 : interval_;
	this.timeout = false;
	this.index = -1 + subs.push(this);
	this.done = false;
	this.go();
}

Sub.prototype.command;
Sub.prototype.condition;
Sub.prototype.fin;
Sub.prototype.interval;
Sub.prototype.timeout;
Sub.prototype.timeout;
Sub.prototype.index;
Sub.prototype.done;

Sub.prototype.go = function () {
	if(this.condition()) {
		this.command();
		global_name = "subs[" + this.index + "]";
		this.timeout = window.setTimeout(global_name+".go();", this.interval);
	} else {
		this.done = true;
		this.fin();
	}
}

Sub.prototype.stop = function () {
	window.clearTimeout(this.timeout);
}







/****************************************************/
/*******************  IMAGES  ***********************/
/****************************************************/

Image.prototype.source;
Image.prototype.index;
Image.prototype.onComplete;

Images = [];

Image.prototype.load = function(url, onComplete) {
	this.src = url;
	this.index = Images.push(this) - 1;
	this.onComplete = onComplete;
	this.check();
}

Image.prototype.check = function() {
	if(this.complete) {
		if(this.onComplete !== undefined) this.onComplete(this);
	} else {
		setTimeout("Images[" + this.index +"].check()", 50);
	}
}

function new_Image(url, onComplete) {
	image = new Image();
	image.load(url, onComplete);
}

/* UTILISATION :
créer un nouvel élément de la classe Image avec "img = new Image()",
puis appeler "img.load(url, callback)", ou url est l'url relative 
de l'image et callback (facultatif) est la fonction qui intervient 
quand l'image sera chargée, prenant l'image en argument.

var image = new Image();
image.load(
	"http://zedax.com/upload/images/Prototypage/zedax-geom-HR.jpg",
	function(img) {$("images").appendChild(img);}
);
*/




/****************************************************/
/*******************  ARRAYS  ***********************/
/****************************************************/

function range(debut, fin, pas) {
	if(pas===undefined) pas=1;
	if(fin===undefined) {fin=debut;debut=0;}
	a=[]; ind=0;
	if(debut>fin) {t=fin; fin=debut; debut=t;}
	if(debut===undefined) debut=0;
	if(pas<0) {pas*=-1;}
	for(i=debut; i<fin; i+=pas) {
		a[ind]=i;
		ind++;
	}
	return a;
}

Array.prototype.countElements = function(elem) {
	var compteur=0;
	for(var i=0; i<this.length; i++) {
		if(this[i]==elem) compteur++;
	}
	return compteur;
}



/****************************************************/
/*******************  ID  ***************************/
/****************************************************/


random_int = function(n) {
	x = Math.random() * n;
	x = parseInt(x);
	return x;
}

function rand_char() {return "abcdefghijklmnopqrstuvwxyz".charAt(random_int(26));}

// Retourne l'id d'un object, en en créant un nouveau s'il n'existe pas.
function identify(object) {
	nom = object.id;
	if(object.id == undefined || object.id == null || object.id == "") {
		nom="autoID_" + rand_char() + rand_char();
		while(document.getElementById(nom) !== null) {
			nom += rand_char();
		}
		object.id = nom;
	}
	return nom;
}






/****************************************************/
/*******************  PROTOTYPAGE  ******************/
/****************************************************/

Object.prototype.set_opacity = function(x) {
	this.style.filter = "alpha(opacity=" + (x*100) + ")";	
	this.style.opacity = x;
}

Object.prototype.hide = function () {this.style.display = "none";};
Object.prototype.show = function () {this.style.display = "block";};

Object.prototype.disable = function () {this.disabled = false;};
Object.prototype.enable = function () {this.disabled = true;};

Object.prototype.addText = function(t) {this.appendChild(document.createTextNode(t));}
Object.prototype.flush = function() {this.addText("\n");}
Object.prototype.empty = function() {this.innerHTML="";}




/****************************************************/
/*******************  ANIMS  ************************/
/****************************************************/


var data_pulse = new Array();
var debug_id=[];
function pulse(objet) {
	id = identify(objet); debug_id.push(id);
	data_pulse[id] = 0;
	pulse_go(id);
}

function integ(x) {
	return parseInt(x);
}

function trig(x) {
	return 2*Math.abs(x-integ(x)-0.5);
}

function pulse_go(id) {
	$(id).set_opacity(trig(data_pulse[id]));
	data_pulse[id] += 0.1/3;
	if(data_pulse[id] <= 1.01) {
		setTimeout("pulse_go(\"" + id + "\");", 10);
	}
}






/****************************************************/
/*******************  GRAPH  ************************/
/****************************************************/

function getStyleTag(t) {
	s = document.createElement("style");
	s.setAttribute("type", "text/css");
	s.setAttribute("media", "screen");
	s.addText(t);
	return s;
}

function graph(f) {
	s = getStyleTag(".plot{width:1px;height:1px;position:fixed;background-color:black;}");
	document.body.appendChild(s);
	document.body.flush();
	plot = function(x, y) {
		div=document.createElement("div");
		div.style.bottom=y+"px";
		div.style.left=x+"px";
		div.className="plot";
		document.body.appendChild(div);
		document.body.flush();
	};
	for(var i=0; i<=300; i++) {
		plot(i, (f(i/100)+1)*200);
	}
}


































