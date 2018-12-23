<?php
include_once "../../config.inc.php";
if(empty($_SESSION["UserId"]))
{
    return;
}
$response = array();
$response["code"] = "";
$response["message"] = "";
$response['data'] = null;

//$fc="1".":".$_FILES['documentfile']['name'];

try
{
	
$target_dir = "../../data/";
$target_file = $target_dir . basename($_FILES["documentfile"]["name"]);
$uploadOk = 1;
$fileType = pathinfo($target_file,PATHINFO_EXTENSION);

$response['testing1'] = ($_FILES["documentfile"]["name"]);
$response['testing2'] = basename($_FILES["documentfile"]["name"]);
$response['testing3'] = ($_FILES["documentfile"]["name"]);
$response['testing4'] = $fileType;
$response['testing5'] = $target_dir;

$response["code"] = "success";


/*
// Check if file already exists
if (file_exists($target_file)) {
    $response["message"] = "Sorry, file already exists.";
    $uploadOk = 0;
    $response["testing1"] = $target_file;
}
// Check file size
if ($_FILES["docuementfile"]["size"] > 500000) {
    $response["message"] = "Sorry, your file is too large.";
    $uploadOk = 0;
}
	// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    $response['testing5'] = "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["documentfile"]["tmp_name"], $target_file)) {
        $response['testing5'] = "The file ". basename( $_FILES["documentfile"]["name"]). " has been uploaded.";
    } else {
        $response['testing5'] = "Sorry, there was an error uploading your file.";
    }
}
*/
////////////////////////////////////////////////////////$row = 1;
/*
if (($handle = fopen("../../data/US Inventory Table 10_12_2017.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($data);
        //echo "<p> $num fields in line $row: <br /></p>\n";
        $row++;
        for ($c=0; $c < $num; $c++) {
            $respons['testing3'] =  $data[$c] . "<br />\n";
        }
    }
    fclose($handle);
}
*/
$File = '../../data/US Inventory Table No blanks.csv';
$inc = 0;

$arrResult  = array();
$handle     = fopen($File, "r");
if(empty($handle) === false) {
    while(($data = fgetcsv($handle, 1000, ",")) !== FALSE){
        $arrResult[$inc] = $data;
        $inc = $inc + 1;
    }
    fclose($handle);
}
$response['testing3'] = $arrResult[2];
//print_r($arrResult);
	
	//$response['testing3'] = $data[1];
	$response['testing4'] = $inc;
    echo json_encode($response);
    //file_put_contents ("../../data/log.txt","\r\n".$path,FILE_APPEND); 
    

}
catch(PDOException $ex)
{
	//$response["code"] = "error 2";
	$response["message"] = $ex->getMessage();
	//echo json_encode($response);
}

?>