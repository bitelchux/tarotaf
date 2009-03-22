<?php

/*
 * RESPOND TO REQUESTS, HANDLE SQL COMMUNICATIONS : USERS AND GAMES ("TOP" LEVEL)
 */

require_once("sqlconfig.php");
require_once("com.php");
require_once("linking.php");

function get_available_games_list() {
	$gametable = get_table_name('games');
	$lines = get("SELECT `id`, `type`, `remaining`, `room` FROM `taf_games` WHERE `remaining` >= 1");
	return $lines;
}

function get_available_games_list_from_room($room) {
	$gametable = get_table_name('games');
	$lines = get("SELECT `id`, `type`, `remaining`, `room` FROM `taf_games` WHERE `remaining` >= 1 AND `room` = '$room'");
	return $lines;
}

function get_update() {
	is_logged_in() or fail("update", "Not logged in or session expired");
	$username = $_SESSION['user'];
	$answer = new answer($username, new query("update", "success", "Giving update information."));
	
	$gameids =  $_SESSION['games'];
	$condition = "`id` = '" . implode("' OR `id` = '", $gameids) . "'";
	$condition = "0 OR " . $condition;
	
	$gametable = get_table_name('games');
	$lines = get("SELECT * FROM `$gametable` WHERE $condition");
	
	$answer -> used_games = array();
	for($i=0; $i<count($lines); $i++) {
		$game_array = $lines[$i];
		$type = $game_array['type'];
		$serial = $game_array['obj'];
		$players = $game_array['players'];
		$numplayer = array_search($username, explode(", ", $players));
		if($numplayer===FALSE)
			fail("internal", "$username not found. Should not happen.");
		$rgame = sqlline_to_relative_object($numplayer, $type, $serial, $_SESSION['user']);
		
		$answer -> used_games[] = 
			array(
				'id' => $game_array['gameid'], 
				'type' => $type,
				'players' => $players,
				'room' => $game_array['room'],
				'numplayer' => $numplayer,
				'object' => $rgame,
			);
	}
	
	// update.php doesn't give such information :
	$answer -> available_games = NULL;
	$answer -> rooms = NULL;
	
	return $answer;
}

function join_game($gameid) {
	is_logged_in() or fail("join", "Not logged in or session expired");

	$gametable = get_table_name('games');
	$lines = get("SELECT * FROM `$gametable` WHERE `id` = '$gameid'");
	
	$game = $lines[0] or fail("join", "game not found");
	
	$remaining = $game['remaining'];
	$type = $game['type'];
	$serial = $game['obj'];
	$players = $game['players'];
	
	if(($remaining+0) <= 0)
		fail("join", "this game does not accept any more player");
	
	if(array_search($_SESSION['user'], explode(", ", $players))!==FALSE)
		fail("join", "you are already a player of the game");
	
	if($players)
		$players .= ", ";
	$players .= $_SESSION['user'];
	$remaining--;
	
	$modify_game = "";
	if($remaining==0) {
		//START THE GAME !
		$game_obj = unserialize($serial);
		$game_obj -> new_game();
		$nserial = serialize($game_obj);
		$modify_game = ", `obj` = '$nserial' ";
	}
		
	
	$req = "UPDATE `$gametable` ";
	$req.= "SET `remaining` = '$remaining', `players` = '$players' $modify_game";
	$req.= "WHERE `id` = $gameid LIMIT 1 ;";
	send($req);
	
	$_SESSION["games"][] = $gameid;
	
	$answer = get_update();
	
	$username = $_SESSION['user'];
	$answer -> query = new query("join", "success", "joining game $gameid");
	$answer -> username = $username;
	
	return $answer;
}


function action($gameid, $action) {
	is_logged_in() or fail("action", "Not logged in or session expired");
	
	$gametable = get_table_name('games');

	$lines = get("SELECT * FROM `$gametable` WHERE `id` = '$gameid'");

	$game = $lines[0] or fail("action", "game not found");

	if($game['remaining']>0) fail("action", "game not full");

	$type = $game['type'];
	$obj = $game['obj'];
	$players = explode(", ", $game['players']);
	$numplayer = array_search($user, $players);
	
	if($numplayer===FALSE) fail("internal", "user not in game, should be impossible !");
	
	$game_obj = unserialize($obj);
	$game_obj -> action($numplayer, $action);
	$res_obj = serialize($game_obj);
	
	$req = "UPDATE `$gametable` ";
	$req.= "SET `obj` = '$res_obj' ";
	$req.= "WHERE `id` = $gameid LIMIT 1 ;";
	send($req);
	
	$answer = get_update();
	
	$answer -> query = new query("action", "success", "on game <$gamid> action <$action>");
	return $answer;
}

function list_games() {
	$answer = get_update();
	$answer -> query = new query("list", "success","retrieving a list of the game");
	$answer -> available_games = get_available_games_list();
	return $answer;
}

function create_game($type, $room, $options="") {
	is_logged_in() or fail("create", "Not logged in or session expired");
	is_true_game($type) or fail("create", "$type is not a valid game");
	
	eval("\$new_game = new $type($options);");
	$new_game or fail("internal", "unable to instanciate a game of the type $type");
	$obj = serialize($new_game);
	
	$gametable = get_table_name('games');
	$req = "INSERT INTO `$gametable` ";
	$req.= "       (`id`, `obj` , `type` , `remaining`, `room` , `players`) ";
	$req.= "VALUES (NULL, '$obj', '$type', '4'        , '$room', '');";
	
	send($req);
	
	$answer = list_games();
	$answer -> query = new query("create", "success", "game created. Yay.");
	return $answer;
}


?>
