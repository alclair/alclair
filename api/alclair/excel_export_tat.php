<?php
include_once "../../config.inc.php";
include_once "../../includes/PHPExcel/Classes/PHPExcel.php";

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

require '/var/www/html/otisdev/vendor/autoload.php';
//require $rootScope["RootPath"]."vendor/autoload.php";

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
	$letter = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X' , 'Y', 'Z',
    							 'AA', 'AB', 'AC', 'AD', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ',
    							  'BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN', 'BO', 'BP', 'BQ', 'BR', 'BS', 'BT', 'BU', 'BV', 'BW', 'BX', 'BY', 'BZ',
    							  'CA', 'CB', 'CC', 'CD', 'CE', 'CF', 'CG', 'CH', 'CI', 'CJ', 'CK', 'CL', 'CM', 'CN', 'CO', 'CP', 'CQ', 'CR', 'CS', 'CT', 'CU', 'CV', 'CW', 'CX', 'CY', 'CZ',
    							  'DA', 'DB', 'DC', 'DD', 'DE', 'DF', 'DG', 'DH', 'DI', 'DJ', 'DK', 'DL', 'DM', 'DN', 'DO', 'DP', 'DQ', 'DR', 'DS', 'DT', 'DU', 'DV', 'DW', 'DX', 'DY', 'DZ',
    							  'EA', 'EB', 'EC', 'ED', 'EE', 'EF', 'EG', 'EH', 'EI', 'EJ', 'EK', 'EL', 'EM', 'EN', 'EO', 'EP', 'EQ', 'ER', 'ES', 'ET', 'EU', 'EV', 'EW', 'EX', 'EY', 'EZ');
    
	
    $StartDate = $_REQUEST['StartDate'];
    $EndDate = $_REQUEST['EndDate'];
    
    $mydate=getdate(date("U"));
    $month_number = $mydate["mon"];
    //$month_number = (int)$month_number;
	$month_string = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'); 
	
	// Create new Spreadsheet object
	$spreadsheet = new Spreadsheet();
	
	// Set workbook properties
	$spreadsheet->getProperties()->setCreator('Tyler Folsom')
        ->setLastModifiedBy('Tyler Folsom')
        ->setTitle('Testing')
        ->setSubject('PhpSpreadsheet')
        ->setDescription('Turnaround Time')
        ->setKeywords('Microsoft office 2013 php PhpSpreadsheet')
        ->setCategory('Excel');
 
	// Set worksheet title
	$spreadsheet->getActiveSheet()->setTitle('OTIS');
	
	$spreadsheet->setActiveSheetIndex(0)
			->setCellValue("A1", "Designed For") 
			->setCellValue("B1", "Turnaround Time") 
			->setCellValue("C1",  "Impressions Received") 
			->setCellValue("D1", "Completed On") 
			->setCellValue("E1", "Model") 
			->setCellValue("F1", "Order ID");
			
if(!empty($_REQUEST["StartDate"])) {
	$conditionSql.=" and (t1.date>=:StartDate)";
	$params[":StartDate"]=$_REQUEST["StartDate"];
}
if(!empty($_REQUEST["EndDate"])) {
	$conditionSql.=" and (t1.date<=:EndDate)";
	$params[":EndDate"]=date("m/d/Y H:i:s",strtotime($_REQUEST["EndDate"] . '23:59:59'));
}
	
