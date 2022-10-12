<?php
ini_set('memory_limit','600M');
include_once "../../config.inc.php";
include_once "../../includes/tcpdf/tcpdf_include.php";
include_once('../../lib/phpqrcode/qrlib.php');
//include_once('../../lib/BarCode/Barcode39.php');
include_once('../../vendor/fobiaweb/barcode39/Barcode39.php');

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
        
        $query = "SELECT t1.*, to_char(t1.received_date,'MM/dd/yyyy') as received_date, to_char(t1.estimated_ship_date,'MM/dd/yyyy') as estimated_ship_date, to_char(t1.date_entered,'MM/dd/yyyy') as date_entered, to_char(t1.rma_performed_date,'MM/dd/yyyy') as rma_performed_date, t2.name AS model,  to_char(t1.original_ship_date_of_order,'MM/dd/yyyy') as original_ship_date_of_order
        FROM repair_form AS t1
        LEFT JOIN monitors AS t2 ON t1.monitor_id = t2.id
        WHERE t1.id = :ID";
        
        $stmt = pdo_query( $pdo, $query, $params );       		
        $result = pdo_fetch_all( $stmt );	     
        
       
 //////////////////////////////////////////////////// 2 DIFFERENT WAYS OF BARCODES (1 IS BARCODE AND OTHER IS QR CODE ///////////////////////////////////////////////////
$barcode = "TESTING7";
if(strcmp($result[0]['model'], 'Reference')) {
	//$barcode = $_GET['ID'] . " - " . $result[0]['model'];
	$barcode = "R" . $_GET['ID'];
} else {
	//$barcode = $_GET['ID'] . " - Ref";
	$barcode = "R" . $_GET['ID'];
}
//$barcode = urldecode( "TESTING" );

//$bc = new Barcode39( "R" . $barcode);
$bc = new Barcode39( $barcode);
$file_path = $rootScope["RootPath"]."data/export/";
$response["test1"] = $file_path;
//echo json_encode($response);
//exit;
$file_name =  $barcode . '.gif';
$entire_pathname = $file_path . $barcode . '.gif';

$bc->barcode_text_size = 0; 

// set object 
//$bc = new Barcode39("123-ABC"); 
// set text size 
$bc->barcode_text_size = 0; 
// set barcode bar thickness (thick bars) 
$bc->barcode_bar_thick = 4; 
// set barcode bar thickness (thin bars) 
$bc->barcode_bar_thin = 2; 
// save barcode GIF file 
//$bc->draw("barcode.gif");
$bc->draw( $entire_pathname);

        // COMMENTED OUT FOR REPAIR FORM AS DO NOT NEED TO KNOW IF PRINTED OR NOT
        //$stmt = pdo_query( $pdo,  "UPDATE import_orders SET printed = TRUE WHERE id = :ID", $params ); 
                
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

/* 10/12/2022
	If MODEL IS Exp Pro, EXP CORE or EXP CORE+
	Change ORANGE to LIGHT BLUE for Travler
*/
if(stristr($result[0]['model'], "EXP") || stristr($result[0]['model'], "EXP")) {
	$left_column_name =
"
	<table width='420px' style=\"font-family:arial;font-size:22px;border-spacing: 80px 0px;border-left-style: solid #00ff00; border-left-width:140px; border-left-color:#03A9F4\">
		<tr style=\"font-weight:bold;float:left;padding-right:20cm;\">
        		<td style=\"floa:right;padding-left:20cm;\"><b style=\"float:right;padding-left:20cm;white-space:nowrap;\">NAME</b></td>
		</tr>
";
} else {
$left_column_name =
"
	<table width='420px' style=\"font-family:arial;font-size:22px;border-spacing: 80px 0px;border-left-style: solid #00ff00; border-left-width:140px; border-left-color:#FFA500\">
		<tr style=\"font-weight:bold;float:left;padding-right:20cm;\">
        		<td style=\"floa:right;padding-left:20cm;\"><b style=\"float:right;padding-left:20cm;white-space:nowrap;\">NAME</b></td>
		</tr>
";
}

