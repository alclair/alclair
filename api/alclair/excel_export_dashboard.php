<?php
include_once "../../config.inc.php";
include_once "../../includes/PHPExcel/Classes/PHPExcel.php";

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

//require '/var/www/html/otisdev/vendor/autoload.php';
require $rootScope["RootPath"]."vendor/autoload.php";

if(empty($_SESSION["UserId"])&&$_REQUEST["token"]!=$rootScope["SWDApiToken"])
{
    return;
}
session_start();
if(!empty($_REQUEST["UserId"]))
{
    //return;
    $testing5= $_REQUEST["UserId"];
}
else {
	$testing5="Admin is empty";
	//$testing5=$_SESSION["UserId"] - 1 + 1;
}

$response = array();
$response["code"] = "";
$response["message"] = "";
$response['data'] = null;

try
{	 
	$letter = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X' , 'Y', 'Z',
    							 'AA', 'AB', 'AC', 'AD', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ',
    							  'BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN', 'BO', 'BP', 'BQ', 'BR', 'BS', 'BT', 'BU', 'BV', 'BW', 'BX', 'BY', 'BZ',
    							  'CA', 'CB', 'CC', 'CD', 'CE', 'CF', 'CG', 'CH', 'CI', 'CJ', 'CK', 'CL', 'CM', 'CN', 'CO', 'CP', 'CQ', 'CR', 'CS', 'CT', 'CU', 'CV', 'CW', 'CX', 'CY', 'CZ',
    							  'DA', 'DB', 'DC', 'DD', 'DE', 'DF', 'DG', 'DH', 'DI', 'DJ', 'DK', 'DL', 'DM', 'DN', 'DO', 'DP', 'DQ', 'DR', 'DS', 'DT', 'DU', 'DV', 'DW', 'DX', 'DY', 'DZ',
    							  'EA', 'EB', 'EC', 'ED', 'EE', 'EF', 'EG', 'EH', 'EI', 'EJ', 'EK', 'EL', 'EM', 'EN', 'EO', 'EP', 'EQ', 'ER', 'ES', 'ET', 'EU', 'EV', 'EW', 'EX', 'EY', 'EZ');
    
	
    $year = $_REQUEST['Year'];
    $month = $_REQUEST['Month'];
    
    //$year = "2019";
    //$month = "3";
    
    $mydate=getdate(date("U"));
    $month_number = $mydate["mon"];
    //$month_number = (int)$month_number;
	$month_string = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'); 
	
	// Create new Spreadsheet object
	$spreadsheet = new Spreadsheet();
	//$objPHPExcel = new PHPExcel();
	
	// Set workbook properties
	$spreadsheet->getProperties()->setCreator('Tyler Folsom')
        ->setLastModifiedBy('Tyler Folsom')
        ->setTitle('Dashboard')
        ->setSubject('PhpSpreadsheet')
        ->setDescription('Dashboard Output.')
        ->setKeywords('Microsoft office 2013 php PhpSpreadsheet')
        ->setCategory('Excel');
 
	// Set worksheet title
	$spreadsheet->getActiveSheet()->setTitle('OTIS');
	
	$spreadsheet->setActiveSheetIndex(0)
			->setCellValue("A1", "Alclair Audio") 
			->setCellValue("A2", "QC Summary") 
			->setCellValue("A3",  $year) 
			
			->setCellValue("B4", "Jan") 
			->setCellValue("C4", "Feb") 
			->setCellValue("D4", "Mar")
			->setCellValue("E4", "Apr")
			->setCellValue("F4", "May")
			->setCellValue("G4", "Jun")
			->setCellValue("H4", "Jul")
			->setCellValue("I4", "Aug")
			->setCellValue("J4", "Sep")
			->setCellValue("K4", "Oct")
			->setCellValue("L4", "Nov")
			->setCellValue("M4", "Dec")
			
			->setCellValue("A5", "Shop Days")
			->setCellValue("A6", "Build Rate")
			->setCellValue("A7", "Units Tested")
			->setCellValue("A8", "Units Failed")
			->setCellValue("A9", "First Pass Yield")
			->setCellValue("A10", "First Pass Failure Rate")
			
			->setCellValue("A12", "Avg tested per shop day")
			->setCellValue("A13", "Shop days lost to QC")
		
			->setCellValue("A15", "Failure Mode Count")
			->setCellValue("A16", "Sound")
			->setCellValue("A17", "Shell")
			->setCellValue("A18", "Jack")
			->setCellValue("A19", "Faceplate")
			->setCellValue("A20", "Ports")
			
			->setCellValue("A23", "Failure Mode % of Total")
			->setCellValue("A24", "Sound")
			->setCellValue("A25", "Shell")
			->setCellValue("A26", "Jack")
			->setCellValue("A27", "Faceplate")
			->setCellValue("A28", "Ports")
			->setCellValue("A31", "Failures Per");

	
