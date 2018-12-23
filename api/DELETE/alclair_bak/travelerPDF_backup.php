<?php
ini_set('memory_limit','600M');
include_once "../../config.inc.php";
include_once "../../includes/tcpdf/tcpdf_include.php";
//if(strpos($host,"assetvision.com")!==false)
//{
	$root="/var/www/html/swd";
//}
//else
//{
//	$root="/home2/caraburo/www/swd";
//}
include_once $root."/includes/phpmailer/class.phpmailer.php";
if(empty($_SESSION["UserId"])&&$_REQUEST["token"]!=$rootScope["SWDApiToken"])
{
    return;
}
$response = array();
$response["code"] = "";
$response["message"] = "";
$response['data'] = null;

// https://dev.swdmanager.com/api/alclair/travelerPDF.php
// LINE 1 OF 3 TO COMMENT FOR TESTING
// $_REQUEST["orderID"] = 9729;
try
{	             
    $id = $_REQUEST["id"];
    $html = "<div>Haven't found ticket</div>";
    $result = array();
        $conditionSql = "";
        $params = array();
        $params[":ID"] = $_REQUEST["ID"];
        
        $query = "SELECT *, to_char(date,'MM/dd/yyyy') as ordered_date, to_char(
        ,'MM/dd/yyyy') as impressions_date, to_char(received_date,'MM/dd/yyyy') as received_date FROM import_orders WHERE id = :ID";
        $stmt = pdo_query( $pdo, $query, $params );       		
        $result = pdo_fetch_all( $stmt );	     
        
        $stmt = pdo_query( $pdo,  "UPDATE import_orders SET printed = TRUE WHERE id = :ID", $params ); 
                
		//$count = $result->rowCount();
    /*
     * Create PDF Object
     */ 
    //create new pdf document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION_L, PDF_UNIT, PDF_PAGE_FORMAT, true, "UTF-8", false);
    
    //set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor("Alclair");
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
    $pdf->SetMargins(PDF_MARGIN_LEFT_A, PDF_MARGIN_TOP_A, PDF_MARGIN_RIGHT_A);
    //$pdf->setHeaderMargin(PDF_MARGIN_HEADER);
    //$pdf->setFooterMargin(PDF_MARGIN_FOOTER);
    
    //set auto page breaks
    $pdf->SetAutoPageBreak( TRUE, PDF_MARGIN_BOTTOM_A);
    $pdf->SetAutoPageBreak( TRUE, 10); 
    // REMOVES THE HORIZONTAL LINE AT THE TOP FROM PRINTING
    $pdf->setPrintHeader(false);
    
    //set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    $pdf->SetFont("helvetica",'',12);
 

if ($result[0]['artwork'] == 'Nope') {
	
} else {
	$artwork_white = '<input type="checkbox" name="WHITE" value="WHITE" checked="checked">WHITE';
	$artwork_white = '<input style=\"font-size:11px;\" type="checkbox" name="WHITE" value="WHITE" checked="checked">WHITE';
    $artwork_black = '<input style=\"font-size:11px;\" type="checkbox" name="BLACK" value="BLACK" >BLACK';
}
//&#8209;
$artwork_logo = "<td colspan='2'  style=\"text-align:left;\"><strike>LOGO</strike>&nbsp;<strike>C ICON</strike>&nbsp;&nbsp;<strike>V STAMP</strike></td>";
$artwork_logo = "<td colspan='2' style=\"text-align:left;\"><strike>LOGO</strike>&nbsp;&nbsp;&nbsp;<strike>C ICON</strike>&nbsp;&nbsp;&nbsp;<strike>V STAMP</strike></td>";
$artwork_logo2 = "<td colspan='4'  style=\"text-align:left;\"><strike>SCRIPT</strike>&nbsp;&nbsp;<b style=\"color:green;\">CUSTOM</b></td>";

if(strlen($result[0]['rush_process']) < 2 ) { // THIS IS HEAR BECAUSE WHEN TESTING A BUNCH OF ROWS WERE BLANK AND THAT WAS RETURNING "TRUE"
	$rush_process = "<td colspan='2' style=\"text-align:left;font-size:40px;\"><b style=\"color:red;\"></b></td>"; // PRINTS NOTHING, SIMPLE FIX
} elseif ($result[0]['rush_process'] == TRUE || $result[0]['rush_process'] == 'Yes' ) {
	$rush_process = "<td colspan='2' style=\"text-align:left;font-size:40px;\"><b style=\"color:red;\">**RUSH**</b></td>";
}

