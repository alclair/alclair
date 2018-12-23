<?php	
//get the controller, action and querystring parames from the urldecode
//variables will be saved to $rootScope variables

$rootScope["RequestUrl"]=$_SERVER["REQUEST_URI"];

// $rootScope["RequestUrl"]='/account/login';
// echo "<br/> It is" . $_SERVER["REQUEST_URI"];

$db_server="localhost";
$db_database="ajswanso_alclair";
$db_user="ajswanso_aaudio";
$db_password="1Alclair!";
$pdo_alclair=new PDO("pgsql:host=$db_server;dbname=$db_database;user=$db_user;password=$db_password"); 
$query_alclair="SELECT * FROM auth_user WHERE id = 1";
$stmt_alclair = pdo_query($pdo_alclair, $query_alclair, null);
$row_alclair = pdo_fetch_array($stmt_alclair);
// echo "</br>Username id = 1 is " . $row_alclair["username"];
if(strpos($rootScope["RequestUrl"],"?")!==false)
{
	$rootScope["QueryString"]=substr($rootScope["RequestUrl"],strpos($rootScope["RequestUrl"],"?"));
	$rootScope["RequestUrlPath"]=substr($rootScope["RequestUrl"],0,strpos($rootScope["RequestUrl"],"?"));
}
else
{
	$rootScope["RequestUrlPath"]=$rootScope["RequestUrl"];
	
}
$rootScope["RequestUrlPath"]=str_replace($rootScope["RootUrl"],"",$rootScope["RequestUrlPath"]);
$arr=explode("/",$rootScope["RequestUrlPath"]);

if(count($arr)>1&&!empty($arr[1]))
{
	$rootScope["Controller"]=$arr[1];
}
else
{
	$rootScope["Controller"]="home";
	//$rootScope["Controller"]="alclair";
	$rootScope["Action"]="index";
	//$rootScope["Action"]="qc_form";
}
//print_r($rootScope);
if(strtolower($rootScope["Controller"])=="index.php")
{
	$rootScope["Controller"]="home";
}
if(count($arr)>2&&!empty($arr[2]))
{
	$rootScope["Action"]=$arr[2];
}
else
{
	$rootScope["Action"]="index";
	//$rootScope["Action"]="qc_form";
}
if(count($arr)>3&&!empty($arr[3]))
{
	$rootScope["Id"]=$arr[3];
}


if(!file_exists($rootScope["RootPath"]."controllers/".$rootScope["Controller"]))
{

	$rootScope["Controller"]="home";
	$rootScope["Action"]="error";
	
}
//print_r($rootScope);
if(!file_exists($rootScope["RootPath"]."controllers/".$rootScope["Controller"]."/".$rootScope["Action"].".action.php"))
{

	$rootScope["Controller"]="home";
	$rootScope["Action"]="error";
}
if(count($arr)>3&&!empty($arr[3]))
{
		$rootScope["Id"]=$arr[3];
}
if(!empty($rootScope["QueryString"]))
{

	$arr1=explode("&",str_replace("?","",$rootScope["QueryString"]));	
	for($i=0;$i<count($arr1);$i++)
	{
		if(!empty($arr1[$i])&&strpos($arr1[$i],"=")!==false)
		{
			$arr2=explode("=",$arr1[$i]);	
			if(count($arr2)==2)
			{
				$rootScope[$arr2[0]]=$arr2[1];
			}
		}		
	}
}
?>