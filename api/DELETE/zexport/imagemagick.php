<?php
$inFile = "/home2/caraburo/www/swd/data/120201-20151224120238_1452811643.pdf";
$outFile = "/home2/caraburo/www/swd/data/test-cropped.jpg";
$image = new Imagick($inFile);
//$image->cropImage(800,800, 0,0);
$image->writeImage($outFile);
?>