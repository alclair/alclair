<?php
$dir = realpath(__DIR__);
$dir = str_replace('/api/cron', '', $dir);
include_once $dir."/config.inc.php";
include_once $dir."/includes/phpmailer/class.phpmailer.php";
//1. send daily export, tickets and welllogs
$yesterday = new DateTime('-1 day');
//echo $date->format('Y-m-d H:i:s');
$startDate=$yesterday->format("Y-m-d");
$endDate=$yesterday->format("Y-m-d");

if($rootScope["SWDCustomer"] != "lng" && $rootScope["SWDCustomer"] != "alclair") {  

$url=$rootScope["RootUrl"]."/api/export/exportTicket.php?startdate=$startDate&enddate=$endDate&token=".$rootScope["SWDApiToken"];
//echo $url;    
$json=file_get_contents($url);
$list=json_decode($json,true);
$file_ticket=$rootScope["RootPath"]."data/export/".$list["data"];

// TYLER CODE - Code is here for Horizon's "Daily Disposal Total
$url=$rootScope["RootUrl"]."/api/export/exportDailyDisposalTotal_Olsen.php?startdate=$startDate&enddate=$endDate&token=".$rootScope["SWDApiToken"];
$json=file_get_contents($url);
$list=json_decode($json,true);
$file_disposal=$rootScope["RootPath"]."data/export/".$list["data"];

$url=$rootScope["RootUrl"]."/api/export/exportDailyDisposalTotal_Irgens.php?startdate=$startDate&enddate=$endDate&token=".$rootScope["SWDApiToken"];
$json=file_get_contents($url);
$list=json_decode($json,true);
$file_disposal2=$rootScope["RootPath"]."data/export/".$list["data"];
// Tyler Code ends here

//echo $url;   
$url=$rootScope["RootUrl"]."/api/export/exportWellLog.php?startdate=$startDate&enddate=$endDate&token=".$rootScope["SWDApiToken"]; 
$json=file_get_contents($url);
$list=json_decode($json,true);
$file_welllog=$rootScope["RootPath"]."data/export/".$list["data"];

} elseif($rootScope["SWDCustomer"] == "alclair") {

$url=$rootScope["RootUrl"]."/api/export/emailDailyAlclair.php?token=".$rootScope["SWDApiToken"]; 
$json=file_get_contents($url);
$list=json_decode($json,true);
$file_alclair=$rootScope["RootPath"]."data/export/".$list["data"];

} else {

$url=$rootScope["RootUrl"]."/api/export/emailLNG.php?token=".$rootScope["SWDApiToken"]; 
$json=file_get_contents($url);
$list=json_decode($json,true);
$file_lng=$rootScope["RootPath"]."data/export/".$list["data"];

}

$url=$rootScope["RootUrl"]."/api/cron/get.php";
//echo $url;    
$json=file_get_contents($url);
$list=json_decode($json,true);
$emails=$list["daily_emails"];

if($rootScope["SWDCustomer"] == "alclair") {
	
	$mail3= new PHPMailer();
    $mail3->IsSendmail(); // telling the class to use IsSendmail
	$mail3->Host       = "localhost"; // SMTP server
	$mail3->SMTPAuth   = false;                  // disable SMTP authentication  
    $mail3->SetFrom($rootScope["SupportEmail"], $rootScope["SupportName"]);
    $mail3->AddReplyTo($rootScope["SupportEmail"], $rootScope["SupportName"]);
    
	$arr=TokenizeString("tyler@alclair.com, marc@alclair.com, scott@alclair.com");
	//$arr=TokenizeString("tyler@alclair.com");
	for($i=0;$i<count($arr);$i++)
	{
		$mail3->AddAddress($arr[$i], "");
	}
	
	//print_r($arr);
	//return;
	$mail3->Subject    = "Manufacturing Report";
	$body3="<p>The attached file is an Excel document displaying the which customer's orders have been in a single station for over 1 day.</p>";
	$mail3->MsgHTML($body3);
	$mail3->AddAttachment($file_alclair, "Daily Manufacturing Report ".date("m-d-Y").".xlsx");
	
	if(!$mail3->Send()) 
	{
		$error="Error: Alclair Manufacturing Report Excel document";
		file_put_contents($rootScope["RootPath"]."data/daily-log.txt",$error,FILE_APPEND);		
	} 
	
}


