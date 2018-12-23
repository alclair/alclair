<?php 
// include Barcode39 class 
// include "../../lib/BarCode/Barcode39.php"; 
include_once('../../vendor/fobiaweb/barcode39/Barcode39.php');
// set object 
$bc = new Barcode39("123-ABC"); 

// set text size 
$bc->barcode_text_size = 5; 

// set barcode bar thickness (thick bars) 
$bc->barcode_bar_thick = 4; 

// set barcode bar thickness (thin bars) 
$bc->barcode_bar_thin = 2; 

// save barcode GIF file 
$bc->draw(); 

?>