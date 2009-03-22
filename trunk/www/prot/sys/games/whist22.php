<?php

class whist22 {
	var $game;
	
	function whist22() {
		$this -> game = 'initialized';
	}
	
	function new_game() {
		$this -> game = array(
			"gametype" =>    "whist22",
			"bets" =>        "undef",
			"hands" =>       "undef",
			"wontricks" =>   "undef",
			"dealer" =>      0,
			"starter" =>     "undef",
			"betting" =>     "undef",
			"ncards" =>      5,
			"toplay" =>      "undef",
			"tricks" =>      "undef",
			"current_trick"=>"undef"
		);
		$this -> start_game();
		return array(TRUE, "game started");
	}
	
	function action($numplayer, $actionserial) {
		$array=explode("-", $actionserial);
		$action=$array[0];
		$numplayer=$numplayer+0;
		if($action=="play") {
			$card = $array[1]+0;
			$status = $this -> play($numplayer, $card);
			return $status;
		}
		if($action=="bet") {
			$bet = $array[1]+0;
			$status = $this -> bet($numplayer, $bet);
			return $status;
		}
		return array(FALSE, "action '$action' does not exist for whist22");
	}
	
	function relativize($numplayer) {
		$rgame = $this -> game;
		if(gettype($rgame)=="array") {
			foreach($rgame['hands'] as $i => $hand) {
				if($i!=$numplayer) {
					$rgame['hands'][$i]="invisible";
				}
			}
		}
		return $rgame;
	}
	
	function new_deck() {
		return range(1, 22);
	}
	
	function pop_val(&$array, $val) {
		if(!in_array($val, $array))
			return false;
		$i = array_search($val, $array);
		$n = count($array);
		for(;$i<=$n-2;$i++) {
			$array[$i] = $array[$i+1];
		}
		array_pop($array);
		return true;
	}

	
	function deal_cards() {
		$ncards = $this -> game["ncards"];
		$deck=whist22::new_deck();
		shuffle($deck) or die("shuffle deck error (!!)");
		for($i=0; $i<$ncards*4; $i++) {
			$this -> game["hands"][$i % 4][]=$deck[$i];
		}
	}
	
	function array_max($array) {
		$m=-1;
		foreach($array as $val)
			if($val>$m) $m=$val;
		return $m;
	}
	
	function start_trick() {
		$this -> game["toplay"] = $this -> game["starter"];
		$this -> game["current_trick"]=array(
			"starter" => $this -> game["starter"],
			"cards" => array(-1, -1, -1, -1)
		);
	}
	
	function end_trick() {
		$trick = $this->game["current_trick"]["cards"];
		$wincard = whist22::array_max($trick);
		$winner = array_search($wincard, $trick);
		if($winner===FALSE) die("end_trick() : error 42");
		$this -> game["starter"] = $winner;
		$this -> game["tricks"][]=$trick;
		$this -> game["wontricks"][$winner]++;
		if(count($this -> game["hands"][0]) == 0) {
			$this -> end_play();
		} else {
			$this -> start_trick();
		}
	}
	
	function start_play() {
		$this -> game["bets"] = array(-1, -1, -1, -1);
		$this -> game["hands"] = array(array(), array(), array(), array());
		$this -> game["starter"] = ($this -> game["dealer"]+1)%4;
		$this -> game["wontricks"]=array(0,0,0,0);
		$this -> game["betting"]=1;
		$this -> game["tricks"]=array();
		$this -> deal_cards();
		$this -> start_trick();
	}
	
	function end_play() {
		for($i=0;$i<4;$i++) {
			$wontricks = $this -> game["wontricks"][$i];
			$bet = $this -> game["bets"][$i];
			$bad = abs($wontricks-$bet);
			$this -> game["scores"][$i] -= $bad;
		}
		if($this -> game["ncards"]==1) {
			$this -> game["ncards"]=5;
			$this -> game["dealer"]=($this -> game["dealer"]+1)%4;
		} else {
			$this -> game["ncards"]--;
		}
		$this -> start_play();
	}
	
	function start_game() {
		$this -> game["ncards"] = 5;
		$this -> game["scores"] = array(0,0,0,0);
		$this -> start_play();
	}
	
	function moveon() {
		if(
			!$this -> game["betting"] &&
			$this -> game["current_trick"]["cards"][0]>=0 && 
			$this -> game["current_trick"]["cards"][1]>=0 && 
			$this -> game["current_trick"]["cards"][2]>=0 && 
			$this -> game["current_trick"]["cards"][3]>=0
		)
		{
			$this -> end_trick();
		}
		if(
			$this -> game["betting"] &&
			$this -> game["bets"][0]>=0 && 
			$this -> game["bets"][1]>=0 && 
			$this -> game["bets"][2]>=0 && 
			$this -> game["bets"][3]>=0
		)
			$this -> game["betting"]=0;
	}
	
	function play($numplayer, $card) {
		if($this->game["betting"]) {
			return array(FALSE, "Bets not ready");
		}
		if($numplayer != $this->game["toplay"])
			return array(FALSE, "Not your turn, $numplayer. ".$this->game['toplay']." is about to play.");
		if($card==0)
			$real_card=22;
		else
			$real_card=$card;
		if(!whist22::pop_val($this->game['hands'][$numplayer], $real_card))
			return array(FALSE, "The card $card is NOT in your hand.");
		$this->game["current_trick"]['cards'][$numplayer] = $card;
		$this->game["toplay"]=($this->game["toplay"]+1)%4;
		$this->moveon();
		return array(TRUE, "Card played : $card");
	}
	
	function sumwithout($array, $forbid) {
		$s=0;
		foreach($array as $i=>$n) {
			if($i!=$forbid)
				$s+=$n;
		}
		return $s;
	}
	
	function bet($numplayer, $bet) {
		if(!$this->game["betting"]) {
			return array(FALSE, "Bets already done");
		}
		$bet=$bet+0;
		if($bet<0)
			return array(FALSE, "I doubt you make $bet tricks.");
		if($numplayer != $this->game["toplay"])
			return array(FALSE, "Not your turn to bet, $numplayer. ".$this->game['toplay']." is about to play.");
		if(
			($numplayer+1)%4==$this->game["starter"] && 
			whist22::sumwithout($this->game["bets"], $numplayer)+$bet == count($this->game["hands"][0])
		)
			return array(FALSE, "You can't bet $bet.");
		$this->game["bets"][$numplayer] = $bet;
		$this->game["toplay"]=($this->game["toplay"]+1)%4;
		$this->moveon();
		return array(TRUE, "Bet done : $bet");
	}
}












?>
