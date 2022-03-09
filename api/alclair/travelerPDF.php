<?php
ini_set('memory_limit','600M');
include_once "../../config.inc.php";
include_once "../../includes/tcpdf/tcpdf_include.php";
include_once('../../lib/phpqrcode/qrlib.php');
//include_once('../../lib/BarCode/Barcode39.php');
include_once('../../vendor/fobiaweb/barcode39/Barcode39.php');

//http://phpqrcode.sourceforge.net/examples/index.php?example=005
//http://www.shayanderson.com/php/php-barcode-generator-class-code-39.htm

//}
include_once "../../includes/phpmailer/class.phpmailer.php";
if(empty($_SESSION["UserId"])&&$_REQUEST["token"]!=$rootScope["SWDApiToken"])
{
    return;
}
$response = array();
$response["code"] = "";
$response["message"] = "";

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
        
        $query = "SELECT *, to_char(date,'MM/dd/yy') as ordered_date, to_char(estimated_ship_date,'MM/dd/yy') as estimated_ship_date, to_char(received_date,'MM/dd/yy') as received_date, t2.color as impression_color 
        FROM import_orders
        LEFT JOIN impression_colors AS t2 ON import_orders.impression_color_id = t2.id
        WHERE import_orders.id = :ID";
        $stmt = pdo_query( $pdo, $query, $params );       		
        $result = pdo_fetch_all( $stmt );	     
        
        $stmt = pdo_query( $pdo,  "UPDATE import_orders SET printed = TRUE WHERE id = :ID", $params ); 
        $response['data'] = "After Update";
		//$count = $result->rowCount();
		
		
//////////////////////////////////////////////////////////////// 2 DIFFERENT WAYS OF BARCODES (1 IS BARCODE AND OTHER IS QR CODE ////////////////////////////////////////////////////////////////
$barcode = "TESTING7";
if(strcmp($result[0]['model'], 'Reference')) {
	$barcode = $_GET['ID'] . " - " . $result[0]['model'];
	$barcode = $_GET['ID'];
} else {
	$barcode = $_GET['ID'] . " - Ref";
	$barcode = $_GET['ID'];
}
//$barcode = urldecode( "TESTING" );
$bc = new Barcode39( $barcode);
$file_path = $rootScope["RootPath"]."data/export/";
$file_name =  $barcode . '.gif';
$entire_pathname = $file_path . $barcode . '.gif';

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

     
     ///////////////////////////////////////////////////////////////////////////// 2ND WAY (QR CODE) IS NOT BEING USED - THE SCANNER COULD NOT READ IT ////////////////////////////////////////////
    // we need to be sure ours script does not output anything!!! 
    // otherwise it will break up PNG binary! 
   /*  
    ob_start("callback"); 
     
    // here DB request or some processing 
    //$codeText = 'DEMO - '.$param; 
    //$codeText = 'DEMO - '. 'test'; 
    $codeText = 'Q1'; 
    // end of processing here 
    $debugLog = ob_get_contents(); 
    ob_end_clean(); 
     
    // outputs image directly into browser, as PNG stream 
    //QRcode::png($codeText);
    $tempDir = $rootScope["RootPath"]."data/export/";
    //$codeContents = 'Test QRcode'; 
    $codeContents = 'Q1'; 
     
    // we need to generate filename somehow,  
    // with md5 or with database ID used to obtains $codeContents... 
    $fileName_barcode = '005_file_'.md5($codeContents).'.png'; 
	$pngAbsoluteFilePath = $tempDir.$fileName_barcode; 
    $urlRelativeFilePath = EXAMPLE_TMP_URLRELPATH.$fileName_barcode; 
    
    if (!file_exists($pngAbsoluteFilePath)) { 
        QRcode::png($codeContents, $pngAbsoluteFilePath); 
        //echo 'File generated!'; 
        //echo '<hr />'; 
    } else { 
        //echo 'File already generated! We can use this cached file to speed up site on common codes!'; 
        //echo '<hr />'; 
    } */
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////     

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
 
