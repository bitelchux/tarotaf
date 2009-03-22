<html>
<head>
	<title>testing</title>
</head>

<?php
require_once("jsonformat.php");
require_once("whist22.php");
require_once("../libs/json.php");

if(!empty($_POST['serial'])) {
	$serial = $_POST['serial'];
	$action = $_POST['action'];
	$numplayer = $_POST['numplayer']+0;
	
	$object = unserialize(stripslashes($serial));
	echo "<!--\n";
	var_dump($serial);
	var_dump($object);
	echo "-->\n";
	if($_POST['action']!="")
		$result = $object -> action($numplayer, $action);
	else
		$result = "Enter a numplayer like '0' and an action like 'bet-0' or 'play-19'";
	$numplayer=$object->game["toplay"];
	$url = "rem/act.php?id=42&action=".$_POST['action'];
} else {
	$object = new whist22;
	$object -> new_game();
	$action = "bet-1";
	$numplayer=1;
}
$nserial = serialize($object);
?>

<script>

function link() {
	numplayer = <?php echo $numplayer; ?>;
	eval("game="+document.getElementById('object').innerHTML+";");
	hand=game['hands'][numplayer];
	pbutt=document.getElementById("playbuttons");
	for(i=0; i<hand.length; i++) {
		c=hand[i];
		if(c==22)
			pbutt.innerHTML += '<input type="button" value="play-0" onclick="document.forms[0].action.value=\'play-0\';document.forms[0].submit();" />\n';
		pbutt.innerHTML += '<input type="button" value="play-'+c+'" onclick="document.forms[0].action.value=\'play-'+c+'\';document.forms[0].submit();" />\n';
	}
}

</script>


<body onload="link()">

<form action="testingwhist22.php" method="POST">
	<input type="hidden" name="serial" value='<?php echo $nserial; ?>' />
	#<input type="text" name="numplayer" size="1" value='<?php echo $numplayer; ?>' />
	<input type="text" name="action" value='<?php echo $action; ?>' />
	<input type="submit" value="act" /> 
	<?php if($url) echo "(Url du type : " . $url . ")\n"; ?>
	<br \>
	bet:
	<input type="button" value="bet-0" onclick="document.forms[0].action.value='bet-0';document.forms[0].submit();" />
	<input type="button" value="bet-1" onclick="document.forms[0].action.value='bet-1';document.forms[0].submit();" />
	<input type="button" value="bet-2" onclick="document.forms[0].action.value='bet-2';document.forms[0].submit();" />
	<div id="playbuttons">
	</div>
	<br />
</form>

<?php if($result) echo "<pre>result : ". json_format(json_encode($result)) . " </pre>\n" ?>
<pre id="object"><?php echo json_format(json_encode($object->game)); ?></pre>