$left_column_name_response =  
"		<tr style=\"color:blue;\">
        		<td style=\"text-align:left;font-size:36px;\"><b>{$result[0]["customer_name"]}</b></td>
		</tr>
";

$left_column_orderID =
'
		<tr style=\"font-weight:bold;float:left;padding-right:20cm;\">
        		<td style=\"floa:right;padding-left:20cm;\"><b style=\"float:right;padding-left:20cm;\">RMA #</b></td>
		</tr>
';
$left_column_orderID_response =  
"		<tr style=\"color:blue;\">
        		<td style=\"text-align:left;font-size:24px;\"><b>{$result[0]["rma_number"]}</b></td>
		</tr>
";

$left_column_email = 
"		<tr style=\"font-weight:bold;\">
    			<td style=\"text-align:left;\">EMAIL</td>
		</tr>
";
$left_column_email_response =  
"		<tr style=\"color:blue;\">
        		<td style=\"text-align:left;\">{$result[0]["email"]}</td>
		</tr>
";
$left_column_phone = 
"		<tr style=\"font-weight:bold;\">
    			<td style=\"text-align:left;\">PHONE</td>
		</tr>
";
$left_column_phone_response =  
"		<tr style=\"color:blue;\">
        		<td style=\"text-align:left;\">{$result[0]["phone"]}</td>
		</tr>
";
/*$left_column_church = 
"		<tr style=\"font-weight:bold;\">
    			<td style=\"text-align:left;\">BAND/CHURCH:</td>
		</tr>
";
$left_column_church_response =  
"		<tr style=\"color:blue;\">
        		<td style=\"text-align:left;\">{$result[0]["band_church"]}</td>
		</tr>
";*/
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
    			<td style=\"text-align:left;\">DIAGNOSIS</td>
		</tr>
";
$string = $result[0]["diagnosis"] . "<span style=\"color:black;\"> [ ]</span>";
$string = str_replace("\n", "<span style=\"color:black;\">[ ]</span><br>", $string); // REPLACES /n WITH <br> TO CREATE LINE BREAKS FOR THE TRAVELER
/*
$left_column_note_response =  
"		<tr style=\"color:blue;\">
        		<td style=\"text-align:left;\">{$result[0]["diagnosis"]}</td>
		</tr>
";
*/
$left_column_note_response =  
"		<tr style=\"color:blue;\">
        		<td style=\"text-align:left;\">$string</td>
		</tr>
";

/*
if ($result[0]["personal_item"] == TRUE) {
	$left_column_impression_date = 
	"<tr style=\"font-weight:bold;\">
    	<td style=\"text-align:left;\">QUOTE   <span style=\"color:red;\"> PERSONAL ITEM</span></td>
	</tr>
	";
} else {
	$left_column_impression_date = 
	"<tr style=\"font-weight:bold;\">
    	<td style=\"text-align:left;\">QUOTE </td>
	</tr>
	";	
}
*/

if ($result[0]["personal_item"] == TRUE) {
	$left_column_personal_item = 
	"<tr style=\"font-weight:bold;\">
    	<td style=\"text-align:left;\"><span style=\"color:red;\"> PERSONAL ITEM</span></td>
	</tr>
	";
}
if ($result[0]["personal_item"] == TRUE) {
	$left_column_personal_item_response = 
	"<tr style=\"color:blue;\">
        		<td style=\"text-align:left;\"> {$result[0]["personal_item_text"]}</td>
		</tr>
	";
}


// WARRANTY REPAIR
if ($result[0]["warranty_repair"] == TRUE) {
	$left_column_warranty_repair = 
	"<tr style=\"font-weight:bold;\">
    	<td style=\"text-align:left;\"><span style=\"color:white;background-color: #222222\"> WARRANTY REPAIR</span></td>
	</tr>
	";
} else {
	$left_column_warranty_repair = 
	"<tr style=\"font-weight:bold;\">
    	<td style=\"text-align:left;\"></td>
	</tr>
	";	
}