//style=\"float:left;\" position: "absolute" 
if ($result[0]["additional_items"] == TRUE) {
	$column_items = 'Yes, there are additional items.';
} else {
	$column_items = 'No additional items.';
}
if ($result[0]["consult_highrise"] == TRUE) {
	$column_highrise = 'Consult Highrise!';
} else {
	$column_highrise = '';
}

    if(count($result) > 0)
    {
        $html = "";
        $ticket_index = 1;
        $td_height = "5px";   	  
        //foreach($result as $ticket)
        {
            //$pdf->lastPage();
            $pdf->AddPage(); 
 //w3schools.com/cssref/pr_font_font-style.asp
//"<div style=\"font-weight:200;\"><span style=\"font-weight:bold;color:green;\">Ticket $ticket_index was created at:</span> {$ticket["date_created"]}<br/>
//>padding-right:50px;display: inline-block;'>
/*
$left_column_name =
'
	<table width="420px" style="font-family:arial;font-size:22px;border-spacing: 80px 0px;border-left-style: solid #00ff00; border-left-width:140px; border-left-color:#3F704D">
		<tr style=\"font-weight:bold;float:left;padding-right:20cm;\">
        		<td style=\"floa:right;padding-left:20cm;\"><b style=\"float:right;padding-left:20cm;\">NAME & ORDER ID:</b></td>
		</tr>
';
$left_column_name_response =  
"		<tr style=\"color:blue;\">
        		<td style=\"text-align:left;\">{$result[0]["designed_for"]}<br/><span style=\"color:green;\">&nbsp;&nbsp;&nbsp;&nbsp;<b>{$result[0]["order_id"]}</b></span></td>
		</tr>
";*/
$left_column_name =
'
	<table width="420px" style="font-family:arial;font-size:22px;border-spacing: 80px 0px;border-left-style: solid #00ff00; border-left-width:140px; border-left-color:#3F704D">
		<tr style=\"font-weight:bold;float:left;padding-right:20cm;\">
        		<td style=\"floa:right;padding-left:20cm;\"><b style=\"float:right;padding-left:20cm;\">NAME:</b></td>
		</tr>
';
$left_column_name_response =  
"		<tr style=\"color:blue;\">
        		<td style=\"text-align:left;font-size:24px;\"><b>{$result[0]["designed_for"]}</b></td>
		</tr>
";

$left_column_orderID =
'
		<tr style=\"font-weight:bold;float:left;padding-right:20cm;\">
        		<td style=\"floa:right;padding-left:20cm;\"><b style=\"float:right;padding-left:20cm;\">ORDER ID:</b></td>
		</tr>
';
$left_column_orderID_response =  
"		<tr style=\"color:blue;\">
        		<td style=\"text-align:left;font-size:24px;\"><b>{$result[0]["order_id"]}</b></td>
		</tr>
";

 
$left_column_email = 
"		<tr style=\"font-weight:bold;\">
    			<td style=\"text-align:left;\">EMAIL:</td>
		</tr>
";
$left_column_email_response =  
"		<tr style=\"color:blue;\">
        		<td style=\"text-align:left;\">{$result[0]["email"]}</td>
		</tr>
";
$left_column_phone = 
"		<tr style=\"font-weight:bold;\">
    			<td style=\"text-align:left;\">PHONE:</td>
		</tr>
";
$left_column_phone_response =  
"		<tr style=\"color:blue;\">
        		<td style=\"text-align:left;\">{$result[0]["phone"]}</td>
		</tr>
";
$left_column_church = 
"		<tr style=\"font-weight:bold;\">
    			<td style=\"text-align:left;\">BAND/CHURCH:</td>
		</tr>
";
$left_column_church_response =  
"		<tr style=\"color:blue;\">
        		<td style=\"text-align:left;\">{$result[0]["band_church"]}</td>
		</tr>
";
$left_column_address = 
"		<tr style=\"font-weight:bold;\">
    			<td style=\"text-align:left;\">ADDRESS:</td>
		</tr>
";
$left_column_address_response =  
"		<tr style=\"color:blue;\">
        		<td style=\"text-align:left;\">{$result[0]["address"]}</td>
		</tr>
";
$left_column_note = 
"		<tr style=\"font-weight:bold;\">
    			<td style=\"text-align:left;\">NOTE:</td>
		</tr>
";
$left_column_note_response =  
"		<tr style=\"color:blue;\">
        		<td style=\"text-align:left;\">{$result[0]["notes"]}</td>
		</tr>
";
$left_column_impression_date = 
"		<tr style=\"font-weight:bold;\">
    			<td style=\"text-align:left;\">ORDERED:</td>
		</tr>
";
$left_column_impression_date_response =  
"		<tr style=\"color:blue;\">
        		<td style=\"text-align:left;\">{$result[0]["ordered_date"]}</td>
		</tr>
	</table>
";

$middle_column_order_id = 
"
	<table style='font-family:arial;'>
		<tr style=\"font-weight:bold;\">
        		<td style=\"text-align:left;\">ORDER ID:</td>
		</tr>
";
$middle_column_order_id_response =  
"		<tr style=\"color:blue;\">
        		<td style=\"text-align:left;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$_REQUEST["orderID"]}</td>
		</tr>
	</table>
";

$right_column_monitor = 
"		<tr style=\"font-weight:bold;\">
    			<td style=\"text-align:left;\">MONITOR:</td>
    			<td style=\"text-align:left;\"></td>
		</tr>
";
$right_column_monitor_response =  
"		<tr style=\"font-size:14px;\">
			 <td colspan='2' style=\"text-align:left;font-size:24px;\"><b style=\"color:green;\">" . $result[0]['model'] . "</b></td>" . 
			 $rush_process . "
		</tr>
";

$right_column_cable = 
"		<tr style=\"font-weight:bold;\">
    		<td style=\"text-align:left;\">CABLE:</td>
		</tr>
";
$right_column_cable_response =  
"		<tr style=\"font-size:14px;\">
			 <td colspan='2' style=\"text-align:left;font-size:24px;\"><b style=\"color:green;\">" . $result[0]['cable_color'] . "</b></td>
		</tr>
";

/*$right_column_artwork = 
"	<tr style=\"color:black;\">
    	<td  style=\"text-align:left;\">ARTWORK:</td>
    </tr>
";*/
$right_column_artwork = 
"	<tr>
    		<td colspan='4' style=\"font-weight:bold;\">ARTWORK:
    		<span style=\"color:red;\"><b>{$result[0]["artwork"]}</b></span></td>
	</tr>
";
/*$right_column_artwork_response =  
"		<tr style=\"font-size:14px;\">
			 <td colspan='2' style=\"text-align:left;font-size:24px;\"><b style=\"color:green;\">" . $result[0]['artwork'] . "</b></td>
		</tr>
";*/
 $right_column_artwork_response = 
"	<tr>
    		<td style=\"text-align:left;\">LEFT LOGO</td>
    		<td style=\"text-align:left;\">RIGHT LOGO</td>
	</tr>
";
 $right_column_artwork_response2 = 
"	<tr  style=\"color:blue;\">
    		<td style=\"text-align:left;font-size:24px;\">" . $result[0]["left_alclair_logo"] . "</td>
    		<td style=\"text-align:left;font-size:24px;\">" . $result[0]["right_alclair_logo"] . "</td>
	</tr>
";


 $right_column_colors = 
"	<tr>
    		<td colspan='4' style=\"font-weight:bold;\">COLORS: </td>
	</tr>
";
 $right_column_colors_shells_response = 
"	<tr>
    		<td style=\"text-align:left;\">LEFT SHELL</td>
    		<td style=\"text-align:left;\">RIGHT SHELL</td>
	</tr>
";
 $right_column_colors_shells_response2 = 
"	<tr  style=\"color:blue;\">
    		<td style=\"text-align:left;font-size:24px;\">" . $result[0]["left_shell"] . "</td>
    		<td style=\"text-align:left;font-size:24px;\">" . $result[0]["right_shell"] . "</td>
	</tr>
";

$right_column_tips = 
"	<tr>
    		<td style=\"font-size:20px\">TIPS: <span style=\"color:red;\"><b>{$result[0]["clear_canal"]}</b></span></td>
    		<td style=\"font-size:20px\">TIPS: <span style=\"color:red;\"><b>{$result[0]["clear_canal"]}</b></span></td>
	</tr>
";
$right_column_colors_faceplates_response = 
"	<tr>
    		<td style=\"text-align:left;\">LEFT FACE</td>
    		<td style=\"text-align:left;\">RIGHT FACE</td>
	</tr>
";
 $right_column_colors_faceplates_response2 = 
"	<tr  style=\"color:blue;\">
    		<td style=\"text-align:left;font-size:24px;\">{$result[0]["left_faceplate"]}</td>
    		<td style=\"text-align:left;font-size:24px;\">{$result[0]["right_faceplate"]}</td>
	</tr>
";

$right_column_addons_and_highrise = 
"	<tr>
			<td colspan='4' style=\"font-weight:bold;\">ADDED NOTES:</td>
	</tr>
";
// 2 <td> WERE USED BECAUSE I COULDN'T FIGURE OUT HOW TO NOT MAKE THE TEXT WRAP
 $right_column_items = 
"		<tr style=\"color:green;font-size:20px;float:left\">
			<td>" .
					$column_items . "
			</td>
		</tr>
";
// 2 <td> WERE USED BECAUSE I COULDN'T FIGURE OUT HOW TO NOT MAKE THE TEXT WRAP
 $right_column_highrise = 
"		<tr style=\"color:green;font-size:20px;\">
			<td>" .
					$column_highrise . "
			</td>
		</tr>
";

$right_column_received_date = 
"		<tr style=\"font-weight:bold;\">
    			<td style=\"text-align:left;\">IMPRESSIONS RECEIVED :</td>
		</tr>
";
$right_column_received_date_response =  
"		<tr style=\"color:blue;\">
        		<td style=\"text-align:left;\">{$result[0]["received_date"]}</td>
		</tr>
	</table>
";


//	<td  colspan='2'  style=\"text-align:left;font-size:12px;\">LOGO&nbsp;&nbsp;C-ICON&nbsp;&nbsp;V-STAMP&nbsp;&nbsp;SCRIPT&nbsp;&nbsp;CUSTOM</td>           
            $ticket_html3 = "<table> <tr><td>" . 
            $left_column_name  . $left_column_name_response  . 
             "<tr><td colspan=\"2\" style='height:$td_height;'></td></tr>" .
			 $left_column_orderID  . $left_column_orderID_response  . 
             "<tr><td colspan=\"2\" style='height:$td_height;'></td></tr>" .

            $left_column_email  . $left_column_email_response  . 
             "<tr><td colspan=\"2\" style='height:$td_height;'></td></tr>" .
            $left_column_phone  . $left_column_phone_response  . 
             "<tr><td colspan=\"2\" style='height:$td_height;'></td></tr>" .
            $left_column_church  . $left_column_church_response  . 
             "<tr><td colspan=\"2\" style='height:$td_height;'></td></tr>" .
            //$left_column_address  . $left_column_address_response  . 
             //"<tr><td colspan=\"2\" style='height:$td_height;'></td></tr>" .
            $left_column_note  . $left_column_note_response  . 
            "<tr><td colspan=\"2\" style='height:$td_height;'></td></tr>" .
            "<tr><td colspan=\"2\" style='height:$td_height;'></td></tr>" .
             "<tr><td colspan=\"2\" style='height:$td_height;'></td></tr>" .
            $left_column_impression_date  . $left_column_impression_date_response  . 
            "</td>" .
             //<td>" . 
            //$middle_column_order_id . $middle_column_order_id_response . "</td>
            "<td>" . 
            "<table style=\"font-size:20px;\">" . //$right_column_checkboxes1_response .  
             $right_column_monitor . $right_column_monitor_response .
             "<tr><td colspan=\"2\" style='height:$td_height;'></td></tr>" .
            $right_column_cable . $right_column_cable_response .
            "<tr><td colspan=\"2\" style='height:$td_height;'></td></tr>" .
            $right_column_artwork . $right_column_artwork_response . $right_column_artwork_response2 . 
             
             "<tr><td colspan=\"2\" style='height:$td_height;'></td></tr>" .
             $right_column_colors .  $right_column_colors_shells_response  . $right_column_colors_shells_response2 . 
             "<tr><td colspan=\"2\" style='height:$td_height;'></td></tr>" .
             $right_column_tips .  $right_column_colors_faceplates_response  .  $right_column_colors_faceplates_response2 .
             "<tr><td colspan=\"2\" style='height:$td_height;'></td></tr>" .
             $right_column_addons_and_highrise .
             $right_column_items .
             $right_column_highrise .
            "<tr><td colspan=\'2\' style='height:$td_height;'></td></tr>" .
            //"<tr><td colspan=\'2\' style='height:$td_height;'></td></tr>" .
            "	<tr>
    			<td style=\"font-size:2px\"></td>
				<td style=\"font-size:2px\"></td>
			</tr>" .

             $right_column_received_date .  $right_column_received_date_response .
             "</td></tr></table>";

            
            $pdf->writeHTML( $ticket_html3, true, false, true, false, "" );
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
    
    $filename = "Alclair Traveler {$_REQUEST["startdate"]} to {$_REQUEST["enddate"]} as Batch No. $batch_num.pdf";
    $pdf->Output("../../data/exportpdf/$filename", "F"); 
    
    //$url=$rootScope["RootUrl"]."/api/export/exportTicket.php?startdate=$startDate&enddate=$endDate&token=".$rootScope["SWDApiToken"];
//echo $url;    
//$json=file_get_contents($pdf);
//$list=json_decode($json,true);
$file_=$rootScope["RootPath"]."data/exportpdf/".$filename;
 
 // HERE IS WHERE THE EMAIL SECTION OF THE CODE STARTS
 
    
    $response['code'] = 'success';
    $response['data'] = $filename;
    $response['test'] = $test;
    // LINE 3 OF 3 TO COMMENT FOR TESTING CODE
	echo json_encode($response);
}
catch(PDOException $ex)
{	
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}
?>