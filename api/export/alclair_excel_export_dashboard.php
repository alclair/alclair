<?php
$dir = realpath(__DIR__);
$dir = str_replace('/api/export', '', $dir);

include_once $dir."/config.inc.php";
include_once $dir."/includes/phpmailer/class.phpmailer.php";

$month=$_REQUEST["month"];
$year=$_REQUEST["year"];

$UserId = $_SESSION["UserId"];
$IsAdmin = $_SESSION["IsAdmin"];
$IsManager = $_SESSION["IsManager"];

$url=$rootScope["RootUrl"]."/api/alclair/excel_export_dashboard.php?Month=$month&Year=$year&UserId=$UserId&IsAdmin=$IsAdmin&IsManager=$IsManager&token=".$rootScope["SWDApiToken"];

$response["test1"] = $url;
//echo json_encode($response);
//exit;

//$url=$rootScope["RootUrl"]."/api/reports/lng_export_data.php?lng_queens=$lng_queens&StartDate=".$_REQUEST["startdate"]."&EndDate=".$_REQUEST["enddate"]."&UserId=$UserId&token=".$rootScope["SWDApiToken"];
//$url=$rootScope["RootUrl"]."/api/export/exportTicket.php?startdate=$startDate&enddate=$endDate&token=".$rootScope["SWDApiToken"];

$json=file_get_contents($url);
$list=json_decode($json,true);
$file_lng=$rootScope["RootPath"]."data/export/excel/".$list["data"];
//$file_lng=$rootScope["RootPath"]."data/export/";

//$url=$rootScope["RootUrl"]."/api/reports/lng_export_data.php";

if(file_exists($file_lng)) 
{
	$mail3= new PHPMailer();
    $mail3->IsSendmail(); // telling the class to use IsSendmail
	$mail3->Host       = "localhost"; // SMTP server
	$mail3->SMTPAuth   = false;                  // disable SMTP authentication  
    $mail3->SetFrom($rootScope["SupportEmail"], "OTIS Dashboard");
    $mail3->AddReplyTo($rootScope["SupportEmail"], "OTIS Dashboard");
    
	//$arr=TokenizeString($emails);
		
	//for($i=0;$i<count($arr);$i++)
	//{
		$mail3->AddAddress($_SESSION["Email"]);
	//}
	//print_r($arr);
	//return;
	$mail3->Subject    = "Excel Export";
	$body3="<p>The attached file is the QC Stat Tracking Report.</p>";
	$mail3->MsgHTML($body3);
	//$mail3->AddAttachment($file_lng, "ZzZzZ-".date("m-d-Y").".xlsx");
	//$mail3->AddAttachment($file_lng, "Step4.xlsx");
	$mail3->AddAttachment($file_lng, "Alclair Audio QC Stat Tracking Report4444-".date("m-d-Y").".xlsx");
	//$mail->AddAttachment($file_disposal, "DailyDisposal.xlsx");

	echo json_encode($response);

	if(!$mail3->Send()) 
	{
		$error="Error: Alclair  Excel document";
		file_put_contents($rootScope["RootPath"]."data/daily-log.txt",$error,FILE_APPEND);		
	} 

} // CLOSE IF STATEMENT  -> if(file_exists($file_lng)&&!empty($emails))

else 
{
	$response['data2'] = 'Not here';
	echo json_encode($response);
}
?>