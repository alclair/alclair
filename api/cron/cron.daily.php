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

$RootUrl="https://otisdev.alclr.co";
$RootPath="/var/www/html/otisdev/";

$url=$rootScope["RootUrl"]."/api/export/emailDailyAlclair.php"; 
//$url=$RootUrl."/api/export/emailDailyAlclair.php"; 
$json=file_get_contents($url);
$list=json_decode($json,true);
$file_alclair=$rootScope["RootPath"]."data/export/excel/".$list["data"];

	$mail3= new PHPMailer();
    $mail3->IsSendmail(); // telling the class to use IsSendmail
	$mail3->Host       = "localhost"; // SMTP server
	$mail3->SMTPAuth   = false;                  // disable SMTP authentication  
    $mail3->SetFrom($rootScope["SupportEmail"], $rootScope["SupportName"]);
    $mail3->AddReplyTo($rootScope["SupportEmail"], $rootScope["SupportName"]);
    
	//$arr=TokenizeString("tyler@alclair.com, marc@alclair.com, scott@alclair.com");
	$arr=TokenizeString("tyler@alclair.com");
	for($i=0;$i<count($arr);$i++)
	{
		$mail3->AddAddress($arr[$i], "");
	}
	
	$mail3->Subject    = "Manufacturing Report";
	$body3="<p>The attached file is an Excel document displaying the which customer's orders have been in a single station for over 1 day.</p>";
	$mail3->MsgHTML($body3);
	$mail3->AddAttachment($file_alclair, "Daily Manufacturing Report ".date("m-d-Y").".xlsx");
	
	if(!$mail3->Send()) 
	{
		$error="Error: Alclair Manufacturing Report Excel document";
		file_put_contents($rootScope["RootPath"]."data/daily-log.txt",$error,FILE_APPEND);		
	} 
	
?>		