//for ($ind = 1; $ind <= $month_number; $ind++) { 
for ($ind = 1; $ind <= $month_number; $ind++) { 	
	   
   $x = $month_string[$ind-1]; 
//SHOP DAYS
$query = "SELECT DISTINCT to_char(t1.qc_date,'dd') AS created
              FROM qc_form as t1
              WHERE to_char(qc_date,'yyyy') = :year AND to_char(qc_date,'MM') = :month
              GROUP BY created
              ORDER BY created ASC";
$stmt = pdo_query( $pdo, $query, array(":year"=>$year, ":month"=>$x));
$shop_days = pdo_fetch_all( $stmt );
$num_shop_days = count($shop_days);

//BUILD RATE
//Scott has this in his head

//UNITS TESTED
$query =  "SELECT pass_or_fail, ( SELECT COUNT(pass_or_fail) ) AS num_tested
              FROM qc_form as t1
              WHERE to_char(qc_date,'yyyy') = :year AND to_char(qc_date,'MM') = :month AND build_type_id = 1 AND pass_or_fail !='IMPORTED'
              GROUP BY pass_or_fail";
$stmt = pdo_query( $pdo, $query, array(":year"=>$year, ":month"=>$x));
$units_tested = pdo_fetch_all( $stmt );   
$num_units_tested = 0;
for ($y = 0; $y < count($units_tested); $y++) {
	$num_units_tested = $num_units_tested + $units_tested[$y]["num_tested"];
}           

//UNITS FAILED
$query =  "SELECT pass_or_fail, ( SELECT COUNT(pass_or_fail) ) AS num_failed
              FROM qc_form as t1
              WHERE to_char(qc_date,'yyyy') = :year AND to_char(qc_date,'MM') = :month AND build_type_id = 1 AND pass_or_fail ='FAIL'
              GROUP BY pass_or_fail";
$stmt = pdo_query( $pdo, $query, array(":year"=>$year, ":month"=>$x));
$units_failed = pdo_fetch_all( $stmt );  
$num_units_failed = $units_failed[0]["num_failed"];

$avg_tested_per_shop_day = $num_units_tested / $num_shop_days;
$shop_days_lost_to_qc = $num_units_failed / $avg_tested_per_shop_day;
    
$query = "SELECT id, sound_signature, sound_balanced, to_char(qc_date,'dd') AS created FROM qc_form WHERE to_char(qc_date,'yyyy') = :year AND to_char(qc_date,'MM') = :month AND build_type_id = 1 AND pass_or_fail != 'IMPORTED' AND ( sound_signature = 'FALSE' OR sound_signature IS NULL OR sound_balanced = 'FALSE' OR sound_balanced IS NULL)";
$stmt = pdo_query( $pdo, $query, array(":year"=>$year, ":month"=>$x));
$result = pdo_fetch_all( $stmt );
$sound_failures = pdo_rows_affected( $stmt );

$query = "SELECT id, shells_defects, shells_colors, shells_faced_down, shells_label, shells_edges, shells_shine, shells_canal, to_char(qc_date,'dd') AS created FROM qc_form WHERE to_char(qc_date,'yyyy') = :year AND to_char(qc_date,'MM') = :month AND build_type_id = 1 AND pass_or_fail != 'IMPORTED' AND ( 
shells_defects = 'FALSE' OR shells_colors = 'FALSE' OR shells_faced_down = 'FALSE' OR shells_label = 'FALSE' OR shells_edges = 'FALSE' OR shells_shine = 'FALSE' OR shells_canal = 'FALSE' OR 
shells_defects IS NULL OR shells_colors IS NULL OR shells_faced_down IS NULL OR shells_label IS NULL OR shells_edges IS NULL OR shells_shine IS NULL OR shells_canal IS NULL)";
$stmt = pdo_query( $pdo, $query, array(":year"=>$year, ":month"=>$x));
$result = pdo_fetch_all( $stmt );
$shell_failures = pdo_rows_affected( $stmt );

