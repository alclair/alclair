<?php$response['test'] = "File type is " . $fileType . " and " . $target_file;
echo json_encode($response);
exit;

$File = $target_file;

while ($line = fgets($fh)) {
  // <... Do your work with the line ...>
  // echo($line);
}
fclose($fh);

$myfile = fopen($File, "r");
while ($line = fgets($myfile)) {
  // <... Do your work with the line ...>
  $response['test'] = "EXISTS " . $line;
  // echo($line);
}
fclose($myfile);
//$response['test'] = "GOT HERE " . empty($myfile);
echo json_encode($response);
exit;

if(file_exists($File)) {
	//$response['test'] = "EXISTS";
} else {
	//$response['test'] = "DOES NOT EXIST";
}
if(empty($myfile) === false) {
    //$response['test'] = "NOT EMPTY";
} else {
	//$response['test'] = "EMPTY";
}


$yaml = <<<EOD
---
invoice: 34843
date: "2001-01-23"
bill-to: &id001
  given: Chris
  family: Dumars
  address:
    lines: |-
      458 Walkman Dr.
              Suite #292
    city: Royal Oak
    state: MI
    postal: 48046
ship-to: *id001
product:
- sku: BL394D
  quantity: 4
  description: Basketball
  price: 450
- sku: BL4438H
  quantity: 1
  description: Super Hoop
  price: 2392
tax: 251.420000
total: 4443.520000
comments: Late afternoon is best. Backup contact is Nancy Billsmer @ 338-4338.
...
EOD;


$parsed = yaml_parse($yaml);
$response['test'] = $parsed['invoice'];


$homepage = file_get_contents($File, FALSE, NULL, 20, 14);
$response['test'] = "GOT HERE " . var_dump($homepage);
echo json_encode($response);
exit;
//echo fread($myfile,filesize($File));
//$fread = fread($myfile,filesize($File));
//fclose($myfile);

// PUT ALL OF THE DATA INTO $arrResult
$arrResult  = array();
$inc = 0;
if(empty($myfile) === false) {
    $fread = fread($myfile,filesize($File));
    fclose($myfile);
}
$parsed = yaml_parse($fread);

$response['test'] = "GOT HERE " . $fread;
echo json_encode($response);
exit;


$parsed = yaml_parse($fread);
var_dump($parsed);

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>