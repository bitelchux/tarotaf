<?php

// USE :
// url 
//    /rem/act.php
// arguments in GET (or POST if not in GET) : 
//    action=ACTIONOBJECT

$here = getcwd();
chdir("../sys");
require_once("remote_act.php");
chdir($here);

?>
