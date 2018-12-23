<?php
function pdo_query($pdo,$query,$params,$debug=0)
{    
	$log=false;
	if(strpos($query,"applog")===false)
	{
		if(strpos(strtolower($query),"delete")!==false||
		strpos(strtolower($query),"insert")!==false||
		strpos(strtolower($query),"update")!==false)
		{
			$log=true;
		}
	}
    if($debug==1)
    {
        print_r($params);
    }
    $stmt=$pdo->prepare($query);
	if(!is_null($params))
	{
        while(list($key,$value)=each($params))
        {      
            $stmt->bindValue($key,$value);
            if($debug==1||$log==true)
            {
                if(empty($value))
                {
                    $value="null";
                }
                else
                {
                    $value="'".str_replace("'","''",$value)."'";
					
                }
                $query=str_replace($key,$value,$query);            
            }
        }
	}
    $stmt->execute();
    if($debug==1)
    {
		//file_put_contents("/home2/caraburo/www/swd/api/log.txt",$query);
    }
	
    if($log==true) //debug don't need to log
    {
        log_query ($query,$_REQUEST["REQUEST_URI"]);
    }
    return $stmt;
}


function pdo_rows_affected($stmt)
{
    return $stmt->rowCount();
}

function pdo_fetch_array($stmt,$style=null)
{
    return $stmt->fetch($style);
}

function pdo_fetch_all($stmt,$style=null)
{
	return $stmt->fetchAll($style);
}

function pdo_errors()
{
    global $pdo;
    print_r($pdo->errorInfo());
}

function pdo_commit($pdo)
{
    $pdo->exec("COMMIT");
}

function pdo_rollback($pdo)
{
    $pdo->exec("ROLLBACK TRAN");
}

function pdo_begin_transaction($pdo)
{
    $pdo->exec("BEGIN TRAN");
}
function array_copy($arr1,&$arr2)
{
	foreach($arr1 as $key=>$value)
	{
		$arr2[$key]=$value;
	}
}
function log_query($query,$page)
{
    global $pdo;
    
    $by=0;
    if(!empty($_SESSION['fantasy_member_id']))
    {
        $by=$_SESSION['fantasy_member_id'];
    }
	
	//$log_query="insert into fantasylig_applog (Query,QueryDatetime,QueryBy,PageName) values(:Query,now(),'$by',:PageName)";
    
    // $stmt=pdo_query($pdo,$log_query,array(":Query"=>$query,":PageName"=>$page));   
}

function html_escape($str)
{
    return strip_tags($str);
}
function encrypt($text,$encrypt_key)
{
	if(empty($text)) return null;
    
    return @base64_encode(mcrypt_encrypt(MCRYPT_BLOWFISH, $encrypt_key, $text, MCRYPT_MODE_CFB));
}
function decrypt($text,$encrypt_key)
{
	if(empty($text)) return null;
    
    return @mcrypt_decrypt(MCRYPT_BLOWFISH, $encrypt_key, base64_decode($text), MCRYPT_MODE_CFB);
}
function EscapeToken($token)
{
    $token=str_replace("=","MEEM",$token);
	$token=str_replace("+","PLUS",$token);
	$token=str_replace("/","SLASH",$token);
	$token=str_replace("&","AMP",$token);
    return $token;
}
function UnescapeToken($token)
{
    $token=str_replace("MEEM","=",$token);
	$token=str_replace("PLUS","+",$token);
	$token=str_replace("SLASH","/",$token);
	$token=str_replace("AMP","&",$token);
    return $token;
}
function GetActionFile($controller=null,$action=null)
{	
	global $rootScope;
	
	if(!isset($controller)||empty($controller))
	{
		$controller=$rootScope["Controller"];	
	}	
		if(!isset($action)||empty($action))
	{
		$action=$rootScope["Action"];
	}	
	$controller=strtolower($controller);	
	$action=strtolower($action);	
	$filePath= $rootScope["RootPath"]."/controllers/".$controller."/".$action.".action.php";
	if(!file_exists($filePath))
	{
		return $rootScope["RootPath"]."controllers/home/error.action.php";;
	}
	return $rootScope["RootPath"]."/controllers/".$controller."/".$action.".action.php";
}
function GetViewFile($controller=null,$action=null)
{
	global $rootScope;
	
	if(!isset($controller)||empty($controller))
	{
		$controller=$rootScope["Controller"];		
	}	
	if(!isset($action)||empty($action))
	{
		$action=$rootScope["Action"];		
	}
	$controller=strtolower($controller);	
	$action=strtolower($action);	
	return $rootScope["RootPath"]."views/".$controller."/".$action.".view.php";
}
function ParseDate($str)
{
	if(strtotime($str)!==false)
	{
		return date("Y-m-d",strtotime($str));
	}
	else
	{
		return null;
	}
}
function GetCurrentWeekStart()
{	
	return date("Y-m-d",strtotime('monday this week'));
}
function GetCurrentWeekEnd()
{
	return date("Y-m-d",strtotime("sunday this week"));
}
function GetPreviousWeekStart()
{
	return date("Y-m-d",strtotime('monday last week'));
}
function GetPreviousWeekEnd()
{
	return date("Y-m-d",strtotime("sunday last week"));
}

