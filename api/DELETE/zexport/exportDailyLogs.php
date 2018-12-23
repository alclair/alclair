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

$column_color = array("8db4e2","d8e4bc","fcd5b4"); 

try
{	  
    $params = null;
    $startdate = $_REQUEST['startdate'];
    $enddate = $_REQUEST['enddate'];    

    $query =   "select to_char(date_logged,'yyyy-MM-dd') as logged,sum(oil_sold_barrels) as total_oil_sold, 
                sum(level_oil_tank_1_ft) as total_oil_tank1,sum(level_oil_tank_2_ft) as total_oil_tank2,sum(level_oil_tank_3_ft) as total_oil_tank3,
                sum(level_gun_ft) as total_gun,
                sum(level_skim_tank_1_ft) as total_skim_tank1,sum(level_skim_tank_2_ft) as total_skim_tank2,
                sum(injection_rate) as total_injection_rate,sum(injection_pressure) as total_injection_pressure,
                sum(flowmeter_barrels) as total_flowmeter_barrels
                from well_logs_dailywelllog
                where date_logged >= to_date('$startdate','YYYY-MM-DD') and date_logged <= to_date('$enddate','YYYY-MM-DD')
                group by logged
                order by logged asc"; 

    $stmt = pdo_query( $pdo, $query, $params ); 
    $result = pdo_fetch_all( $stmt );

    $query_2 = "select to_char(t1.date_delivered,'yyyy-MM-dd') as created,t3.type as water_type,  sum(t1.barrels_delivered) as total_barrels_delivered
              from ticket_tracker_ticket as t1
              left join ticket_tracker_watertype as t3 on t1.water_type_id = t3.id              
              where date_delivered >= to_date('$startdate','YYYY-MM-DD') and date_delivered <= (to_date('$enddate','YYYY-MM-DD') )
              group by created ,water_type
              order by created asc";  

    $stmt_2 = pdo_query( $pdo, $query_2, null ); 
    $result_2 = pdo_fetch_all( $stmt_2 );

    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0)->setTitle("Daily Logs");
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("C1","Water(Barrels Per Day)")
                ->setCellValue("G1","Oil (Barrels)")
                ->setCellValue("N1","Process Measurements");    
    $objPHPExcel->setActiveSheetIndex(0)
                ->mergeCells("C1:F1")
                ->mergeCells("G1:M1")
                ->mergeCells("N1:P1");    
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("C1:F1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("G1:M1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("N1:P1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("B2","Date")
                ->setCellValue("C2","Total BPD Accepted")
                ->setCellValue("D2","Flowback")
                ->setCellValue("E2","Production")
                ->setCellValue("F2","Dirty Water")
                ->setCellValue("G2","Oil Sold Barrels")
                ->setCellValue("H2","Oil Tank 1 Level")
                ->setCellValue("I2","Oil Tank 2 Level")
                ->setCellValue("J2","Oil Tank 3 Level")
                ->setCellValue("K2","Gun Barrel Level")
                ->setCellValue("L2","Skim Tank 1 Level")
                ->setCellValue("M2","Skim Tank 2 Level")
                ->setCellValue("N2","Injection Rate(BPD)")
                ->setCellValue("O2","Injection Pressure")
                ->setCellValue("P2","Flowmeter (BBLS)");
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("B2:P2")->getAlignment()->setWrapText(true);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("B2:P2")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

    $s_date = date("Y-m-d",strtotime($startdate));    
    $e_date = date("Y-m-d",strtotime($enddate));     
    $rowcount = 3;
    for($date = $s_date; $date<=$e_date;$date=date("Y-m-d",strtotime("$date +1 day")))
    {
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue("A$rowcount",date("D",strtotime("$date")))
                    ->setCellValue("B$rowcount",date("n/j",strtotime("$date")));  

        $objPHPExcel->setActiveSheetIndex(0)->getStyle("C$rowcount:F$rowcount")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB($column_color[0]); 
        $objPHPExcel->setActiveSheetIndex(0)->getStyle("G$rowcount:M$rowcount")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB($column_color[1]);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle("N$rowcount:P$rowcount")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB($column_color[2]); 

        $chars = array("C","D","E","F","G","H","I","J","K","L","M","N","O","P");
        foreach($chars as $char)
        {
            $objPHPExcel->setActiveSheetIndex(0)->getStyle("$char"."$rowcount")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->setActiveSheetIndex(0)->getStyle("$char"."$rowcount")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        }   

        $rowcount++;
    }
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A".($rowcount),"Monthly") 
                ->setCellValue("A".($rowcount + 1),"Monthly")    
                ->setCellValue("B".($rowcount),"Totals") 
                ->setCellValue("B".($rowcount + 1),"Average");

    $time_startdate = strtotime($startdate);
    foreach($result as $item)
    {
        $day = strtotime($item["logged"]) - $time_startdate; 
        $day = intval($day / 86400);
        $day = $day + 3;

        //echo(strtotime($item["logged"])."-".$time_startdate."-$day"."<br/>");

        $objPHPExcel->setActiveSheetIndex(0)            
            ->setCellValue("G$day",$item["total_oil_sold"])
            ->setCellValue("H$day",$item["total_oil_tank1"])
            ->setCellValue("I$day",$item["total_oil_tank2"])
            ->setCellValue("J$day",$item["total_oil_tank3"])
            ->setCellValue("K$day",$item["total_gun"])
            ->setCellValue("L$day",$item["total_skim_tank1"])
            ->setCellValue("M$day",$item["total_skim_tank2"])
            ->setCellValue("N$day",$item["total_injection_rate"])
            ->setCellValue("O$day",$item["total_injection_pressure"])
            ->setCellValue("P$day",$item["total_flowmeter_barrels"]);                    
    }


    $total_flowback = 0;
    $total_production = 0;
    $total_dirty_water = 0;

    $total_day = array(); 
    foreach($result_2 as $item)
    {
        $day = strtotime($item["created"]) - $time_startdate; 
        $day = intval($day / 86400);
        $day = $day + 3;

        //echo($day."-".$item["water_type"]."<br/>");

        if($item['water_type'] == "FLOWBACK")
        {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D$day", $item["total_barrels_delivered"]);
            $total_flowback += $item["total_barrels_delivered"];
        }
        else if($item['water_type'] == "PRODUCTION")
        {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E$day", $item["total_barrels_delivered"]);
            $total_production += $item["total_barrels_delivered"];
        }
        else if($item['water_type'] == "DIRTY WATER")
        {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F$day", $item["total_barrels_delivered"]);
            $total_dirty_water += $item["total_barrels_delivered"]; 
        }

        if(empty($total_day["$day"]) == true)
            $total_day["$day"] = $item["total_barrels_delivered"];
        else
            $total_day["$day"] += $item["total_barrels_delivered"];              
    }

    foreach($total_day as $key => $value)
    {
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C".($key),$value);  
    }


    $total = $total_flowback + $total_production + $total_dirty_water;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C".($rowcount),$total); 
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D".($rowcount),$total_flowback); 
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E".($rowcount),$total_production); 
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F".($rowcount),$total_dirty_water);  

    $charss = array("C","D","E","F","G");
    foreach($charss as $char)
    {
        $total = 0;
        $count = 0;
        for($index = 3; $index < $rowcount; $index++)
        {
            $cellvalue = $objPHPExcel->setActiveSheetIndex(0)->getCell("$char"."$index")->getValue();
            if(empty($cellvalue) == false)
            {
                $total += $cellvalue;
                $count++;
            }
        }

        if($char == "G")
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("$char".($rowcount),$total);		

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("$char".($rowcount + 1),$count == 0 ? "-" : intval($total/$count));

        for($startindex = 0; $startindex <= 2; $startindex++)
        {
            $objPHPExcel->setActiveSheetIndex(0)->getStyle("$char".($rowcount + $startindex))->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->setActiveSheetIndex(0)->getStyle("$char".($rowcount + $startindex))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        }
    }

    //Set fill color
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("C$rowcount:F$rowcount")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB($column_color[0]); 
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("G$rowcount")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB($column_color[1]); 

    $objPHPExcel->setActiveSheetIndex(0)->getStyle("C".($rowcount+1).":F".($rowcount+1)."")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB($column_color[0]); 
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("G".($rowcount+1)."")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB($column_color[1]); 

    $objPHPExcel->setActiveSheetIndex(0)->getStyle("C".($rowcount+2).":F".($rowcount+2)."")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB($column_color[0]); 
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("G".($rowcount+2)."")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB($column_color[1]); 

    $charsss = array(
                        array("C","D","E","F"),
                        array("G","H","I","J","K","L","M"),
                        array("N","O","P"),
                    );
    for($index = 0; $index < count($charsss); $index++)
    {
        foreach($charsss[$index] as $char)
        {
            $objPHPExcel->setActiveSheetIndex(0)->getStyle("$char"."1:$char"."2")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB($column_color[$index]); 

            $objPHPExcel->setActiveSheetIndex(0)->getStyle("$char"."1")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->setActiveSheetIndex(0)->getStyle("$char"."1")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            $objPHPExcel->setActiveSheetIndex(0)->getStyle("$char"."2")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->setActiveSheetIndex(0)->getStyle("$char"."2")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        }
    }

    //Set '-' if empty
    $charss = array("C","D","E","F","G","H","I","J","K","L","M","N","O","P");
    foreach($charss as $char)
    {    
        for($index = 3; $index < $rowcount; $index++)
        {
            $cellvalue = $objPHPExcel->setActiveSheetIndex(0)->getCell("$char"."$index")->getValue();
            if(empty($cellvalue) == true)
            {            
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue("$char"."$index","-");
            }
            $objPHPExcel->setActiveSheetIndex(0)->getStyle("$char"."$index")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        }   
    }

    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("C".($rowcount+2),"Total BPD")
                ->setCellValue("D".($rowcount+2),"Flowback")
                ->setCellValue("E".($rowcount+2),"Production")
                ->setCellValue("F".($rowcount+2),"Dirty Water")
                ->setCellValue("G".($rowcount+2),"Oil Sold");

    $objPHPExcel->setActiveSheetIndex(0)->freezePane("C3");

    //$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
    $filename = "Export-DailyLogs-From(".str_replace("/","-",$startdate).")-To(".str_replace("/","-",$enddate).")-".time().".xlsx";
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