// CHECKING TO SEE IF ONLY HEARING PROTECTION IS ON THE ORDER
// ONLY HEARING PROTECTION CHANGES THE TRAVELER CONSIDERABLY
// IF ONLY HEARING PROTECTION THEN MAKE A PINK BORDER OTHERWISE MAKE A GREEN BORDER
//if ( ( $result[0]["musicians_plugs"] == TRUE) && strlen($result[0]['model'] ) < 3 ) {
if ( $result[0]["musicians_plugs"] == TRUE && $result[0]["use_for_estimated_ship_date"] != TRUE ) {	
	if($result[0]["ordered_date"] < '11/05/2021') {
		$border_color = "#FFFF33"; // YELLOW
		$border_color = "#FF69B4"; // PINK FOR HEARING PROTECTION	
	} else {
		$border_color = "#FF69B4"; // PINK FOR HEARING PROTECTION	
	}
//} elseif ($result[0]["hearing_protection"] == TRUE ) {
} elseif( (stristr($result[0]['model'], "Exp") || stristr($result[0]['model'], "EXP")) && $result[0]["use_for_estimated_ship_date"] != TRUE ) {
	$border_color = "#0022FF"; // BLUE FOR EXP PRO
} elseif ( (stristr($result[0]['model'], "AHP") || stristr($result[0]['model'], "Acrylic")) && $result[0]["use_for_estimated_ship_date"] != TRUE ) {
		$border_color = "#800080"; // PURPLE FOR ACRYLIC HEARING PROTECTION
} elseif ( (stristr($result[0]['model'], "SHP") || stristr($result[0]['model'], "Silicone") || stristr($result[0]['model'], "Full")) && $result[0]["use_for_estimated_ship_date"] != TRUE ) {
		$border_color = "#FF69B4"; // PINK FOR HEARING PROTECTION
} elseif (stristr($result[0]['model'], "Sec") && $result[0]["use_for_estimated_ship_date"] != TRUE ) {
		$border_color = "#FFFF00"; // PINK FOR HEARING PROTECTION
} elseif ( (stristr($result[0]['model'], "MP") || stristr($result[0]['model'], "Musician") || stristr($result[0]['model'], "Canal") ) && $result[0]["use_for_estimated_ship_date"] != TRUE ) {
		$border_color = "#FF69B4"; // POR Musicians Plugs
} else {
		$border_color = "#3F704D"; // GREEN FOR CUSTOMS
}

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
	//$rush_process = "<td colspan='2' style=\"text-align:left;font-size:40px;\"><b style=\"color:red;\"></b></td>"; // PRINTS NOTHING, SIMPLE FIX
	//$rush_process = "<td  style=\"text-align:left;font-size:30px;\"><b style=\"color:red;\"></b></td>"; // PRINTS NOTHING, SIMPLE FIX
} elseif ($result[0]['rush_process'] == TRUE || $result[0]['rush_process'] == 'Yes' ) {
	$rush_process = "<td colspan='2' style=\"text-align:left;font-size:40px;\"><b style=\"color:red;\">**RUSH**</b></td>";
	$rush_process = "<td style=\"text-align:left;font-size:30px;\"><b style=\"color:red;\">**RUSH**</b></td>";
}