// CUSTOMER CONTACTED
if ($result[0]["customer_contacted"] == TRUE || $result[0]["customer_billed"] == TRUE || $result[0]["consulted"] == TRUE) {
	if($result[0]["customer_contacted"] == TRUE) { 
		$customer_contacted = "<span style=\"color:black;font:12px;\"> CONTACTED</span> <br/>";
	} else {
		$customer_contacted = "";
	}
	if($result[0]["customer_billed"] == TRUE) {
		 $customer_billed = "<span style=\"color:black;font:12px;\"> BILLED</span> <br/>";
	} else {
		$customer_billed = "";
	}
	if($result[0]["consulted"] == TRUE) { 
		$customer_consulted = "<span style=\"color:black;font:12px;\"> CONSULT BEFORE SHIP</span> <br/>";
	} else {
		$customer_consulted = "";
	}
	$left_column_customer_contacted = 
	"<tr style=\"font-weight:bold;\">
    	<td style=\"text-align:left;\"><span style=\"color:black;\"> CUSTOMER</span>
    		<br/>
    		$customer_contacted
			$customer_billed
			$customer_consulted
    		
    	</td>
	</tr>
	</table>
	";
} else {
	$left_column_customer_contacted = 
	"<tr style=\"font-weight:bold;\">
    	<td style=\"text-align:left;\"> </td>
	</tr>
	</table>
	";	
}

// CUSTOMER BILLED
if ($result[0]["customer_billed"] == TRUE) {
	$left_column_customer_billed = 
	"<tr style=\"font-weight:bold;\">
    	<td style=\"text-align:left;\"><span style=\"color:red;\"> CUSTOMER BILLED</span></td>
	</tr>
	";
} else {
	$left_column_customer_billed = 
	"<tr style=\"font-weight:bold;\">
    	<td style=\"text-align:left;\"></td>
	</tr>
	";	
}

// CONSULT BEFORE SHIP
if ($result[0]["consulted"] == TRUE) {
	$left_column_consulted = 
	"<tr style=\"font-weight:bold;\">
    	<td style=\"text-align:left;\"><span style=\"color:red;\"> CONSULTED</span></td>
	</tr>
	</table>
	";
} else {
	$left_column_consulted = 
	"<tr style=\"font-weight:bold;\">
    	<td style=\"text-align:left;\"></td>
	</tr>
	</table>
	";	
}

/*
$left_column_impression_date = 
"		<tr style=\"font-weight:bold;\">
    			<td style=\"text-align:left;\">QUOTE  </td>
		</tr>
";*/

$left_column_report_fit_issue = 
	"<tr style=\"font-weight:bold;\">
    	<td style=\"text-align:left;\"></td>
	</tr>
	";	

if($result[0]["rep_fit_issue"] == TRUE) {
$left_column_report_fit_issue_response =  
"		<tr style=\"color:red;font-size:14px;font-weight:bold\">
        		<td style=\"text-align:left;\">FIT ADJUSTMENT PERFORMED: 	<br/> <span style=\"font-size:18;\">&#91; &#93; </span>  ________ FOR INITIALS</td></tr>"; 
} else {
	$left_column_report_fit_issue_response =  
"		<tr style=\"color:red;font-size:14px;\">
        		<td style=\"text-align:left;\"></td>
		</tr>
";
}

if($result[0]["quotation"]) {
$left_column_impression_date_response =  
"		<tr style=\"color:blue;\">
        		<td style=\"text-align:left;\">$ {$result[0]["quotation"]}</td>
		</tr>
	
";
//</table>
} else {
	$left_column_impression_date_response =  
"		<tr style=\"color:blue;\">
        		<td style=\"text-align:left;\">{$result[0]["quotation"]}</td>
		</tr>
";
//</table>
}


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
    			<td style=\"text-align:left;\">MODEL:</td>
";
$right_column_monitor_response =  
"		<tr style=\"font-size:14px;\">
			 <td colspan='2' style=\"text-align:left;font-size:24px;margin-top:-50px;display:inline-block;\"><b style=\"color:blue;\">" . $result[0]['model'] . "</b></td>" . 
			 $rush_process . "
		</tr>
";

