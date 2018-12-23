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
try
{	             
    $id = $_REQUEST["id"];
    $html = "<div>Haven't found ticket</div>";
    $result = array();
        $conditionSql = "";
        $params = array();
        
            $start_filter = $_REQUEST["startdate"];
			$end_filter = $_REQUEST["enddate"];
			
			$test = $_REQUEST["startdate"] . ' ' .$_REQUEST["enddate"];
        
        if(!empty($_REQUEST["startdate"]))
        {
	        //echo "Hello, there are many ${var}s";
            $conditionSql .= " and (t1.date_created>=:startdate)";
            
            // OUTPUTS 1 IF IN DAYLIGHT SAVINGS TIME, 0 OTHERISE
            // DAYLIGHT SAVINGS TIME STARTS ON THE SECOND SUNDAY IN MARCH
            // DAYLIGHT SAVINGS TIMES ENDS ON THE FIRST SUNDAY IN NOVEMBER
            if (date('I', time()))
				{
					$params[":startdate"] = date("m/d/Y H:i:s",strtotime($_REQUEST["startdate"]) + 5 * 3600);		
				}
			else
				{
					$params[":startdate"] = date("m/d/Y H:i:s",strtotime($_REQUEST["startdate"]) + 6 * 3600);
				}
            
            //$meeting_time = date('g:i a', strtotime($time_date_data) - 6 * 3600);
        }
			
        if(!empty($_REQUEST["enddate"]))
        {
            $conditionSql .= " and (t1.date_created<=:enddate)";
            if (date('I', time()))
				{
					$params[":enddate"] = date("m/d/Y H:i:s",strtotime($_REQUEST["enddate"]) + 5* 3600);		
				}
			else
				{
					$params[":enddate"] = date("m/d/Y H:i:s",strtotime($_REQUEST["enddate"]) + 6 * 3600);
				}
        }
			     
		if($_REQUEST["TicketCreator"] > 1) {
			$conditionSql .= " AND (t1.created_by_id = :UserID)";
			$params[":UserID"] = $_REQUEST["TicketCreator"];
		}
			     
        $query =  "select  t2.first_name as first_name, t2.last_name as last_name, t2.email as email, SUM(barrels_delivered) as barrels_delivered, COUNT(*) as ticket_quantity
                  from ticket_tracker_ticket as t1
                  left join auth_user as t2 on t1.created_by_id = t2.id
                  left join ticket_tracker_disposalwell as t3 on t1.disposal_well_id = t3.id
                  where 1 = 1 $conditionSql and t1.disposal_well_id = {$_REQUEST["SearchDisposalWell"]}
                  group by email, first_name, last_name";
        $stmt = pdo_query( $pdo, $query, $params ); 
        $result = pdo_fetch_all( $stmt );
                
		//$count = $result->rowCount();
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
	$query4 = "SELECT t1.ticket_number as ticket_number, t1.date_delivered as date_delivered, t1.barrels_delivered as barrels_delivered, t1.barrel_rate as barrel_rate, to_char(t1.date_created,'MM/dd/yyyy HH24:MI') as date_created, t2.type as water_type, t3.common_name as disposal_well, t4.current_well_name as source_well, t4.file_number as well_file_number
FROM ticket_tracker_ticket as t1
left join ticket_tracker_watertype as t2 on t1.water_type_id = t2.id
left join ticket_tracker_disposalwell as t3 on t1.disposal_well_id = t3.id
left join ticket_tracker_well as t4 on t1.source_well_id = t4.id
WHERE 1 = 1 $conditionSql and t1.disposal_well_id = {$_REQUEST["SearchDisposalWell"]}
ORDER BY date_delivered asc";
//{$_REQUEST["DisposalWells"]}";
$stmt4 = pdo_query( $pdo, $query4, $params); 
$result4 = pdo_fetch_all( $stmt4 );
$ticketNumber=array();
$dateDelivered=array();
$barrelsDelivered=array();
$rate=array();
$dateCreated=array();
$waterType=array();
$disposalWell=array();
$sourceWell=array();
$wellFileNumber=array();
foreach($result4 as $ticket4) {
	array_push($ticketNumber, $ticket4["ticket_number"]);
	array_push($dateDelivered, $ticket4["date_delivered"]);
	array_push($barrelsDelivered, $ticket4["barrels_delivered"]);
	if(date("I",time()))
		{ array_push($dateCreated, date("m/d/Y H:i:s",strtotime($ticket4["date_created"]) - 5 * 3600 )); 
		}
	else
		{ array_push($dateCreated, date("m/d/Y H:i:s",strtotime($ticket4["date_created"]) - 6 * 3600 )); 
		}

	//date("m/d/Y H:i:s",strtotime($_REQUEST["enddate"]) + 5 * 3600)
	//if(empty($ticket4["barrel_rate"])) {
	if(empty($ticket4["barrel_rate"])) {
		array_push($rate, "n/a");
	}
	else {
		array_push($rate, "$".$ticket4["barrel_rate"]);
	}
	
	if(empty($ticket4["water_type"])) {
		array_push($waterType, 'Not Entered');
	}
	else {
		array_push($waterType, $ticket4["water_type"]);
	}
	
	array_push($disposalWell, $ticket4["disposal_well"]);
	
	if(empty($ticket4["source_well"])) {
		array_push($sourceWell, 'Not Entered');
	}
	else {
		array_push($sourceWell, $ticket4["source_well"]);
		//array_push($sourceWell, substr($ticket4["source_well"],0,10));
	}
	
	if(empty($ticket4["well_file_number"])) {
		array_push($wellFileNumber, "n/a");	
	}
	else {
		array_push($wellFileNumber, $ticket4["well_file_number"]);
	}
}
$ticketNumber = implode("<br>", $ticketNumber);
$dateDelivered = implode("<br>", $dateDelivered);
$barrelsDelivered = implode("<br>", $barrelsDelivered);
$rate = implode("<br>", $rate);
$dateCreated = implode("<br>", $dateCreated);
$waterType = implode("<br>", $waterType);
$disposalWell = implode("<br>", $disposalWell);
$sourceWell = implode("<br>", $sourceWell);
$wellFileNumber = implode("<br>", $wellFileNumber);	
	
	foreach($result as $ticket) {
		$n_barrels = $ticket["barrels_delivered"]+$n_barrels;
		$tot_tickets = $ticket["ticket_quantity"]+$tot_tickets;
	}
	$email=array();
	$firstname=array();
	$lastname=array();
	
	$ticket_quantity=array();
	$barrels_delivered=array();
	foreach($result as $ticket) {
		if ( !in_array($ticket["email"], $email) ) {
			array_push($firstname, $ticket["first_name"]);
			array_push($lastname, $ticket["last_name"]);
			array_push($email, $ticket["email"]);
			
			array_push($ticket_quantity, $ticket["ticket_quantity"]);
			array_push($barrels_delivered, $ticket["barrels_delivered"]);
		}
	}	
	
	$firstname = implode("<br>", $firstname);
	$lastname = implode("<br>", $lastname);
	$email = implode("<br>", $email);
	
	$ticket_quantity = implode("<br>", $ticket_quantity);
	$barrels_delivered = implode("<br>", $barrels_delivered);
// TYLER CODE - This reads the "batch_num" from 'recon_report'
            $query2 =  "SELECT  batch_num FROM recon_report ORDER BY batch_num desc limit 1";
            $stmt2 = pdo_query( $pdo, $query2, null); 
            $result2 = pdo_fetch_all( $stmt2 );
            //print_r($result);
            $batch_num = $result2[0]["batch_num"] + 1;
    if(count($result) > 0)
    {
        $html = "";
        $ticket_index = 1;
        $td_height = "5px";        
        //foreach($result as $ticket)
        {
            //$pdf->lastPage();
            $pdf->AddPage();
            $ticket_html = 
 //w3schools.com/cssref/pr_font_font-style.asp
//"<div style=\"font-weight:200;\"><span style=\"font-weight:bold;color:green;\">Ticket $ticket_index was created at:</span> {$ticket["date_created"]}<br/>
"<div ><span style=\"font-size:24px;font-weight:bold;color:red;text-align:left;\">BOH Reconciliation Report </span> <span style=\"font-size:20px;font-weight:bold;color:red;margin-right: 10px;float:right;\"> <br/>Batch # $batch_num </span> <br/><br/>
<table>
<br><br/>
	
    <tr style=\"font-weight:bold;\">
        <td style=\"text-align:center;\">Start Date:</td>
        <td style=\"text-align:center;\">End Date:</td>
    </tr>
    <tr style=\"color:blue;\">
        <td style=\"text-align:center;\">{$_REQUEST["startdate"]}</td>
        <td style=\"text-align:center;\">{$_REQUEST["enddate"]}</td>
    </tr>
    
    <tr><td colspan=\"2\" style='height:$td_height;'></td></tr>
    <tr style=\"font-weight:bold;\">
        <td style=\"text-align:center;\">Number of Tickets:</td>
        <td style=\"text-align:center;\">Barrels delivered:</td>
    </tr>
    <tr style=\"color:blue;\">
        <td style=\"text-align:center;\">$tot_tickets</td> 
        <td style=\"text-align:center;\">$n_barrels</td>
    </tr>
    <tr><td colspan=\"2\" style='height:$td_height;'></td></tr>
    <tr style=\"font-weight:bold;\">
        <th style=\"font-size:14px;text-align:center; width:205px;\"></th>
        <th style=\"font-size:14px;text-align:center;width:230px;\">Disposal Well:</th>
        <th style=\"font-size:14px;text-align:center;width:230px;\"></th>
    </tr>
    <tr style=\"color:blue;\">
        <td style=\"text-align:center;\"></td>
        <td style=\"text-align:center;\">{$result4[0]["disposal_well"]}</td>
        <td style=\"text-align:center;\"></td>
    </tr>
    
    
    <tr><td colspan=\"2\" style='height:$td_height;'></td></tr>
    <tr style=\"font-weight:bold;\">
    <td>Tickets generated by:</td>
     </tr>   
    </table>
    
    <table>
    <tr>
        <th style=\"font-size:14px;text-align:left; width:125px;\">First Name:</th>
        <th style=\"font-size:14px;text-align:left;width:125px;\">Last Name:</th>
        <th style=\"font-size:14px;text-align:left;width:215px;\">Email:</th>
        <th style=\"font-size:14px;text-align:center;width:100px;\"># of Tickets:</th>
        <th style=\"font-size:14px;text-align:center;width:90px;\"># of Barrels:</th>
    </tr>
	<tr style=\"color:blue;\">
		<td style=\"font-size:14px;text-align:left;width:125px;\">$firstname</td>
		<td style=\"font-size:14px;text-align:left;width:125px;\">$lastname</td>
		<td style=\"font-size:14px;text-align:left;width:215px;\">$email</td>
		<td style=\"font-size:14px;text-align:center;width:100px;\">$ticket_quantity</td>
		<td style=\"font-size:14px;text-align:center;width:90px;\">$barrels_delivered</td>
	</tr>
	<tr><td colspan=\"2\" style='height:$td_height;'></td></tr>
    <tr style=\"font-weight:bold;\">
    <td>The Tickets:</td>
     </tr>   
</table>
<table>
<tr>
        <th style=\"font-size:14px;text-align:left; width:80px;\">Ticket #:</th>
        <th style=\"font-size:14px;text-align:left;width:90px;\">Delivered on:</th>
        <th style=\"font-size:14px;text-align:center;width:60px;\">Barrels:</th>
        
        <th style=\"font-size:14px;text-align:center;width:200px;\">Source Well:</th>
        <th style=\"font-size:14px;text-align:center;width:75px;\">Well File #:</th>
        <th style=\"font-size:14px;text-align:center;width:160px;\">Date Entered:</th>
</tr>
<tr style=\"color:blue;\">
		<td style=\"font-size:13px;text-align:left;width:80px;\">$ticketNumber</td>
		<td style=\"font-size:13px;text-align:left;width:90px;\">$dateDelivered</td>
		<td style=\"font-size:13px;text-align:center;width:60px;\">$barrelsDelivered</td>
		
		<td style=\"font-size:13px;text-align:center;width:200px;\">$sourceWell</td>
		<td style=\"font-size:13px;text-align:center;width:75px;\">$wellFileNumber</td>
		<td style=\"font-size:13px;text-align:center;width:160px;\">$dateCreated</td>
 </tr>";  
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
    
    $filename = "Recon-Report-from {$_REQUEST["startdate"]} to {$_REQUEST["enddate"]} as Batch No. $batch_num.pdf";
    $pdf->Output("../../data/exportpdf/$filename", "F"); 
    
    //$url=$rootScope["RootUrl"]."/api/export/exportTicket.php?startdate=$startDate&enddate=$endDate&token=".$rootScope["SWDApiToken"];
//echo $url;    
//$json=file_get_contents($pdf);
//$list=json_decode($json,true);
$file_=$rootScope["RootPath"]."data/exportpdf/".$filename;
 
 // HERE IS WHERE THE EMAIL SECTION OF THE CODE STARTS
 
    $user_email=$_SESSION["Email"] ;
//if(file_exists($pdf)&&!empty($user_email))
//{
	$mail= new PHPMailer();
    $mail->IsSendmail(); // telling the class to use IsSendmail
	$mail->Host       = "localhost"; // SMTP server
	$mail->SMTPAuth   = false;                  // disable SMTP authentication  
    $mail->SetFrom($rootScope["SupportEmail"], $rootScope["SupportName"]);
    $mail->AddReplyTo($rootScope["SupportEmail"], $rootScope["SupportName"]);
    
	$arr=TokenizeString($user_email);
		
	for($i=0;$i<count($arr);$i++)
	{
		$mail->AddAddress($arr[$i], "");
	}
	//print_r($arr);
	//return;
	$mail->Subject    = "Reconciliation Report";
	$body="<p>The attached file is the Reconciliation Report between {$_REQUEST["startdate"]} and {$_REQUEST["enddate"]}.</p>";
	$mail->MsgHTML($body);
	$mail->AddAttachment($file_ ,$filename);
	
//	if(!$mail->Send()) 
//	{
		//$error="Error: daily ticket and well log export for $startDate-$endDate\r\n\r\n";
		//file_put_contents($rootScope["RootPath"]."data/daily-log.txt",$error,FILE_APPEND);		
//	} 
 // HERE IS WHERE THE EMAIL SECTION OF THE CODE ENDS
	//echo "INSERT INTO recon_report(batch_num, start_filter, end_filter, pdf_filename) VALUES ($batch_num, $start_filter, $end_filter, $filename)";
	$stmt3 = pdo_query( $pdo, "INSERT INTO recon_report(batch_num, start_filter, end_filter, pdf_filename, disposal_well_id) VALUES ($batch_num, '$start_filter', '$end_filter','$filename',{$_REQUEST["SearchDisposalWell"]})",null);
	$result3 = pdo_fetch_array($stmt3);
	
	//}
    $response['code'] = 'success';
    $response['data'] = $filename;
    $response['test'] = $test;
    
	echo json_encode($response);
}
catch(PDOException $ex)
{	
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}
?>