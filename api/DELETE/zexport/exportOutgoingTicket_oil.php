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

    $query =   "select t1.id, t1.ticket_number, to_char(t1.date_delivered,'yyyy-MM-dd') as date_delivered, t1.barrels_delivered, t1.observed_temperature, t1.bsw, t1.gravity, t1.total_dollars, t1.top_ft, t1.top_in, t1.top_decimal, t1.bottom_ft, t1.bottom_in, t1.bottom_decimal, t1.top_temperature, t1.bottom_temperature, t1.oil_price
                from ticket_tracker_oil as t1
                where date_delivered >= to_date('$startdate','YYYY-MM-DD') and date_delivered <= to_date('$enddate','YYYY-MM-DD')               
                order by date_delivered asc";  

    $stmt = pdo_query( $pdo, $query, $params ); 
    $result = pdo_fetch_all( $stmt );

// DATA - FILE # - LOCATION - WELL NAME - OIL COMPANY - TRUCKING COMPANY - TOTAL BBLS - BOL# - SOLID % - H2O % - INTER. % -  OIL % - µCi/g - $ PER BBL - TOTAL $  PER LOAD - BBLS SOLIDS - DISPOSAL COST - BBLS H2O - DISPOSAL COST - BBLS OIL - OIL SALES - TOTAL REVENUE PER LOAD
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0)->setTitle("Tickets");
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("B2","Oil Sales")
                
                ->setCellValue("A4","Date")
                ->setCellValue("B4","Ticket Number")
                ->setCellValue("C4","Total BBLs")
                ->setCellValue("D4","Observed Temp.")
                ->setCellValue("E4","BS&W")
                ->setCellValue("F4","API gravity")
                ->setCellValue("G4","Top Temp")
                ->setCellValue("H4","Top Measurement")
				->setCellValue("i4","Bottom Temp")
                ->setCellValue("J4","Bottom Measurement")
				->setCellValue("K4","Oil Price")
				->setCellValue("L4","Total $ - Deduct");

	$objPHPExcel->setActiveSheetIndex(0)->getStyle("E2")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("A4:L4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("C5:L999")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
	$objPHPExcel->getActiveSheet(0)->getStyle('A4:L4')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);	
	$objPHPExcel->getActiveSheet(0)->getStyle('A4:L4')->getBorders()->getInside()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
	//$objPHPExcel->getActiveSheet(0)->getStyle('O7')->getNumberFormat()->setFormatCode("\$#,##0.00");
	$objPHPExcel->getActiveSheet(0)->getStyle('K5:L1000')->getNumberFormat()->setFormatCode("\$#,##0.00");
	$objPHPExcel->getActiveSheet(0)->getStyle('E2')->getFont()->setBold(true)->setSize(14);
	$objPHPExcel->getActiveSheet(0)->getStyle('L4:L4')->getFont()->setBold(true)->setSize(12);
	
	$objPHPExcel->getActiveSheet()->getStyle('A4:L4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFD966');
	
	$objPHPExcel->getActiveSheet(0)->getStyle('A5:L1000')->getFont()->setBold(false)->setSize(11);
	
	
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(14);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(13);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(9);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(13);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(9);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(9);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(9);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(18);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(12);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(18);
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(18);
		
    $rowcount = 5;
    foreach($result as $item)
    {
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A$rowcount",$item["date_delivered"])
            ->setCellValue("B$rowcount",$item["ticket_number"])
            ->setCellValue("C$rowcount",$item["barrels_delivered"])
            ->setCellValue("D$rowcount",$item["observed_temperature"]." °F")
            ->setCellValue("E$rowcount",$item["bsw"])
            ->setCellValue("F$rowcount",$item["gravity"])
            ->setCellValue("G$rowcount",$item["top_temperature"]." °F")
            ->setCellValue("H$rowcount",$item["top_ft"]. "ft.  " .$item["top_in"]."". $item["top_decimal"]."in.")
            ->setCellValue("I$rowcount",$item["bottom_temperature"]." °F")            
            ->setCellValue("J$rowcount",$item["bottom_ft"]. "ft.  " . $item["bottom_in"]."". $item["bottom_decimal"]."in.")
            ->setCellValue("K$rowcount",$item["oil_price"])			
			->setCellValue("L$rowcount",$item["total_dollars"]);
            
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