if($rootScope["SWDCustomer"] != "lng" && $rootScope["SWDCustomer"] != "alclair") { 
	
if(file_exists($file_ticket)&&file_exists($file_welllog)&&!empty($emails))
{
	$mail= new PHPMailer();
    $mail->IsSendmail(); // telling the class to use IsSendmail
	$mail->Host       = "localhost"; // SMTP server
	$mail->SMTPAuth   = false;                  // disable SMTP authentication  
    $mail->SetFrom($rootScope["SupportEmail"], $rootScope["SupportName"]);
    $mail->AddReplyTo($rootScope["SupportEmail"], $rootScope["SupportName"]);
    
	$arr=TokenizeString($emails);
		
	for($i=0;$i<count($arr);$i++)
	{
		$mail->AddAddress($arr[$i], "");
	}
	//print_r($arr);
	//return;
	$mail->Subject    = "SWD Daily export for Tickets and Well Logs";
	// ON NOVEMBER 11, 2016 Josh sent an email requesting to remove Daily Well Logs from MBI's cron
if ( $rootScope["SWDCustomer"] != "horizon"  ) {
	$body="<p>The attached files are SWD daily ticket and well log export between $startDate and $endDate.</p>";  
	$mail->MsgHTML($body);
	$mail->AddAttachment($file_ticket, "DailyTicket.xlsx");
	$mail->AddAttachment($file_welllog, "DailyWellLog.xlsx");
	//$mail->AddAttachment($file_disposal, "DailyDisposal.xlsx");
} else {
	$body="<p>The attached file is SWD daily ticket export between $startDate and $endDate.</p>";
	$mail->MsgHTML($body);
	$mail->AddAttachment($file_ticket, "DailyTicket.xlsx");
}
	if(!$mail->Send()) 
	{
		$error="Error: daily ticket and well log export for $startDate-$endDate\r\n\r\n";
		file_put_contents($rootScope["RootPath"]."data/daily-log.txt",$error,FILE_APPEND);		
	} 
}

//TYLER CODE - Horizon specific code
$json=file_get_contents($url);
$list=json_decode($json,true);
$emails=$list["daily_disposal_total"];

if(file_exists($file_disposal)&&!empty($emails))
{
	$mail= new PHPMailer();
    $mail->IsSendmail(); // telling the class to use IsSendmail
	$mail->Host       = "localhost"; // SMTP server
	$mail->SMTPAuth   = false;                  // disable SMTP authentication  
    $mail->SetFrom($rootScope["SupportEmail"], $rootScope["SupportName"]);
    $mail->AddReplyTo($rootScope["SupportEmail"], $rootScope["SupportName"]);
    
	$arr=TokenizeString($emails);
		
	for($i=0;$i<count($arr);$i++)
	{
		$mail->AddAddress($arr[$i], "");
	}
	//print_r($arr);
	//return;
	$mail->Subject    = "HORIZON Oilfield Services - Load Summary";
	$body="<p>The attached file is the MBI Daily Load Summary for $startDate.</p>";
	$mail->MsgHTML($body);
	$mail->AddAttachment($file_disposal, "DailyDisposalTotal (KILLDEER-MBI).xlsx");
	
	if(!$mail->Send()) 
	{
		$error="Error: daily ticket and well log export for $startDate-$endDate\r\n\r\n";
		file_put_contents($rootScope["RootPath"]."data/daily-log.txt",$error,FILE_APPEND);		
	} 
	
	$mail2= new PHPMailer();
    $mail2->IsSendmail(); // telling the class to use IsSendmail
	$mail2->Host       = "localhost"; // SMTP server
	$mail2->SMTPAuth   = false;                  // disable SMTP authentication  
    $mail2->SetFrom($rootScope["SupportEmail"], $rootScope["SupportName"]);
    $mail2->AddReplyTo($rootScope["SupportEmail"], $rootScope["SupportName"]);
    
	$arr2=TokenizeString($emails);
		
	for($i=0;$i<count($arr);$i++)
	{
		$mail2->AddAddress($arr2[$i], "");
	}
	//print_r($arr);
	//return;
	$mail2->Subject    = "HORIZON Oilfield Services - Load Summary";
	$body2="<p>The attached file is the MBI Daily Load Summary for $startDate.</p>";
	$mail2->MsgHTML($body2);
	$mail2->AddAttachment($file_disposal2, "DailyDisposalTotal (IRGENS-MBI).xlsx");
	
	if(!$mail2->Send()) 
	{
		$error="Error: daily ticket and well log export for $startDate-$endDate\r\n\r\n";
		file_put_contents($rootScope["RootPath"]."data/daily-log.txt",$error,FILE_APPEND);		
	} 
}

} else {

//echo file_exists($file_lng);
//echo !empty($emails);
//echo $emails;
if(file_exists($file_lng)&&!empty($emails))
{
	$mail3= new PHPMailer();
    $mail3->IsSendmail(); // telling the class to use IsSendmail
	$mail3->Host       = "localhost"; // SMTP server
	$mail3->SMTPAuth   = false;                  // disable SMTP authentication  
    $mail3->SetFrom($rootScope["SupportEmail"], $rootScope["SupportName"]);
    $mail3->AddReplyTo($rootScope["SupportEmail"], $rootScope["SupportName"]);
    
	$arr=TokenizeString($emails);
		
	for($i=0;$i<count($arr);$i++)
	{
		$mail3->AddAddress($arr[$i], "");
	}
	//print_r($arr);
	//return;
	$mail3->Subject    = "Queen Readings";
	$body3="<p>The attached file is an Excel document displaying the current readings for each Queen trailer.</p>";
	$mail3->MsgHTML($body3);
	$mail3->AddAttachment($file_lng, "Export-Queen-Readings-".date("m-d-Y").".xlsx");
	//$mail->AddAttachment($file_disposal, "DailyDisposal.xlsx");
	
	if(!$mail3->Send()) 
	{
		$error="Error: Queen readings Excel document";
		file_put_contents($rootScope["RootPath"]."data/daily-log.txt",$error,FILE_APPEND);		
	} 

} // CLOSE IF STATEMENT  -> if(file_exists($file_lng)&&!empty($emails))

} // END ELSE STATEMENT
?>		