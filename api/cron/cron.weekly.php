<?php
$dir = realpath(__DIR__);
$dir = str_replace('/api/cron', '', $dir);
include_once $dir."/config.inc.php";
include_once $dir."/includes/phpmailer/class.phpmailer.php";
//1. send weekly export, tickets and welllogs on Sunday morning,last Sunday to last Saturday
$lastSunday = date('Y-m-d', strtotime('last Sunday'));
$lastSaturday=date('Y-m-d', strtotime('last Saturday'));
if($lastSunday>$lastSaturday)
{	
	$date=date_create($lastSunday);
	date_add($date,date_interval_create_from_date_string("-7 days"));
	$lastSunday=date_format($date,"Y-m-d");
}
//echo $date->format('Y-m-d H:i:s');
$startDate=$lastSunday;
$endDate=$lastSaturday;
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
$emails=$list["weekly_emails"];

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
	$mail->Subject    = "SWD Weekly export for Tickets and Well Logs";
	$body="<p>The attached files are SWD weekly ticket and well log export between $startDate and $endDate.</p>";
	$mail->MsgHTML($body);
	$mail->AddAttachment($file_ticket, "WeeklyTicket.xlsx");
	$mail->AddAttachment($file_welllog, "WeeklyWellLog.xlsx");
			
	if(!$mail->Send()) 
	{
		$error="Error: weekly ticket and well log export for $startDate-$endDate\r\n\r\n";
		file_put_contents($rootScope["RootPath"]."data/weekly-log.txt",$error,FILE_APPEND);		
	} 
	
}

?>