//SELECT * FROM qc_form WHERE qc_date >= '01/01/2018' AND qc_date < '2/1/2018'
//UPDATE qc_form SET shells_canal = TRUE WHERE qc_date <= '2/1/2018'

$query = "SELECT id, jacks_location, jacks_debris, jacks_cable, to_char(qc_date,'dd') AS created FROM qc_form WHERE to_char(qc_date,'yyyy') = :year AND to_char(qc_date,'MM') = :month AND build_type_id = 1 AND pass_or_fail != 'IMPORTED' AND ( 
jacks_location = 'FALSE' OR jacks_debris = 'FALSE' OR jacks_cable  = 'FALSE' OR
jacks_location  IS NULL OR jacks_debris  IS NULL OR jacks_cable IS NULL)";
$stmt = pdo_query( $pdo, $query, array(":year"=>$year, ":month"=>$x));
$result = pdo_fetch_all( $stmt );
$jack_failures = pdo_rows_affected( $stmt );

$query = "SELECT id, faceplate_seams, faceplate_shine, faceplate_colors, faceplate_rounded, to_char(qc_date,'dd') AS created FROM qc_form WHERE to_char(qc_date,'yyyy') = :year AND to_char(qc_date,'MM') = :month AND build_type_id = 1 AND pass_or_fail != 'IMPORTED' AND ( 
faceplate_seams = 'FALSE' OR faceplate_shine = 'FALSE' OR faceplate_colors = 'FALSE' OR faceplate_rounded = 'FALSE' OR
faceplate_seams IS NULL OR faceplate_shine IS NULL OR faceplate_colors IS NULL OR faceplate_rounded IS NULL)";
$stmt = pdo_query( $pdo, $query, array(":year"=>$year, ":month"=>$x));
$result = pdo_fetch_all( $stmt );
$faceplate_failures = pdo_rows_affected( $stmt );

$query = "SELECT id, jacks_location, jacks_debris, jacks_cable, to_char(qc_date,'dd') AS created FROM qc_form WHERE to_char(qc_date,'yyyy') = :year AND to_char(qc_date,'MM') = :month AND build_type_id = 1 AND pass_or_fail != 'IMPORTED' AND ( 
ports_cleaned  = 'FALSE' OR ports_smooth = 'FALSE' OR
ports_cleaned IS NULL OR ports_smooth IS NULL)";
$stmt = pdo_query( $pdo, $query, array(":year"=>$year, ":month"=>$x));
$result = pdo_fetch_all( $stmt );
$port_failures = pdo_rows_affected( $stmt );

/*echo "Sound " . $sound_failures . "</br>";
echo "Shell " . $shell_failures . "</br>";
echo "Jack " . $jack_failures . "</br>";
echo "Face " . $faceplate_failures . "</br>";
echo "Port " . $port_failures . "</br>";*/

$sum_failure_mode_count = $sound_failures + $shell_failures + $jack_failures + $faceplate_failures + $port_failures;

if($sum_failure_mode_count == 0) {
	$sound_failure_percent_total = 0;
	$shell_failure_percent_total = 0;
	$jack_failure_percent_total = 0;
	$faceplate_failure_percent_total = 0;
	$port_failure_percent_total = 0;

} else {
	$sound_failure_percent_total = round($sound_failures / $sum_failure_mode_count*100);
	$shell_failure_percent_total = round($shell_failures / $sum_failure_mode_count * 100);
	$jack_failure_percent_total = round($jack_failures / $sum_failure_mode_count * 100);
	$faceplate_failure_percent_total = round($faceplate_failures / $sum_failure_mode_count * 100);
	$port_failure_percent_total = round($port_failures / $sum_failure_mode_count * 100);
}


$sum_failure_percent_total = $sound_failure_percent_total + $shell_failure_percent_total + $jack_failure_percent_total + $faceplate_failure_percent_total + $port_failure_percent_total;

