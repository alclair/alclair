<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
include_once "../../config.inc.php";
//$surveyId=1;
?>

<!--<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script type="text/javascript" src="<?=$rootScope["RootUrl"]?>/js/dual-list-box.js"></script>
<script type="text/javascript" src="<?=$rootScope["RootUrl"]?>/js/dual-list-box.min.js"></script>
-->

<div id="main-container" class="container" ng-controller="ticketAddCtrl">

<html>
	<head>
		<style>
			table, th, td {
			width: 1370px;
		}
		</style>
	</head>
	<body>
		<table>
			<tr>
				<th colspan="2"><h1>Available Customers</h1></th>
				<th colspan="2"><h1>Selected Customers</h1></th>
  			</tr>
		</table>

		<!--<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/prettify.css" media="screen,print">-->
		<script type="text/javascript" src="<?=$rootScope["RootUrl"]?>/js/multiselect.min.js"></script>
<body>
	
	<!-- CODE TO POPULATE THE LEFT LISTBOX -->
<div class="row">
    <div class="col-xs-5">
        <select name="from[]" id="search" class="form-control" size="20" multiple="multiple">
	        <?php
		        $params = null;
				// CODE USED FOR INITIAL SETUP OF LISTBOXES
				//$query='select ticket_tracker_well.id as id, file_number as file_number, current_well_name as name from ticket_tracker_well where ticket_tracker_well.id = id';
				// CODE USED FOR AFTER INITIAL SET OF LISTBOXES
				$query='select wells_list.id as id, file_number as file_number, current_well_name as name from wells_list where wells_list.id = id';
				$stmt=pdo_query($pdo,$query,$params);
				$row=pdo_fetch_all($stmt);
		        
				$a = '';
		        for ($i=0; $i<sizeof($row); $i++) {
						$a .= "<option ";
						$a .='value="'.$row[$i]['id'].'">';
						$a .= $row[$i]['file_number']. '&nbsp;&nbsp;'.$row[$i]['name'];
						$a .= '</option>';
				}
						//echo $a;
						echo $_REQUEST["unique_customer_id"]
				?>
        </select>
    </div>
    
    <!-- CODE DEFINES WHAT THE LISTBOX BUTTONS DO -->
    <div class="col-xs-2">
        <button type="button" id="search_rightAll" class="btn btn-block"><i class="glyphicon glyphicon-forward"></i></button>
        <button type="button" id="search_rightSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-right"></i></button>
        <button type="button" id="search_leftSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-left"></i></button>
        <button type="button" id="search_leftAll" class="btn btn-block"><i class="glyphicon glyphicon-backward"></i></button>
    </div>
    
    <!-- CODE TO POPULATE THE RIGHT LISTBOX -->
    <div class="col-xs-5">
        <select name="to[]" id="search_to" class="form-control" size="20" multiple="multiple">
	       	<?php    
		       	
		       		// CODE TO FIND DINSTINCT SOURCE WELL IDs TO POPULATE THE RIGHT LISTBOX FOR THE CUSTOMER
			   		// CODE WILL BE COMMENTED OUT AFTER IT HAS BEEN USED FOR EACH CUSTOMER
  		       	  	
  		       	  /*	
  		       	  	$query = "SELECT DISTINCT ON (t1.source_well_id) t1.id as id, t1.source_well_id as well_id, twell.current_well_name as well_name FROM ticket_tracker_ticket as t1
			   	  	LEFT JOIN ticket_tracker_well as twell on t1.source_well_id = twell.id";
			   	  	$stmt=pdo_query($pdo,$query,null);
			   	  	$row_1=pdo_fetch_all($stmt); 

			   	  	$e='';
			   	  	for($i=0; $i<sizeof($row_1); $i++) {
				   	  	if($i == sizeof($row_1) - 1) {
				   	  		$e .= "'" . $row_1[$i]["well_name"] . "'"; }
				   	  	else {
				   	  		$e .= " '" . $row_1[$i]["well_name"] . "',"; }
			   	  	}			   
						*/
					
			   	  	// FIND ID, FILE NUMBER AND WELL NAME TO POPULATE THE RIGHT LISTBOX
			   	  	// THIS CODE WILL ALSO BE COMMENTED OUT AFTER THE RIGHT LISTBOX IS POPULATED FOR THE CUSTOMER 
				
				  //$query = "SELECT ticket_tracker_well.id as id, file_number as file_number, current_well_name as name FROM ticket_tracker_well where current_well_name IN ($e)";
				  //$stmt=pdo_query($pdo,$query,null);
				  //$row2=pdo_fetch_all($stmt);

				  // CODE FOR THE LIVE SITE AFTER EACH CUSTOMER'S UNIQUE PRODUCER'S SITES HAVE BEEN ADDED
		        $params2 = null;
		        // CODE USED FOR INITIAL SETUP OF LISTBOXES
				// $query2='select well2.id as id, file_number as file_number, current_well_name as name from well2 where well2.id = id';
				// CODE USED FOR AFTER INITIAL SET OF LISTBOXES
				$query2='select ticket_tracker_well.id as id, file_number as file_number, current_well_name as name from ticket_tracker_well where ticket_tracker_well.id = id';
				$stmt2=pdo_query($pdo,$query2,$params2);
				$row2=pdo_fetch_all($stmt2);
		        
				$b = '';
		        for ($j=0; $j<sizeof($row2); $j++) {
						$b .= "<option ";
						$b .='value="'.$row2[$j]['id'].'">';
						$b .= $row2[$j]['file_number']. '&nbsp;&nbsp;'.$row2[$j]['name'];
						$b .= '</option>';
				}
						echo $b;
				?>
        </select>
			   	  	
    </div>
</div>
</br>
<div class="row alert">
	<div class="form-group col-md-8">
	</div>
	<div class="form-group col-md-2">
        <button type="button" class="btn btn-primary" onclick="saveData()">
			<i class="fa fa-save"></i>  Save Selected Source Wells                          
		</button>
	</div>
</div> 
    
</body>
</html>

<script src="<?=$rootScope["RootUrl"]?>/includes/app/dualboxCtrl.js"></script>

<script type="text/javascript">
$(document).ready(function() {
    $('#search').multiselect({
        search: {
            left: '<input type="text" name="q" class="form-control" placeholder="Search..." />',
            right: '<input type="text" name="q" class="form-control" placeholder="Search..." />',
        }
    });
});

function saveData()
{
	var data = $("#search_to option").map(function() {
		return $.trim($(this).val());
	}).get().join(",");
    
    $.post("/api/wells/customer_source_wells_table.php", { to: data }, function(data) {
	   //after post  
	 // alert("Data: " + data );
	  if (data == "success") {
                       toastr.success("Source well table has been updated.");
                   }
	 else {
		 toastr.error('Please add or remove a well from the "Selected Source Wells" list.');
	}
	
    });
}
</script>

<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>
<!--
    <script type="text/javascript">
        $('select').DualListBox();
    </script>
</body>
</html>
-->

<!--
<script>
	$(document).ready(function() {
	<select id="test">
		$('.test').DualListBox();
	</select>
	});
</script>
-->

<!--<script>
	<select id="test">
		$('test').DualListBox();
	</select>
</script>-->
<!--</div>-
<script src="<?=$rootScope["RootUrl"]?>/includes/app/dualboxCtrl.js"></script>
<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>