$query2 = pdo_query($pdo, "SELECT *, to_char(t1.date,'MM/dd/yyyy') as date
						FROM order_status_log AS t1 
						LEFT JOIN import_orders AS t2 ON t1.import_orders_id = t2.id
						WHERE t1.order_status_id = 12 AND t1.date >= :StartDate AND t1.date <= :EndDate AND t1.import_orders_id IS NOT NULL AND t2.active = TRUE", array(":StartDate"=>$params[":StartDate"], ":EndDate"=>$params[":EndDate"]));
$store_done_data = pdo_fetch_all( $query2 );

$workingDays = array(1, 2, 3, 4, 5); # date format = N (1 = Monday, ...)
$holidayDays = array('*-12-25', '*-01-01', '2013-12-23'); # variable and fixed holidays
$store_start_data = array();
$difference = array();
$ind = 0;

for ($i = 0; $i < count($store_done_data); $i++) {
	
	$query = pdo_query($pdo, "SELECT *, to_char(received_date, 'MM/dd/yyyy') as start_date FROM import_orders WHERE id = :import_orders_id" , array(":import_orders_id"=>$store_done_data[$i]["import_orders_id"]));
	$store_start_data = pdo_fetch_all( $query );
	$rowcount = pdo_rows_affected( $query );	
		
	if ($rowcount != 0 ) {
		$from = $store_start_data[0]["start_date"];
			$to = $store_done_data[$i]["date"];
			$from = new DateTime($from);
			$from->modify('+1 day');
			$to = new DateTime($to);
			//$to->modify('+1 day');
			$interval = new DateInterval('P1D');
			$periods = new DatePeriod($from, $interval, $to);

			$days = 0;
			foreach ($periods as $period) {
        		if (!in_array($period->format('N'), $workingDays)) continue;
				if (in_array($period->format('Y-m-d'), $holidayDays)) continue;
				if (in_array($period->format('*-m-d'), $holidayDays)) continue;
				$days++;
			}	
				
			$difference[$ind] = $days;
			
			if($difference[$ind] == 0 ) {
				$response["test3"]  = $store_done_data[$i]["import_orders_id"];
			}
			$ind++;				
		}
	} 
	// CLOSE FOR LOOP
	
	$response["num_of_orders1"] = $ind;
	$response["num_of_orders2"] = count($difference);
	$response["min"] = min($difference);
	$response["max"] = max($difference);
	$response["avg"] = round(array_sum($difference)/count($difference));
	
	$values = array_count_values($difference); 
	$response["mode"] = array_search(max($values), $values);
	
	$count = count($difference);
    sort($difference);
    $mid = floor($count/2);
    $response["median"] =  ($difference[$mid]+$difference[$mid+1-$count%2])/2;
   
	$counter = 0;
	for($j = 0; $j < count($difference); $j++) {
		if($difference[$j] == 0) {
			$counter++;
		}
	}
	
	$store_start_data2 = array();
	$difference2 = array();
	$difference_zero = array();
	$ids_to_keep  = array();
	$ids_to_keep_zero  = array();
	$ind = 0;
	$ind2 = 0;
	$ind3 = 0;
	$OrdersList = array();
	$OrdersList_zero = array();
	
	// THIS LOOP CALCULATES THE NUMBER OF DAYS THE ORDER TOOK TO COMPLETE
	for ($i = 0; $i < count($store_done_data); $i++) {

		$query2 = pdo_query($pdo, "SELECT *, to_char(received_date, 'MM/dd/yyyy') as start_date FROM import_orders WHERE id = :import_orders_id" , array(":import_orders_id"=>$store_done_data[$i]["import_orders_id"]));
		$store_start_data2 = pdo_fetch_all( $query2 );
		$rowcount2 = pdo_rows_affected( $query2 );
		
		
		if ($rowcount2 != 0 ) {
		
			$from = $store_start_data2[0]["start_date"];
			$to = $store_done_data[$i]["date"];
			$from = new DateTime($from);
			$from->modify('+1 day');
			$to = new DateTime($to);
			//$to->modify('+1 day');
			$interval = new DateInterval('P1D');
			$periods = new DatePeriod($from, $interval, $to);

			$days = 0;
			foreach ($periods as $period) {
        		if (!in_array($period->format('N'), $workingDays)) continue;
				if (in_array($period->format('Y-m-d'), $holidayDays)) continue;
				if (in_array($period->format('*-m-d'), $holidayDays)) continue;
				$days++;
			}	
				
			$difference2[$ind2] = $days;	
			$difference_zero[$ind3] = $days;	
		
		
			// IF THE ORDER TOOK LONGER THAN THE AVERAGE THEN IT GETS SAVED FOR OUTPUT TO THE TURN AROUND TIME SCREEN
			//if($difference2[$ind2] > $response["avg"]) { //$response["avg"] - $response["avg"]) {
				$ids_to_keep[$ind2] = $store_done_data[$i]["import_orders_id"];
				
				$query3 = pdo_query($pdo, "SELECT t1.*, to_char(t1.date,'MM/dd/yyyy') as date, to_char(t1.estimated_ship_date,'MM/dd/yyyy') as estimated_ship_date, to_char(t1.received_date,'MM/dd/yyyy') as received_date,IEMs.id AS monitor_id, t2.status_of_order
                  FROM import_orders AS t1
                  LEFT JOIN monitors AS IEMs ON t1.model = IEMs.name
                  LEFT JOIN order_status_table AS t2 ON t1.order_status_id = t2.order_in_manufacturing
                  WHERE 1=1 AND t1.active = TRUE AND t1.id = :import_orders_id", array(":import_orders_id"=>$ids_to_keep[$ind2]));
                  $OrdersList[$ind2] = pdo_fetch_array( $query3 );
                  $OrdersList[$ind2]["difference"] = $difference2[$ind2];
                  $OrdersList[$ind2]["done_date"] = $store_done_data[$i]["date"];
				  $ind2++;
			//}	
			/*if($difference_zero[$ind3] == 0) {
				$ids_to_keep_zero[$ind3] = $store_done_data[$i]["import_orders_id"];
				
				$query4 = pdo_query($pdo, "SELECT t1.*, to_char(t1.date,'MM/dd/yyyy') as date, to_char(t1.estimated_ship_date,'MM/dd/yyyy') as estimated_ship_date, to_char(t1.received_date,'MM/dd/yyyy') as received_date,IEMs.id AS monitor_id, t2.status_of_order
                  FROM import_orders AS t1
                  LEFT JOIN monitors AS IEMs ON t1.model = IEMs.name
                  LEFT JOIN order_status_table AS t2 ON t1.order_status_id = t2.order_in_manufacturing
                  WHERE 1=1 AND t1.active = TRUE AND t1.id = :import_orders_id", array(":import_orders_id"=>$ids_to_keep_zero[$ind3]));
                  
                  $response["test"] = pdo_rows_affected( $query4 ) . " and it is also " . count($OrdersList_zero[$ind3]);
                  if(pdo_rows_affected($query4) != 0) {
	                  $OrdersList_zero[$ind3] = pdo_fetch_array( $query4 );
	                  $OrdersList_zero[$ind3]["difference"] = $difference_zero[$ind3];
					  $OrdersList_zero[$ind3]["done_date"] = $store_done_data[$i]["date"];
					  $ind3++;
				  }

			}*/
				
		}
	} 
	
	
	$Sorted = array();
	$ind = 0;
	for ($i = 0; $i < count($ids_to_keep); $i++) {
		if($i == 0 ) {
			$Sorted[0] = $OrdersList[0];
		} elseif($i == 1) {
			if( is_null($OrdersList[$i]) ) {
				$Sorted[1] = $Sorted[0]; // NO MATTER WHAT THE ORIGINAL VALUE WAS IT MOVES TO THE SECOND SPOT IN THE ARRAY
				$Sorted[0] = $OrdersList[1]; // NULL VALUE GOES TO THE FRONT OF THE ARRAY
			} elseif( $OrdersList[1]["difference"] < $Sorted[0]["difference"] ) {
				$Sorted[1] = $OrdersList[1];
			} else {
				$Sorted[1] = $Sorted[0];
				$Sorted[0] = $OrdersList[1];
			}
					
		} else {
			for ($k = 0; $k < count($Sorted); $k++) {
				if( $OrdersList[$i]["difference"] > $Sorted[$k]["difference"]) {
					for ($m = $i; $m > $k; $m--) {
						$Sorted[$m] = $Sorted[$m-1];
					}	
					$Sorted[$k] = $OrdersList[$i];
					break;
				} elseif( $k == count($Sorted) - 1)  {
					$Sorted[$i] = $OrdersList[$i];
				}
			} // CLOSE FOR LOOP
		} // CLOSE IF STATEMENT
	}  // CLOSE FOR LOOPS

	for ($m = 0; $m < count($Sorted); $m++) {
		$ind = $m+2;
		$spreadsheet->setActiveSheetIndex(0)
			->setCellValue($letter[0].$ind, $Sorted[$m]["designed_for"]) 
			->setCellValue($letter[1].$ind, $Sorted[$m]["difference"]) 
			->setCellValue($letter[2].$ind,  $Sorted[$m]["received_date"]) 
			->setCellValue($letter[3].$ind, $Sorted[$m]["done_date"]) 
			->setCellValue($letter[4].$ind, $Sorted[$m]["model"]) 
			->setCellValue($letter[5].$ind, $Sorted[$m]["order_id"]);
	}		

	// SET WIDTH, BOLD & FONT SIZE
	$spreadsheet->getActiveSheet()->getColumnDimension("A")->setWidth(20);
	$spreadsheet->getActiveSheet()->getColumnDimension("B")->setWidth(20);
	$spreadsheet->getActiveSheet()->getColumnDimension("C")->setWidth(20);
	$spreadsheet->getActiveSheet()->getColumnDimension("D")->setWidth(20);
	$spreadsheet->getActiveSheet()->getColumnDimension("E")->setWidth(20);
	$spreadsheet->getActiveSheet()->getColumnDimension("F")->setWidth(20);
	$spreadsheet->getActiveSheet(0)->getStyle('A1:F1')->getFont()->setBold(true)->setSize(12);	
	
	$num_rows = count($Sorted)+1;
	// VERTICALLY & HORIZONTALLY CENTER
	$spreadsheet->setActiveSheetIndex(0)->getStyle("A1:F".$num_rows)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$spreadsheet->setActiveSheetIndex(0)->getStyle("A1:F".$num_rows)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						
	$writer = IOFactory::createWriter($spreadsheet, 'Xls');
    //$filename = "Export-Queen-Readings(".str_replace("/","-",$startdate).")-To(".str_replace("/","-",$enddate).")-".time().".xlsx";
    //$filename = "Export-Queen-Readings.xlsx";
    //$filename = "ZzZzZ-".date("m-d-Y").".xlsx";
	//$filename = "Step4.xlsx";
	$filename = "Export-Turnaround-Time-Data-".date("m-d-Y").".xlsx";
	//echo $filename;

    //$filename = "hello.xlsx";
    //$objWriter->save("../../data/export/$filename");
    $writer->save("../../data/export/excel/$filename");
	
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