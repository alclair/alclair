<?php
include_once "../../config.inc.php";
include_once "../../includes/PHPExcel/Classes/PHPExcel.php";
if(empty($_SESSION["UserId"])&&$_REQUEST["token"]!=$rootScope["SWDApiToken"])
{
    return;
}
session_start();
if(!empty($_REQUEST["UserId"]))
{
    //return;
    $testing5= $_REQUEST["UserId"];
}
else {
	$testing5="Admin is empty";
	//$testing5=$_SESSION["UserId"] - 1 + 1;
}

$response = array();
$response["code"] = "";
$response["message"] = "";
$response['data'] = null;

try
{	 
	
    $params = null;
    $conditionSql= "";
	$lng_queens = $_REQUEST["lng_queens"];
	
	//$lng_queens = 2;
    $new_StartDate = '';
    $new_EndDate = '';

    $letter = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X' , 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ', 'BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG');
    $inc = 0;
    
    //$_REQUEST["movement"] = "IMPORT";
	if(!empty($_REQUEST["StartDate"])) {
		$conditionSql.=" and (t1.date>=:StartDate)";
		$params[":StartDate"]=$_REQUEST["StartDate"];
	}
	
	if(!empty($_REQUEST["EndDate"])) {
		$conditionSql.=" and (t1.date <= :EndDate)";
		$params[":EndDate"]=date("m/d/Y H:i:s",strtotime($_REQUEST["EndDate"] . '23:59:59'));
	}
	
	/*if(!empty($_REQUEST["StartDate"]))
	{
		if (date('I', time()))
		{	
			$TIME_START = date("m/d/Y H:i:s",strtotime($_REQUEST["StartDate"] . '00:00:00') + 5 * 3600);
		}
		else
		{
			$TIME_START = date("m/d/Y H:i:s",strtotime($_REQUEST["StartDate"] . '00:00:00')+ 6 * 3600);
		}
		$conditionSql.=" and (t1.date>=:StartDate)";
		$params[":StartDate"]=$TIME_START;
		//$params[":StartDate"]=$_REQUEST["StartDate"];
	}
	
	if(!empty($_REQUEST["EndDate"]))
	{
		//$_REQUEST["EndDate"] = date("m/d/Y H:i:s",strtotime($_REQUEST["EndDate"] . '23:59:59'));

		if (date('I', time()))
		{
			$TIME_END = date("m/d/Y H:i:s",strtotime($_REQUEST["EndDate"] . '23:59:59') + 5 * 3600);
		}
		else
		{
			$TIME_END = date("m/d/Y H:i:s",strtotime($_REQUEST["EndDate"] . '23:59:59') + 6 * 3600);
		}
		$conditionSql.=" and (t1.date<=:EndDate)";
		$params[":EndDate"]=$TIME_END;
		//$params[":EndDate"]=$_REQUEST["EndDate"];
	}*/
	

	if( $_REQUEST["movement"] != undefined)
    {
	    if ($_REQUEST["movement"] != '0') {
	   		$conditionSql .= " AND (t1.movement = :movement)";
	   		$params[":movement"] = $_REQUEST["movement"];
	   		
	   		$response["test"] = $_REQUEST["movement"];
	   		$response["test"] = "DEFINED";
	   		//echo json_encode($response);
	   		//exit;
		}
	} else {
		$response["test"] = "NOT DEFINED";
	}
	
	if( $_REQUEST["company_id"] >= 1)
    {
	    //$response["test"] = "DEFINED and " . $_REQUEST["company_id"];
		//echo json_encode($response);
	   	//exit;
	    
	    $query2 = "SELECT * FROM shipping_addresses WHERE id = :company_id";
	    $params2[":company_id"] = $_REQUEST["company_id"];
	    $stmt2 = pdo_query( $pdo, $query2, $params2); 
		$result2 = pdo_fetch_all( $stmt2 );
		$company_is = $result2[0]["title"];
		//$rows_in_result2 = pdo_rows_affected($stmt2);
		
		$conditionSql .= " AND (t1.title = :company_is)";
	   	$params[":company_is"] = $company_is;
	} else {
		$response["test2"] = "NOT DEFINED";
	}

	$objPHPExcel = new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0)->setTitle("iFi audio Excel");
	
	$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue("A1", "Year /年份") 
			->setCellValue("B1", "Month/月份") 
			->setCellValue("C1", "Ship Date") 
			->setCellValue("D1", "IN/OUT 进出库") 
			->setCellValue("E1", "Warehouse/仓库") 
			->setCellValue("F1", "Country 国家") 
			->setCellValue("G1", "Dealer/End customer/Amazon   客户名称") 
			->setCellValue("H1", "Order type 销售类型") 
			->setCellValue("I1", "PO#    订单号") 
			->setCellValue("J1", "Product 产品名称") 
			->setCellValue("K1", "Qty") 
			->setCellValue("L1", "Discount price 代理价") 
			->setCellValue("M1", "Amount 总额") 
			->setCellValue("N1", "Serial Number 序列号") 
			->setCellValue("O1", "Shipping address &contact person&phone no.  收件人地址联系人及电话") 
			->setCellValue("P1", "Tracking 快递单号") 
			->setCellValue("Q1", "Shipping Cost 运费") 
			->setCellValue("R1", "Remarks 备注");
			

	// FINDS ALL OF THE ENTRIES INSIDE LOG MOVEMENT
	// EVERY IMPORT/EXPORT HAS A SINGLE LOG ENTRY INSIDE LOG MOMEMENT
	$query = "SELECT t1.*, to_char(t1.date,'MM/dd/yyyy') as date, to_char(t1.date, 'yyyy') as year, to_char(t1.date, 'yyyy-MM') as month
					  FROM log_movement AS t1
					  WHERE 1=1 $conditionSql";// $orderBySql $pagingSql";
    $stmt = pdo_query( $pdo, $query, $params); 
    $result = pdo_fetch_all( $stmt );
    $rows_in_result = pdo_rows_affected($stmt);
   //echo $rows_in_result;
   //echo $conditionSql;
   //echo $params[":StartDate"];
   //echo $params[":EndDate"];
  

