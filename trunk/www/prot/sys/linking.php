<?php

require_once("com.php");

/*
 * LINK ACTUAL GAMES AND HANDLE SOME CONVERSIONS OF TYPES
 */

$true_games = array("whist22", "belote", "tarot", "coinche");

require_once("games/whist22.php");

function is_true_game($type) {
	global $true_games;
	return array_search($type, $true_games)!==FALSE;
}

function sqlline_to_object($type, $serial) {
	//$type = $game_array['type'];
	//$serial = $game_array['obj'];
	
	$new_game_object = unserialize($serial);
	if($new_game_object===FALSE) {
		fail("internal", "invalid serial");
	}
	
	return $new_game_object;
}

function sqlline_to_relative_object($numplayer, $type, $serial) {
	//$players = explode(", ", $game_array['players']);
	//$numplayer = array_search($user, $players);
	
	if($numplayer===FALSE)
		fail("internal", "user ($user) not in game, should be impossible. By the way, this message should not even be displayed");
	
	$game_object = sqlline_to_object($type, $serial);
	$relative_object = $game_object -> relativize($numplayer);
	return $relative_object;
}





?>
