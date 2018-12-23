<?php
ini_set('memory_limit','600M');
include_once "../../config.inc.php";
include_once "../../includes/tcpdf/tcpdf_include.php";
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
    $id = $_REQUEST["id"];
    $html = "<div>Haven't found ticket</div>";  
    $result = array();

    if(empty($id) == false)
    {
        $query =  "select t1.id, t1.ticket_number, to_char(t1.date_delivered,'yyyy-MM-dd') as date_delivered, to_char(t1.date_created,'yyyy-MM-dd') as date_created, t1.barrels_delivered, t1.local, t1.barrel_rate, t1.producer_name, t1.tenorm_picocuries, t1.percent_solid, t1.percent_h2o, t1.percent_interphase, t1.percent_oil, t1.washout_barrels,  t1.notes,
twell.file_number as source_well_file_number,twell.current_well_name as source_well_name, toperator.name as source_well_operator_name, twell.qq as qq, twell.township as township, twell.rng as range, twell.section as section, t4.name as company_name, t2.type as fluid_type, t1.truck_type
                from ticket_tracker_ticket as t1
                left join ticket_tracker_well as twell on t1.source_well_id =twell.id
                left join ticket_tracker_operator toperator on twell.current_operator_id=toperator.id
                left join ticket_tracker_truckingcompany as t4 on t1.trucking_company_id = t4.id 
                left join ticket_tracker_fluidtype as t2 on t1.fluid_type_id = t2.id
                where t1.id = :id";
                 
        $stmt = pdo_query( $pdo, $query, array(":id"=>$id) ); 
        $result = pdo_fetch_all( $stmt );
    }
    else
    {
        $conditionSql = "";
        $params = array();

        if(!empty($_REQUEST["startdate"]))
        {
            $conditionSql .= " and (t1.date_delivered>=:startdate)";
            $params[":startdate"] = date("m/d/Y",strtotime($_REQUEST["startdate"]));
        }
        if(!empty($_REQUEST["enddate"]))
        {
            $conditionSql .= " and (t1.date_delivered<=:enddate)";
            $params[":enddate"] = date("m/d/Y",strtotime($_REQUEST["enddate"]));
        }
       /* if(!empty($_REQUEST["disposalwell"]))
        {
            $conditionSql .= " and (t1.disposal_well_id=:disposalwell)";            
            $params[":disposalwell"] = $_REQUEST["disposalwell"];
        }
        if(!empty($_REQUEST["sourcewell"]))
        {
            $conditionSql .= " and (t1.source_well_id=:sourcewell)";            
            $params[":sourcewell"] = $_REQUEST["sourcewell"];
        }
		if(!empty($_REQUEST["operator_id"]))
        {
            $conditionSql .= " and (twell.current_operator_id=:operator_id)";            
            $params[":operator_id"] = $_REQUEST["operator_id"];
        }
        if(!empty($_REQUEST["truckingcompany"]))
        {
            $conditionSql .= " and (t1.trucking_company_id=:truckingcompany)";            
            $params[":truckingcompany"] = $_REQUEST["truckingcompany"];
        }*/
        

        $query =  "select t1.id, t1.ticket_number, to_char(t1.date_delivered,'yyyy-MM-dd') as date_delivered, to_char(t1.date_created,'yyyy-MM-dd') as date_created, t1.barrels_delivered, t1.local, t1.barrel_rate, t1.producer_name, t1.tenorm_picocuries, t1.percent_solid, t1.percent_h2o, t1.percent_interphase, t1.percent_oil, t1.washout_barrels, t1.notes,
twell.file_number as source_well_file_number,twell.current_well_name as source_well_name, toperator.name as source_well_operator_name, twell.qq as qq, twell.township as township, twell.rng as range, twell.section as section, t4.name as company_name, t2.type as fluid_type, t1.truck_type
                from ticket_tracker_ticket as t1
                left join ticket_tracker_well as twell on t1.source_well_id =twell.id
                left join ticket_tracker_operator toperator on twell.current_operator_id=toperator.id
                left join ticket_tracker_truckingcompany as t4 on t1.trucking_company_id = t4.id 
                left join ticket_tracker_fluidtype as t2 on t1.fluid_type_id = t2.id
                where 1 = 1 $conditionSql order by id desc";
        $stmt = pdo_query( $pdo, $query, $params ); 
        $result = pdo_fetch_all( $stmt );
    }


    /*
     * Create PDF Object
     */ 
    //create new pdf document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, "UTF-8", false);

    //set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor("BOH");
    $pdf->SetTitle("Export Ticket in PDF");
    $pdf->SetSubject("Export Ticket in PDF");
    $pdf->SetKeywords("ticket,tcpdf");

    //set default header data
    //$pdf->setHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, "001", PDF_HEADER_STRING);

    //set header and footer fonts
    $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    //set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    //set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->setHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->setFooterMargin(PDF_MARGIN_FOOTER);

    //set auto page breaks
    //$pdf->SetAutoPageBreak( TRUE, PDF_MARGIN_BOTTOM);
    $pdf->SetAutoPageBreak( TRUE, 10); 

    //set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    $pdf->SetFont("helvetica",'',12);

    /*
     * Create PDF Object End
     */ 

    //add a page
    //$pdf->AddPage();

    //output the HTML content
    //$pdf->writeHTML( $html, true, false, true, false, '');


    if(count($result) > 0)
    {
        $html = "";
        $ticket_index = 1;
        $td_height = "5px";        

        foreach($result as $ticket)
        {
            //$pdf->lastPage();
            $pdf->AddPage();

            $ticket_html = 
"<div style=\"font-weight:200;\"><span style=\"font-weight:bold;color:green;\">Ticket $ticket_index was created at:</span> {$ticket["date_created"]}<br/>
<table>
    <tr style=\"font-weight:bold;\">
        <td>Ticket number:</td>
        <td>Source Well Name:</td>
    </tr>
    <tr>
        <td>{$ticket["ticket_number"]}</td>
        <td>{$ticket["source_well_name"]}</td>
    </tr>
    <tr><td colspan=\"2\" style='height:$td_height;'></td></tr>
    <tr style=\"font-weight:bold;\">
        <td>Producer (Oil Company):</td>
        <td>Trucking Company:</td>
    </tr>
    <tr>
        <td>{$ticket["producer_name"]}</td>
        <td>{$ticket["company_name"]}</td>
    </tr>
    <tr><td colspan=\"2\" style='height:$td_height;'></td></tr>
    <tr style=\"font-weight:bold;\">
        <td>Barrels Delivered:</td>
        <td>Fluid Type:</td>
    </tr>
    <tr>
        <td>{$ticket["barrels_delivered"]}</td>
        <td>{$ticket["fluid_type"]}</td>
    </tr>
    <tr><td colspan=\"2\" style='height:$td_height;'></td></tr>
    <tr style=\"font-weight:bold;\">
        <td>Barrel Rate:</td>    
        <td>Truck Type:</td>    
    </tr>
    <tr>
        <td>$".$ticket["barrel_rate"]."</td>   
        <td>{$ticket["truck_type"]}</td>
    </tr>
    
    <tr><td colspan=\"2\" style='height:$td_height;'></td></tr>
    <tr style=\"font-weight:bold;\">
        <td>Date delivered:</td>
        <td>uCi/g:</td>
    </tr>
    <tr>
        <td>{$ticket["date_delivered"]}</td>
        <td>{$ticket["tenorm_picocuries"]}</td>
    </tr>
    <tr><td colspan=\"2\" style='height:$td_height;'></td></tr>
    <tr style=\"font-weight:bold;\">
        <td>% Solid:</td>
        <td>% H2O:</td>
    </tr>
    <tr>
        <td>{$ticket["percent_solid"]}</td>
        <td>{$ticket["percent_h2o"]}</td>
    </tr>
    <tr><td colspan=\"2\" style='height:$td_height;'></td></tr>
    <tr style=\"font-weight:bold;\">
        <td>% Interphase:</td>
        <td>% Oil:</td>
    </tr>
    <tr>
        <td>{$ticket["percent_interphase"]}</td>
        <td>{$ticket["percent_oil"]}</td>
    </tr>   
    <tr><td colspan=\"2\" style='height:$td_height;'></td></tr>
    <tr style=\"font-weight:bold;\">
        <td>Source Well File Number:</td>
        <td>Washout Barrels:</td>
    </tr>
    <tr>
        <td>{$ticket["source_well_file_number"]}</td>
        <td>{$ticket["washout_barrels"]}</td>
    </tr>
    <tr><td colspan=\"2\" style='height:$td_height;'></td></tr>
    <tr style=\"font-weight:bold;\">
        <td>Notes:</td>
    </tr>
    <tr>
        <td colspan=\"2\">{$ticket["notes"]}</td>
    </tr>
      
    <tr><td colspan=\"2\" style='height:$td_height;'></td></tr>
</table>";  

            $ticket_html .= "<span style=\"font-weight:bold;\">Document/Image:</span>";
            $stmt_file = pdo_query($pdo, "select filepath from ticket_tracker_indexupload where ticket_id = {$ticket["id"]}",null);
            $result_file = pdo_fetch_all($stmt_file);  
            if(count($result_file) > 0)
            {
                $ticket_html .= "<br/>";
                foreach($result_file as $file)
                {                
                    if(stristr($file["filepath"],".pdf") == FALSE)
                    {                        
                        $img_size = getimagesize("../../data/".$file['filepath']);
                        if($img_size[1] < 300)
                            $ticket_html .= '<img src="../../data/'.$file['filepath'].'"/><br/>';
                        else
                            $ticket_html .= '<img src="../../data/'.$file['filepath'].'" style="height:300px;"/><br/>';

                    }
					else
					{
						$inFile = $rootScope["RootPath"]."/data/".$file["filepath"];
						$jpg=str_replace(".pdf",".jpg",$file["filepath"]);
						$outFile = $rootScope["RootPath"]."/data/".$jpg;
						if(!file_exists($outFile))
						{
							$image = new Imagick($inFile);						
							$image->writeImage($outFile);
						}
												

                        $img_size = getimagesize("../../data/".$jpg);
                        if($img_size[1] < 300)
                            $ticket_html .= '<img src="../../data/'.$jpg.'"/><br/>';
                        else
                            $ticket_html .= '<img src="../../data/'.$jpg.'" style="height:300px;"/><br/>';

					}
                }
            }
            else
            {
                $ticket_html .= " No document or image<br/>";
            }
            $ticket_html .= "<br/><br/><br/></div>";
            $ticket_index++;

            $html .= $ticket_html;

            $pdf->writeHTML( $ticket_html, true, false, true, false, "" );
        }
    }
    else
    {
        $response['code'] = 'success';
        $response['data'] = "";
        echo json_encode($response);
        exit;
    }       

    //close and output pdf document
    $filename = "Export-Ticket-in-PDF-".time().".pdf";
    $pdf->Output("../../data/exportpdf/$filename", "F"); 


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