if(strlen($result[0]['rush_process']) < 2 ) { // THIS IS HEAR BECAUSE WHEN TESTING A BUNCH OF ROWS WERE BLANK AND THAT WAS RETURNING "TRUE" 
	$left_column_name =
"
	<table width='420px' style=\"font-family:arial;font-size:22px;border-spacing: 80px 0px;border-left-style: solid #00FF00; border-left-width:140px; border-left-color:{$border_color}; white-space:nowrap;\">
		<tr style=\"font-weight:bold;float:left;padding-right:20cm;\">
        		<td style=\"float:right;padding-left:20cm;\"><b style=\"float:right;padding-left:20cm;\">NAME:</b></td>
		</tr>
";
} elseif ($result[0]['rush_process'] == TRUE || $result[0]['rush_process'] == 'Yes' ) {
	$left_column_name =
"
	<table width='420px' style=\"font-family:arial;font-size:22px;border-spacing: 80px 0px;border-left-style: solid #00FF00; border-left-width:140px; border-left-color:#ff0000; white-space:pre;\">
		<tr style=\"font-weight:bold;float:left;padding-right:20cm;\">
        		<td style=\"float:right;padding-left:20cm;\"><b style=\"float:right;padding-left:20cm;\">NAME:</b></td>
		</tr>
";}


//style=\"float:left;\" position: "absolute" 
if ($result[0]["additional_items"] == TRUE) {
	$column_items = 'ADD ON';
} else {
	$column_items = 'No additional items.';
	$column_items = '';
}
if ($result[0]["consult_highrise"] == TRUE) {
	$column_highrise = 'CONSULT HIGHRISE';
} else {
	$column_highrise = '';
}
if ($result[0]["international"] == TRUE) {
	$column_international = 'INTERNATIONAL';
} else {
	$column_international = '';
}
if ($result[0]["universals"] == TRUE) {
	$column_universals = 'UNIVERSALS';
} else {
	$column_universals = '';
}
if ($result[0]["num_earphones_per_order"] > 1) {
	$column_num_of_earphones = 'GROUP OF ' . "<span style=\"color:red;\">" . $result[0]["num_earphones_per_order"] . "</span>";
} 

// CHECKING TO SEE IF ONLY HEARING PROTECTION IS ON THE ORDER
// ONLY HEARING PROTECTION CHANGES THE TRAVELER CONSIDERABLY
// IF HEARING PROTECTION AND A MONITOR ARE ON THE ORDER THEN STILL PRINT "HEARING PROTECTION" AT THE BOTTOM OF THE TRAVELER
// THIS CASE WOULD PRINT A GREEN BORDER & NOT A PINK ONE
if ($result[0]["hearing_protection"] == TRUE && strlen($result[0]['model']) > 2) {
	$column_hearing_protection = 'HEARING PROTECTION';
	$column_hearing_protection_color = "&nbsp;" . $result[0]["hearing_protection_color"] . "&nbsp;";
	$hearing_protection_background_color = '#222222';
} else {
	$column_hearing_protection = '';
	$column_hearing_protection_color = '';
	$hearing_protection_background_color = '#FFFFFF';
}
$column_musicians_plugs_filter = '';
if ($result[0]["musicians_plugs"] == TRUE) {
	$column_musicians_plugs = "MUSICIAN'S PLUGS";
	if($result[0]["musicians_plugs_9db"] == TRUE) {
		$column_musicians_plugs_filter = $column_musicians_plugs_filter . " 10dB";	
	}
	if($result[0]["musicians_plugs_15db"] == TRUE) {
		$column_musicians_plugs_filter = $column_musicians_plugs_filter . " 15dB";	
	}
	if($result[0]["musicians_plugs_25db"] == TRUE) {
		$column_musicians_plugs_filter = $column_musicians_plugs_filter . " 25dB";	
	}
} else {
	$column_musicians_plugs = '';
	$column_musicians_plugs_filter = '';
}
if ($result[0]["pickup"] == TRUE) {
	$column_pickup = 'CUSTOMER PICKUP';
} else {
	$column_pickup = '';
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
/*
$left_column_name =
'
	<table width="420px" style="font-family:arial;font-size:22px;border-spacing: 80px 0px;border-left-style: solid #00ff00; border-left-width:140px; border-left-color:#3F704D">
		<tr style=\"font-weight:bold;float:left;padding-right:20cm;\">
        		<td style=\"floa:right;padding-left:20cm;\"><b style=\"float:right;padding-left:20cm;\">NAME:</b></td>
		</tr>
';*/
$left_column_name_response =  
"		<tr style=\"color:blue;\">
        		<td style=\"text-align:left;font-size:24px;\"><b>{$result[0]["designed_for"]}</b></td>".
        		$rush_process . "
		</tr>
";

if ( strlen($result[0]["designed_for"]) >= 22) {
	$text_size = 28;//"30px;";
} elseif (strlen($result[0]["designed_for"]) >= 18 &&  strlen($result[0]["designed_for"]) < 22) {
	$text_size = 30;//"30px;";
} elseif (strlen($result[0]["designed_for"]) == 17 ) {
	$text_size = 35;//"35px;";
} elseif (strlen($result[0]["designed_for"]) <= 16) {
	$text_size = 36;//"36px;";
} 
//$text_size = 30;
//<td style=\"text-align:left;font-size:30px;	white-space:nowrap;\"><b>{$result[0]["designed_for"]}</b></td>
$left_column_name_response =  
"		<tr style=\"color:blue;\">
        		<td style=\"text-align:left;font-size:{$text_size}px;white-space:nowrap;\"><b>{$result[0]["designed_for"]}</b></td>
        </tr>
        <tr>" . 
        		$rush_process . "
		</tr>
";

$left_column_orderID =
'
		<tr style=\"font-weight:bold;float:left;padding-right:20cm;\">
        		<td style=\"floa:right;padding-left:20cm;\"><b style=\"float:right;padding-left:20cm;\">ORDER:</b></td>
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
$left_column_impressions_color = 
"		<tr style=\"font-weight:bold;\">
    			<td style=\"text-align:left;\">IMP. COLOR:</td>
		</tr>
";
$left_column_phone_response =  
"		<tr style=\"color:blue;\">
        		<td style=\"text-align:left;\">{$result[0]["phone"]}</td>
		</tr>
";
$left_column_impressions_color_response = 
"		<tr style=\"color:blue;\">
        		<td style=\"text-align:left;\">{$result[0]["impression_color"]}</td>
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
"		<tr  style=\"color:blue;\">
        		<td colspan=\"2\"  style=\"text-align:left;\">{$result[0]["notes"]}</td>
		</tr>
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
    			
		
";

// CHECKING TO SEE IF ONLY HEARING PROTECTION IS ON THE ORDER
// ONLY HEARING PROTECTION CHANGES THE TRAVELER CONSIDERABLY
// PRINTS "HEARING PROTECTION" UNDER MONITOR INSTEAD OF THE MONITOR ITSELF
if ($result[0]["hearing_protection"] == TRUE && strlen($result[0]['model'] ) < 2 ) {
	$right_column_monitor_response =  
"		<tr style=\"font-size:14px;\">
			 <td colspan='2' style=\"text-align:left;font-size:30px;margin-top:-50px;display:inline-block;\"><b style=\"color:green;\">" . "HEARING " . "</b></td>" . 
			 //$rush_process . "
		"</tr>
		<tr style=\"font-size:14px;\">
			 <td colspan='2' style=\"text-align:left;font-size:30px;margin-top:-50px;display:inline-block;\"><b style=\"color:green;\">" . "PROTECTION " . "</b></td>" . 
			 //$rush_process . "
		"</tr>
";

} elseif ($result[0]["musicians_plugs"] == TRUE && strlen($result[0]['model'] ) < 2 ) {
	$right_column_monitor_response =  
"		<tr style=\"font-size:14px;\">
			 <td colspan='2' style=\"text-align:left;font-size:30px;margin-top:-50px;display:inline-block;\"><b style=\"color:green;\">" . "MUSICIAN'S " . "</b></td>" . 
			 //$rush_process . "
		"</tr>
		<tr style=\"font-size:14px;\">
			 <td colspan='2' style=\"text-align:left;font-size:30px;margin-top:-50px;display:inline-block;\"><b style=\"color:green;\">" . "PLUGS " . "</b></td>" . 
			 //$rush_process . "
		"</tr>
";


	$right_column_cable = 
"		<tr style=\"font-weight:bold;\">
    		<td style=\"text-align:left;\"></td>
		</tr>
";
	$right_column_cable_response =  
"		<tr style=\"font-size:14px;\">
			 <td colspan='2' style=\"text-align:left;font-size:30px;\"></td>
		</tr>
";
} else {
	if(!strcmp($result[0]['model'], 'ESM')) {
		$right_column_monitor_response =  
"		<tr style=\"font-size:14px;\">
			 <td colspan='2' style=\"text-align:left;font-size:30px;margin-top:-50px;display:inline-block;\"><b style=\"color:green;\">" . "Super ESM" . "</b></td>" . 
			 //$rush_process . "
		"</tr>
";	
	} else {
		$right_column_monitor_response =  
"		<tr style=\"font-size:14px;\">
			 <td colspan='2' style=\"text-align:left;font-size:30px;margin-top:-50px;display:inline-block;\"><b style=\"color:green;\">" . $result[0]['model'] . "</b></td>" . 
			 //$rush_process . "
		"</tr>
";	
	}
	
	$right_column_cable = 
"		<tr style=\"font-weight:bold;\">
    		<td style=\"text-align:left;\">CABLE:</td>
		</tr>
";

	if($result[0]['model'] == "Studio3" || $result[0]['model'] == "Studio4" || $result[0]['model'] == "Electro" ) {
		$right_column_cable_response =  
		"<tr style=\"font-size:14px;\">
			 <td colspan='2' style=\"text-align:left;font-size:30px;\"><b style=\"color:green;\">" . "Studio" . "</b></td>
		</tr>";
	} elseif ($result[0]['model'] == "ESM") {
		$right_column_cable_response =  
		"<tr style=\"font-size:14px;\">
			 <td colspan='2' style=\"text-align:left;font-size:30px;\"><b style=\"color:green;\">" . "SuperBaX" . "</b></td>
		</tr>";
	} else {
		$right_column_cable_response =  
		"<tr style=\"font-size:14px;\">
			 <td colspan='2' style=\"text-align:left;font-size:30px;\"><b style=\"color:green;\">" . $result[0]['cable_color'] . "</b></td>
		</tr>";
	}
}


/*$right_column_artwork = 
"	<tr style=\"color:black;\">
    	<td  style=\"text-align:left;\">ARTWORK:</td>
    </tr>
";*/

if ( ($result[0]["hearing_protection"] == TRUE || $result[0]["musicians_plugs"] == TRUE) && strlen($result[0]['model'] ) < 3 ) {
	//$right_column_artwork = "";
	//$right_column_artwork = "";
} else {
	if(strcmp($result[0]["artwork"], 'None')) {
	$right_column_artwork = 
	"	<tr>
    			<td colspan='4' style=\"font-weight:bold;\"><span style=\"background-color: #40D8E0;\">ARTWORK:</span></td>
			</tr>";
	//<span style=\"color:red;\"><b>{$result[0]["artwork"]}</b></span></td>
	} else {
	$right_column_artwork = 
	"	<tr>
    		<td colspan='4' style=\"font-weight:bold;\">ARTWORK:</td>
    	</tr>";
		//<span style=\"color:red;\"><b>{$result[0]["artwork"]}</b></span></td>
	}	
}


/*$right_column_artwork_response =  
"		<tr style=\"font-size:14px;\">
			 <td colspan='2' style=\"text-align:left;font-size:24px;\"><b style=\"color:green;\">" . $result[0]['artwork'] . "</b></td>
		</tr>
";*/

if ( strlen($result[0]["left_alclair_logo"]) >= 19) {
	$text_size_left = 20;//"30px;";
} elseif (strlen($result[0]["left_alclair_logo"]) >= 10 &&  strlen($result[0]["left_shell"]) < 19) {
	$text_size_left =24;//"30px;";
} elseif (strlen($result[0]["left_alclair_logo"]) <= 9) {
	$text_size_left = 30;//"36px;";
} 
if ( strlen($result[0]["right_alclair_logo"]) >= 19) {
	$text_size_right = 20;//"30px;";
} elseif (strlen($result[0]["right_alclair_logo"]) >= 10 &&  strlen($result[0]["right_shell"]) < 19) {
	$text_size_right =24;//"30px;";
} elseif (strlen($result[0]["right_alclair_logo"]) <= 9) {
	$text_size_right = 30;//"36px;";
} 

if ( ($result[0]["hearing_protection"] == TRUE || $result[0]["musicians_plugs"] == TRUE) && strlen($result[0]['model'] ) < 3 ) {
	$left_logo_html = "";
	$right_logo_html = "";
} else {	
	if(strlen($result[0]["left_alclair_logo"]) < 2) {
		$left_background_color = '#FFFFFF';
		$left_logo_html = "<td style=\"text-align:left;\"><span style=\"background-color:" .  $left_background_color . "\">LEFT</span></td>";
	} else {
		$left_background_color = '#40D8E0';
		$left_logo_html = "<td style=\"text-align:left;\"><span style=\"background-color:" .  $left_background_color . "\">LEFT LOGO</span></td>";
	}
	if(strlen($result[0]["right_alclair_logo"]) < 2) {
		$right_background_color = '#FFFFFF';
		$right_logo_html = "<td style=\"text-align:left;\"><span style=\"background-color:" .  $right_background_color . "\">RIGHT</span></td>";
	} else {
		$right_background_color = '#40D8E0';
		$right_logo_html = "<td style=\"text-align:left;\"><span style=\"background-color:" .  $right_background_color . "\">RIGHT LOGO</span></td>";
	}

	 $right_column_artwork_response = 
	"	<tr>" . $left_logo_html . $right_logo_html . "
	    		
		</tr>
	";
	//<td style=\"text-align:left;\"><span style=\"background-color:" .  $left_background_color . "\">LEFT LOGO</span></td>
	//<td style=\"text-align:left;\"><span style=\"background-color:" .  $right_background_color . "\">RIGHT LOGO</span></td>
	
	 $right_column_artwork_response2 = 
	"	<tr  style=\"color:blue;\">
	    		<td style=\"text-align:left;font-size:{$text_size_left}px;\"><b>" . $result[0]["left_alclair_logo"] . "</b></td>
	    		<td style=\"text-align:left;font-size:{$text_size_right}px;\"><b>" . $result[0]["right_alclair_logo"] . "</b></td>
		</tr>
	";
}
if ( strlen($result[0]["left_shell"]) >= 19) {
	$text_size_left = 20;//"30px;";
} elseif (strlen($result[0]["left_shell"]) >= 10 &&  strlen($result[0]["left_shell"]) < 19) {
	$text_size_left =24;//"30px;";
} elseif (strlen($result[0]["left_shell"]) <= 9) {
	$text_size_left = 30;//"36px;";
} 
if ( strlen($result[0]["right_shell"]) >= 19) {
	$text_size_right = 20;//"30px;";
} elseif (strlen($result[0]["right_shell"]) >= 10 &&  strlen($result[0]["right_shell"]) < 19) {
	$text_size_right =24;//"30px;";
} elseif (strlen($result[0]["right_shell"]) <= 9) {
	$text_size_right = 30;//"36px;";
} 
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

// CHECKING TO SEE IF ONLY HEARING PROTECTION IS ON THE ORDER
// ONLY HEARING PROTECTION CHANGES THE TRAVELER CONSIDERABLY
/*
if ($result[0]["hearing_protection"] == TRUE && strlen($result[0]['model']) < 2) {
	 $right_column_colors_shells_response2 = 
"	<tr  style=\"color:blue;\">
    		<td style=\"text-align:left;font-size:{$text_size_left}px;\"><b>" . $result[0]["hearing_protection_color"]  . "</b></td>
    		<td style=\"text-align:left;font-size:{$text_size_right}px;\"><b>" . $result[0]["hearing_protection_color"] . "</b></td>
	</tr>
";

//} else {  // COMMENTED OUT MAY 13TH, 2021 FOR A QUICK FIX
*/
 $right_column_colors_shells_response2 = 
"	<tr  style=\"color:blue;\">
    		<td style=\"text-align:left;font-size:{$text_size_left}px;\"><b>" . $result[0]["left_shell"] . "</b></td>
    		<td style=\"text-align:left;font-size:{$text_size_right}px;\"><b>" . $result[0]["right_shell"] . "</b></td>
	</tr>
";
//}

//if(strlen($result[0]["clear_canal"]) < 3 && strlen($result[0]["left_tip"]) < 3) {
if(strlen($result[0]["left_tip"]) < 3 || $result[0]["left_tip"] == NULL ) {
	$left_tip_background_color = '#FFFFFF';
} else {
	//if(strlen($result[0]["left_tip"]) > 2) {
		$canal_color_left = $result[0]["left_tip"];
	//} else {
		//$canal_color_left = $result[0]["clear_canal"];
		//$canal_color_left = " Clear";
	//}

	$left_tip_background_color = '#00FF00';
}

//if(strlen($result[0]["clear_canal"]) < 3  && strlen($result[0]["right_tip"]) < 3) {
if(strlen($result[0]["right_tip"]) < 3  && $result[0]["right_tip"] == NULL ) {
	$right_tip_background_color = '#FFFFFF';
} else {
	//if(strlen($result[0]["right_tip"]) > 2) {
		$canal_color_right = $result[0]["right_tip"];
	//} else {
		//$canal_color_right = $result[0]["clear_canal"];
		//$canal_color_right = " Clear";
	//}
	$right_tip_background_color = '#00FF00';
}

// CHECKING TO SEE IF ONLY HEARING PROTECTION IS ON THE ORDER
// ONLY HEARING PROTECTION CHANGES THE TRAVELER CONSIDERABLY
if ($result[0]["hearing_protection"] == TRUE && strlen($result[0]['model']) < 2) {
	$right_column_tips = 
"	<tr>
    		<td style=\"font-size:20px;font-weight:bold;\"><span style=\"background-color:" . $left_tip_background_color . "\"></span><span style=\"color:red;\"><b></b></span></td>
    		<td style=\"font-size:20px;font-weight:bold;\"><span style=\"background-color:" . $right_tip_background_color . "\"></span><span style=\"color:red;\"><b></b></span></td>
	</tr>
";

$right_column_colors_faceplates_response = 
"	<tr>
		<td style=\"text-align:left;\"></td>
    	<td style=\"text-align:left;\"></td>
	</tr>
";
 $right_column_colors_faceplates_response2 = 
"	<tr  style=\"color:blue;\">
		<td style=\"text-align:left;font-size:{$text_size_left}px;\"><b>"."</b></td>
    	<td style=\"text-align:left;font-size:{$text_size_right}px;\"><b>"."</b></td>
	</tr>
";

} else {
	if (strlen($result[0]["clear_canal"]) == 2 && $result[0]["left_tip"] == NULL && $result[0]["right_tip"] == NULL) { // 2 MEANS THAT CLEAR CANAL IS "NO" - 2 CHARACTERS LONG
		$right_column_tips = 
"	<tr>
    		<td style=\"font-size:20px;font-weight:bold;\"><span style=\"background-color:" . $left_tip_background_color . "\">TIPS:</span><span style=\"color:red;\"><b></b></span></td>
    		<td style=\"font-size:20px;font-weight:bold;\"><span style=\"background-color:" . $right_tip_background_color . "\">TIPS:</span><span style=\"color:red;\"><b></b></span></td>
	</tr>
";
	} else {
	$right_column_tips = 
"	<tr>
    		<td style=\"font-size:20px;font-weight:bold;\"><span style=\"background-color:" . $left_tip_background_color . "\">TIPS:</span><span style=\"color:red;\"><b> {$canal_color_left}</b></span></td>
    		<td style=\"font-size:20px;font-weight:bold;\"><span style=\"background-color:" . $right_tip_background_color . "\">TIPS:</span><span style=\"color:red;\"><b> {$canal_color_right}</b></span></td>
	</tr>
";
	}
	
if ( strlen($result[0]["left_faceplate"]) >= 19) {
	$text_size_left = 20;//"30px;";
} elseif (strlen($result[0]["left_faceplate"]) >= 10 &&  strlen($result[0]["left_faceplate"]) < 19) {
	$text_size_left =24;//"30px;";
} elseif (strlen($result[0]["left_faceplate"]) <= 9) {
	$text_size_left = 30;//"36px;";
} 
if ( strlen($result[0]["right_faceplate"]) >= 19) {
	$text_size_right = 20;//"30px;";
} elseif (strlen($result[0]["right_faceplate"]) >= 10 &&  strlen($result[0]["right_faceplate"]) < 19) {
	$text_size_right =24;//"30px;";
} elseif (strlen($result[0]["right_faceplate"]) <= 9) {
	$text_size_right = 30;//"36px;";
} 
$right_column_colors_faceplates_response = 
"	<tr>
    		<td style=\"text-align:left;\">LEFT FACE</td>
    		<td style=\"text-align:left;\">RIGHT FACE</td>
	</tr>
";
 $right_column_colors_faceplates_response2 = 
"	<tr  style=\"color:blue;\">
			<td style=\"text-align:left;font-size:{$text_size_left}px;\"><b>" . $result[0]["left_faceplate"] . "</b></td>
    		<td style=\"text-align:left;font-size:{$text_size_right}px;\"><b>" . $result[0]["right_faceplate"] . "</b></td>
	</tr>
";
	
}
	
$right_column_addons_and_highrise = 
"	<tr>
			<td colspan='4' style=\"font-weight:bold;\">ADDED NOTES:</td>
	</tr>
";
// 2 <td> WERE USED BECAUSE I COULDN'T FIGURE OUT HOW TO NOT MAKE THE TEXT WRAP
 $right_column_items = 
"		<tr style=\"color:#FF0000;font-size:20px;float:left;font-weight:bolder\">
			<td>" .
					$column_items . "
			</td>
			<td  style=\"white-space:nowrap;\">" .
				$column_highrise . "
			</td>
		</tr>
";

$right_column_num_of_earphones = 
"		<tr style=\"color:#FF0000;font-size:20px;float:left;font-weight:bolder;\">
			<td  style=\"white-space:nowrap;\">" .
					$column_num_of_earphones . "
			</td>
			<td  style=\"white-space:nowrap;\">" .
					$column_universals . "
			</td>	
		</tr>
";

$right_column_hearing_protection = 
"		<tr style=\"background-color: {$hearing_protection_background_color}; color:#FFD700;font-size:20px;float:left;font-weight:bolder;\">
			<td  style=\"white-space:nowrap;\">" .
					$column_hearing_protection . "
			</td>
			<td  style=\"white-space:nowrap;\">" . " 
				<span style=\"background-color: {$hearing_protection_background_color}; color: #FFD700\">" . $column_hearing_protection_color . "</span>
			</td>
		</tr>
";
/*$right_column_musicians_plugs = 
"		<tr style=\"color:#00B200;font-size:16px;float:left;font-weight:bolder;\">
			<td  style=\"white-space:nowrap;\">" .
					$column_musicians_plugs . " <br/><span style=\"color:#222222;\">" . $column_musicians_plugs_filter . "</span>
			</td>
			<td  style=\"white-space:nowrap;\">" . "
			</td>
		</tr>
";*/
$right_column_musicians_plugs = 
"		<tr style=\"color:#FFA500;font-size:20px;float:left;font-weight:bolder;\">
			<td  style=\"white-space:nowrap;\">" .
					$column_musicians_plugs . "
			</td>
			<td  style=\"white-space:nowrap;\">" . "
				<span style=\"; color: #FFA500\">" . $column_musicians_plugs_filter . "</span>
			</td>
		</tr>
";



$right_column_pickup = 
"		<tr style=\"color:#00B200;font-size:20px;float:left;font-weight:bolder;\">
			<td  style=\"white-space:nowrap;\">" .
					$column_pickup . "
			</td>
			<td  style=\"white-space:nowrap;\">" .
					$column_international . "
			</td>	
		</tr>
";

// 2 <td> WERE USED BECAUSE I COULDN'T FIGURE OUT HOW TO NOT MAKE THE TEXT WRAP
 $right_column_highrise = 
"		<tr style=\"color:#FF0000;font-size:20px;float:left;font-weight:bolder\">
			<td>" .
					$column_highrise . "
			</td>
		</tr>
";

$left_column_order_date = 
"		<tr style=\"font-weight:bold;\">
    			<td style=\"text-align:left;\">ORDERED:</td>
		</tr>
";

// HERE TWICE BECAUSE </table> NEEDED TO BE REMOVED
/*$left_column_impression_date_response =  
"		<tr style=\"color:blue;\">
        		<td style=\"text-align:left;\">{$result[0]["ordered_date"]}</td>
		</tr>
	</table>
";*/
$left_column_order_date_response =  
"		<tr style=\"color:blue;\">
        		<td style=\"text-align:left;\">{$result[0]["ordered_date"]}</td>
		</tr>
";


$left_column_received_date = 
"		<tr style=\"font-weight:bold;\">
    			<td style=\"text-align:left;\">IMP DATE:</td>
		</tr>
";
$left_column_received_date_response =  
"		<tr style=\"color:blue;\">
        		<td style=\"text-align:left;\">{$result[0]["received_date"]}</td>
		</tr>
";

$right_column_ship_date = 
"		<tr style=\"font-weight:bold;\">
    			<td style=\"text-align:left;\">DUE DATE:</td>
		</tr>
";
$right_column_ship_date_response =  
"		<tr style=\"color:blue;\">
        		<td style=\"text-align:left;\">{$result[0]["estimated_ship_date"]}</td>
		</tr>
	</table>
";
	//$testcode = '<img src=\"/var/www/html/dev/data/export/{$fileName_barcode}\" </td></tr>';
	//$testcode = "<img src='"/var/www/html/dev/data/export/' . $fileName_barcode . '" </td></tr>'";
	//$step_1 = '<img src=\"/var/www/html/dev/data/export/'$fileName_barcode'/\"';
	//$step_1 = '<img src="/var/www/html/dev/data/export/005_file_457d2f313cb4e48ae5c08e6333be9b45.png" width="300" height="300" ALIGN=”right”></td></tr>';
	//$step_1 = '<img src="/var/www/html/dev/data/export/TESTING4.gif" width="300" height="300" ALIGN=”right”></td></tr>';
	//$step_2 = $fileName_barcode;
	//$step_2 = $fileName_barcode . '"';
	
	//$step_2 = $test_filename . '"';
	//$step_1 =  '<img src="/var/www/html/dev/data/export/' . $step_2 . '" width="300" height="300" ALIGN=”right”>';
	
	// RE-CREATE STEP_1 A FEW LINES BELOW BECAUSE I ACCIDENTALLY HARD SET STEP_1 TO THE DEV FOLDER
	// WHEN ALCLAIR IS USING THIS CODE IT NEEDS TO BE ALCLAIR'S FOLDER
	// THE FOR LOOP BACKTRACKS THROUGH THE FILEPATH BY 2 BACKSLASHES THEN RECREATES THE FILEPATH TO BE CORRECT
	// THE FILEPATH SHOULD EITHER BE /var/www/html/dev/data/export/ OR /var/www/html/alclair/data/export/
	$step_1 = '<img src="/var/www/html/otis/data/export/';
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
	$response["test"] = $filepath_for_pdf;
	//echo json_encode($response);
    //exit;
	$step_1 = '<img HEIGHT="500%" src="' . $filepath_for_pdf;
	
    //echo json_encode($response);
    //exit;
	//$step_3 = ' </td></tr>';
	//$step_3 = ' width="300" height="200" ALIGN=”right”></td></tr>';
	$step_2 = $file_name . '"';
	$step_3 = ' align="left"”></td></tr>';
	$the_barcode = $step_1 . $step_2 . $step_3;
	//$testcode = $step_1 ;


 	//$testcode = '<img src="/var/www/html/dev/data/export/005_file_457d2f313cb4e48ae5c08e6333be9b45.png" width="300" height="300" ALIGN=”right”></td></tr>';


//	<td  colspan='2'  style=\"text-align:left;font-size:12px;\">LOGO&nbsp;&nbsp;C-ICON&nbsp;&nbsp;V-STAMP&nbsp;&nbsp;SCRIPT&nbsp;&nbsp;CUSTOM</td>           
            $ticket_html3 = "<table style=\"white-space:nowrap;\"> <tr><td>" . 
            $left_column_name  . $left_column_name_response  . 
             "<tr><td colspan=\"2\" style='height:$td_height;'></td></tr>" .
			 $left_column_orderID  . $left_column_orderID_response  . 
             "<tr><td colspan=\"2\" style='height:$td_height;'></td></tr>" .

            //$left_column_email  . $left_column_email_response  . 
             //"<tr><td colspan=\"2\" style='height:$td_height;'></td></tr>" .
            $left_column_impressions_color  . $left_column_impressions_color_response  . 
             "<tr><td colspan=\"2\" style='height:$td_height;'></td></tr>" .
            //$left_column_church  . $left_column_church_response  . 
             //"<tr><td colspan=\"2\" style='height:$td_height;'></td></tr>" .
            //$left_column_address  . $left_column_address_response  . 
             //"<tr><td colspan=\"2\" style='height:$td_height;'></td></tr>" .
            $left_column_note  . $left_column_note_response  . 
            "<tr><td colspan=\"2\" style='height:$td_height;'></td></tr>" .
            "<tr><td colspan=\"2\" style='height:$td_height;'></td></tr>" .
            //"<tr><td colspan=\"2\" style='height:$td_height;'></td></tr>" .
            $left_column_order_date  . $left_column_order_date_response  . 
           "<tr><td colspan=\"2\" style='height:$td_height;'></td></tr>" .
            $left_column_received_date  . $left_column_received_date_response  . 
           "<tr><td colspan=\"2\" style='height:$td_height;'></td></tr>" .
            $right_column_ship_date .  $right_column_ship_date_response .
            "</td>" .   
             //'<img src="'.$urlRelativeFilePath.'" />' . 
             //"<img src=$rootScope["RootPath"].'data/export/'.$urlRelativeFilePath.'/>'" . 
             //'<img src="/var/www/html/dev/logo.jpg"  width="50" height="50">';
             //$pdf->Image($rootScope["RootPath"].'data/export/005_file_457d2f313cb4e48ae5c08e6333be9b45.png ',10,10,-300) . 
             //$rootScope["RootPath"]."data/export/";
             
             //<td>" . 
            //$middle_column_order_id . $middle_column_order_id_response . "</td>
            "<td>" . 
            "<table style=\"font-size:20px;\">" . //$right_column_checkboxes1_response .  
             $right_column_monitor . "<td style=\"display:inline-block;\" rowspan=\"7\" >" . 
             	//'<img src="/var/www/html/dev/data/export/005_file_457d2f313cb4e48ae5c08e6333be9b45.png" width="300" height="300" ALIGN=”right”></td></tr>' .
             	//'<img src="/var/www/html/dev/data/export/". '$fileName_barcode' ."""  ></td></tr>' .
             	$the_barcode . 
             	//'<img src="/var/www/html/dev/data/export/005_file_457d2f313cb4e48ae5c08e6333be9b45.png" width="200" height="200" ALIGN=”right”></td></tr>' .
             //<img src="{{traveler.link_to_design_image}}" alt="Design Image" style="parent:hover img {display: block}">
             $right_column_monitor_response .
             "<tr><td colspan=\"2\" style='height:$td_height;'></td></tr>" .
            $right_column_cable . $right_column_cable_response .
            "<tr><td colspan=\"2\" style='height:$td_height;'></td></tr>" .
            $right_column_artwork . $right_column_artwork_response . $right_column_artwork_response2 . 
             
             "<tr><td colspan=\"2\" style='height:$td_height;'></td></tr>" .
             $right_column_colors .  $right_column_colors_shells_response  . $right_column_colors_shells_response2 . 
             "<tr><td colspan=\"2\" style='height:$td_height;'></td></tr>" .
             $right_column_tips .  $right_column_colors_faceplates_response  .  $right_column_colors_faceplates_response2 .
             "<tr><td colspan=\"2\" style='height:2px;'></td></tr>" .
             //$right_column_addons_and_highrise .
             $right_column_items .
             $right_column_num_of_earphones .
             $right_column_hearing_protection .
             $right_column_musicians_plugs .
             $right_column_pickup .
             //$right_column_highrise .
            //"<tr><td colspan=\'2\' style='height:$td_height;'></td></tr>" .
            //"<tr><td colspan=\'2\' style='height:$td_height;'></td></tr>" .
            "	<tr>
    			<td style=\"font-size:2px\"></td>
				<td style=\"font-size:2px\"></td>
			</tr></table>" .

             //$right_column_received_date .  $right_column_received_date_response .
             "</td></tr></table>";
            
            $pdf->writeHTML( $ticket_html3, true, false, true, false, "" );
    	}
    }
    else
    {
        $response['code'] = 'success';
        $response['count'] = count($result);
        $response['data'] = "NOT WORKING";
        echo json_encode($response);
        exit;
    }       
    //close and output pdf document
    
    //$filename = "Alclair Traveler {$_REQUEST["startdate"]} to {$_REQUEST["enddate"]} as Batch No. $batch_num.pdf";
    $filename = "Alclair Traveler.pdf";
    $pdf->Output("../../data/exportpdf/$filename", "F"); 
    
    //$url=$rootScope["RootUrl"]."/api/export/exportTicket.php?startdate=$startDate&enddate=$endDate&token=".$rootScope["SWDApiToken"];
//echo $url;    
//$json=file_get_contents($pdf);
//$list=json_decode($json,true);
$file_=$rootScope["RootPath"]."data/exportpdf/".$filename;
 
 // HERE IS WHERE THE EMAIL SECTION OF THE CODE STARTS
    $response['code'] = 'success';
    $response['data'] = $filename;

    // LINE 3 OF 3 TO COMMENT FOR TESTING CODE
	echo json_encode($response);
}
catch(PDOException $ex)
{	
	$response["code"] = "error";
	$response['data'] = "broken";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}
?>
