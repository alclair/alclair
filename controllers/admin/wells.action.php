<?php
if(empty($_SESSION["UserId"])||empty($_SESSION["IsAdmin"]))
{
	header("Location: {$rootScope["RootUrl"]}/account/not_authorized");
	return;
}
/*
$stmt = pdo_query( $pdo,
					   'select distinct name from ticket_tracker_county order by name',
						null
					 );	
$result=pdo_fetch_all ($stmt,PDO::FETCH_ASSOC);
$viewScope["countylist"]=json_encode($result);
$stmt = pdo_query( $pdo,
					   'select * from ticket_tracker_field order by name',
						null
					 );	
$result = pdo_fetch_all($stmt,PDO::FETCH_ASSOC);
$viewScope["fields"]=json_encode($result);
*/
include_once GetViewFile();
?>