<?php
	
// Report all errors except E_NOTICE
//error_reporting(E_ALL & ~E_NOTincludes/header.inc.phpICE);

session_start();
//phpinfo();
$rootScope=array();

// USE THIS CODE TO CHANGE THE NAME OF A TABLE
// ALTER TABLE test RENAME TO test9999

//$rootScope["RootPath"]="/home/ajswanso/public_html/otis/";
$rootScope["RootPath"]="/var/www/html/otisdev/";

$rootScope["RootUrl"]="https://otisdev.alclr.co";
//$rootScope["RootUrl"]="http://54.173.238.250";

$rootScope['m_Theme'] = $rootScope['RootUrl'].'/css/metronic_v4.5.0/theme/assets/';	
$rootScope["APIUrl"]="/api/";
$rootScope["PageSize"]="100";
$rootScope["SWDCustomer"]="alclair";
$rootScope["SWDRootUrl"]="https://otisdev.alclr.co/";
//$rootScope["SWDRootUrl"]="http://54.173.238.250/";

$rootScope["SWDApiToken"]="83167892";

$rootScope["SupportEmail"]="tyler@alclair.com";

$rootScope["SupportName"]="OTIS is speaking to you DEV";

$db_server="54.173.238.250";
//$db_server="127.0.0.1";
$db_server="localhost";
$db_database="ajswanso_alclair";
$db_user="postgres";
$db_password="Gorilla1";

try
{
        $pdo=new PDO("pgsql:host=$db_server;dbname=$db_database;user=$db_user;password=$db_password");

}

catch(PDOException $ex)

{
        echo "Can't connect to the DB server: ".$ex->getMessage();

        die();

}
require_once "includes/functions.inc.php";
$query="select * from settings where id=1";
$stmt=pdo_query($pdo,$query,null);
$row=pdo_fetch_array($stmt);
$rootScope["site_name"]=$row["site_name"];
?>