if($num_units_failed == 0) {
	$failure_per = 0;
} else {
	$failure_per = $sum_failure_mode_count / $num_units_failed;	
}


	//$colors_key = array('FFFFFF', '00B050', 'B1A0C7', 'FFFF00', 'FF5050', '7EB4E2');
	//$colors_h1 = array('FABF8F', 'DA9694', '92CDDC', '538DD5', 'A6A6A6');
	//$colors_h2 = array('FDE9D9', 'F2DCDB', 'DAEEF3', 'C5D9F1', 'D9D9D9');							  
	
					
	// SET WIDTH, BOLD & FONT SIZE
	$spreadsheet->getActiveSheet()->getColumnDimension("A")->setWidth(20);
	$spreadsheet->getActiveSheet(0)->getStyle('A1:A3')->getFont()->setBold(true)->setSize(12);	
	
	// VERTICALLY & HORIZONTALLY CENTER
	$spreadsheet->setActiveSheetIndex(0)->getStyle("A1:A3")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$spreadsheet->setActiveSheetIndex(0)->getStyle("A1:A3")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		
		$fisrt_pass_failure_rate = $num_units_failed / $num_units_tested;
		$first_pass_yield = round((1 - $fisrt_pass_failure_rate)*100)."%";
		$spreadsheet->setActiveSheetIndex(0)
			->setCellValue($letter[$ind]."5", $num_shop_days)
			->setCellValue($letter[$ind]."6", " ")
			->setCellValue($letter[$ind]."7", $num_units_tested)
			->setCellValue($letter[$ind]."8", $num_units_failed)
			->setCellValue($letter[$ind]."9", $first_pass_yield)
			->setCellValue($letter[$ind]."10", round($fisrt_pass_failure_rate*100)."%")
			->setCellValue($letter[$ind]."12", $avg_tested_per_shop_day)
			->setCellValue($letter[$ind]."13", $shop_days_lost_to_qc)
			->setCellValue($letter[$ind]."16", $sound_failures)
			->setCellValue($letter[$ind]."17", $shell_failures)
			->setCellValue($letter[$ind]."18", $jack_failures)
			->setCellValue($letter[$ind]."19", $faceplate_failures)
			->setCellValue($letter[$ind]."20", $port_failures)
			->setCellValue($letter[$ind]."21", $sum_failure_mode_count)
			->setCellValue($letter[$ind]."24", $sound_failure_percent_total."%")
			->setCellValue($letter[$ind]."25", $shell_failure_percent_total."%")
			->setCellValue($letter[$ind]."26", $jack_failure_percent_total."%")
			->setCellValue($letter[$ind]."27", $faceplate_failure_percent_total."%")
			->setCellValue($letter[$ind]."28", $port_failure_percent_total."%")
			->setCellValue($letter[$ind]."29", $sum_failure_percent_total."%")
			->setCellValue($letter[$ind]."31", $failure_per);
				
	$spreadsheet->getActiveSheet()->getStyle($letter[$ind].'9:'.$letter[$ind].'10')->getNumberFormat()->applyFromArray( 
       array( 'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));	
    	$spreadsheet->getActiveSheet()->getStyle($letter[$ind].'24:'.$letter[$ind].'29')->getNumberFormat()->applyFromArray( 
       array( 'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));	   
    	$spreadsheet->getActiveSheet()->getStyle($letter[$ind]."12:".$letter[$ind]."13")->getNumberFormat()->setFormatCode('0.0');   
    $spreadsheet->setActiveSheetIndex(0)->getStyle($letter[$ind]."1:".$letter[$ind]."35")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$spreadsheet->setActiveSheetIndex(0)->getStyle($letter[$ind]."1:".$letter[$ind]."35")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$spreadsheet->getActiveSheet(0)->getStyle($letter[$ind]."20")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$spreadsheet->getActiveSheet(0)->getStyle($letter[$ind]."28")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$spreadsheet->getActiveSheet()->getStyle($letter[$ind]."31")->getNumberFormat()->setFormatCode('0.00');   
}  // CLOSE FOR LOOP

	$filename = "Export-Dashboard-Data-".date("m-d-Y").".xlsx";
	//echo $filename;
	//echo $filename;
    //$filename = "hello.xlsx";
    //$objWriter->save("../../data/export/$filename");
    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save("../../data/export/excel/$filename");

    $response['code'] = 'success';
    $response['data'] = $filename;
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}
?>