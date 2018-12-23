<?php
ini_set('memory_limit','600M');
include_once "../../config.inc.php";
//include('../lib/full/qrlib.php');
include_once('../../lib/phpqrcode/qrlib.php');
include_once('../../lib/BarCode/Barcode39.php');
//include_once "../../includes/tcpdf/include/barcodes/qrcode.php";


 // outputs image directly into browser, as PNG stream 
    //QRcode::png('PHP QR Code :)');
// how to use image from example 001 
//echo '<img src="example_001_simple_png_output.php" />';
    
    // set Barcode39 object 
$tempDir = $rootScope["RootPath"]."data/export/";
//$fileName = 'Testing_barcode.png'; 
$pngAbsoluteFilePath = $tempDir.$fileName; 
//$bc = new Barcode39("Shay Anderson"); 
// display new barcode 
//$bc->draw($pngAbsoluteFilePath);
$barcode = "TESTING4";
//$barcode = urldecode( "TESTING" );
$bc = new Barcode39( $barcode);
$file_path = $rootScope["RootPath"]."data/export/";
$file_name = $file_path . $barcode . '.gif';
$bc->draw( $file_name);
$data = file_get_contents( $file_name); // Read the file's contents

echo $data;
$pngAbsoluteFilePath = $file_name; 
echo 'Server PNG File: '.$pngAbsoluteFilePath;
echo '<hr />'; 
$urlRelativeFilePath = EXAMPLE_TMP_URLRELPATH.$file_name; 
echo '<img src="/var/www/html/dev/data/export/TESTING2.gif" />';
echo '<hr />'; 
//echo '<img src="'.$urlRelativeFilePath.'" />';  

//force_download( $barcode . '.gif', $data);
//unlink( $file_name);

//echo $bc;
 /*   
   $param = $_GET['id']; // remember to sanitize that - it is user input! 
   $param = 5;  
    // we need to be sure ours script does not output anything!!! 
    // otherwise it will break up PNG binary! 
     
    ob_start("callback"); 
     
    // here DB request or some processing 
    //$codeText = 'DEMO - '.$param; 
    $codeText = 'DEMO - '. 'test'; 
    // end of processing here 
    $debugLog = ob_get_contents(); 
    ob_end_clean(); 
     
    // outputs image directly into browser, as PNG stream 
    //QRcode::png($codeText);
    $tempDir = $rootScope["RootPath"]."data/export/";
    $codeContents = 'Test QRcode'; 
     
    // we need to generate filename somehow,  
    // with md5 or with database ID used to obtains $codeContents... 
    $fileName = '005_file_'.md5($codeContents).'.png'; 
	$pngAbsoluteFilePath = $tempDir.$fileName; 
    $urlRelativeFilePath = EXAMPLE_TMP_URLRELPATH.$fileName; 
    
    if (!file_exists($pngAbsoluteFilePath)) { 
        QRcode::png($codeContents, $pngAbsoluteFilePath); 
        echo 'File generated!'; 
        echo '<hr />'; 
    } else { 
        echo 'File already generated! We can use this cached file to speed up site on common codes!'; 
        echo '<hr />'; 
    } 
     
    echo 'Server PNG File: '.$pngAbsoluteFilePath; 
    echo '<hr />'; 
     
    // displaying 
    echo '<img src="'.$urlRelativeFilePath.'" />';  
    */
?>