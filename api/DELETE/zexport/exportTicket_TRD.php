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

    $query =   "select t1.id, t1.ticket_number, to_char(t1.date_delivered,'yyyy-MM-dd') as date_delivered, t1.barrels_delivered, t1.local, t1.barrel_rate, t1.producer_name, t1.tenorm_picocuries, t1.percent_solid, t1.percent_h2o, t1.percent_interphase, t1.percent_oil, t1.truck_type,
twell.file_number as source_well_file_number,twell.current_well_name as source_well_name, toperator.name as source_well_operator_name, twell.qq as qq, twell.township as township, twell.rng as range, twell.section as section, twell.field_name as field_name, t4.name as company_name, t2.type as fluid_type, t1.washout
                from ticket_tracker_ticket as t1
                left join ticket_tracker_well as twell on t1.source_well_id =twell.id
                left join ticket_tracker_operator toperator on twell.current_operator_id=toperator.id
                left join ticket_tracker_truckingcompany as t4 on t1.trucking_company_id = t4.id 
                left join ticket_tracker_fluidtype as t2 on t2.id = t1.fluid_type_id
                where date_delivered >= to_date('$startdate','YYYY-MM-DD') and date_delivered <= to_date('$enddate','YYYY-MM-DD')               
                order by date_delivered asc";  

    $stmt = pdo_query( $pdo, $query, $params ); 
    $result = pdo_fetch_all( $stmt );

