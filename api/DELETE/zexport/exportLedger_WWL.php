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

    $query =   "select t1.id, t1.ticket_number, to_char(t1.date_delivered,'yyyy-MM-dd') as date_delivered, t1.picocuries, t1.light_weight, t1.heavy_weight, t1.tons, t1.total_dollars, 							t2.name as landfill_site, t2.freight_fee as freight_fee, t2.tipping_fee as tipping_fee
                from ticket_tracker_landfill as t1
                left join landfill_disposal_sites as t2 on t1.landfill_disposal_site_id = t2.id 
                where date_delivered >= to_date('$startdate','YYYY-MM-DD') and date_delivered <= to_date('$enddate','YYYY-MM-DD')               
                order by date_delivered asc";  
                
                select t1.*, to_char(t1.date_delivered,'MM/dd/yyyy') as date_delivered,
                  to_char(t1.date_created,'MM/dd/yyyy') as date_created, t5.name as tank_name, t6.type as fluid_type
                  from ticket_tracker_oil as t1
                  LEFT JOIN tanks AS t5 ON t1.tank_id = t5.id
				  LEFT JOIN ticket_tracker_fluidtype AS t6 ON t1.fluid_type_id = t6.id
                  where t1.id = :id

    $query =   "SELECT t1.*, t2.name as tank_from_name, t3.name as tank_to_name, t4.type as fluid_type, t5.first_name, t5.last_name
    						 FROM tank_movement_tracker,  
                			LEFT JOIN tanks AS t2 ON t1.tank_from_id = t2.id
                			LEFT JOIN tanks AS t3 ON t1.tank_to_id = t3.id
                			LEFT JOIN ticket_tracker_fluidtype AS t4 ON t1.fluid_type_id = t4.id
                			LEFT JOIN tanks AS t5 ON t1.created_by_id = t5.id
                			
                			WHERE date >= to_date('$startdate','YYYY-MM-DD') AND date <= to_date('$enddate','YYYY-MM-DD')               
							ORDER BY date ASC";  

    $stmt = pdo_query( $pdo, $query, $params ); 
    $result = pdo_fetch_all( $stmt );

    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0)->setTitle("Tickets");
    $objPHPExcel->setActiveSheetIndex(0)
                ->mergeCells('B3:E3')
                ->setCellValue("B3","Tank Ledger")
                
                ->setCellValue("A5","Date")
                ->setCellValue("B5","Movement Type")
                ->setCellValue("C5","From Tank")
                ->setCellValue("D5","To Tank")
                ->setCellValue("E5","Fluid")
				->setCellValue("F5","Barrels")
				->setCellValue("G5","User")
				->setCellValue("H5","Ticket #")
				->setCellValue("I5","Ending Barrels From")
                ->setCellValue("J5","Ending Barrels To");

	$objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:J5")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	$objPHPExcel->getActiveSheet(0)->getStyle('B3:E3')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
	$objPHPExcel->getActiveSheet(0)->getStyle('A5:J5')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);	
	$objPHPExcel->getActiveSheet(0)->getStyle('A5:J5')->getBorders()->getInside()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
	//$objPHPExcel->getActiveSheet(0)->getStyle('O7')->getNumberFormat()->setFormatCode("\$#,##0.00");
	//$objPHPExcel->getActiveSheet(0)->getStyle('H6:H1000')->getNumberFormat()->setFormatCode("\$#,##0.00");
	//$objPHPExcel->getActiveSheet(0)->getStyle('I6:I1000')->getNumberFormat()->setFormatCode("\$#,##0.00");
	//$objPHPExcel->getActiveSheet(0)->getStyle('J6:J1000')->getNumberFormat()->setFormatCode("\$#,##0.00");
	$objPHPExcel->getActiveSheet(0)->getStyle('B3')->getFont()->setBold(true)->setSize(14);
	$objPHPExcel->getActiveSheet(0)->getStyle('A5:J5')->getFont()->setBold(true)->setSize(11);
	
	$objPHPExcel->getActiveSheet()->getStyle('B3:E3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('F4B084');
	$objPHPExcel->getActiveSheet()->getStyle('A5:J5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('F4B084');
	
	$objPHPExcel->getActiveSheet(0)->getStyle('A6:G1000')->getFont()->setBold(false)->setSize(11);
	
	
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(14);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(14);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(9);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(16.5);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(17.5);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(9);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(13.0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(11);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(11);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(9);
		
    $rowcount = 6;
    foreach($result as $item)
    {
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A$rowcount",$item[""])
            ->setCellValue("B$rowcount",$item["date"])
            ->setCellValue("C$rowcount",$item["movement_type"])
            ->setCellValue("D$rowcount",$item["tank_from_name"])
            ->setCellValue("E$rowcount",$item["tank_to_name"])
			->setCellValue("F$rowcount",$item["fluid_type"])
            ->setCellValue("G$rowcount",$item["barrels_delivered"])
            ->setCellValue("H$rowcount",$item["first_name"]." ".$item["last_name"])
            ->setCellValue("I$rowcount",$item["ending_barrels_from"])
            ->setCellValue("J$rowcount",$item["ending_barrels_to"]);
            
            //->setCellValue("L$rowcount",$item["tenorm_picocuries"]);
            //->setCellValue("L$rowcount",number_format((float)$item["barrel_rate"],2,'.', ''));
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
