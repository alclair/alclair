<?php
include_once "../../config.inc.php";

$dir = "/wp-content/uploads/configurator/custom";

// Open a directory, and read its contents
$ind = 0;
$stored = [];
if (is_dir($dir)){
  if ($dh = opendir($dir)){
    while (($file = readdir($dh)) !== false){
      echo "filename:" . $file . "<br>";
      $stored[$ind] = $file;
    }
    closedir($dh);
  }
} else {
	echo "Try again!";
}


for($i = 0; $i < count($stored); $i++) {
	if (in_array($stored[$i], $people)) {
			copy($stored[$i], 'bar/' . $stored[$i]);
	}	else {
		echo "Match not found";
  }
?>