<?php
$dir = realpath(__DIR__);
$dir = str_replace('/api/cron', '', $dir);
include_once $dir."/config.inc.php";
include_once $dir."/includes/phpmailer/class.phpmailer.php";
//1. send monthly export, tickets and welllogs on 1st day of the month
$currentMonthFirstDate = date("Y-m-01");
$date=date_create($currentMonthFirstDate);
date_add($date,date_interval_create_from_date_string("-1 month"));
$lastMonthFirstDate=date_format($date,"Y-m-d");
$date=date_create($currentMonthFirstDate);
date_add($date,date_interval_create_from_date_string("-1 day"));
$lastMonthLastDate=date_format($date,"Y-m-d");

//echo $date->format('Y-m-d H:i:s');
$startDate=$lastMonthFirstDate;
$endDate=$lastMonthLastDate;
$url=$rootScope["RootUrl"]."/api/export/exportTicket.php?startdate=$startDate&enddate=$endDate&token=".$rootScope["SWDApiToken"];
$json=file_get_contents($url);
$list=json_decode($json,true);
$file_ticket=$rootScope["RootPath"]."data/export/".$list["data"];

$url=$rootScope["RootUrl"]."/api/export/exportWellLog.php?startdate=$startDate&enddate=$endDate&token=".$rootScope["SWDApiToken"];
$json=file_get_contents($url);
$list=json_decode($json,true);
$file_welllog=$rootScope["RootPath"]."data/export/".$list["data"];

$url=$rootScope["RootUrl"]."/api/cron/get.php";
//echo $url;    
$json=file_get_contents($url);
$list=json_decode($json,true);
$emails=$list["monthly_emails"];

if(file_exists($file_ticket)&&file_exists($file_welllog))
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
	
	$mail->Subject    = "SWD monthly export for Tickets and Well Logs";
	$body="<p>The attached files are SWD monthly ticket and well log export between $startDate and $endDate.</p>";
	$mail->MsgHTML($body);
	$mail->AddAttachment($file_ticket, "MonthlyTicket.xlsx");
	$mail->AddAttachment($file_welllog, "MonthlyWellLog.xlsx");
			
	if(!$mail->Send()) 
	{
		$error="Error: monthly ticket and well log export for $startDate-$endDate\r\n\r\n";
		file_put_contents($rootScope["RootPath"]."data/monthly-log.txt",$error,FILE_APPEND);		
	} 
	
}

?>