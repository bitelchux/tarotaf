<html>
<head>
<title>Protocole de communication</title>
</head>
<body>

Normalement, il n'y a pas besoin de game.tricks.

<h2>Un joueur, toi.</h2>
<?php
    $hub=array(
        "id" => 1337,
        "username" => "hub56",
        "name" => "Hubert",
        "avatar" => "http://overcaffeinated.net/images/caovoador.jpg",
        "hand" => array(5, 11, 16, 20),
        "bet" => 2
    );
?>


<pre>
    array(
        "id" => 1337,
        "username" => "hub56",
        "name" => "Hubert",
        "avatar" => "http://overcaffeinated.net/images/caovoador.jpg",
        "hand" => array(5, 11, 16, 20),
        "bet" => 2
    );
</pre>
<?php echo json_encode($hub); ?>



<h2>Un joueur, pas toi.</h2>
<?php
$sou=array(
    "id" => 1337,
    "username" => "soute",
    "name" => "Soukakpool",
    "avatar" => "http://farm1.static.flickr.com/33/47768573_438c4d8f1a.jpg",
    "hand" => "invisible",
    "bet" => 1
);
?>
<pre>
    array(
        "id" => 1337,
        "username" => "soute",
        "name" => "Soukakpool",
        "avatar" => "http://farm1.static.flickr.com/33/47768573_438c4d8f1a.jpg",
        "hand" => "invisible",
        "bet" => 1
    );
</pre>
<?php echo json_encode($sou); ?>


<?php
$hub=array(
    "id" => 1337,
    "username" => "hub56",
    "name" => "Hubert",
    "avatar" => "http://overcaffeinated.net/images/caovoador.jpg",
    "hand" => array(5, 11, 16, 20),
    "bet" => 2
);


$sou=array(
    "id" => 1337,
    "username" => "soute",
    "name" => "Soukakpool",
    "avatar" => "http://farm1.static.flickr.com/33/47768573_438c4d8f1a.jpg",
    "hand" => "invisible",
    "bet" => 1
);
    
$bob=array(
    "id" => 1337,
    "username" => "bob2000",
    "name" => "Robert",
    "avatar" => "http://www.thomashudgins.com/picts/private_collections/bob2000.jpg",
    "hand" => "invisible",
    "bet" => 2
);

$game=array(
    "bets" => array(-1, -1, -1, -1),
    "hands" => array("invisible", "invisible", array(5, 11, 16, 20), "invisible"),
    "dealer" => 0,
    "starter" => 1,
    "toplay" => 3,
    "current_trick" => array(2, 12),
    "tricks" => array(array(3, 1, 7, 15))
);

?>
<h2>Un jeu</h2>
<pre>
    array(
        "gametype" => "whist22",
        "players" => array($hub, $manu, $sou, $bob),
        "dealer" => 0,
        "starter" => 1,
        "toplay" => 3,
        "current_trick" => array(2, 12),
        "tricks" => array(array(3, 1, 7, 15))
    );
</pre>
<?php echo json_encode($game); ?>


