<?php

// USE :
// url 
//    /rem/register.php
// arguments in POST : 
//    user=kevin2000
//    pass=yooman_172839
//    other if necessary ...

$here = getcwd();
chdir("../sys");
require_once("remote_register.php");
chdir($here);



?>
