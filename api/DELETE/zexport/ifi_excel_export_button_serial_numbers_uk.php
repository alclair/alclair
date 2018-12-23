<?php
$dir = realpath(__DIR__);
$dir = str_replace('/api/export', '', $dir);
include_once $dir."/config.inc.php";
include_once $dir."/includes/phpmailer/class.phpmailer.php";

$category_id=$_REQUEST["category_id"];
$product_id=$_REQUEST["product_id"];
$status=$_REQUEST["status_value"];
$startdate = $_REQUEST["StartDate"];
$enddate = $_REQUEST["EndDate"];
$UserId = $_SESSION["UserId"];
$IsAdmin = $_SESSION["IsAdmin"];
$IsManager = $_SESSION["IsManager"];

$url=$rootScope["RootUrl"]."/api/ifi_uk/ifi_excel_export_serial_numbers_uk.php?category_id=$category_id&product_id=$product_id&status=$status&UserId=$UserId&IsAdmin=$IsAdmin&IsManager=$IsManager&token=".$rootScope["SWDApiToken"];

//$url=$rootScope["RootUrl"]."/api/reports/lng_export_data.php?lng_queens=$lng_queens&StartDate=".$_REQUEST["startdate"]."&EndDate=".$_REQUEST["enddate"]."&UserId=$UserId&token=".$rootScope["SWDApiToken"];
//$url=$rootScope["RootUrl"]."/api/export/exportTicket.php?startdate=$startDate&enddate=$endDate&token=".$rootScope["SWDApiToken"];

$json=file_get_contents($url);
$list=json_decode($json,true);
$file_lng=$rootScope["RootPath"]."data/export/".$list["data"];
//$file_lng=$rootScope["RootPath"]."data/export/";

//$url=$rootScope["RootUrl"]."/api/reports/lng_export_data.php";

if(file_exists($file_lng)) 
{
	$mail3= new PHPMailer();
    $mail3->IsSendmail(); // telling the class to use IsSendmail
	$mail3->Host       = "localhost"; // SMTP server
	$mail3->SMTPAuth   = false;                  // disable SMTP authentication  
    $mail3->SetFrom($rootScope["SupportEmail"], $rootScope["SupportName"]);
    $mail3->AddReplyTo($rootScope["SupportEmail"], $rootScope["SupportName"]);
    
	//$arr=TokenizeString($emails);
		
	//for($i=0;$i<count($arr);$i++)
	//{
		$mail3->AddAddress($_SESSION["Email"]);
	//}
	//print_r($arr);
	//return;
	$mail3->Subject    = "Excel Export";
	$body3="<p>The attached file is an Excel document displaying the data from the log history.</p>";
	$mail3->MsgHTML($body3);
	//$mail3->AddAttachment($file_lng, "ZzZzZ-".date("m-d-Y").".xlsx");
	//$mail3->AddAttachment($file_lng, "Step4.xlsx");
	$mail3->AddAttachment($file_lng, "iFi audio Excel Export-".date("m-d-Y").".xlsx");
	//$mail->AddAttachment($file_disposal, "DailyDisposal.xlsx");
	$response['data2'] = $_REQUEST["status_value"];
	echo json_encode($response);

	if(!$mail3->Send()) 
	{
		$error="Error: iFi audio Excel document";
		file_put_contents($rootScope["RootPath"]."data/daily-log.txt",$error,FILE_APPEND);		
	} 

} // CLOSE IF STATEMENT  -> if(file_exists($file_lng)&&!empty($emails))

else 
{
	$response['data2'] = 'Not here';
	echo json_encode($response);
}