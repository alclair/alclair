<?php
include_once "../../config.inc.php";
ini_set('max_execution_time', 10000);

//directory to process
$directoryPath =  "../../data/";

//create an array of the JPG images
$images = glob($directoryPath . "*.{jpg,JPG}", GLOB_BRACE);

//say hello
echo("hello<br />");

$maxWidth = 1080; //maximum image width in pixels
$maxFileSize = 200; //200K

foreach ($images as $image) { //loop through each image
	compressUploadedImage($image, $maxFileSize, $maxWidth);	
	//write the image path to the screen
	echo($image . '<br />');
}	

//announce the completed processing
echo("Done!");
?>