function GetDefaultRate($disposal_well_id, $water_type_id)
{
	global $pdo;
	$query="select barrel_rate from ticket_tracker_defaultrate where disposal_well_id=:disposal_well_id and water_type_id=:water_type_id";
	$params=array(":disposal_well_id"=>$disposal_well_id, ":water_type_id"=>$water_type_id);
	$stmt=pdo_query($pdo,$query,$params);
	$row=pdo_fetch_array($stmt);
	return $row[0];
}
function GetBarrelRate($disposal_well_id, $water_type_id, $source_well_id,$billto_operator_id, $billto_trucking_company_id)
{
	global $pdo;
	$query="select barrel_rate from ticket_tracker_rate where disposal_well_id=:disposal_well_id and water_type_id=:water_type_id 
	source_well_id=:source_well_id 
	and trucking_company_id=:trucking_company_id and billto_option='trucking company'";
	$params=array(":disposal_well_id"=>$disposal_well_id, 
	":water_type_id"=>$water_type_id,
	":trucking_company_id"=>$billto_trucking_company_id);
	$stmt=pdo_query($pdo,$query,$params);
	$row=pdo_fetch_array($stmt);
	if(!empty($row[0]))
	{
		return $row[0];
	}
	else
	{
		$query="select barrel_rate from ticket_tracker_rate where disposal_well_id=:disposal_well_id and water_type_id=:water_type_id 
		and source_well_id=:source_well_id  and
		billto_operator_id=:billto_operator_id and billto_option='operator'";
		$params=array(":disposal_well_id"=>$disposal_well_id, 
		":water_type_id"=>$water_type_id,
		":billto_operator_id"=>$billto_operator_id);
		$stmt=pdo_query($pdo,$query,$params);
		$row=pdo_fetch_array($stmt);
		
		return $row[0];
	}
}
function TokenizeString($input)
{
	$return=array();
	$input=str_replace("\r\n","|",$input);
	$input=str_replace("\n","|",$input);
	$input=str_replace(",","|",$input);
	$input=str_replace(";","|",$input);
	$tmp=explode("|",$input);
	//print_r($tmp);
	for($i=0;$i<count($tmp);$i++)
	{
		if(!empty($tmp[$i]))
		{
			$return[]=$tmp[$i];
		}
	}
	return $return;
}
/*
Compresses an image by both size and quality

$path: The path to the image file
$maxFileSizeKilo: The maximum file size in kilobytes
$maxWidth: The maximum image width in pixels
*/

function compressUploadedImage($path, $maxFileSizeKilo, $maxWidth)
{
	//load image data
	$imageData = new IMagick($path);
	
	//if the image file size is too large, compress the image
	if ($imageData->getImageLength() > $maxFileSizeKilo*1000) {
		$width = $imageData->getImageWidth();
		
		if ($width > $maxWidth) {
			$imageData->resizeImage($maxWidth, 0, 0, 0.0);
		}
		
		$imageData->setImageCompression(imagick::COMPRESSION_JPEG);
		$imageData->setImageCompressionQuality(50); //reduce quality to 50% of original
		$imageData->writeImage($path); //write the compressed image to disk
	}
}
?>