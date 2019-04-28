<?php

use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;

require '/var/www/html/otisdev/vendor/autoload.php';

$woocommerce = new Client(
    'https://alclair.com',
	'ck_acc872e19a1908cd5abadfd29a84e5edf8d34469',
	'cs_87fe15086357b7e90a8d2457552ddb957ba939fb',
	[ 'version' => 'wc/v3', ]
);


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