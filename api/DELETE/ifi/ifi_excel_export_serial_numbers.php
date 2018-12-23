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
	
    $params_category_id = null;
    $condition_category_id = "";
    
    $params_status = null;
    $condition_status = "";
    
    $params_product = null;
    $condition_product = "";
	
	//$lng_queens = 2;
    $new_StartDate = '';
    $new_EndDate = '';

	$colors_key = array('FFFFFF', '00B050', 'B1A0C7', 'FFFF00', 'FF5050', '7EB4E2');
	$colors_h1 = array('FABF8F', 'DA9694', '92CDDC', '538DD5', 'A6A6A6');
	$colors_h2 = array('FDE9D9', 'F2DCDB', 'DAEEF3', 'C5D9F1', 'D9D9D9');
	
    $letter = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X' , 'Y', 'Z',
    							 'AA', 'AB', 'AC', 'AD', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ',
    							  'BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN', 'BO', 'BP', 'BQ', 'BR', 'BS', 'BT', 'BU', 'BV', 'BW', 'BX', 'BY', 'BZ',
    							  'CA', 'CB', 'CC', 'CD', 'CE', 'CF', 'CG', 'CH', 'CI', 'CJ', 'CK', 'CL', 'CM', 'CN', 'CO', 'CP', 'CQ', 'CR', 'CS', 'CT', 'CU', 'CV', 'CW', 'CX', 'CY', 'CZ',
    							  'DA', 'DB', 'DC', 'DD', 'DE', 'DF', 'DG', 'DH', 'DI', 'DJ', 'DK', 'DL', 'DM', 'DN', 'DO', 'DP', 'DQ', 'DR', 'DS', 'DT', 'DU', 'DV', 'DW', 'DX', 'DY', 'DZ',
    							  'EA', 'EB', 'EC', 'ED', 'EE', 'EF', 'EG', 'EH', 'EI', 'EJ', 'EK', 'EL', 'EM', 'EN', 'EO', 'EP', 'EQ', 'ER', 'ES', 'ET', 'EU', 'EV', 'EW', 'EX', 'EY', 'EZ');
    							  
	 if($_REQUEST["product_id"] >= 1)
    {
		$condition_product .= " AND (products.id = :product_id)";
		$params_product[":product_id"] = $_REQUEST["product_id"];
	}
	if($_REQUEST["category_id"] >= 1)
    {
		$condition_category_id .= " AND (id = :category_id)";
		$params_category_id[":category_id"] = $_REQUEST["category_id"];
	}
	if( $_REQUEST["status"] != undefined)
    {
	    if ($_REQUEST["status"] != '0') {
	   		$condition_status .= " AND (status = :status)";
	   		$params_status[":status"] = $_REQUEST["status"];
	   		
	   		$response["test"] = $_REQUEST["status"];
	   		//echo json_encode($response);
	   		//exit;
		}
	}

	$objPHPExcel = new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0)->setTitle("iFi audio Excel");
	
	$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue("B2", "KEY:") 
			->setCellValue("B3", "AMAZON") 
			->setCellValue("B4", "DEMO UNIT") 
			->setCellValue("B5", "OPEN - NEEDS TESTING") 
			->setCellValue("B6", "FAULTY")
			->setCellValue("B7", "SHIPPED"); 
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(24);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("B2:B7")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("B2:B7")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->getActiveSheet(0)->getStyle('B2:B7')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$objPHPExcel->getActiveSheet(0)->getStyle('B2:B7')->getFont()->setBold(true)->setSize(12);	
	$objPHPExcel->getActiveSheet()->getStyle('B2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF');
	$objPHPExcel->getActiveSheet()->getStyle('B3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('00B050');
	$objPHPExcel->getActiveSheet()->getStyle('B4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('B1A0C7');
	$objPHPExcel->getActiveSheet()->getStyle('B5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
	$objPHPExcel->getActiveSheet()->getStyle('B6')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF5050');
	$objPHPExcel->getActiveSheet()->getStyle('B7')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('7EB4E2');
	
/*	$objPHPExcel->setActiveSheetIndex(0)
                ->mergeCells('G4:I4')
                ->setCellValue("G4","Mud Tracking")
                ->mergeCells('F5:J5')
                ->setCellValue("F5","BS & W Tracking for Killdeer TRD");*/
			
	// LENGTH OF PRODUCT CATEGORIES
	 //$stmt = pdo_query( $pdo, "SELECT * FROM product_categories", null); 
	 $stmt = pdo_query( $pdo, "SELECT * FROM product_categories WHERE 1=1 $condition_category_id", $params_category_id); 
	 $result = pdo_fetch_all( $stmt );
	 $rows_in_product_categories = pdo_rows_affected($stmt);
	
	// NUMBER OF PRODUCTS IN EACH CATEGORY
	$ind = 3; // STARTING AT LETTER "D"
	$inc = 4;  // STARTING AT LETTER "E"
	$ind_colors_h1 = 0;
	foreach ($result as $category) {
		/*$stmt = pdo_query( $pdo,  "SELECT products.*, t2.name AS category,
(SELECT COUNT(*) FROM serial_numbers WHERE serial_numbers.product_id = products.id) AS total_quantity,
(SELECT COUNT(*) FROM serial_numbers WHERE serial_numbers.product_id = products.id AND serial_numbers.status='SEALED') AS SEALED_quantity,
(SELECT COUNT(*) FROM serial_numbers WHERE serial_numbers.product_id = products.id AND serial_numbers.status='AMAZON') AS amazon_quantity,
(SELECT COUNT(*) FROM serial_numbers WHERE serial_numbers.product_id = products.id AND serial_numbers.status='DEMO') AS demo_quantity,
(SELECT COUNT(*) FROM serial_numbers WHERE serial_numbers.product_id = products.id AND serial_numbers.status='FAULTY') AS faulty_quantity,
(SELECT COUNT(*) FROM serial_numbers WHERE serial_numbers.product_id = products.id AND serial_numbers.status='SHIPPED') AS shipped_quantity

FROM products 
LEFT JOIN product_categories AS t2 ON products.category_id = t2.id
WHERE products.active = TRUE AND products.category_id = :category_id ORDER BY products.id", array(":category_id"=>$category["id"])); 
*/
$params_product[":category_id"] = $category["id"];
$stmt = pdo_query( $pdo,  "SELECT products.*, t2.name AS category,
(SELECT COUNT(*) FROM serial_numbers WHERE serial_numbers.product_id = products.id AND serial_numbers.active = TRUE) AS total_quantity,
(SELECT COUNT(*) FROM serial_numbers WHERE serial_numbers.product_id = products.id AND serial_numbers.status='SEALED' AND serial_numbers.active = TRUE) AS sealed_quantity,
(SELECT COUNT(*) FROM serial_numbers WHERE serial_numbers.product_id = products.id AND serial_numbers.status='AMAZON' AND serial_numbers.active = TRUE) AS amazon_quantity,
(SELECT COUNT(*) FROM serial_numbers WHERE serial_numbers.product_id = products.id AND serial_numbers.status='DEMO' AND serial_numbers.active = TRUE) AS demo_quantity,
(SELECT COUNT(*) FROM serial_numbers WHERE serial_numbers.product_id = products.id AND serial_numbers.status='FAULTY' AND serial_numbers.active = TRUE) AS faulty_quantity,
(SELECT COUNT(*) FROM serial_numbers WHERE serial_numbers.product_id = products.id AND serial_numbers.status='SHIPPED' AND serial_numbers.active = TRUE) AS shipped_quantity

FROM products 
LEFT JOIN product_categories AS t2 ON products.category_id = t2.id
WHERE products.active = TRUE AND products.category_id = :category_id $condition_product ORDER BY products.id", $params_product); 
		
		//$stmt = pdo_query( $pdo, "SELECT * FROM products WHERE category_id = :category_id", array(":category_id"=>$category["id"])); 
		$result_product = pdo_fetch_all( $stmt );
		$rows_in_products = pdo_rows_affected($stmt);
		
		// MERGES CELLS & PRINTS THE CATEGORY NAME & FORMATTING
		if($rows_in_products > 0) {
			$end = $inc+$rows_in_products*3-3;
		} else {
			$end = $inc;
		}
		
		// BUILD THE HEADER FOR EACH PRODUCT CATEGORY (NANO, MICRO, PRO, RETRO, ACCESSORIES)
		$objPHPExcel->setActiveSheetIndex(0)
			->mergeCells($letter[$inc]."4:".$letter[$end]."4")  // STARTS AT E4 THEN FIGURES OUT WHERE THE CATEGORY WILL END
			->setCellValue($letter[$inc]."4", $category["name"]);
		$objPHPExcel->setActiveSheetIndex(0)->getStyle($letter[$inc]."4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex(0)->getStyle($letter[$inc]."4")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet(0)->getStyle($letter[$inc]."4")->getFont()->setBold(true)->setSize(12);	
		$objPHPExcel->getActiveSheet()->getStyle($letter[$inc]."4")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($colors_h1[$ind_colors_h1]);
		$objPHPExcel->getActiveSheet(0)->getStyle($letter[$inc]."4")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		// SET GENERIC ROW HEIGHTS
		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(15);
		$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(25);
		$objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(25);
		$objPHPExcel->getActiveSheet()->getRowDimension('4')->setRowHeight(25);
		$objPHPExcel->getActiveSheet()->getRowDimension('5')->setRowHeight(25);
		$objPHPExcel->getActiveSheet()->getRowDimension('6')->setRowHeight(25);
		$objPHPExcel->getActiveSheet()->getRowDimension('7')->setRowHeight(25);
		$objPHPExcel->getActiveSheet()->getRowDimension('8')->setRowHeight(25);
		$objPHPExcel->getActiveSheet()->getRowDimension('9')->setRowHeight(25);
		$objPHPExcel->getActiveSheet()->getRowDimension('10')->setRowHeight(25);
		
		// 	FIND ALL SERIAL NUMBERS OF A PRODUCT	
		foreach ($result_product as $product) {

			//$stmt = pdo_query( $pdo, "SELECT * FROM serial_numbers WHERE category_id = :category_id AND product_name = :product_name", array(":category_id"=>$category["id"], ":product_name"=>$product["name"])); 
			
			$params_status[":category_id"] = $category["id"];
			$params_status[":product_name"] = $product["name"];
			//$stmt = pdo_query( $pdo, "SELECT * FROM serial_numbers WHERE active = TRUE AND category_id = :category_id AND product_name = :product_name $condition_status", $params_status); 
			$stmt = pdo_query( $pdo, "SELECT * FROM serial_numbers WHERE active = TRUE AND category_id = :category_id AND product_name = :product_name $condition_status
ORDER BY CASE status
WHEN 'SEALED' THEN 1
WHEN 'DEMO' THEN 2
WHEN 'FAULTY' THEN 3
WHEN 'AMAZON' THEN 4
WHEN 'SHIPPED' THEN 5
END", $params_status); 
			
			

			$result_sn = pdo_fetch_all( $stmt );
			$rows_in_sn = pdo_rows_affected($stmt);
			
		// 	
			$quantity_in_US = $product["sealed_quantity"] + $product["demo_quantity"] + $product["faulty_quantity"];
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue($letter[$ind]."7", "EAN") 
				->setCellValue($letter[$ind]."8", "US") 
				->setCellValue($letter[$ind]."9", "AZ") 
				->setCellValue($letter[$ind]."10", "Total"); 
			if($category["name"] == "Accessory") {
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue($letter[$ind+1]."6", $product["name"]) 
					->setCellValue($letter[$ind+1]."7", $product["ean"]) 
					->setCellValue($letter[$ind+1]."8", $quantity_in_US) 
					->setCellValue($letter[$ind+1]."9", $product["amazon_quantity"])
					->setCellValue($letter[$ind+1]."10", $product["total_quantity"]); 
			} else {
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue($letter[$ind+1]."6", $category["name"] . " " . $product["name"]) 
					->setCellValue($letter[$ind+1]."7", $product["ean"]) 
					->setCellValue($letter[$ind+1]."8", $quantity_in_US) 
					->setCellValue($letter[$ind+1]."9", $product["amazon_quantity"])
					->setCellValue($letter[$ind+1]."10", $product["total_quantity"]); 
			}
				// SET WIDTH
				$objPHPExcel->getActiveSheet()->getColumnDimension($letter[$ind])->setWidth(5);
				$objPHPExcel->getActiveSheet()->getColumnDimension($letter[$ind+1])->setWidth(20);
				// CENTER IN CELL HORIZONTALLY
				$objPHPExcel->setActiveSheetIndex(0)->getStyle($letter[$ind]."7:".$letter[$ind+1]."10")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->setActiveSheetIndex(0)->getStyle($letter[$ind+1]."6:".$letter[$ind+1]."10")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				// CENTER IN CELL VERTICALLY
				$objPHPExcel->setActiveSheetIndex(0)->getStyle($letter[$ind]."7:".$letter[$ind]."10")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$objPHPExcel->setActiveSheetIndex(0)->getStyle($letter[$ind+1]."6:".$letter[$ind+1]."10")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				// SET FONT SIZE
				$objPHPExcel->getActiveSheet(0)->getStyle($letter[$ind]."7:".$letter[$ind]."10")->getFont()->setBold(true)->setSize(12);					
				$objPHPExcel->getActiveSheet(0)->getStyle($letter[$ind+1]."6:".$letter[$ind+1]."10")->getFont()->setBold(true)->setSize(12);	
				// SET FONT COLOR TO RED
				$objPHPExcel->getActiveSheet(0)->getStyle($letter[$ind]."7:".$letter[$ind]."10")->getFont()->getColor()->setRGB('FF0000');
				$objPHPExcel->getActiveSheet(0)->getStyle($letter[$ind+1]."8:".$letter[$ind+1]."10")->getFont()->getColor()->setRGB('FF0000');
				// ADD BORDERS TO CELLS
				$objPHPExcel->getActiveSheet(0)->getStyle($letter[$ind]."7:".$letter[$ind+1]."10")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
				$objPHPExcel->getActiveSheet(0)->getStyle($letter[$ind+1]."6:".$letter[$ind+1]."10")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
				// SET CELL COLORS								
				$objPHPExcel->getActiveSheet()->getStyle($letter[$ind+1]."6:".$letter[$ind+1]."7")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($colors_h1[$ind_colors_h1]);
				$objPHPExcel->getActiveSheet()->getStyle($letter[$ind+1]."8:".$letter[$ind+1]."10")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($colors_h2[$ind_colors_h1]);
									
				$SN_ind = 1;
				foreach ($result_sn as $SN) {
					if ($SN["status"] == 'SHIPPED')
						$cell_color = $colors_key[5];
					elseif ($SN["status"] == 'AMAZON')
						$cell_color = $colors_key[1];
					elseif ($SN["status"] == 'DEMO')
						$cell_color = $colors_key[2];	
					elseif ($SN["status"] == 'OPEN BOX')
						$cell_color = $colors_key[3];
					elseif ($SN["status"] == 'FAULTY')
						$cell_color = $colors_key[4];
					elseif ($SN["status"] == 'SEALED')
						$cell_color = $colors_key[0];

					$row_number = 11 + $SN_ind;
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue($letter[$ind].$row_number, $SN_ind) 
						->setCellValue($letter[$ind+1].$row_number, $SN["serial_number"]); 
					$objPHPExcel->getActiveSheet(0)->getStyle($letter[$ind].$row_number.":".$letter[$ind+1].$row_number)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle($letter[$ind].$row_number.":".$letter[$ind+1].$row_number)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getRowDimension($row_number)->setRowHeight(15);
					$objPHPExcel->getActiveSheet()->getStyle($letter[$ind+1].$row_number)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($cell_color);

					$SN_ind = $SN_ind + 1;
				}
				
				
			
			$ind = $ind + 3;
		}
		
		$inc = $inc+$rows_in_products*3;
		$ind_colors_h1 = $ind_colors_h1 + 1;
		//$inc = $inc + 1; // SKIP A COLUMN
		
	}
			
/*
	// FINDS ALL OF THE ENTRIES INSIDE LOG MOVEMENT
	// EVERY IMPORT/EXPORT HAS A SINGLE LOG ENTRY INSIDE LOG MOMEMENT
          $query = "SELECT t1.*, category.name AS category_name
                  FROM serial_numbers AS t1
                  LEFT JOIN product_categories AS category ON t1.category_id = category.id
                  WHERE 1=1 $conditionSql $orderBySql $pagingSql";
    $stmt = pdo_query( $pdo, $query, $params); 
    $result = pdo_fetch_all( $stmt );
    $rows_in_result = pdo_rows_affected($stmt);

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
			->setCellValue("L".$inc, "$" . (100-$item["discount"])*$product_info[0]["price"]/100)
			->setCellValue("M".$inc, "$" . (100-$item["discount"])*$product_info[0]["price"]/100)
			->setCellValue("N".$inc, $item["serial_number"])
			->setCellValue("O".$inc, $log_output["company"] .PHP_EOL . $log_output["name"] . PHP_EOL . $log_output["address_1"] . "\r" . $log_output["address_2"] . "\r" . $log_output["city"] . "\r" . $log_output["state"] . "\r" . $log_output["zip_code"])
			->setCellValue("P".$inc, $item["carrier_name"] . " " . $item["tracking"])
			->setCellValue("Q".$inc, $item["shipping_cost"])
			->setCellValue("R".$inc, $item["log_movement_notes"]);	
			
			$objPHPExcel->getActiveSheet()->getRowDimension($inc)->setRowHeight(55);
			$objPHPExcel->getActiveSheet(0)->getStyle('A' . $inc . ':R' . $inc)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
			$objPHPExcel->getActiveSheet()->getStyle('H' . $inc)->getAlignment()->setWrapText(true);
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

*/
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