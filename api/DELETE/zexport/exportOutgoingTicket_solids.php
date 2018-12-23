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

    $stmt = pdo_query( $pdo, $query, $params ); 
    $result = pdo_fetch_all( $stmt );

// DATA - FILE # - LOCATION - WELL NAME - OIL COMPANY - TRUCKING COMPANY - TOTAL BBLS - BOL# - SOLID % - H2O % - INTER. % -  OIL % - µCi/g - $ PER BBL - TOTAL $  PER LOAD - BBLS SOLIDS - DISPOSAL COST - BBLS H2O - DISPOSAL COST - BBLS OIL - OIL SALES - TOTAL REVENUE PER LOAD
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0)->setTitle("Tickets");
    $objPHPExcel->setActiveSheetIndex(0)
                ->mergeCells('B3:E3')
                ->setCellValue("B3","Non Norm Solids Disposal")
                
                ->setCellValue("A5","Date")
                ->setCellValue("B5","Ticket #")
                ->setCellValue("C5","pCl/g")
                ->setCellValue("D5","Tare Weight (lbs.)")
                ->setCellValue("E5","Loaded Weight (lbs.)")
				->setCellValue("F5","Tons")
				->setCellValue("G5","Landfill Location")
				->setCellValue("H5","Freight Fee")
				->setCellValue("I5","Tipping Fee")
                ->setCellValue("J5","Total $");

	$objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:J5")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	$objPHPExcel->getActiveSheet(0)->getStyle('B3:E3')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
	$objPHPExcel->getActiveSheet(0)->getStyle('A5:J5')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);	
	$objPHPExcel->getActiveSheet(0)->getStyle('A5:J5')->getBorders()->getInside()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
	//$objPHPExcel->getActiveSheet(0)->getStyle('O7')->getNumberFormat()->setFormatCode("\$#,##0.00");
	$objPHPExcel->getActiveSheet(0)->getStyle('H6:H1000')->getNumberFormat()->setFormatCode("\$#,##0.00");
	$objPHPExcel->getActiveSheet(0)->getStyle('I6:I1000')->getNumberFormat()->setFormatCode("\$#,##0.00");
	$objPHPExcel->getActiveSheet(0)->getStyle('J6:J1000')->getNumberFormat()->setFormatCode("\$#,##0.00");
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
            ->setCellValue("A$rowcount",$item["date_delivered"])
            ->setCellValue("B$rowcount",$item["ticket_number"])
            ->setCellValue("C$rowcount",$item["picocuries"])
            ->setCellValue("D$rowcount",$item["light_weight"])
            ->setCellValue("E$rowcount",$item["heavy_weight"])
			->setCellValue("F$rowcount",$item["tons"])
            ->setCellValue("G$rowcount",$item["landfill_site"])
            ->setCellValue("H$rowcount",$item["freight_fee"])
            ->setCellValue("I$rowcount",$item["tipping_fee"])
            ->setCellValue("J$rowcount",$item["total_dollars"]);
            
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
