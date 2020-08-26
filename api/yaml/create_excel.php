<?php
include_once "../../config.inc.php";

include_once "../../includes/PHPExcel/Classes/PHPExcel.php";
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
require '/var/www/html/otisdev/vendor/autoload.php';

if(empty($_SESSION["UserId"]))
{
    return;
}
$response = array();
$response["code"] = "";
$response["message"] = "";
$response['data'] = null;

try
{
	
$response["test"] = "Got Here!";
//echo json_encode($response);
//exit;


// SUBSTR FUNCTION TO REMOVE THE FIRST CHARACTER IN STRING WHICH IS A COMMA
$customer_id = $_REQUEST["customer_id"];
$customer_id = substr($customer_id, 1, strlen($customer_id)-1);
$date = $_REQUEST["date"];
$date = substr($date, 1, strlen($date)-1);
$author = $_REQUEST["author"];
$author = substr($author, 1, strlen($author)-1);
$body = $_REQUEST["body"];
$body = substr($body, 1, strlen($body)-1);
$no_record = $_REQUEST["no_record"];
$no_record = substr($no_record, 1, strlen($no_record)-1);

$response["test"] = $body;
//echo json_encode($response);
//exit;

$c_ids = explode(',', $customer_id);
$time = explode(',', $date);
$auth = explode(',', $author);
$text = explode(',', $body);
$yaml_not_working = explode(',', $no_record);

$response["test"] = count($c_ids);
$response["test"] = $c_ids[0] . " - " . $c_ids[1] . " - " . $c_ids[2] . " - " . $c_ids[3] . " - " . $c_ids[4] . " - " ;


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Create new Spreadsheet object
$spreadsheet = new Spreadsheet();
// Set workbook properties
$spreadsheet->getProperties()->setCreator('Tyler Folsom')
    ->setLastModifiedBy('Tyler Folsom')
    ->setTitle('YAML')
    ->setSubject('PhpSpreadsheet')
    ->setDescription('A Simple Excel Spreadsheet generated using PhpSpreadsheet.')
    ->setKeywords('Microsoft office 2013 php PhpSpreadsheet')
    ->setCategory('YAML IMPORT');
    
// Set worksheet title
$spreadsheet->getActiveSheet()->setTitle('YAML Import');

//Set active sheet index to the first sheet, and add some data
$spreadsheet->setActiveSheetIndex(0)
        ->setCellValue("A1", "User ID") 
		->setCellValue("B1", "Date 1") 
		->setCellValue("C1",  "Author") 
		->setCellValue("D1", "Note Content");

$row = 2;
for($p = 0; $p < count($c_ids); $p++) {
	$spreadsheet->setActiveSheetIndex(0)
       ->setCellValue("A".$row, $c_ids[$p]) 
	   ->setCellValue("B".$row, $time[$p]) 
	   ->setCellValue("C".$row,  $auth[$p]) 
	   ->setCellValue("D".$row, $text[$p]);
	$row++;
}

$filename = "z_71.xlsx";
$writer_dev = IOFactory::createWriter($spreadsheet, 'Xlsx');
//$writer_dev->save("/var/www/html/otisdev/data/export/woocommerce/$filename");
$writer_dev->save($rootScope["RootPath"]."/data/yaml/$filename");
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$response["yaml_not_working"] = $yaml_not_working;
echo json_encode($response);

}
catch(PDOException $ex)
{
	//$response["code"] = "error 2";
	$response["message"] = $ex->getMessage();
	//echo json_encode($response);
}
?>