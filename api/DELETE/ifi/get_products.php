<?php
include_once "../../config.inc.php";
if(empty($_SESSION["UserId"]))
{
    return;
}
$response = array();
$response["code"] = "";
$response["message"] = "";
$response['data'] = null;

try
{	   
	$conditionSql = "";
    $pagingSql = "";
    $orderBySqlDirection = "DESC";
    $orderBySql = " ORDER BY id $orderBySqlDirection";
    $params = array();
    
     if($_REQUEST['category_type_id'] !=  0)
    {
        $conditionSql .= " AND (products.category_id = :category_id)";
        $params[":category_id"] = $_REQUEST['category_type_id'];
    }
    if($_REQUEST['product_id'] !=  0)
    {
        $conditionSql .= " AND (products.id = :product_id)";
        $params[":product_id"] = $_REQUEST['product_id'];
    }
    
    if( !empty($_REQUEST['id']) )
    {        
        $stmt = pdo_query( $pdo,
                           'SELECT * FROM products WHERE id = :id',
                            array(":id"=>$_REQUEST['id'])
                         );	
        $result = pdo_fetch_array($stmt);
    }
    else if(!empty($_REQUEST["SearchText"]))
    {
        $stmt = pdo_query( $pdo,
                           'select * from products where (name ilike :SearchText ) order by id',
                            array(":SearchText"=>"%".$_REQUEST["SearchText"]."%")
                            //,1
                         );	
        $result = pdo_fetch_all($stmt);
    }
    else
    { 
	    				
	     $query = "SELECT products.*, t2.name AS category, (SELECT COUNT(*) FROM serial_numbers WHERE serial_numbers.product_id = products.id) AS total_quantity
FROM products 
LEFT JOIN product_categories AS t2 ON products.category_id = t2.id
WHERE products.active = TRUE  $conditionSql ORDER BY products.id";

$query = "SELECT products.*, t2.name AS category,
(SELECT COUNT(*) FROM serial_numbers WHERE serial_numbers.product_id = products.id) AS total_quantity,
(SELECT COUNT(*) FROM serial_numbers WHERE serial_numbers.product_id = products.id AND serial_numbers.status='SEALED') AS SEALED_quantity,
(SELECT COUNT(*) FROM serial_numbers WHERE serial_numbers.product_id = products.id AND serial_numbers.status='AMAZON') AS amazon_quantity,
(SELECT COUNT(*) FROM serial_numbers WHERE serial_numbers.product_id = products.id AND serial_numbers.status='DEMO') AS demo_quantity,
(SELECT COUNT(*) FROM serial_numbers WHERE serial_numbers.product_id = products.id AND serial_numbers.status='FAULTY') AS faulty_quantity,
(SELECT COUNT(*) FROM serial_numbers WHERE serial_numbers.product_id = products.id AND serial_numbers.status='SHIPPED') AS shipped_quantity

FROM products 
LEFT JOIN product_categories AS t2 ON products.category_id = t2.id
WHERE products.active = TRUE   $conditionSql ORDER BY products.id";

        $stmt = pdo_query( $pdo, $query, $params); 
        $result = pdo_fetch_all($stmt);
    }	
	
	$response['code']='success';
	$response['data'] = $result;
	
	//var_export($result);
	
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>