/*$right_column_cable = 
"		<tr style=\"font-weight:bold;\">
    		<td style=\"text-align:left;\">CABLE:</td>
		</tr>
";
$right_column_cable_response =  
"		<tr style=\"font-size:14px;\">
			 <td colspan='2' style=\"text-align:left;font-size:24px;\"><b style=\"color:green;\">" . $result[0]['cable_color'] . "</b></td>
		</tr>
";*/

/*$right_column_artwork = 
"	<tr style=\"color:black;\">
    	<td  style=\"text-align:left;\">ARTWORK:</td>
    </tr>
";*/
/* <td colspan='4' style=\"font-weight:bold;\">ARTWORK
	

*/
if( strlen($result[0]["left_alclair_logo"]) >= 4 || strlen($result[0]["right_alclair_logo"]) >= 4  ) {
$right_column_artwork = 
"	<tr>
    		    		
    		<td colspan='4' style=\"font-weight:bold;\"><span style=\"background-color: #40D8E0;\">ARTWORK:</span>
    		<span style=\"color:red;\"><b>YES</b></span></td>
    </tr>
";
} else {
$right_column_artwork = 
"	<tr>
    		<td colspan='4' style=\"font-weight:bold;\">ARTWORK:
    		<span style=\"color:red;\"><b>NO</b></span></td>
	</tr>
";	
}
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
    		<td colspan='4' style=\"font-weight:bold;\">COLORS </td>
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
    		<td style=\"text-align:left;font-size:24px;\">" . $result[0]["shell_left_color"] . "</td>
    		<td style=\"text-align:left;font-size:24px;\">" . $result[0]["shell_right_color"] . "</td>
	</tr>
";

/*if(  strlen($result[0]["shell_left_tip"]) >= 1 || strlen($result[0]["shell_right_tip"]) >= 1  ) {
$right_column_tips = 
"	<tr>
    		<td style=\"font-size:20px;font-weight:bold;\"><span style=\"background-color: #40D8E0;\">TIPS </span> <span style=\"color:red;\"><b>{$result[0]["shell_left_tip"]}</b></span></td>
    		<td style=\"font-size:20px;font-weight:bold;\"><span style=\"background-color: #40D8E0;\">TIPS </span> <span style=\"color:red;\"><b>{$result[0]["shell_right_tip"]}</b></span></td>
	</tr>
";
} else {
$right_column_tips = 
"	<tr>
    		<td style=\"font-size:20px;font-weight:bold;\">TIPS <span style=\"color:red;\"><b>{$result[0]["shell_left_tip"]}</b></span></td>
    		<td style=\"font-size:20px;font-weight:bold;\">TIPS <span style=\"color:red;\"><b>{$result[0]["shell_right_tip"]}</b></span></td>
	</tr>
";
}*/

if(  strlen($result[0]["shell_left_tip"]) >= 1  ) {
$right_column_tips = 
"	<tr>
    		<td style=\"font-size:20px;font-weight:bold;\"><span style=\"background-color: #40D8E0;\">TIPS </span> <span style=\"color:red;\"><b>{$result[0]["shell_left_tip"]}</b></span></td>
";
} else {
$right_column_tips = 
"	<tr>
    		<td style=\"font-size:20px;font-weight:bold;\">TIPS <span style=\"color:red;\"><b>{$result[0]["shell_left_tip"]}</b></span></td>
";
}

if( strlen($result[0]["shell_right_tip"]) >= 1  ) {
$right_column_tips = $right_column_tips . 
"	
    		<td style=\"font-size:20px;font-weight:bold;\"><span style=\"background-color: #40D8E0;\">TIPS </span> <span style=\"color:red;\"><b>{$result[0]["shell_right_tip"]}</b></span></td>
	</tr>
";
} else {
$right_column_tips = $right_column_tips . 
"	
    		<td style=\"font-size:20px;font-weight:bold;\">TIPS <span style=\"color:red;\"><b>{$result[0]["shell_right_tip"]}</b></span></td>
	</tr>
";
}



$right_column_colors_faceplates_response = 
"	<tr>
    		<td style=\"text-align:left;\">LEFT FACE</td>
    		<td style=\"text-align:left;\">RIGHT FACE</td>
	</tr>
