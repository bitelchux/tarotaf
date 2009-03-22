<?php

// USE :
// url 
//    /rem/login.php
// arguments in POST : 
//    user=kevin2000
//    pass=yooman_172839

$here = getcwd();
chdir("../sys");
require_once("remote_login.php");
chdir($here);

?>
