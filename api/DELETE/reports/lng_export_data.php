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
    
	if(date("I",time())) { 
		$new_StartDate = date("m-d-Y H:i:s",strtotime($_REQUEST["StartDate"] . '00:00:00')  + 5 * 3600 ); 
		$new_EndDate   = date("m-d-Y H:i:s",strtotime($_REQUEST["EndDate"] . '23:59:59') + 5 * 3600 ); 
	}
	else { 
		$new_StartDate = date("m-d-Y H:i:s",strtotime($_REQUEST["StartDate"] . '00:00:00')  + 6 * 3600 ); 
		$new_EndDate   = date("m-d-Y H:i:s",strtotime($_REQUEST["EndDate"] . '23:59:59') + 6 * 3600 ); 
	}
    
	$response['start'] = $new_StartDate;
	$response['end'] = $new_EndDate;

	if(!empty($_REQUEST["StartDate"]))
	{
		$conditionSql.=" and (time_stamp >= '".$new_StartDate."')";
		$params[":StartDate"]=$new_StartDate;//$_REQUEST["StartDate"];
	}
	if(!empty($_REQUEST["EndDate"]))
	{
		$conditionSql.=" and (time_stamp <=  '".$new_EndDate."')";
		$params[":EndDate"]=$new_EndDate;//$_REQUEST["EndDate"] . ' 23:59:59';
	}

	if($lng_queens == 0) {
		// USED REQUEST INSTEAD OF SESSION BECAUSE SESSION WASN'T WORKING FOR SOME REASON
		// PASS IN ISADMIN, ISMANAGER AND USERID FROM api/export/lng_export_button.php
		if($_REQUEST["IsAdmin"] == 1 || $_REQUEST["IsManager"] == 1) {
			$stmt = pdo_query( $pdo, 'SELECT * FROM queens ORDER BY queens_id',null);	
			//$stmt = pdo_query( $pdo, 'SELECT * FROM queens WHERE queens_id = 1 or queens_id = 2 or queens_id = 3 or queens_id = 4 or queens_id = 5 or queens_id = 6',null);	
			$queens = pdo_fetch_all($stmt);
			$testing = "in here";
			$testing2 = json_encode($queens);
		}
		else {
			$stmt = pdo_query( $pdo, 'SELECT * FROM auth_user WHERE id=:id', array(":id"=>$_REQUEST["UserId"]));	
			$output = pdo_fetch_array($stmt);
		
			$stmt = pdo_query( $pdo, 'SELECT * FROM queens WHERE customer_id=:customer_id', array(":customer_id"=>$output["customer_id"]));	
			$queens = pdo_fetch_all($stmt);	
			$testing = "no here";
		}
	} else {
		//$stmt = pdo_query( $pdo, 'SELECT * FROM queens WHERE queens_id = :queens_id',array(":queens_id"=>$lng_queens));	
		
		$stmt = pdo_query( $pdo, 'SELECT * FROM queens WHERE queens_id = :queens_id', array(":queens_id"=>$lng_queens));	
		$queens = pdo_fetch_all($stmt);
		//$testing = $_SESSION["IsAdmin"];
		//$testing = $_SESSION["UserId"];
		$testing = $queens[0]["name"];
		$testing2 = $queens["name"];
		$testing3 = "at least this worked";
		$teting4 = $new_StartDate;
	}

	$objPHPExcel = new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0)->setTitle("Queen Data");
	/*$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue("A1",$lng_queens)     		
		->setCellValue("A2",$testing3)						
		->setCellValue("B2",$output["username"]) 
		->setCellValue("C2",$rootScope['isManager']) 
		->setCellValue("D2",$testing5); */
			// Time Stamp, Tank Level, Flow Rate, Totalizer

	foreach ($queens as $queen) {
    
		if ($queen["queens_id"] == 1) {
			$query =   "SELECT tank_level, flow_rate*60 as flow_rate, to_char(time_stamp, 'yyyy-MM-dd HH24:MI') as time_stamp, totalizer, row_number() over() row_num_1 FROM inox_q1
			WHERE 1 = 1 $conditionSql
			ORDER BY time_stamp ASC";	
			//$inc = $inc + 1;
		}
		elseif ($queen["queens_id"] == 2) {
			$query =   "SELECT tank_level, flow_rate, to_char(time_stamp, 'yyyy-MM-dd HH24:MI') as time_stamp, totalizer, row_number() over() row_num_1 FROM chart_q2
								WHERE 1 = 1 $conditionSql
								ORDER BY time_stamp ASC";	
			//$inc = $inc + 1;
		}
		elseif ($queen["queens_id"] == 3) {
			$query =   "SELECT tank_level, flow_rate*60 as flow_rate, to_char(time_stamp, 'yyyy-MM-dd HH24:MI') as time_stamp, totalizer, row_number() over() row_num_1 FROM inox_q3
			WHERE 1 = 1 $conditionSql
			ORDER BY time_stamp ASC";	
			//$inc = $inc + 1;
		}
		elseif ($queen["queens_id"] == 4) {
			$query =   "SELECT tank_level, flow_rate*60 as flow_rate, to_char(time_stamp, 'yyyy-MM-dd HH24:MI') as time_stamp, totalizer, row_number() over() row_num_1 FROM inox_q4
			WHERE 1 = 1 $conditionSql
			ORDER BY time_stamp  ASC";	
			//$inc = $inc + 1;
		}
		elseif ($queen["queens_id"] == 5) {
			$query =   "SELECT tank_level, flow_rate, to_char(time_stamp, 'yyyy-MM-dd HH24:MI') as time_stamp, totalizer, row_number() over() row_num_1 FROM chart_q5
			WHERE 1 = 1 $conditionSql
			ORDER BY time_stamp  ASC";	
			//$inc = $inc + 1;	
		}
		elseif ($queen["queens_id"] == 6) {
			$query =   "SELECT tank_level, flow_rate*60 as flow_rate, to_char(time_stamp, 'yyyy-MM-dd HH24:MI') as time_stamp, totalizer, row_number() over() row_num_1 FROM inox_q6
			WHERE 1 = 1 $conditionSql
			ORDER BY time_stamp  ASC";
			//$inc = $inc + 1;	
		}
		elseif ($queen["queens_id"] == 7) {
			$query =   "SELECT tank_level, flow_rate, to_char(time_stamp, 'yyyy-MM-dd HH24:MI') as time_stamp, totalizer, row_number() over() row_num_1 FROM chart_q7
			WHERE 1 = 1 $conditionSql
			ORDER BY time_stamp  ASC";	
			//$inc = $inc + 1;	
		}
		elseif ($queen["queens_id"] == 8) {
			$query =   "SELECT tank_level, flow_rate, to_char(time_stamp, 'yyyy-MM-dd HH24:MI') as time_stamp, totalizer, row_number() over() row_num_1 FROM chart_q8
			WHERE 1 = 1 $conditionSql
			ORDER BY time_stamp  ASC";	
			//$inc = $inc + 1;	
		}
		elseif ($queen["queens_id"] == 9) {
			$query =   "SELECT tank_level, flow_rate, to_char(time_stamp, 'yyyy-MM-dd HH24:MI') as time_stamp, totalizer, row_number() over() row_num_1 FROM chart_q9
			WHERE 1 = 1 $conditionSql
			ORDER BY time_stamp  ASC";	
			//$inc = $inc + 1;	
		}
		elseif ($queen["queens_id"] == 10) {
			$query =   "SELECT tank_level, flow_rate, to_char(time_stamp, 'yyyy-MM-dd HH24:MI') as time_stamp, totalizer, row_number() over() row_num_1 FROM chart_q10
			WHERE 1 = 1 $conditionSql
			ORDER BY time_stamp  ASC";	
			//$inc = $inc + 1;	
		}
		elseif ($queen["queens_id"] == 13) {
			$query =   "SELECT tank_level, flow_rate, to_char(time_stamp, 'yyyy-MM-dd HH24:MI') as time_stamp, totalizer, row_number() over() row_num_1 FROM chart_PQ34
			WHERE 1 = 1 $conditionSql
			ORDER BY time_stamp  ASC";	
			//$inc = $inc + 1;	
		}
		elseif ($queen["queens_id"] == 16) {
			$query =   "SELECT tank_level, flow_rate, to_char(time_stamp, 'yyyy-MM-dd HH24:MI') as time_stamp, totalizer, row_number() over() row_num_1 FROM chart_PQ66
			WHERE 1 = 1 $conditionSql
			ORDER BY time_stamp  ASC";	
			//$inc = $inc + 1;	
		}
		else {
			continue;
		}

 			
    	$stmt= pdo_query( $pdo, $query, null); 
		$result = pdo_fetch_all( $stmt );
    
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue($letter[$inc]."1",$queen["name"]) //$queen["name"])    	// A inc
			//->setCellValue("A1","INOX Q1 - Not in field")    		
			->setCellValue($letter[$inc]."2","Time Stamp")							// A inc+1
			->setCellValue($letter[$inc+1]."2","Tank Level")
			->setCellValue($letter[$inc+2]."2","Flow Rate")
			->setCellValue($letter[$inc+3]."2","Totalizer");
	
		$inc_data = 3;
		foreach($result as $key => $item) {
			$UTC = new DateTimeZone("UTC");
			$newTZ = new DateTimeZone("America/Chicago");
			$date = new DateTime($item["time_stamp"], $UTC );
			$date->setTimezone( $newTZ );
			
			if(date("I",time())) { 
				$tz_central = date("m-d-Y H:i:s",strtotime($item["time_stamp"])  - 5 * 3600 ); 
			}
			else { 
				$tz_central = date("m-d-Y H:i:s",strtotime($item["time_stamp"])  - 6 * 3600 ); 
			}
		
			if($key == 0) {
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue($letter[$inc].$inc_data, $tz_central)
					->setCellValue($letter[$inc+1].$inc_data, $item["tank_level"])
					->setCellValue($letter[$inc+2].$inc_data, $item["flow_rate"])
					->setCellValue($letter[$inc+3].$inc_data, "-");				
			} else {
				$Previous = $result[$key-1];
				$totalizer = $item["totalizer"]-$Previous["totalizer"];
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue($letter[$inc].$inc_data, $tz_central)
					->setCellValue($letter[$inc+1].$inc_data, $item["tank_level"])
					->setCellValue($letter[$inc+2].$inc_data, $item["flow_rate"])
					->setCellValue($letter[$inc+3].$inc_data, $totalizer);
			}

				
			$inc_data = $inc_data + 1;
		}
		$inc = $inc + 4;
	} 
    
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
	$filename = "Export-Queen-Data-".date("m-d-Y").".xlsx";
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