";
 $right_column_colors_faceplates_response2 = 
"	<tr  style=\"color:blue;\">
    		<td style=\"text-align:left;font-size:24px;\">{$result[0]["shell_left_face"]}</td>
    		<td style=\"text-align:left;font-size:24px;\">{$result[0]["shell_right_face"]}</td>
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

/*
$right_column_received_date = 
"		<tr style=\"font-weight:bold;\">
    			<td style=\"text-align:left;\">RECEIVED DATE</td>
		</tr>
";
*/

$right_column_received_date = 
"		<tr style=\"font-weight:bold;\">
    			<td style=\"text-align:left;\">RECEIVED DATE</td>
    			<td style=\"text-align:left;\">ESTIMATED SHIP DATE</td>
		</tr>
";
/*
$right_column_received_date_response =  
"		<tr style=\"color:blue;\">
        		<td style=\"text-align:left;\">{$result[0]["received_date"]}</td>
		</tr>
	
";
*/

$right_column_received_date_response =  
"		<tr style=\"color:blue;\">
        		<td style=\"text-align:left;\">{$result[0]["received_date"]}</td>
        		<td style=\"text-align:left;\">{$result[0]["estimated_ship_date"]}</td>
		</tr>
	
";

$testing = 
"		<tr style=\"font-weight:bold;\">
    			<td style=\"text-align:left;color:red;\">ORIG ORDER SHIPPED ON</td>
		</tr>
		
";
$testing2 =  
"		<tr style=\"color:blue;\">
        		<td style=\"text-align:left;\">{$result[0]["original_ship_date_of_order"]}</td>
        		
		</tr>
		</table>
	
";

$right_column_estimated_ship_date = 
"		<tr style=\"font-weight:bold;\">
    			<td style=\"text-align:left;\">EST. SHIP DATE</td>
		</tr>
";
$right_column_estimated_ship_date_response =  
"		<tr style=\"color:blue;\">
        		<td style=\"text-align:left;\">{$result[0]["estimated_ship_date"]}</td>
		</tr>
	</table>
";

// RE-CREATE STEP_1 A FEW LINES BELOW BECAUSE I ACCIDENTALLY HARD SET STEP_1 TO THE DEV FOLDER
	// WHEN ALCLAIR IS USING THIS CODE IT NEEDS TO BE ALCLAIR'S FOLDER
	// THE FOR LOOP BACKTRACKS THROUGH THE FILEPATH BY 2 BACKSLASHES THEN RECREATES THE FILEPATH TO BE CORRECT
	// THE FILEPATH SHOULD EITHER BE /var/www/html/dev/data/export/ OR /var/www/html/alclair/data/export/
	$step_1 = '<img src="/var/www/html/dev/data/export/';
	$this_path =  getcwd();
	$num_of_slashes = 0;
	for ($x = strlen($this_path)-1; $x >= 1; $x=$x-1) {
		if ($this_path[$x] == '/') {
			$num_of_slashes = $num_of_slashes + 1;
			if ($num_of_slashes == 2) {
				//echo json_encode($response);
				break;
			}
		}
	}	
	$filepath_for_pdf =  substr($this_path, 0, $x) . "/data/export/";
	$step_1 = '<img HEIGHT="500%" src="' . $filepath_for_pdf;
	
    //echo json_encode($response);
    //exit;
	//$step_3 = ' </td></tr>';
	//$step_3 = ' width="300" height="200" ALIGN=”right”></td></tr>';
	$step_2 = $file_name . '"';
	$step_3 = ' align="left"”></td></tr>';
	$the_barcode = $step_1 . $step_2 . $step_3;