// DATA - FILE # - LOCATION - WELL NAME - OIL COMPANY - TRUCKING COMPANY - TOTAL BBLS - BOL# - SOLID % - H2O % - INTER. % -  OIL % - µCi/g - $ PER BBL - TOTAL $  PER LOAD - BBLS SOLIDS - DISPOSAL COST - BBLS H2O - DISPOSAL COST - BBLS OIL - OIL SALES - TOTAL REVENUE PER LOAD
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0)->setTitle("Tickets");
    $objPHPExcel->setActiveSheetIndex(0)
                ->mergeCells('G4:I4')
                ->setCellValue("G4","Mud Tracking")
                ->mergeCells('F5:J5')
                ->setCellValue("F5","BS & W Tracking for Killdeer TRD")

				//->mergeCells('P5:Q5')
                ->setCellValue("U5","Landlord BBLC")
                ->mergeCells('V5:W5')
                ->setCellValue("V5","SOLIDS DISPOSAL")
                ->mergeCells('X5:Y5')
                ->setCellValue("X5","Salt Water Disposal")
                ->mergeCells('Z5:AA5')
                ->setCellValue("Z5","Interphase")
                ->mergeCells('AB5:AC5')
                ->setCellValue("AB5","Oil Sales")
                //->mergeCells('AD5:AD6')
                ->setCellValue("AD5","Net Revenue")
                
                ->setCellValue("A6","Date Delivered")
                ->setCellValue("B6","File #")
                ->setCellValue("C6","Location")
                ->setCellValue("D6","Well Name")
                ->setCellValue("E6","Field Name") //{"Oil Company")
                ->setCellValue("F6","Field Distance")
				->setCellValue("G6","Oil Company")
				->setCellValue("H6","Trucking Company")
				->setCellValue("I6","Type of Fluid")
				->setCellValue("J6","Type of Truck")
                ->setCellValue("K6","Total BBLs")
                ->setCellValue("L6","BOL #")
                ->setCellValue("M6","Solid %")
                ->setCellValue("N6","H2O %")
                ->setCellValue("O6","Inter. %")
                ->setCellValue("P6","Oil  %")
                ->setCellValue("Q6","µCi/g")
                ->setCellValue("R6","$ per BBL")
                ->setCellValue("S6","Washout Y/N")
                ->setCellValue("T6","Gross Revenue")
                ->setCellValue("U6","at $0.40/bbl")
                ->setCellValue("V6","BBLs Solids")
                ->setCellValue("W6","Disposal Cost")
                ->setCellValue("X6","BBLs H2O")
                ->setCellValue("Y6","Disposal Cost")
                ->setCellValue("Z6","BBLs Int.")
                ->setCellValue("AA6","Process Cost")
			    ->setCellValue("AB6","BBLs Oil")
			    ->setCellValue("AC6","Oil Sales")
                
               ->setCellValue("V3","$".$_REQUEST["landfill"]."/ton")
               ->setCellValue("X3","$".$_REQUEST["SWDdisposal"]."/BBL")
               ->setCellValue("AB3","$".$_REQUEST["OilPrice"]."/BBL")
               ->setCellValue("U3","$".$_REQUEST["LandlordCost"]."/BBL")
               ->setCellValue("Z3","$".$_REQUEST["InterphaseCost"]."/BBL");


	$objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:AD6")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("B")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("J")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("K")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("L")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("M")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("N")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("O")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("P")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("Q")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("R")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("S")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	$objPHPExcel->getActiveSheet(0)->getStyle('G4:I4')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
	$objPHPExcel->getActiveSheet(0)->getStyle('F5:J5')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
	
	//$objPHPExcel->getActiveSheet(0)->getStyle('O7')->getNumberFormat()->setFormatCode("\$#,##0.00");
	$objPHPExcel->getActiveSheet(0)->getStyle('R7:R1000')->getNumberFormat()->setFormatCode("\$#,##0.00");
	$objPHPExcel->getActiveSheet(0)->getStyle('T7:T1000')->getNumberFormat()->setFormatCode("\$#,#0.##");
	$objPHPExcel->getActiveSheet(0)->getStyle('U7:U1000')->getNumberFormat()->setFormatCode("\$#,##0.00");
	$objPHPExcel->getActiveSheet(0)->getStyle('W7:W1000')->getNumberFormat()->setFormatCode("\$#,##0.00");
	$objPHPExcel->getActiveSheet(0)->getStyle('Y7:Y1000')->getNumberFormat()->setFormatCode("\$#,##0.00");
	$objPHPExcel->getActiveSheet(0)->getStyle('AA7:AA1000')->getNumberFormat()->setFormatCode("\$#,##0.00");
	$objPHPExcel->getActiveSheet(0)->getStyle('AC7:AC1000')->getNumberFormat()->setFormatCode("\$#,##0.00");
	$objPHPExcel->getActiveSheet(0)->getStyle('AD7:AD1000')->getNumberFormat()->setFormatCode("\$#,##0.00");
	
	
	$objPHPExcel->getActiveSheet(0)->getStyle('G4')->getFont()->setBold(false)->setSize(20);
	$objPHPExcel->getActiveSheet(0)->getStyle('F5')->getFont()->setBold(false)->setSize(20);
	$objPHPExcel->getActiveSheet()->getStyle('G4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('92D050');
	$objPHPExcel->getActiveSheet()->getStyle('F5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
	$objPHPExcel->getActiveSheet()->getStyle('A6:T6')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF0000');
	$objPHPExcel->getActiveSheet(0)->getStyle('A6:AC6')->getFont()->setBold(true)->setSize(12);
	$objPHPExcel->getActiveSheet(0)->getStyle('A6:AC6')->getBorders()->getInside()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
	$objPHPExcel->getActiveSheet(0)->getStyle('A6:AC6')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
	
	$objPHPExcel->getActiveSheet()->getStyle('U5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('7030A0');
	$objPHPExcel->getActiveSheet()->getStyle('U6')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF33CC');
	$objPHPExcel->getActiveSheet()->getStyle('V5:V6')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('92D050');
	$objPHPExcel->getActiveSheet()->getStyle('W5:W6')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('A9D08E');
	$objPHPExcel->getActiveSheet()->getStyle('X5:Y5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('9BC2E6');
	$objPHPExcel->getActiveSheet()->getStyle('X6:Y6')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('BDD7EE');
	$objPHPExcel->getActiveSheet()->getStyle('Z5:AA5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('F4B084');
	$objPHPExcel->getActiveSheet()->getStyle('Z6:AA6')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('C65911');
	$objPHPExcel->getActiveSheet()->getStyle('AB5:AC5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('AEAAAA');
	$objPHPExcel->getActiveSheet()->getStyle('AB6:AC6')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF');
	$objPHPExcel->getActiveSheet()->getStyle('AD5:AD6')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF0000');	
	
	//$objPHPExcel->getActiveSheet()->getStyle('U5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('92D050');
	$objPHPExcel->getActiveSheet(0)->getStyle('U5:AC5')->getFont()->setBold(true)->setSize(16);
	$objPHPExcel->getActiveSheet(0)->getStyle('AD5')->getFont()->setBold(true)->setSize(12);
	$objPHPExcel->getActiveSheet(0)->getStyle('U5')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
	$objPHPExcel->getActiveSheet(0)->getStyle('V5:W5')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
	$objPHPExcel->getActiveSheet(0)->getStyle('X5:Y5')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
	$objPHPExcel->getActiveSheet(0)->getStyle('Z5:AA5')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
	$objPHPExcel->getActiveSheet(0)->getStyle('AB5:AC5')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
	$objPHPExcel->getActiveSheet(0)->getStyle('AD5:AD6')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
	
	//$objPHPExcel->getActiveSheet()->getStyle('P6:Q6')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('92D050');
	//$objPHPExcel->getActiveSheet(0)->getStyle('P6:Q6')->getFont()->setBold(true)->setSize(12);
	//$objPHPExcel->getActiveSheet(0)->getStyle('P6:Q6')->getBorders()->getInside()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
	//$objPHPExcel->getActiveSheet(0)->getStyle('P6:Q6')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
	
	//$objPHPExcel->getActiveSheet()->getStyle('R5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('9BC2E6');
	//$objPHPExcel->getActiveSheet(0)->getStyle('R5')->getFont()->setBold(true)->setSize(16);
	//$objPHPExcel->getActiveSheet(0)->getStyle('R5:S5')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
	//$objPHPExcel->getActiveSheet()->getStyle('R6:S6')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('9BC2E6');
	//$objPHPExcel->getActiveSheet(0)->getStyle('R6:S6')->getFont()->setBold(true)->setSize(12);
	//$objPHPExcel->getActiveSheet(0)->getStyle('R6:S6')->getBorders()->getInside()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
	//$objPHPExcel->getActiveSheet(0)->getStyle('R6:S6')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
	
	//$objPHPExcel->getActiveSheet()->getStyle('T5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('AEAAAA');
	//$objPHPExcel->getActiveSheet(0)->getStyle('T5')->getFont()->setBold(true)->setSize(16);
	//$objPHPExcel->getActiveSheet(0)->getStyle('T5:U5')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
	//$objPHPExcel->getActiveSheet()->getStyle('T6:U6')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('A9A9A9');
	//$objPHPExcel->getActiveSheet(0)->getStyle('T6:U6')->getFont()->setBold(true)->setSize(12);
	//$objPHPExcel->getActiveSheet(0)->getStyle('T6:U6')->getBorders()->getInside()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
	//$objPHPExcel->getActiveSheet(0)->getStyle('T6:U6')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
	
	//$objPHPExcel->getActiveSheet()->getStyle('V5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF0000');
	//$objPHPExcel->getActiveSheet(0)->getStyle('V5')->getFont()->setBold(true)->setSize(12);
	//$objPHPExcel->getActiveSheet(0)->getStyle('V5')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
	//$objPHPExcel->getActiveSheet()->getStyle('T6:U6')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('A9A9A9');
	//$objPHPExcel->getActiveSheet(0)->getStyle('T6:U6')->getFont()->setBold(true)->setSize(12);
	//$objPHPExcel->getActiveSheet(0)->getStyle('T6:U6')->getBorders()->getInside()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
	//$objPHPExcel->getActiveSheet(0)->getStyle('T6:U6')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
	//$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(false);
	//$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(false);
	//$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setAutoSize(false);
	//$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(false);
	
	$objPHPExcel->getActiveSheet()->getRowDimension('6')->setRowHeight(34.50);
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15.00);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(9.00);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(27.17);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(29.67);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(14.00);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(18.00);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15.00);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(17.75);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10.00);	
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(7);
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(8.50);	
	$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(4.95);	
	$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(4.95);	
	$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(4.95);
	$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(4.95);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(6.15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(6.50);
	$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(9.50);
	$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(9.75);		
	$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(20.0);				
	$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(10.25);	
	$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(13.25);	
	$objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(9.0);	
	$objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setWidth(13.25);	
	$objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setWidth(8.50);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setWidth(11.25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setWidth(8.50);	
	$objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setWidth(9.0);	
	$objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setWidth(15.00);		
	$objPHPExcel->getActiveSheet()->getStyle('H6')->getAlignment()->setWrapText(true); 	
	$objPHPExcel->getActiveSheet()->getStyle('J6:T6')->getAlignment()->setWrapText(true); 	
	
	//$objPHPExcel->getActiveSheet(0)->getStyle('A6:O6')->getFill()->cellColor('F28A8C');
	//cellColor('B5', 'F28A8C');

    $rowcount = 7;
    foreach($result as $item)
    {
	    
	if($item["washout"] == "true") { $washout = "Yes"; }
    else { $washout = "No";}
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A$rowcount",$item["date_delivered"])
            ->setCellValue("B$rowcount",$item["source_well_file_number"])
            ->setCellValue("C$rowcount",$item["qq"] . "-" . $item["township"] . "-" . $item["range"] . "-" . $item["section"])
            ->setCellValue("D$rowcount",$item["source_well_name"])
            ->setCellValue("E$rowcount",$item["field_name"])
            //->setCellValue("F$rowcount",$item["    "])
            ->setCellValue("G$rowcount",$item["producer_name"])
			->setCellValue("H$rowcount",$item["company_name"])
			->setCellValue("I$rowcount",$item["fluid_type"])
			->setCellValue("J$rowcount",$item["truck_type"])
			->setCellValue("K$rowcount",$item["barrels_delivered"])
             ->setCellValue("L$rowcount",$item["ticket_number"])
            ->setCellValue("M$rowcount",$item["percent_solid"])
            ->setCellValue("N$rowcount",$item["percent_h2o"])
            ->setCellValue("O$rowcount",$item["percent_interphase"])
            ->setCellValue("P$rowcount",$item["percent_oil"])
            ->setCellValue("Q$rowcount",$item["tenorm_picocuries"])
            ->setCellValue("R$rowcount",number_format((float)$item["barrel_rate"],2,'.', ''))
			->setCellValue("S$rowcount",$washout)
            //->setCellValue("S$rowcount",$item["washout"])
           ->setCellValue("T$rowcount",$item["barrels_delivered"] * $item["barrel_rate"])
            ->setCellValue("U$rowcount",$item["barrels_delivered"] * $_REQUEST["LandlordCost"])
            ->setCellValue("V$rowcount",$item["barrels_delivered"] * $item["percent_solid"]/100)
            ->setCellValue("W$rowcount",$item["barrels_delivered"] * $item["percent_solid"]/100 / 6.67 * $_REQUEST["landfill"])
            ->setCellValue("X$rowcount",$item["barrels_delivered"] * $item["percent_h2o"]/100)
            ->setCellValue("Y$rowcount",$item["barrels_delivered"] * $item["percent_h2o"]/100 * $_REQUEST["SWDdisposal"])
            ->setCellValue("Z$rowcount",$item["barrels_delivered"] * $item["percent_interphase"]/100)
            ->setCellValue("AA$rowcount",$item["barrels_delivered"] * $item["percent_interphase"]/100 * $_REQUEST["InterphaseCost"])
			->setCellValue("AB$rowcount",$item["barrels_delivered"] * $item["percent_oil"]/100)
            ->setCellValue("AC$rowcount",$item["barrels_delivered"] * $item["percent_oil"]/100 * $_REQUEST["OilPrice"])
            // $Total_Dollars_Per_Load - $Disposal_Cost_Solids - $Disposal_Cost_H2O + $Oil_Sales
            ->setCellValue("AD$rowcount",($item["barrels_delivered"] * $item["barrel_rate"]) - ($item["barrels_delivered"] * $_REQUEST["LandlordCost"]) - ($item["barrels_delivered"] * $item["percent_solid"]/100 / 6.67 * $_REQUEST["landfill"]) - ($item["barrels_delivered"] * $item["percent_h2o"]/100 * $_REQUEST["SWDdisposal"]) - ($item["barrels_delivered"] * $item["percent_interphase"]/100 * $_REQUEST["InterphaseCost"]) + ($item["barrels_delivered"] * $item["percent_oil"]/100 * $_REQUEST["OilPrice"]));


            
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
