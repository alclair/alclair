<?php
include_once "../../config.inc.php";
include_once "../../includes/PHPExcel/Classes/PHPExcel.php";
if(empty($_SESSION["UserId"]))
{
    return;
}

$response = array();
$response["code"] = "";
$response["message"] = "";
$response['data'] = null;

$chars = array("","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
$StartRowIndex = 2;
$water_color = array("8db4e2","dce6f1","a6a6a6","ffffff");
$char_all_days = array();
$char_last_three = array();

try
{	  
    $params = null;
    $startdate = $_REQUEST['startdate'];
    $enddate = $_REQUEST['enddate'];    

    $query = "select to_char(t1.date_delivered,'yyyy-MM-dd') as created,t3.name as name,twater.type as water_type,sum(t1.barrels_delivered) as total_barrels_delivered
              from ticket_tracker_ticket as t1
              left join ticket_tracker_well as t2 on t1.source_well_id = t2.id
              left join ticket_tracker_operator as t3 on t2.current_operator_id = t3.id
              left join ticket_tracker_watertype as twater on t1.water_type_id = twater.id 
              where date_delivered >= to_date('$startdate','YYYY-MM-DD') and date_delivered <= to_date('$enddate','YYYY-MM-DD')
              group by created ,name, water_type 
              order by created asc";  

    $stmt = pdo_query( $pdo, $query, $params ); 
    $result = pdo_fetch_all( $stmt );  
    
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0)->setTitle("Oil Company");
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A$StartRowIndex","Oil & Gas Company")
                ->setCellValue("B$StartRowIndex","Water(Barrels/Day)");
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells("A$StartRowIndex:A".($StartRowIndex + 1));
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells("B$StartRowIndex:B".($StartRowIndex + 1));
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A$StartRowIndex")->getAlignment()->setWrapText(true);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("B$StartRowIndex")->getAlignment()->setWrapText(true);

    $rowcount = 3;
    $days_count = 0;
    for($date=date("Y-m-d",strtotime($startdate));$date<=date("Y-m-d",strtotime($enddate));$date=date("Y-m-d",strtotime("$date +1 day")))
    {
        $sss = intval(($rowcount - 1) / 26);
        $fff = $rowcount - ($sss * 26);

        $col = $chars[$sss].$chars[$fff % 27]; 
        array_push($char_all_days,$col);
        //echo("$date-$rowcount-$col"."<br/>");

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue($col."$StartRowIndex",date("D",strtotime("$date")))
                    ->setCellValue($col.($StartRowIndex + 1),date("m/d",strtotime("$date")));
        $rowcount++;
        $days_count++;
    }

    array_push($char_last_three, $chars[intval(($rowcount - 1) / 26)].$chars[($rowcount - (intval(($rowcount - 1) / 26) * 26)) % 27] );
    array_push($char_last_three, $chars[intval((($rowcount + 1) - 1) / 26)].$chars[($rowcount + 1 - (intval((($rowcount + 1) - 1) / 26) * 26)) % 27] );
    array_push($char_last_three, $chars[intval((($rowcount + 2) - 1) / 26)].$chars[($rowcount + 2 - (intval((($rowcount + 2) - 1) / 26) * 26)) % 27] );

    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue($char_last_three[0]."$StartRowIndex","Monthly Total")
                ->setCellValue($char_last_three[1]."$StartRowIndex","Monthly Average");
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($char_last_three[0]."$StartRowIndex".":".$char_last_three[0].($StartRowIndex + 1));
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($char_last_three[1]."$StartRowIndex".":".$char_last_three[1].($StartRowIndex + 1));
    $objPHPExcel->setActiveSheetIndex(0)->getStyle($char_last_three[0]."$StartRowIndex")->getAlignment()->setWrapText(true);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle($char_last_three[1]."$StartRowIndex")->getAlignment()->setWrapText(true);

    //Get all of company
    $array_company = array();
    $company_count = 0;
    foreach($result as $item)
    {
        if(in_array($item["name"], $array_company) == false)
        {
            $indexsss = $StartRowIndex + 2 + $company_count * 4;

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A".($indexsss),$item["name"]);         

            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue("B".($indexsss),"Flowback")
                        ->setCellValue("B".($indexsss + 1),"Production")
                        ->setCellValue("B".($indexsss + 2),"Dirty Water")
                        ->setCellValue("B".($indexsss + 3),"Total")
                        ->setCellValue($char_last_three[2].($indexsss),"Flowback")
                        ->setCellValue($char_last_three[2].($indexsss + 1),"Production")
                        ->setCellValue($char_last_three[2].($indexsss + 2),"Dirty Water")
                        ->setCellValue($char_last_three[2].($indexsss + 3),"Total");

            $objPHPExcel->setActiveSheetIndex(0)->getStyle("B".($indexsss).":".$char_last_three[2].($indexsss))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB($water_color[0]);
            $objPHPExcel->setActiveSheetIndex(0)->getStyle("B".($indexsss + 1).":".$char_last_three[2].($indexsss + 1))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB($water_color[1]);
            $objPHPExcel->setActiveSheetIndex(0)->getStyle("B".($indexsss + 2).":".$char_last_three[2].($indexsss + 2))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB($water_color[2]);

            $objPHPExcel->setActiveSheetIndex(0)->getStyle("B".($indexsss + 3).":".$char_last_three[2].($indexsss + 3))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

            $objPHPExcel->setActiveSheetIndex(0)->mergeCells("A".($indexsss).":"."A".($indexsss + 3));
            $objPHPExcel->setActiveSheetIndex(0)->getStyle("A".($indexsss).":"."A".($indexsss + 3))->getAlignment()->setWrapText(true);

            $objPHPExcel->setActiveSheetIndex(0)->getStyle("A".($indexsss).":"."A".($indexsss + 3))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->setActiveSheetIndex(0)->getStyle("A".($indexsss).":"."A".($indexsss + 3))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

            array_push($array_company,$item["name"]);
            $company_count++;
        }
    }

    //Set Cell Value
    $time_startdate = strtotime($startdate);
    foreach($result as $item)
    {
        $day = strtotime($item["created"]) - $time_startdate; 
        $day = intval($day / 86400) + 3; 
        
        $sss = intval(($day - 1) / 26);
        $fff = $day - ($sss * 26); 

        $col = $chars[$sss].$chars[$fff % 27];

        $company_index = array_search($item["name"], $array_company);
        $cellvalue = $item["total_barrels_delivered"];
        
        $indexsss = $StartRowIndex + 2 + $company_index * 4;
        if($item['water_type'] == "FLOWBACK")
        {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("$col".($indexsss),$cellvalue);
        }
        else if($item['water_type'] == "PRODUCTION")
        {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("$col".($indexsss + 1),$cellvalue);
        }
        else if($item['water_type'] == "DIRTY WATER")
        {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("$col".($indexsss + 2),$cellvalue);
        }
    }

    //Set Total
    $rowcount = 3;
    for($day = 0; $day < $days_count; $day++)
    {
        $sss = intval(($rowcount - 1) / 26);
        $fff = $rowcount - ($sss * 26);

        $col = $chars[$sss].$chars[$fff % 27]; 

        for($index = 0; $index < count($array_company); $index++)
        {
            $indexsss = $StartRowIndex + 2 + $index * 4;

            $cellvalue_1 = $objPHPExcel->setActiveSheetIndex(0)->getCell("$col".($indexsss))->getValue();
            $cellvalue_2 = $objPHPExcel->setActiveSheetIndex(0)->getCell("$col".($indexsss + 1))->getValue();
            $cellvalue_3 = $objPHPExcel->setActiveSheetIndex(0)->getCell("$col".($indexsss + 2))->getValue();             

            if(empty($cellvalue_1) == true)
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue("$col".($indexsss),"-");
            if(empty($cellvalue_2) == true)
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue("$col".($indexsss + 1),"-");
            if(empty($cellvalue_3) == true)
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue("$col".($indexsss + 2),"-"); 

            $cellvalue_1 = empty($cellvalue_1) == true ? 0 : intval($cellvalue_1);
            $cellvalue_2 = empty($cellvalue_2) == true ? 0 : intval($cellvalue_2);
            $cellvalue_3 = empty($cellvalue_3) == true ? 0 : intval($cellvalue_3); 
            //echo("$cellvalue_1-$cellvalue_2-$cellvalue_3"."<br/>");

            if($cellvalue_1 == 0 && $cellvalue_2 == 0 && $cellvalue_3 == 0)
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue("$col".($indexsss + 3),"-");
            else
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue("$col".($indexsss + 3),$cellvalue_1 + $cellvalue_2 + $cellvalue_3);

            for($startindex = 0;$startindex < 4;$startindex++)
            {
                $cell = "$col".($startindex + $indexsss); 
                $objPHPExcel->setActiveSheetIndex(0)->getStyle($cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle($cell)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB($water_color[$startindex]);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle($cell)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN); 
            }

        }

        $rowcount++;
    }

    //Set Total and Average
    for($startindex = $StartRowIndex + 2; $startindex <= $StartRowIndex + 5 + (count($array_company) - 1) * 4; $startindex++)
    {
        $total = 0;
        $count = 0;
        foreach($char_all_days as $char)
        {
            $cell_value = $objPHPExcel->setActiveSheetIndex(0)->getCell("$char"."$startindex")->getValue();
            if($cell_value != '-')
            {
                $total += $cell_value;
                $count++;
            }
        }
        
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($char_last_three[0]."$startindex",$count == 0 ? "-" : $total);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($char_last_three[1]."$startindex",$count == 0 ? "-" : intval($total/$count)); 
        
        $objPHPExcel->setActiveSheetIndex(0)->getStyle($char_last_three[0]."$startindex")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle($char_last_three[1]."$startindex")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $objPHPExcel->setActiveSheetIndex(0)->getStyle($char_last_three[0]."$startindex")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
    }

    //Set Header
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A1","Export-OilCompany-From(".str_replace("/","-",$startdate).")-To(".str_replace("/","-",$enddate).")");
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells("A1:J1");
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:J1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:J1")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:J1")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:J1")->getFont()->setSize(16);

    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(16);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(16);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($char_last_three[2])->setWidth(16);

    $objPHPExcel->setActiveSheetIndex(0)->getRowDimension(1)->setRowHeight(25);    

    //Fixed row and column
    $objPHPExcel->setActiveSheetIndex(0)->freezePane("C4");


    //$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
    $filename = "Export-OilCompany-From(".str_replace("/","-",$startdate).")-To(".str_replace("/","-",$enddate).")-".time().".xlsx";
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