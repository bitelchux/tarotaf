<?php
session_start();
$_SESSION["incr"] ++;
	for($i=0; $i<=1500; $i++) {
		for($j=0; $j<=2000; $j++) {
			cos($i+$j);
		}
	}
header ("Content-type: image/png");
$im = @imagecreatetruecolor(120, 20)
      or die("Cannot Initialize new GD image stream");
$text_color = imagecolorallocate($im, 14, 91, 233);
imagestring($im, 1, 5, 5,  "inrc = " + $_SESSION["incr"], $text_color);
imagepng($im);
imagedestroy($im);
?>