//	<td  colspan='2'  style=\"text-align:left;font-size:12px;\">LOGO&nbsp;&nbsp;C-ICON&nbsp;&nbsp;V-STAMP&nbsp;&nbsp;SCRIPT&nbsp;&nbsp;CUSTOM</td>           
            $ticket_html3 = "<table> <tr><td>" . 
            $left_column_name  . $left_column_name_response  . 
             "<tr><td colspan=\"2\" style='height:$td_height;'></td></tr>" .
			 $left_column_orderID  . $left_column_orderID_response  . 
             "<tr><td colspan=\"2\" style='height:$td_height;'></td></tr>" .

            /*
            $left_column_email  . $left_column_email_response  . 
             "<tr><td colspan=\"2\" style='height:$td_height;'></td></tr>" .
            $left_column_phone  . $left_column_phone_response  . 
             "<tr><td colspan=\"2\" style='height:$td_height;'></td></tr>" .
             */
             
            //$left_column_church  . $left_column_church_response  . 
             //"<tr><td colspan=\"2\" style='height:$td_height;'></td></tr>" .
            //$left_column_address  . $left_column_address_response  . 
             //"<tr><td colspan=\"2\" style='height:$td_height;'></td></tr>" .
            $left_column_note  . $left_column_note_response  . 
            "<tr><td colspan=\"2\" style='height:$td_height;'></td></tr>" .
            "<tr><td colspan=\"2\" style='height:$td_height;'></td></tr>" .
             "<tr><td colspan=\"2\" style='height:$td_height;'></td></tr>" .
			$left_column_report_fit_issue . $left_column_report_fit_issue_response .
			 "<tr><td colspan=\"2\" style='height:$td_height;'></td></tr>" .
            $left_column_personal_item  . $left_column_personal_item_response  . 
            //"</td>" .
            "<tr><td colspan=\"2\" style='height:4px;'></td></tr>" .
            $left_column_warranty_repair .
            //"</td>" .
             "<tr><td colspan=\"2\" style='height:4px;'></td></tr>" .
            $left_column_customer_contacted .
            "</td>" .
            //$middle_column_order_id . $middle_column_order_id_response . "</td>
            "<td>" . 
            "<table style=\"font-size:20px;\">" . //$right_column_checkboxes1_response .  
             $right_column_monitor . 
             "<td style=\"display:inline-block;\" rowspan=\"7\" >" . 
             	//'<img src="/var/www/html/dev/data/export/005_file_457d2f313cb4e48ae5c08e6333be9b45.png" width="300" height="300" ALIGN=”right”></td></tr>' .
             	//'<img src="/var/www/html/dev/data/export/". '$fileName_barcode' ."""  ></td></tr>' .
             	$the_barcode . 

             $right_column_monitor_response .
             "<tr><td colspan=\"2\" style='height:$td_height;'></td></tr>" .
              "<tr><td colspan=\"2\" style='height:$td_height;'></td></tr>" . 
              "<tr><td colspan=\"2\" style='height:$td_height;'></td></tr>" . 
              "<tr><td colspan=\"2\" style='height:$td_height;'></td></tr>" .
            //$right_column_cable . $right_column_cable_response .
            //"<tr><td colspan=\"2\" style='height:$td_height;'></td></tr>" .
            $right_column_artwork . $right_column_artwork_response . $right_column_artwork_response2 . 
             
             "<tr><td colspan=\"2\" style='height:$td_height;'></td></tr>" .
             $right_column_colors .  $right_column_colors_shells_response  . $right_column_colors_shells_response2 . 
             "<tr><td colspan=\"2\" style='height:$td_height;'></td></tr>" .
             $right_column_tips .  $right_column_colors_faceplates_response  .  $right_column_colors_faceplates_response2 .
             "<tr><td colspan=\"2\" style='height:$td_height;'></td></tr>" .
             //$right_column_addons_and_highrise .
             //$right_column_items .
             //$right_column_highrise .
            "<tr><td colspan=\'2\' style='height:$td_height;'></td></tr>" .
            //"<tr><td colspan=\'2\' style='height:$td_height;'></td></tr>" .
            "	<tr>
    			<td style=\"font-size:2px\"></td>
				<td style=\"font-size:2px\"></td>
			</tr>" .

             $right_column_received_date .  $right_column_received_date_response . "</table>" .//$testing . $testing2 .
             //$right_column_estimated_ship_date .  $right_column_estimated_ship_date_response .
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