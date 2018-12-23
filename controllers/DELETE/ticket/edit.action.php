<?php
if(empty($_SESSION["UserId"]))
{
	header("Location: {$rootScope["RootUrl"]}/account/login");
	return;
}
$cache_path=$rootScope["RootPath"]."/data/cache/county_list.txt";
if(file_exists($cache_path))
{
    $viewScope["county_list"]=file_get_contents($cache_path);
}
else
{    
    $stmt = pdo_query( $pdo,
                           'select distinct name from ticket_tracker_county order by name',
                            null
                         );	
    $result=pdo_fetch_all ($stmt,PDO::FETCH_ASSOC);
    $viewScope["county_list"]=json_encode($result);
    $cache_content=$viewScope["county_list"];
    file_put_contents($cache_path,$cache_content);
}
$cache_path=$rootScope["RootPath"]."/data/cache/field_list.txt";
if(file_exists($cache_path))
{
    $viewScope["field_list"]=file_get_contents($cache_path);
}
else
{
    $stmt = pdo_query( $pdo,
                    'select * from ticket_tracker_field order by name',
                     null
                  );	
    $result = pdo_fetch_all($stmt,PDO::FETCH_ASSOC);
    $viewScope["field_list"]=json_encode($result);
    $cache_content=$viewScope["field_list"];
    file_put_contents($cache_path,$cache_content);
}


$cache_path=$rootScope["RootPath"]."/data/cache/operator_list.txt";
if(file_exists($cache_path))
{
    $viewScope["operator_list"]=file_get_contents($cache_path);
}
else
{
    $stmt = pdo_query( $pdo,
					   'select * from ticket_tracker_operator order by name',
						null
					 );	
    $result = pdo_fetch_all($stmt,PDO::FETCH_ASSOC);
    $viewScope["operator_list"]=json_encode($result);
    $cache_content=$viewScope["operator_list"];
    file_put_contents($cache_path,$cache_content);
}

include_once GetViewFile();
?>