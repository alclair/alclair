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
    
    $trucking_company_id = $_REQUEST["trucking_company_id"];
    $source_well_id = $_REQUEST["source_well_id"];
    $disposal_well_id = $_REQUEST["disposal_well_id"];
    
    if(!empty($_REQUEST["trucking_company_id"]))
    {
        $conditionSql .= " and (t1.trucking_company_id=:trucking_company_id)";
        
        $params[":trucking_company_id"]=$_REQUEST["trucking_company_id"];
    }
        if(!empty($_REQUEST["source_well_id"]))
    {
        $conditionSql .= " and (t1.source_well_id=:source_well_id)";
        
        $params[":source_well_id"]=$_REQUEST["source_well_id"];
    }
        if(!empty($_REQUEST["disposal_well_id"]))
    {
        $conditionSql .= " and (t1.disposal_well_id=:disposal_well_id)";
        
        $params[":disposal_well_id"]=$_REQUEST["disposal_well_id"];
    }

        
    $query =   "select t1.id, t1.ticket_number, to_char(t1.date_delivered,'yyyy-MM-dd') as date_delivered, t1.barrels_delivered, twatertype.type as water_type_name,t1.barrel_rate, t1.notes,
                twell.file_number as source_well_file_number,twell.current_well_name as source_well_name, twell.latitude as latitude, twell.longitude as longitude,
                toperator.name as source_well_operator_name,
                tdisposal.common_name as disposal_well_name,tcompany.name as company_name,
                t1.delivery_method,t1.water_source_type
                from ticket_tracker_ticket as t1
                left join ticket_tracker_well as twell on t1.source_well_id =twell.id
                left join ticket_tracker_operator toperator on twell.current_operator_id=toperator.id
                left join ticket_tracker_watertype as twatertype on t1.water_type_id = twatertype.id
                left join ticket_tracker_disposalwell as tdisposal on t1.disposal_well_id = tdisposal.id 
                left join ticket_tracker_truckingcompany as tcompany on t1.trucking_company_id = tcompany.id
                where date_delivered >= to_date('$startdate','YYYY-MM-DD') and date_delivered <= to_date('$enddate','YYYY-MM-DD') $conditionSql
                order by date_delivered asc";  

    $stmt = pdo_query( $pdo, $query, $params ); 
    $result = pdo_fetch_all( $stmt );

    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0)->setTitle("Tickets");
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A1","Date Delivered")
                ->setCellValue("B1","Disposal Well")
                ->setCellValue("C1","Trucking Company")
                ->setCellValue("D1","Water Type")
                ->setCellValue("E1","Barrels Delivered")
				->setCellValue("F1","Barrel Rate")
                ->setCellValue("G1","Ticket Number")
                ->setCellValue("H1","Delivery Method")
                ->setCellValue("I1","Water Source Type")
                ->setCellValue("J1","Well File No.")
                ->setCellValue("K1","Well Name")
                ->setCellValue("L1","Operator")
				->setCellValue("M1","Notes")
				->setCellValue("N1","Latitude")
				->setCellValue("O1","Longitude");

    $rowcount = 2;
    foreach($result as $item)
    {
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A$rowcount",$item["date_delivered"])
            ->setCellValue("B$rowcount",$item["disposal_well_name"])
            ->setCellValue("C$rowcount",$item["company_name"])
            ->setCellValue("D$rowcount",$item["water_type_name"])
            ->setCellValue("E$rowcount",$item["barrels_delivered"])
			->setCellValue("F$rowcount",number_format((float)$item["barrel_rate"],2,'.', ''))
            ->setCellValue("G$rowcount",$item["ticket_number"])
            ->setCellValue("H$rowcount",$item["delivery_method"])
            ->setCellValue("I$rowcount",$item["water_source_type"])
            ->setCellValue("J$rowcount",$item["source_well_file_number"])
            ->setCellValue("K$rowcount",$item["source_well_name"])
            ->setCellValue("L$rowcount",$item["source_well_operator_name"])
			->setCellValue("M$rowcount",$item["notes"])
			->setCellValue("N$rowcount",$item["latitude"])
			->setCellValue("O$rowcount",$item["longitude"]);
            
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(22);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(11);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(13.50);
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(9);
			$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(11.50);
			$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(13.20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);            
			$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
			$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(32);
			$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(32);
			$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(50);
			$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(40);
			$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(40);
        $rowcount++;
    }

    //$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
    $filename = "Export-Tickets-From(".str_replace("/","-",$startdate).")-To(".str_replace("/","-",$enddate).")-".time().".xlsx";
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