// THE FIRST LOOP BELOW STEPS THROUGH EACH LOG ENTRY INSIDE LOG_MOVEMENT
// INSIDE THE LOOP THE CODE FINDS THE INSIDE ORDER_TRACKING THE SERIAL NUMBERS ASSOCIATED WITH THE LOG ENTRY INISDE LOG_MOVEMENT
$inc = 2;
foreach ($result as $log_output) {
	$query = "SELECT t1.serial_number, to_char(t1.date,'MM/dd/yyyy') as date, to_char(t1.date, 'yyyy') as year, to_char(t1.date, 'yyyy-MM') as month, t1.notes AS order_tracking_notes, t1.status, t1.discount, t2.movement, t2.movement_type, t2.company, t2.name, t2.address_1, t2.address_2, t2.city, t2.state, t2.zip_code, t2.notes AS log_movement_notes, t2.carrier_id, t2.warehouse_id, t2.ordertype_id, t2.po_number, t2.shipping_cost, t2.tracking, t2.country, t2.title, t3.name as carrier_name, t4.name as warehouse_name, t5.name as ordertype_name
					  FROM order_tracking AS t1
					  LEFT JOIN log_movement AS t2 ON t1.log_id = t2.id
					  LEFT JOIN carriers AS t3 ON t2.carrier_id = t3.id
					  LEFT JOIN warehouses AS t4 ON t2.warehouse_id = t4.id
					  LEFT JOIN types_of_orders AS t5 ON t2.ordertype_id = t5.id
					  WHERE log_id = :log_id";
    $stmt = pdo_query( $pdo, $query, array(":log_id"=>$log_output["id"])); 
    $output4excel = pdo_fetch_all( $stmt );
    $rows = pdo_rows_affected($stmt);
    
	// IN THIS LOOP  THE CODE STEPS THROUGH EACH SERIAL NUMBER ASSOCIATED WITH THR LOG MOVEMENT
	// THE ORDER_TRACKING TABLE HAS A LOG ID TO ASSOCIATE BACK TO THE LOG_MOVEMENT TABLE
	//if ($rows >= 1) {
	foreach ($output4excel as $key => $item) {
	    $query = "SELECT t1.*, t2.name as category, t3.price
	    				  FROM serial_numbers AS t1
						  LEFT JOIN product_categories AS t2 ON t1.category_id = t2.id
						  LEFT JOIN products AS t3 ON t1.product_id = t3.id
						  WHERE serial_number = :serial_number";
	    $stmt = pdo_query( $pdo, $query, array(":serial_number"=>$item["serial_number"])); 
		$product_info = pdo_fetch_all( $stmt );
		
		if($item["movement"] == "IMPORT") {
			$movement = "IN";
		} else {
			$movement = "OUT";
		}
		
		$discount_value = ($item["discount"]/100)*$product_info[0]["price"];
		$product_price = $product_info[0]["price"]  - $discount_value;
		//->setCellValue("L".$inc, "$" . ($item["discount"]/100)*$product_info[0]["price"])
		//->setCellValue("M".$inc, "$" . (100-$item["discount"])*$product_info[0]["price"]/100)
    	
    	if($product_info[0]["category"] == 'Accessory') {
	    	$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue("A".$inc, $item["year"])
			->setCellValue("B".$inc, $item["month"])
			->setCellValue("C".$inc, $item["date"])
			->setCellValue("D".$inc, $movement) //$item["movement"])
			->setCellValue("E".$inc, $item["warehouse_name"])
			->setCellValue("F".$inc, $item["country"])
			->setCellValue("G".$inc, $log_output["company"])
			->setCellValue("H".$inc, $item["ordertype_name"])
			->setCellValue("I".$inc, $item["po_number"])
			->setCellValue("J".$inc, $product_info[0]["product_name"])
			->setCellValue("K".$inc, "1")
			->setCellValue("L".$inc, $product_price)
			->setCellValue("M".$inc, $product_price)
			->setCellValue("N".$inc, $item["serial_number"])
			->setCellValue("O".$inc, $log_output["company"] .PHP_EOL . $log_output["name"] . PHP_EOL . $log_output["address_1"] . "\r" . $log_output["address_2"] . "\r" . $log_output["city"] . "\r" . $log_output["state"] . "\r" . $log_output["zip_code"])
			->setCellValue("P".$inc, $item["carrier_name"] . " " . $item["tracking"])
			->setCellValue("Q".$inc, $item["shipping_cost"])
			->setCellValue("R".$inc, $item["log_movement_notes"]);	
    	} else {
    	$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue("A".$inc, $item["year"])
			->setCellValue("B".$inc, $item["month"])
			->setCellValue("C".$inc, $item["date"])
			->setCellValue("D".$inc, $movement) //$item["movement"])
			->setCellValue("E".$inc, $item["warehouse_name"])
			->setCellValue("F".$inc, $item["country"])
			->setCellValue("G".$inc, $log_output["company"])
			->setCellValue("H".$inc, $item["ordertype_name"])
			->setCellValue("I".$inc, $item["po_number"])
			->setCellValue("J".$inc, $product_info[0]["category"]. " " . $product_info[0]["product_name"])
			->setCellValue("K".$inc, "1")
			
			->setCellValue("L".$inc, $product_price)
			->setCellValue("M".$inc, $product_price)
			
			//->setCellValue("L".$inc, "$" . ($item["discount"]/100)*$product_info[0]["price"])
			//->setCellValue("M".$inc, "$" . ( $product_info[0]["price"] - 10))
			->setCellValue("N".$inc, $item["serial_number"])
			->setCellValue("O".$inc, $log_output["company"] .PHP_EOL . $log_output["name"] . PHP_EOL . $log_output["address_1"] . "\r" . $log_output["address_2"] . "\r" . $log_output["city"] . "\r" . $log_output["state"] . "\r" . $log_output["zip_code"])
			->setCellValue("P".$inc, $item["carrier_name"] . " " . $item["tracking"])
			->setCellValue("Q".$inc, $item["shipping_cost"])
			->setCellValue("R".$inc, $item["log_movement_notes"]);	
		}
			$objPHPExcel->getActiveSheet()->getRowDimension($inc)->setRowHeight(55);
			$objPHPExcel->getActiveSheet(0)->getStyle('A' . $inc . ':R' . $inc)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
			$objPHPExcel->getActiveSheet()->getStyle('H' . $inc)->getAlignment()->setWrapText(true);
			
			$objPHPExcel->getActiveSheet()->getStyle('L' . $inc)->getNumberFormat()->setFormatCode('$#,##0.00'); 
			$objPHPExcel->getActiveSheet()->getStyle('M' . $inc)->getNumberFormat()->setFormatCode('$### ### ### ##0.00'); 
		$inc = $inc + 1;
	} 
	//}
}
	
	$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(55);
	$objPHPExcel->getActiveSheet(0)->getStyle('A1:R1')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:R100")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:R100")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	
	$objPHPExcel->getActiveSheet(0)->getStyle('A1:R1')->getFont()->setBold(true)->setSize(12);	
	
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(14);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(14);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(14);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(14);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(14);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(14);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(35);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(28);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(14);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(14);
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(24);
	$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(64);
	$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(24);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(24);
	$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(24);

    //$stmt = pdo_query( $pdo, 'SELECT name FROM queens WHERE queens_id = 2',null);	
	//$queen_name = pdo_fetch_all($stmt);
	
	//$response['data'] = $result;
	$response['data2'] = "Data";
   	//$response['code'] = 'success';	
								
	/*$objPHPExcel = new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0)->setTitle("Queen Data");
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue("A1","Queen")
		->setCellValue("B1","Queen");    	*/
    
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
    //$filename = "Export-Queen-Readings(".str_replace("/","-",$startdate).")-To(".str_replace("/","-",$enddate).")-".time().".xlsx";
    //$filename = "Export-Queen-Readings.xlsx";
    //$filename = "ZzZzZ-".date("m-d-Y").".xlsx";
	//$filename = "Step4.xlsx";
	$filename = "Export-Log-Data-".date("m-d-Y").".xlsx";
	//echo $filename;

    //$filename = "hello.xlsx";
    $objWriter->save("../../data/export/$filename");
	
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