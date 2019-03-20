<?php
include_once "../../config.inc.php";
include_once "../../includes/PHPExcel/Classes/PHPExcel.php";

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

require '/var/www/html/otisdev/vendor/autoload.php';

$response = array();
$response["code"] = "";
$response["message"] = "";
$response['data'] = null;

try 
{
	// Create new Spreadsheet object
	$spreadsheet = new Spreadsheet();
	//$objPHPExcel = new PHPExcel();
	
	// Set workbook properties
	$spreadsheet->getProperties()->setCreator('Tyler Folsom')
        ->setLastModifiedBy('Tyler Folsom')
        ->setTitle('Testing')
        ->setSubject('PhpSpreadsheet')
        ->setDescription('A Simple Excel Spreadsheet generated using PhpSpreadsheet.')
        ->setKeywords('Microsoft office 2013 php PhpSpreadsheet')
        ->setCategory('Test file');
 
	// Set worksheet title
	$spreadsheet->getActiveSheet()->setTitle('OTIS');
	//$objPHPExcel->setActiveSheetIndex(0)->setTitle("TESTING");
	
	//Set active sheet index to the first sheet, 
	//and add some data
	$spreadsheet->setActiveSheetIndex(0)
        ->setCellValue("A1", "Date") 
			->setCellValue("B1", "Order ID") 
			->setCellValue("C1",  "Product") 
			->setCellValue("D1", "QTY") 
			->setCellValue("E1", "Model") 
			->setCellValue("F1", "Artwork")
			->setCellValue("G1", "Color")
			->setCellValue("H1", "Rush Process")
			->setCellValue("I1", "Left Shell")
			->setCellValue("J1", "Right Shell")
			->setCellValue("K1", "Left Faceplate")
			->setCellValue("L1", "Right Faceplate")
			->setCellValue("M1", "Cable Color")
			->setCellValue("N1", "Clear Canal")
			->setCellValue("O1", "Left Alclair Logo")
			->setCellValue("P1", "Right Alclair Logo")
			->setCellValue("Q1", "Left Custom Art")
			->setCellValue("R1", "Right Custom Art")
			->setCellValue("S1", "Link to Design Image")
			->setCellValue("T1", "Open Order in Designer")
			->setCellValue("U1", "Designed For")
			->setCellValue("V1", "My Impressions")
			->setCellValue("W1", "64in. Cable")
			->setCellValue("X1", "Mic Cable")
			->setCellValue("Y1", "Forza Audio")
			->setCellValue("Z1", "Cleaning Kit")
			->setCellValue("AA1", "Cleaning Tools")
			->setCellValue("AB1", "Dotz Clip")
			->setCellValue("AC1", "Pelican Case")
			->setCellValue("AD1", "Pelican Case Name")
			->setCellValue("AE1", "Soft Case")
			->setCellValue("AF1", "Hearing Protection")
			->setCellValue("AG1", "Hearing Protection Color")
			->setCellValue("AH1", "Cable Upgrade")
			->setCellValue("AI1", "Cable Upgrade Type")
			->setCellValue("AJ1", "Cable Addon")
			->setCellValue("AK1", "Cable Addon Type")
			->setCellValue("AL1", "Musician's Plugs 9dB")
			->setCellValue("AM1", "Musician's Plugs 15dB")
			->setCellValue("AN1", "Musician's Plugs 25dB")
			->setCellValue("AO1", "Musician's Plugs")
			->setCellValue("AP1", "Wood Box")
			->setCellValue("AQ1", "Standard Cable")
			->setCellValue("AR1", "Long Cable")
			->setCellValue("AS1", "Billing Name")
			->setCellValue("AT1", "Shipping Name")
			->setCellValue("AU1", "Price")
			->setCellValue("AV1", "Coupon")
			->setCellValue("AW1", "Discount")
			->setCellValue("AX1", "Total");
			
			$filename = "Testing-Import2-".date("m-d-Y").".xlsx";
			//new code:
			$writer = IOFactory::createWriter($spreadsheet, 'Xls');
			//$writer->save("../../data/export/woocommerce/$filename");
			$writer->save("/var/www/html/otisdev/data/export/woocommerce/$filename");
			
			$response['code'] = 'success';
			$response['data'] = $filename;
			echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>