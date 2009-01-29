var w = 500;
var vari = 0;
var rolling_timout = false;

var parties = false;

/*function roll_in(width) {
	signe = (w>width)?">":"<";
	limit = (w>width)?width+0.4:width-0.4;
	proceed("w="+width+"+(w-"+width+")*0.9; $('main').style.width=w+'px';", "w"+signe+limit);
	return false;
}*/

/*function proceed(commande, condition) {
	if(rolling_timout) {clearTimeout(rolling_timout);}
	eval(commande);
	if(eval(condition)) {
		rolling_timout = window.setTimeout("proceed(\""+(commande)+"\", \""+(condition)+"\");", 0);
	}
}*/

/*function proceed(commande, condition, fin) {
	if(rolling_timout) {clearTimeout(rolling_timout);}
	eval(commande);
	if(eval(condition)) {
		rolling_timout = window.setTimeout("proceed(\""+(commande)+"\", \""+(condition)+"\", \""+(fin)+"\");", 0);
	} else {
		if(fin !== null) {eval(fin);}
	}
}*/


// quasiment obsolète
function proceed(commande_str, condition_str, fin_str) {
	command   = function () { eval(commande_str); }
	condition = function () { return eval(condition_str); }
	fin       = function () { eval(fin_str); }
	new Sub(command, condition, fin);
}

/*function roll_in(width, init_width) {
	w = (init_width == null) ? 500 : init_width;
	greaten = (w<width);
	command = function() {
		w=width+(w-width)*0.9;
		$('main').style.width=w+'px';
	};
	condition = function () {
		if(greaten) return w < width - 5.4;
		else return w > width + 5.4;
	};
	new Sub(command, condition);
	return false;
}*/

roll_object = false;
roll_width = false;
roll_w = false;
roll_greaten = false;

function roll(object, width, init_width) {
	roll_object = object;
	roll_width = width;
	roll_w = (init_width == null) ? 500 : init_width;
	roll_greaten = (w<width);
	roll_object.style.overflow="hidden";
	command = function() {
		roll_w=roll_width+(roll_w-roll_width)*0.9;
		roll_object.style.width=roll_w+'px';
	};
	condition = function () {
		if(roll_greaten) return roll_w < roll_width - 5.4;
		else return roll_w > roll_width + 5.4;
	};
	new Sub(command, condition);
	return false;
}

function roll_in(width) {
	return roll($("main"), width, 500);
}

zoom_object = false;
zoom_width = false;
zoom_w = false;
zoom_greaten = false;

function zoom(object, width, init_width) {
	zoom_object = object;
	zoom_width = width;
	zoom_w = (init_width == null) ? 500 : init_width;
	zoom_greaten = (w<width);
	zoom_object.style.overflow="hidden";
	command = function() {
		zoom_w=zoom_width+(zoom_w-zoom_width)*0.95;
		zoom_object.style.fontSize=zoom_w+'px';
	};
	condition = function () {
		if(zoom_greaten) return zoom_w < zoom_width - 0.4;
		else return zoom_w > zoom_width + 0.4;
	};
	fin = function() {
		zoom_object.style.display = 'none';
	};
	new Sub(command, condition);
	return false;
}

fade_object = false;
fade_width = false;
fade_w = false;
fade_greaten = false;

function fade(object, width, init_width) {
	fade_object = object;
	fade_width = width;
	fade_w = (init_width == null) ? 10 : init_width;
	fade_greaten = (w<width);
	fade_object.style.overflow="hidden";
	command = function() {
		fade_w=fade_width+(fade_w-fade_width)*0.95;
		fade_object.style.opacity=fade_w+'';
	};
	condition = function () {
		if(fade_greaten) return fade_w < fade_width - 0.01;
		else return fade_w > fade_width + 0.01;
	};
	fin = function() {
		fade_object.style.display = 'none';
	};
	new Sub(command, condition, fin);
	return false;
}

function post_name() {
	$("login_OK").disabled = true;
	$("login_nom").disabled = true;
	$("login_OK").style.backgroundImage="url(loading.gif)";
	$("login_OK").style.backgroundPosition="center center";
	$("login_OK").blur();
	callback=function(t) {
		eval("parties="+t);
		for(i=0; i<parties.length; i++) {
			var item = document.createElement("li");
			item.innerHTML = "Partie n°" + parties[i][0] + " : nombre de joueurs : " + parties[i][1];
			item.innerHTML += " <a href='#' onclick='return join_game(" + parties[i][0] + ")'>rejoindre</a>";
			$("parties").appendChild(item);
			$("parties").innerHTML += "\n";
			$("parties").style.height = 0;
		}
		$("login_nom").style.visibility = "hidden";
		hide($("login"), 20);
		$("parties").style.display = "block";
		show($("parties"));
	};
	error_fun = function (t) {
		var info = document.createElement("span");
		info.innerHTML = t;
		info.id = "info_postname";
		info.style.position="relative";
		info.style.fontWeight="bold";
		info.style.color="red";
		$("post_name").appendChild(info);
		fade(info, 0, 10);
		$("login_OK").style.backgroundImage="none";
		$("login_OK").disabled = false;
		$("login_nom").disabled = false;
	};
	request("start.php", callback, "nom="+$("login_nom").value, error_fun);
	return false;
}

var hide_data = false;
var hide_object = false;
function hide(object, init_data) {
	hide_object = object;
	hide_data = (init_data == null) ? 50 : init_data;
	hide_object.style.overflow="hidden";
	command = function() {
		hide_data=hide_data*0.9;
		hide_object.style.height=hide_data+'px';
		marge = hide_data/10;
		hide_object.style.margin=marge+'px';
	};
	condition = function() {
		return hide_data>0.4;
	};
	fin = function() {
		setTimeout("hide_object.style.borderTop='0px solid black'", 100);
		setTimeout("hide_object.style.borderBottom='0px solid black'", 200);
		setTimeout("hide_object.style.display='none'", 400);
	};
	new Sub(command, condition, fin);
	return false;
}

var show_data = false;
var show_object = false;
function show(object) {
	show_object = object;
	show_data = 0;
	show_object.style.overflow="hidden";
	show_object.style.display='block';
	proceed("show_data=50+(show_data-50)*0.9; show_object.style.height=show_data+'px'; marge = show_data/10; show_object.style.margin=marge+'px';", "show_data<49.6");
	return false;
}

function join_game(id) {
	callback = function(t) {
		if(t.match(/Error/) != null) {
			alert("impossible de rejoindre la partie.");
		} else {
			hide($("parties"));
		}
	};
	request("nothing.php?id="+id, callback);
	return false;
}


/// penser à la construction suivante :  a={cons : 3, let : 4}




//"prototype" de création d'élément (fade, roll, ...)











