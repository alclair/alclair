<?php
include_once "../../config.inc.php";
include_once "../../includes/PHPExcel/Classes/PHPExcel.php";

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

//require $rootScope["RootPath"] . 'vendor/autoload.php';
//require '/var/www/html/otisdev/vendor/autoload.php';
require '/var/www/html/otis/vendor/autoload.php';

$dir = realpath(__DIR__);
$dir = str_replace('/api/export', '', $dir);

include_once $dir."/includes/phpmailer/class.phpmailer.php";
if(empty($_SESSION["UserId"])&&$_REQUEST["token"]!=$rootScope["SWDApiToken"])
{
    //return;
}

$response = array();
$response["code"] = "";
$response["message"] = "";
$response['data'] = null;

try
{	  
    $params = null;   
    
    //SELECT * FROM import_orders WHERE date >= '04/13/2018' AND active = TRUE
    //TESTING PRINT
    
// FOR MANUFACTURING ORDERS
    $t=time();
	$current_date = date("Y-m-d",$t);
	$current_date_minus_3_months = date("m-d-Y", strtotime("-3 months"));
	$current_date_minus_3_months = date("m-d-Y", strtotime("-6 months"));
	
	$query = "SELECT * FROM import_orders WHERE date >= :90_days_ago AND active = TRUE";		
	$stmt = pdo_query( $pdo, $query, array(":90_days_ago"=>$current_date_minus_3_months) ); 
    $result = pdo_fetch_all( $stmt );		
    $rows_in_result = pdo_rows_affected($stmt);
		
    $inc = 0;
    $store_customer = array();
    for ($i = 0; $i < $rows_in_result; $i++) {
		$query = "SELECT *, t2.status_of_order FROM order_status_log AS t1
			 LEFT JOIN order_status_table AS t2 ON t1.order_status_id = t2.order_in_manufacturing

		WHERE t1.import_orders_id = :import_orders_id ORDER BY t1.date DESC LIMIT 2";
		$stmt2 = pdo_query( $pdo, $query, array(":import_orders_id"=>$result[$i]["id"]) ); 
		$customer_status = pdo_fetch_all( $stmt2 );	
		$num_of_rows = pdo_rows_affected($stmt2);	
// END PART FOR MANUFACTURING ORDERS
		
// LOOP FOR MANUFACTURING ORDERS
		if(count($customer_status) == 2) {	
			//echo "Status 1 is " . $customer_status[0]["order_status_id"] . " and status 2 is " . $customer_status[1]["order_status_id"] . " and ID is " . $result[$i]["id"] . "<br/>";
			if($customer_status[0]["order_status_id"] == $customer_status[1]["order_status_id"] && $customer_status[0]["order_status_id"] != 12 && $customer_status[0]["order_status_id"] != 99 && $customer_status[0]["order_status_id"] != 1) {
				//echo "INC is " . $inc . "<br>";
				$store_customer[$inc] = $result[$i];
				$store_customer[$inc]["status_of_order"] = $customer_status[0]["status_of_order"];
				$inc = $inc+1;
			}
		}
	}
// END LOOP FOR MANUFACTURING ORDERS

// FOR REPAIR ORDERS
    $t=time();
	$current_date_repair = date("Y-m-d",$t);
	$current_date_minus_3_months_repair = date("m-d-Y", strtotime("-3 months"));
	
	$query = "SELECT * FROM repair_form WHERE received_date >= :90_days_ago AND active = TRUE";		
	
	$query = "SELECT t1.*, t2.name as model FROM repair_form as t1
					LEFT JOIN monitors AS t2 ON t1.monitor_id = t2.id
					WHERE t1.received_date >= :90_days_ago AND t1.active = TRUE";		
	
	$stmt = pdo_query( $pdo, $query, array(":90_days_ago"=>$current_date_minus_3_months_repair) ); 
    $result_repair = pdo_fetch_all( $stmt );		
    $rows_in_result_repair = pdo_rows_affected($stmt);
    	
    $inc2 = 0;
    $store_customer_repair = array();
    for ($i = 0; $i < $rows_in_result_repair; $i++) {
		$query = "SELECT *, t2.status_of_repair FROM repair_status_log AS t1
			 LEFT JOIN repair_status_table AS t2 ON t1.repair_status_id = t2.order_in_repair

		WHERE t1.repair_form_id= :repair_form_id ORDER BY t1.date DESC LIMIT 2";
		$stmt2 = pdo_query( $pdo, $query, array(":repair_form_id"=>$result_repair[$i]["id"]) ); 
		$customer_status_repair = pdo_fetch_all( $stmt2 );	
		//$num_of_rows_repair = pdo_rows_affected($stmt2);	
// END PART FOR REPAIR ORDERS

// LOOP FOR REPAIR ORDERS
		if(count($customer_status_repair) == 2) {	
			//echo "Status 1 is " . $customer_status[0]["order_status_id"] . " and status 2 is " . $customer_status[1]["order_status_id"] . " and ID is " . $result[$i]["id"] . "<br/>";
			if($customer_status_repair[0]["repair_status_id"] == $customer_status_repair[1]["repair_status_id"] && $customer_status_repair[0]["repair_status_id"] != 13 && $customer_status_repair[0]["repair_status_id"] != 14 && $customer_status_repair[0]["repair_status_id"] != 15 && $customer_status_repair[0]["repair_status_id"] != 99) {
				//echo "INC is " . $inc . "<br>";
				$store_customer_repair[$inc2] = $result_repair[$i];
				$store_customer_repair[$inc2]["status_of_repair"] = $customer_status_repair[0]["status_of_repair"];
				$inc2 = $inc2+1;
			}
		}
	}

// END LOOP FOR REPAIR ORDERS	
	
	/*for ($i = 0; $i < $inc; $i++) {			    
		echo $store_customer[$i]["id"];
		echo $store_customer[$i]["designed_for"] . " " . $store_customer[$i]["model"] . "<br/>";
	}*/
  
	// Create new Spreadsheet object
	$spreadsheet = new Spreadsheet();
  
    // Set workbook properties
	$spreadsheet->getProperties()->setCreator('Tyler Folsom')
        ->setLastModifiedBy('Tyler Folsom')
        ->setTitle('Testing')
        ->setSubject('PhpSpreadsheet')
        ->setDescription('Manufacturing Report')
        ->setKeywords('Microsoft office 2013 php PhpSpreadsheet')
        ->setCategory('Cron');
 
	// Set worksheet title
	$spreadsheet->getActiveSheet()->setTitle('OTIS');
    $spreadsheet->setActiveSheetIndex(0)
			    ->setCellValue("A1","Customer")  
			    ->setCellValue("B1","Monitor")  	
			    ->setCellValue("C1","Station")
			    ->setCellValue("E1","Repair For")  
			    ->setCellValue("F1","Monitor")  	
			    ->setCellValue("G1","Station");
			    
	for ($i = 0; $i < $inc; $i++) {			    
		$p = $i+2;
		$spreadsheet->setActiveSheetIndex(0)
            ->setCellValue("A".$p, $store_customer[$i]["designed_for"])
            ->setCellValue("B".$p, $store_customer[$i]["model"])   
            ->setCellValue("C".$p , $store_customer[$i]["status_of_order"]);
    }    
    
   for ($i = 0; $i < $inc2; $i++) {			    
		$p = $i+2;
		$spreadsheet->setActiveSheetIndex(0)
            ->setCellValue("E".$p, $store_customer_repair[$i]["customer_name"])
            ->setCellValue("F".$p, $store_customer_repair[$i]["model"])   
            ->setCellValue("G".$p , $store_customer_repair[$i]["status_of_repair"]);
    }                                                                       
   
    $spreadsheet->setActiveSheetIndex(0)->getStyle("A1:O500")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    
    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(35);
	$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(25);
	$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(25);
	$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(18);
	$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(25);
	$spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(25);
	
	//$objPHPExcel->getActiveSheet()->getStyle('A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('000000');

	//$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
		
	$spreadsheet->getActiveSheet()->getStyle("A1:O500")->getFont()->setSize(16);

    //$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    //$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
    //$filename = "Export-Queen-Readings(".str_replace("/","-",$startdate).")-To(".str_replace("/","-",$enddate).")-".time().".xlsx";
    //$filename = "Export-Queen-Readings.xlsx";
    $filename = "Daily Manufacturing Report ".date("m-d-Y").".xlsx";

    //$filename = "hello.xlsx";
    //$objWriter->save("../../data/export/$filename");
    
	//new code:
	$writer = IOFactory::createWriter($spreadsheet, 'Xls');
	//$writer->save("../../data/export/woocommerce/$filename");
	$writer->save("../../data/export/excel/$filename");

    $response['code'] = 'success';
    $response['data'] = $filename;
    
    $file_alclair=$rootScope["RootPath"]."data/export/excel/Daily Manufacturing Report ".date("m-d-Y").".xlsx";
    
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