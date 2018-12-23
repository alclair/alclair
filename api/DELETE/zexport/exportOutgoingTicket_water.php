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

        
    $query =   "select t1.id, t1.ticket_number, to_char(t1.date_delivered,'yyyy-MM-dd') as date_delivered, t1.barrels_delivered, twatertype.type as water_type_name,t1.barrel_rate,
                twell.file_number as source_well_file_number,twell.current_well_name as source_well_name, toperator.name as source_well_operator_name,
                tdisposal.common_name as disposal_well_name,tcompany.name as company_name,
                t1.delivery_method,t1.water_source_type
                from ticket_tracker_water as t1
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
    			->mergeCells('B3:E3')
                ->setCellValue("B3","Salt Water Disposal")

                ->setCellValue("A5","Date Delivered")
                ->setCellValue("B5","Sourcel Well")
                ->setCellValue("C5","Trucking Company")
                ->setCellValue("D5","Water Type")
                ->setCellValue("E5","BBLs Delivered")
				->setCellValue("F5","Barrel Rate")
                ->setCellValue("G5","Ticket Number")
                //->setCellValue("H1","Delivery Method")
                //->setCellValue("I1","Water Source Type")
                ->setCellValue("H5","Well File No.")
                ->setCellValue("I5","Disposal Well")
                ->setCellValue("J5","Operator")
                ->setCellValue("K5","Total $");
    
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:K5")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    
    $objPHPExcel->getActiveSheet(0)->getStyle('B3:E3')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);   
    $objPHPExcel->getActiveSheet(0)->getStyle('B3')->getFont()->setBold(true)->setSize(14);
    $objPHPExcel->getActiveSheet()->getStyle('B3:E3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('F4B084');  
                
	$objPHPExcel->getActiveSheet(0)->getStyle('A5:K5')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);	
	$objPHPExcel->getActiveSheet(0)->getStyle('A5:K5')->getBorders()->getInside()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
	$objPHPExcel->getActiveSheet(0)->getStyle('A5:K5')->getFont()->setBold(true)->setSize(11);
	$objPHPExcel->getActiveSheet()->getStyle('A5:K5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('F4B084');
	
	$objPHPExcel->getActiveSheet(0)->getStyle('A6:G1000')->getFont()->setBold(false)->setSize(11); //	->setFontColor('FFFFFF');
	$objPHPExcel->getActiveSheet(0)->getStyle('F6:F1000')->getNumberFormat()->setFormatCode("\$#,##0.00");
	$objPHPExcel->getActiveSheet(0)->getStyle('K6:K1000')->getNumberFormat()->setFormatCode("\$#,##0.00");

    $rowcount = 6;
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
            //->setCellValue("H$rowcount",$item["delivery_method"])
            //->setCellValue("I$rowcount",$item["water_source_type"])
            ->setCellValue("H$rowcount",$item["source_well_file_number"])
            ->setCellValue("I$rowcount",$item["source_well_name"])
            ->setCellValue("J$rowcount",$item["source_well_operator_name"])
            ->setCellValue("K$rowcount",$item["barrels_delivered"]*$item["barrel_rate"]);
                        
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(13);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(22);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(22);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(14);
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(11);
			$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15.0);
			//$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(13.20);
			//$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);            
			$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
			$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(32);
			$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(32);
			$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(10);

			$objPHPExcel->setActiveSheetIndex(0)->getStyle("B3:E999")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);									
			$objPHPExcel->setActiveSheetIndex(0)->getStyle("E2:E999")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle("F2:F999")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle("H2:H999")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle("K2:K999")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
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