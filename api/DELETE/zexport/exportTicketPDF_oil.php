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
        $query =  "select t1.*, to_char(t1.date_delivered,'MM/dd/yyyy') as date_delivered, 
                  to_char(t1.date_created,'MM/dd/yyyy') as date_created
                  from ticket_tracker_oil as t1
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
        /*if(!empty($_REQUEST["disposalwell"]))
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
        

        $query =  "select t1.*, to_char(t1.date_delivered,'MM/dd/yyyy') as date_delivered,
                  to_char(t1.date_created,'MM/dd/yyyy') as date_created
                  from ticket_tracker_oil as t1
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
    $pdf->SetAuthor("TRD");
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
        <td>Pickup Date:</td>
    </tr>
    <tr>
        <td>{$ticket["ticket_number"]}</td>
        <td>{$ticket["date_delivered"]}</td>
    </tr>
    <tr><td colspan=\"2\" style='height:$td_height;'></td></tr>
    <tr style=\"font-weight:bold;\">
        <td>BS&W:</td>
        <td>API gravity:</td>
    </tr>
    <tr>
        <td>{$ticket["bsw"]}</td>
        <td>{$ticket["gravity"]}</td>
    </tr>
    <tr><td colspan=\"2\" style='height:$td_height;'></td></tr>
    <tr style=\"font-weight:bold;\">
        <td>Observed Temperature:</td>
        <td>Barrels Delivered:</td>
    </tr>
    <tr>
        <td>{$ticket["observed_temperature"]} °F</td>
        <td>{$ticket["barrels_delivered"]}</td>
    </tr>
    <tr><td colspan=\"2\" style='height:$td_height;'></td></tr>
    <tr style=\"font-weight:bold;\">
        <td>Top Temperature:</td>
        <td>Bottom Temperature:</td>
    </tr>
    <tr>
        <td>{$ticket["top_temperature"]} °F</td>
        <td>{$ticket["bottom_temperature"]} °F</td>
    </tr>
	<tr><td colspan=\"2\" style='height:$td_height;'></td></tr>
    <tr style=\"font-weight:bold;\">
        <td>Top Measurement:</td>
        <td>Bottom Measurement:</td>
    </tr>
    <tr>
        <td>{$ticket["top_ft"]}ft.  {$ticket["top_in"]}{$ticket["top_decimal"]}in. </td>
        <td>{$ticket["bottom_ft"]}ft.  {$ticket["bottom_in"]}{$ticket["bottom_decimal"]}in.</td>
    </tr>
    
    <tr><td colspan=\"2\" style='height:$td_height;'></td></tr>
    <tr style=\"font-weight:bold;\">
        <td>Tank #:</td>       
        <td>Oil Pirice:</td>        
    </tr>
    <tr>
        <td>{$ticket["tank_number"]}</td>        
        <td>$".$ticket["oil_price"]."</td>        
    </tr>
    <tr><td colspan=\"2\" style='height:$td_height;'></td></tr>
    <tr style=\"font-weight:bold;\">
        <td>Notes:</td>       
        <td>Total $:</td>        
    </tr>
    <tr>
        <td>{$ticket["notes"]}</td>        
        <td>$".$ticket["total_dollars"]."</td>        
    </tr>
     
    <tr><td colspan=\"2\" style='height:$td_height;'></td></tr>
</table>";  

            $ticket_html .= "<span style=\"font-weight:bold;\">Document/Image:</span>";
            $stmt_file = pdo_query($pdo, "select filepath from ticket_tracker_indexupload_oil where ticket_id = {$ticket["id"]}",null);
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