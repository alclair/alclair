<?php
//ini_set('memory_limit', 200000000);  // OK - 512MB
if(empty($_SESSION["UserId"])||empty($_SESSION["IsAdmin"]))
{
	header("Location: {$rootScope["RootUrl"]}/account/not_authorized");
	return;
}
$cache_path=$rootScope["RootPath"]."/data/cache/water_type_list.txt";
if(file_exists($cache_path))
{
    $viewScope["water_type_list"]=file_get_contents($cache_path);
}
else
{  
    $stmt = pdo_query( $pdo,
                           'select * from ticket_tracker_watertype order by type',
                            null
                         );	
    $result = pdo_fetch_all($stmt,PDO::FETCH_ASSOC);
    $viewScope["water_type_list"]=json_encode($result);
    $cache_content=$viewScope["water_type_list"];
    file_put_contents($cache_path,$cache_content);
}
$cache_path=$rootScope["RootPath"]."/data/cache/disposal_well_list.txt";
if(file_exists($cache_path))
{
    $viewScope["disposal_well_list"]=file_get_contents($cache_path);
}
else
{
    $stmt = pdo_query( $pdo,
                           'select * from ticket_tracker_disposalwell order by common_name',
                            null
                         );	
    $result = pdo_fetch_all($stmt,PDO::FETCH_ASSOC);
    $viewScope["disposal_well_list"]=json_encode($result);
    $cache_content=$viewScope["disposal_well_list"];
    file_put_contents($cache_path,$cache_content);
}
include_once GetViewFile();
?>