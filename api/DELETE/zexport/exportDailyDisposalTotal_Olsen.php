<?php
include_once "../../config.inc.php";
include_once "../../includes/PHPExcel/Classes/PHPExcel.php";
if(empty($_SESSION["UserId"])&&$_REQUEST["token"]!=$rootScope["SWDApiToken"])
{
    //return;
}

$response = array();
$response["code"] = "";
$response["message"] = "";
$response['data'] = null;

$column_color = array("8db4e2","d8e4bc","fcd5b4"); 

try
{	  
    $params = null;
    $startdate = $_REQUEST['startdate'];
    $enddate = $_REQUEST['enddate'];    

    $query =   "select t1.id, t1.ticket_number, to_char(t1.date_delivered,'yyyy-MM-dd') as date_delivered, t1.barrels_delivered, twatertype.type as water_type_name,t1.barrel_rate, t1.notes,
                twell.file_number as source_well_file_number,twell.current_well_name as source_well_name, toperator.name as source_well_operator_name, 
                t1.disposal_well_id, tcompany.name as company_name, t1.trucking_company_id, t1.source_well_id, 
                t1.delivery_method,t1.water_source_type
                from ticket_tracker_ticket as t1
                left join ticket_tracker_well as twell on t1.source_well_id =twell.id
                left join ticket_tracker_operator toperator on twell.current_operator_id=toperator.id
                left join ticket_tracker_watertype as twatertype on t1.water_type_id = twatertype.id
                left join ticket_tracker_disposalwell as tdisposal on t1.disposal_well_id = tdisposal.id 
                left join ticket_tracker_truckingcompany as tcompany on t1.trucking_company_id = tcompany.id
                where date_delivered >= to_date('$startdate','YYYY-MM-DD') and date_delivered <= to_date('$enddate','YYYY-MM-DD')  and t1.disposal_well_id = 13   and t1.trucking_company_id = 48          
                order by date_delivered asc";  

    $stmt = pdo_query( $pdo, $query, null ); 
    $result = pdo_fetch_all( $stmt );

    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0)->setTitle("Daily Disposal Total");
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A1","Date")
                ->setCellValue("B1","Disposal Location")
                ->setCellValue("C1","Origin Location")   
                ->setCellValue("D1","Origin Operator") 
                ->setCellValue("E1","Volume (BBLs)") 
                ->setCellValue("F1","Rate (per BBL)") 
                ->setCellValue("G1","Fluid Classification") 
                ->setCellValue("H1","Truck Number")
                ->setCellValue("I1","Driver Name") 
                ->setCellValue("J1","Ticket Number")
                ->setCellValue("K1","Water %")
                ->setCellValue("L1","Oil %")
                ->setCellValue("M1","Solids %")
                ->setCellValue("N1","Fluid Total")
                ->setCellValue("O1","Notes");                                                                      
   
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:O1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$rowcount = 2;
    foreach($result as $item)
    {
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A$rowcount",$item["date_delivered"])
            ->setCellValue("B$rowcount","KILLDEER WEST  SWD #1") // Hard code to show Olson disposal well
            ->setCellValue("C$rowcount",$item["source_well_name"])
            ->setCellValue("D$rowcount",$item["source_well_operator_name"]) 
            ->setCellValue("E$rowcount",$item["barrels_delivered"])
			->setCellValue("F$rowcount",number_format((float)$item["barrel_rate"],2,'.', ''))
			//->setCellValue("G$rowcount",$item["barrels_delivered"])
            ->setCellValue("G$rowcount","Production")
            ->setCellValue("H$rowcount","") 
            ->setCellValue("I$rowcount","")
            ->setCellValue("J$rowcount",$item["ticket_number"])
            ->setCellValue("K$rowcount","")
            ->setCellValue("L$rowcount","")
            ->setCellValue("M$rowcount","")
            //->setCellValue("N$rowcount",$item["barrel_rate"])
           ->setCellValue("N$rowcount",($item["barrels_delivered"] * $item["barrel_rate"]))
            ->setCellValue("O$rowcount",$item["notes"]);
        $rowcount++;
    }

    
        //$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
    $filename = "Export-Disposals-From-Olsen-During(".str_replace("/","-",$startdate).")-To(".str_replace("/","-",$enddate).")-".time().".xlsx";
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