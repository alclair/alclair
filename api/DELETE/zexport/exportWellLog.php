<?php
include_once "../../config.inc.php";
include_once "../../includes/PHPExcel/Classes/PHPExcel.php";
if(empty($_SESSION["UserId"])&&$_REQUEST["token"]!=$rootScope["SWDApiToken"])
{
    return;
}
$response = array();
$response["code"] = "";
$response["message"] = "";
$response['data'] = null;
try
{	  
    $params = null;
    $startdate = $_REQUEST['startdate'];
    $enddate = $_REQUEST['enddate'];    
    $query =   "select to_char(date_logged,'yyyy-MM-dd') as logged,
	oil_sold_barrels as total_oil_sold, 
	(select common_name from ticket_tracker_disposalwell where id=well_logs_dailywelllog.disposal_well_id) as disposal_well_name,
                level_oil_tank_1_ft as total_oil_tank1,
				level_oil_tank_2_ft as total_oil_tank2,
				level_oil_tank_3_ft as total_oil_tank3,
                level_gun_ft as total_gun,
                level_skim_tank_1_ft as total_skim_tank1,
				level_skim_tank_2_ft as total_skim_tank2,
                injection_rate as total_injection_rate,
				injection_pressure as total_injection_pressure,
                flowmeter_barrels as total_flowmeter_barrels, notes
                from well_logs_dailywelllog
                where date_logged >= to_date('$startdate','YYYY-MM-DD') and date_logged <= to_date('$enddate','YYYY-MM-DD')
                
                order by logged asc"; 
    $stmt = pdo_query( $pdo, $query, $params ); 
    $result = pdo_fetch_all( $stmt );
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0)->setTitle("Well Logs");
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A1","Date Logged")
				->setCellValue("B1","Disposal Well")
                ->setCellValue("C1","Oil Sold (bbls)")
                ->setCellValue("D1","Oil Tank #1 (ft)")
                ->setCellValue("E1","Oil Tank #2 (ft)")
                ->setCellValue("F1","Oil Tank #3 (ft)")
                ->setCellValue("G1","Gun (ft)")
                ->setCellValue("H1","Skim Tank #1 (ft)")
                ->setCellValue("I1","Skim Tank #2 (ft)")
                ->setCellValue("J1","Injection Rate")
                ->setCellValue("K1","Injection Pressure")
                ->setCellValue("L1","Flowmeter Total BBLS")
				->setCellValue("M1","Notes");
    $rowcount = 2;
    foreach($result as $item)
    {
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A$rowcount",$item["logged"])
			->setCellValue("B$rowcount",$item["disposal_well_name"])
            ->setCellValue("C$rowcount",$item["total_oil_sold"])
            ->setCellValue("D$rowcount",$item["total_oil_tank1"])
            ->setCellValue("E$rowcount",$item["total_oil_tank2"])
            ->setCellValue("F$rowcount",$item["total_oil_tank3"])
            ->setCellValue("G$rowcount",$item["total_gun"])
            ->setCellValue("H$rowcount",$item["total_skim_tank1"])
            ->setCellValue("I$rowcount",$item["total_skim_tank2"])
            ->setCellValue("J$rowcount",$item["total_injection_rate"])
            ->setCellValue("K$rowcount",$item["total_injection_pressure"])
            ->setCellValue("L$rowcount",$item["total_flowmeter_barrels"])
			->setCellValue("M$rowcount",$item["notes"]);
        $rowcount++;
    }
    //$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
    $filename = "Export-WellLogs-From(".str_replace("/","-",$startdate).")-To(".str_replace("/","-",$enddate).")-".time().".xlsx";
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