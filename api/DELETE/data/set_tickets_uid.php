<?php
include_once "../../config.inc.php";
$query="select * from ticket_tracker_ticket where uid is null";
$stmt=pdo_query($pdo,$query,null);
while($row=pdo_fetch_array($stmt))
{
    while(true)
    {
        $uid=uniqid();
        $query2="select * from ticket_tracker_ticket where uid=:uid";
        $stmt2=pdo_query($pdo,$query2,array(":uid"=>$uid));
        $row2=pdo_fetch_array($stmt2);
        if(empty($row2[0]))
        {
            $query2="update ticket_tracker_ticket set uid=:uid where id=:id";
            $params=array(":id"=>$row["id"],":uid"=>$uid);
            pdo_query($pdo,$query2,$params);
        }
        break;
    }   
}
echo "all set";
?>
