<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
?>

<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/tableresponsive.css"/>

<div ng-controller="displayTanksCtrl"  class="container">

&nbsp;  
	<div class="row">
		<div class="container-fluid">
			<button class="btn btn-primary"  >
				<a class="fa fa-sign-in" href="/ticket/add" style="color: #ffffff"> Receive Product</a>
			</button>
			&nbsp;&nbsp;
			<button class="btn btn-primary"  >
				<a class="fa fa-sign-out" ng-click="outgoingCtrl();" style="color: #ffffff"> Ship Product</a>
				<!--<a ng-if="SearchOutgoingTicketTypes=1" class="fa fa-sign-out" href="/ticket/outgoingwater" style="color: #ffffff"> Ship Product</a>
				<a class="fa fa-sign-out" href="/ticket/oilsale" style="color: #ffffff"> Ship Product</a>-->
			</button>
			&nbsp;&nbsp;
			<button class="btn btn-primary"  >
				<a class="fa fa-edit" style="color: #ffffff" ng-click="transferCtrl();"> Transfer </a>
			</button>
			&nbsp;&nbsp;
			<button class="btn btn-primary"  >
				<a class="fa fa-edit" style="color: #ffffff" ng-click="adjustmentCtrl();"> Adjustment </a>
			</button>
			&nbsp;&nbsp;
        
	<?php if($_SESSION["IsAdmin"]==5) { ?>
			<button class="btn btn-primary"  >
				<a class="fa fa-bank" style="color: #ffffff" > Ledger </a> <!--ng-click="ledgerCtrl();"-->
			</button>
    <?php } ?>    
		<!--<div class="form-group col-md-2">
			<a type="button" class="btn btn-primary"  ng-click="transferCtrl();">
				<span class="glyphicon glyphicon-edit"></span>Transfer&nbsp;&nbsp;
			</a>
		</div>
		<div class="form-group col-md-2">
			<a type="button" class="btn btn-primary"  ng-click="adjustmentCtrl();">
				<span class="glyphicon glyphicon-edit"></span> Adjustment
			</a>
		</div>-->
		</div>
	</div>
&nbsp;  


<?php if ( $rootScope["SWDCustomer"] == "wwl"  ) { ?> 

	<div class="container">
		<div class="col-xs-2">
			<div class="table-responsive">
				<table style="width: auto;" align="center" class = "table-condensed">
					<thead>
						<tr>
							<th class="text-center">Gunbarrel</th>
							<th class="text-center">Barrels</th>
							<th class="text-center">Ft.</th>
						</tr>
					</thead>
					<tbody>
						<tr  ng-repeat='tick in ticket'>
							<!--<div ng-if="tick.id != 1">-->
								<td ng-if="tick.id == 1 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="GB">{{tick.type}}</td>
								<td ng-if="tick.id == 1 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Bbls">{{tick.fluid_amount}}</td>
								<td ng-if="tick.id == 1 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Ft. ">{{tick.fluid_amount/20}}</td>
								<td ng-if="tick.id == 1 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="GB">EMPTY</td>
								<td ng-if="tick.id == 1 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Bbls">0</td>
								<td ng-if="tick.id == 1 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Ft.">0</td>
							<!--</div>-->
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="col-xs-2">
			<div class="table-responsive">
				<table style="width: auto;" align="center" class = "table-condensed">
					<thead>
						<tr>
							<th class="text-center">Oil 1</th>
							<th class="text-center">Barrels</th>
							<th class="text-center">Ft.</th>
						</tr>
					</thead>
					<tbody  ng-repeat='tick in ticket'>

							<td ng-if="tick.id == 2 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Oil 1">{{tick.type}}</td>
							<td ng-if="tick.id == 2 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Bbls">{{tick.fluid_amount}}</td>
							<td ng-if="tick.id == 2 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Ft. ">{{tick.fluid_amount/20}}</td>
							<td ng-if="tick.id == 2 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Oil 1">EMPTY</td>
							<td ng-if="tick.id == 2 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Bbls">0</td>
							<td ng-if="tick.id == 2 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Ft.">0</td>

					</tbody>
				</table>
			</div>
		</div>
		<div class="col-xs-2">
			<div class="table-responsive">
				<table style="width: auto;" align="center" class = "table-condensed">
					<thead>
						<tr>
							<th class="text-center">Oil 2</th>
							<th class="text-center">Barrels</th>
							<th class="text-center">Ft.</th>
						</tr>
					</thead>
					<tbody ng-repeat='tick in ticket'>

						    <td ng-if="tick.id == 3 &&  (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Oil 2">{{tick.type}}</td>
							<td ng-if="tick.id == 3 &&  (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Bbls">{{tick.fluid_amount}}</td>
							<td ng-if="tick.id == 3 &&  (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Ft.">{{tick.fluid_amount/20}}</td>
							<td ng-if="tick.id == 3 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Oil 2">EMPTY</td>
							<td ng-if="tick.id == 3 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Bbls">0</td>
							<td ng-if="tick.id == 3 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Ft.">0</td>

					</tbody>
				</table>
			</div>
		</div>
		<div class="col-xs-2">
			<div class="table-responsive">
				<table style="width: auto;" align="center" class = "table-condensed">
					<thead>
						<tr>
							<th class="text-center">Blue 1</th>
							<th class="text-center">Barrels</th>
							<th class="text-center">Ft.</th>
						</tr>
					</thead>
					<tbody ng-repeat='tick in ticket'>
				
							<td ng-if="tick.id == 4 &&  (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Blue 1">{{tick.type}}</td>
							<td ng-if="tick.id == 4 &&  (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Bbls.">{{tick.fluid_amount}}</td>
							<td ng-if="tick.id == 4 &&  (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Ft.">{{tick.fluid_amount/20}}</td>
							<td ng-if="tick.id == 4 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Blue 1">EMPTY</td>
							<td ng-if="tick.id == 4 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Bbls">0</td>
							<td ng-if="tick.id == 4 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Ft.">0</td>						
					
					</tbody>
				</table>
			</div>
		</div>
		<div class="col-xs-2">
			<div class="table-responsive">
				<table style="width: auto;" align="center" class = "table-condensed">
					<thead>
						<tr>
							<th class="text-center">Blue 2</th>
							<th class="text-center">Barrels</th>
							<th class="text-center">Ft.</th>
						</tr>
					</thead>
					<tbody ng-repeat='tick in ticket'>
						
							<td ng-if="tick.id == 5 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Blue 2">{{tick.type}}</td>
							<td ng-if="tick.id == 5 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Bbls.">{{tick.fluid_amount}}</td>
							<td ng-if="tick.id == 5 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Ft.">{{tick.fluid_amount/20}}</td>
							<td ng-if="tick.id == 5 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Blue 2">EMPTY</td>
							<td ng-if="tick.id == 5 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Bbls">0</td>
							<td ng-if="tick.id == 5 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Ft.">0</td>
					
					</tbody>
				</table>
			</div>
		</div>
		<div class="col-xs-2">
			<div class="table-responsive">
				<table style="width: auto;" align="center" class = "table-condensed">
					<thead>
						<tr>
							<th class="text-center">Blue 3</th>
							<th class="text-center">Barrels</th>
							<th class="text-center">Ft.</th>
						</tr>
					</thead>
					<tbody ng-repeat='tick in ticket'>
						
							<td ng-if="tick.id == 6 &&  (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Blue 3">{{tick.type}}</td>
							<td ng-if="tick.id == 6 &&  (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Bbls">{{tick.fluid_amount}}</td>
							<td ng-if="tick.id == 6 &&  (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Ft.">{{tick.fluid_amount/20}}</td>
							<td ng-if="tick.id == 6 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Blue 3">EMPTY</td>
							<td ng-if="tick.id == 6 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Bbls">0</td>
							<td ng-if="tick.id == 6 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Ft.">0</td>
						
					</tbody>
				</table>
			</div>
		</div>
	</div>
<br>
	<div class="container">
		<div class="col-xs-2">
			<div class="table-responsive">
				<table style="width: auto;" align="center" class = "table-condensed">
					<thead>
						<tr>
							<th class="text-center">Blue 4</th>
							<th class="text-center">Barrels</th>
							<th class="text-center">Ft.</th>
						</tr>
					</thead>
					<tbody ng-repeat='tick in ticket'>
						
							<td ng-if="tick.id == 7 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Blue 4">{{tick.type}}</td>
							<td ng-if="tick.id == 7 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Bbls">{{tick.fluid_amount}}</td>
							<td ng-if="tick.id == 7 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Ft.">{{tick.fluid_amount/20}}</td>
							<td ng-if="tick.id == 7 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Blue 4">EMPTY</td>
							<td ng-if="tick.id == 7 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Bbls">0</td>
							<td ng-if="tick.id == 7 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Ft.">0</td>

					</tbody>
				</table>
			</div>
		</div>
		<div class="col-xs-2">
			<div class="table-responsive">
				<table style="width: auto;" align="center" class = "table-condensed">
					<thead>
						<tr>
							<th class="text-center">Big Tank</th>
							<th class="text-center">Barrels</th>
							<th class="text-center">Ft.</th>
						</tr>
					</thead>
					<tbody ng-repeat='tick in ticket'>

							<td ng-if="tick.id == 8 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Big Tank">{{tick.type}}</td>
							<td ng-if="tick.id == 8 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Bbls">{{tick.fluid_amount}}</td>
							<td ng-if="tick.id == 8 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Ft.">{{tick.fluid_amount/300}}</td>
							<td ng-if="tick.id == 8 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Big Tank">EMPTY</td>
							<td ng-if="tick.id == 8 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Bbls">0</td>
							<td ng-if="tick.id == 8 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Ft.">0</td>

					</tbody>
				</table>
			</div>
		</div>
		<div class="col-xs-2">
			<div class="table-responsive">
				<table style="width: auto;" align="center" class = "table-condensed">
					<thead>
						<tr>
							<th class="text-center">P10</th>
							<th class="text-center">Barrels</th>
							<th class="text-center">Ft.</th>
						</tr>
					</thead>
					<tbody ng-repeat='tick in ticket'>

							<td ng-if="tick.id == 9 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="P10">{{tick.type}}</td>
							<td ng-if="tick.id == 9 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Bbls">{{tick.fluid_amount}}</td>
							<td ng-if="tick.id == 9 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Ft.">{{tick.fluid_amount/20}}</td>
							<td ng-if="tick.id == 9 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="P10">EMPTY</td>
							<td ng-if="tick.id == 9 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Bbls">0</td>
							<td ng-if="tick.id == 9 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Ft.">0</td>

					</tbody>
				</table>
			</div>
		</div>
		<div class="col-xs-2">
			<div class="table-responsive">
				<table style="width: auto;" align="center" class = "table-condensed">
					<thead>
						<tr>
							<th class="text-center">P11</th>
							<th class="text-center">Barrels</th>
							<th class="text-center">Ft.</th>
						</tr>
					</thead>
					<tbody ng-repeat='tick in ticket'>

							<td ng-if="tick.id == 10 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="P11">{{tick.type}}</td>
							<td ng-if="tick.id == 10 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Bbls">{{tick.fluid_amount}}</td>
							<td ng-if="tick.id == 10 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Ft.">{{tick.fluid_amount/20}}</td>
							<td ng-if="tick.id == 10 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="P11">EMPTY</td>
							<td ng-if="tick.id == 10 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Bbls">0</td>
							<td ng-if="tick.id == 10 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Ft.">0</td>

					</tbody>
				</table>
			</div>
		</div>
		<div class="col-xs-2">
			<div class="table-responsive">
				<table style="width: auto;" align="center" class = "table-condensed">
					<thead>
						<tr>
							<th class="text-center">P12</th>
							<th class="text-center">Barrels</th>
							<th class="text-center">Ft.</th>
						</tr>
					</thead>
					<tbody ng-repeat='tick in ticket'>

							<td ng-if="tick.id == 11 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="P12">{{tick.type}}</td>
							<td ng-if="tick.id == 11&& (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Bbls">{{tick.fluid_amount}}</td>
							<td ng-if="tick.id == 11&& (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Ft.">{{tick.fluid_amount/20}}</td>
							<td ng-if="tick.id == 11 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="P12">EMPTY</td>
							<td ng-if="tick.id == 11 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Bbls">0</td>
							<td ng-if="tick.id == 11 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Ft.">0</td>

					</tbody>
				</table>
			</div>
		</div>
		<div class="col-xs-2">
			<div class="table-responsive">
				<table style="width: auto;" align="center" class = "table-condensed">
					<thead>
						<tr>
							<th class="text-center">P13</th>
							<th class="text-center">Barrels</th>
							<th class="text-center">Ft.</th>
						</tr>
					</thead>
					<tbody ng-repeat='tick in ticket'>

							<td ng-if="tick.id == 12 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="P13">{{tick.type}}</td>
							<td ng-if="tick.id == 12 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Bbls">{{tick.fluid_amount}}</td>
							<td ng-if="tick.id == 12 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Ft.">{{tick.fluid_amount/20}}</td>
							<td ng-if="tick.id == 12 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="P13">EMPTY</td>
							<td ng-if="tick.id == 12 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Bbls">0</td>
							<td ng-if="tick.id == 12 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Ft.">0</td>

					</tbody>
				</table>
			</div>
		</div>
	</div>
<br>
	<div class="container">
		<div class="col-xs-2">
			<div class="table-responsive">
				<table style="width: auto;" align="center" class = "table-condensed">
					<thead>
						<tr>
							<th class="text-center">P14</th>
							<th class="text-center">Barrels</th>
							<th class="text-center">Ft.</th>
						</tr>
					</thead>
					<tbody ng-repeat='tick in ticket'>

							<td ng-if="tick.id == 13 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="P14">{{tick.type}}</td>
							<td ng-if="tick.id == 13 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Bbls">{{tick.fluid_amount}}</td>
							<td ng-if="tick.id == 13 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Ft.">{{tick.fluid_amount/20}}</td>
							<td ng-if="tick.id == 13 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="P14">EMPTY</td>
							<td ng-if="tick.id == 13 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Bbls">0</td>
							<td ng-if="tick.id == 13 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Ft.">0</td>

					</tbody>
				</table>
			</div>
		</div>
		<div class="col-xs-2">
			<div class="table-responsive">
				<table style="width: auto;" align="center" class = "table-condensed">
					<thead>
						<tr>
							<th class="text-center">P15</th>
							<th class="text-center">Barrels</th>
							<th class="text-center">Ft.</th>
						</tr>
					</thead>
					<tbody ng-repeat='tick in ticket'>

							<td ng-if="tick.id == 14 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="P15">{{tick.type}}</td>
							<td ng-if="tick.id == 14 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Bbls">{{tick.fluid_amount}}</td>
							<td ng-if="tick.id == 14 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Ft.">{{tick.fluid_amount/20}}</td>
							<td ng-if="tick.id == 14 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="P15">EMPTY</td>
							<td ng-if="tick.id == 14 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Bbls">0</td>
							<td ng-if="tick.id == 14 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Ft.">0</td>

					</tbody>
				</table>
			</div>
		</div>
		<div class="col-xs-2">
			<div class="table-responsive">
				<table style="width: auto;" align="center" class = "table-condensed">
					<thead>
						<tr>
							<th class="text-center">P16</th>
							<th class="text-center">Barrels</th>
							<th class="text-center">Ft.</th>
						</tr>
					</thead>
					<tbody ng-repeat='tick in ticket'>

							<td ng-if="tick.id == 15 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="P16">{{tick.type}}</td>
							<td ng-if="tick.id == 15 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Bbls">{{tick.fluid_amount}}</td>
							<td ng-if="tick.id == 15 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Ft.">{{tick.fluid_amount/20}}</td>
							<td ng-if="tick.id == 15 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="P16">EMPTY</td>
							<td ng-if="tick.id == 15 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Bbls">0</td>
							<td ng-if="tick.id == 15 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Ft.">0</td>

					</tbody>
				</table>
			</div>
		</div>
		<div class="col-xs-2">
			<div class="table-responsive">
				<table style="width: auto;" align="center" class = "table-condensed">
					<thead>
						<tr>
							<th class="text-center">P17</th>
							<th class="text-center">Barrels</th>
							<th class="text-center">Ft.</th>
						</tr>
					</thead>
					<tbody ng-repeat='tick in ticket'>

							<td ng-if="tick.id == 16 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="P17">{{tick.type}}</td>
							<td ng-if="tick.id == 16 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Bbls">{{tick.fluid_amount}}</td>
							<td ng-if="tick.id == 16 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Ft.">{{tick.fluid_amount/20}}</td>
							<td ng-if="tick.id == 16 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="P17">EMPTY</td>
							<td ng-if="tick.id == 16 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Bbls">0</td>
							<td ng-if="tick.id == 16 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Ft.">0</td>

					</tbody>
				</table>
			</div>
		</div>
		<div class="col-xs-2">
			<div class="table-responsive">
				<table style="width: auto;" align="center" class = "table-condensed">
					<thead>
						<tr>
							<th class="text-center">P18</th>
							<th class="text-center">Barrels</th>
							<th class="text-center">Ft.</th>
						</tr>
					</thead>
					<tbody ng-repeat='tick in ticket'>

							<td ng-if="tick.id == 17 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="P18">{{tick.type}}</td>
							<td ng-if="tick.id == 17 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Bbls">{{tick.fluid_amount}}</td>
							<td ng-if="tick.id == 17 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Ft.">{{tick.fluid_amount/20}}</td>
							<td ng-if="tick.id == 17 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="P18">EMPTY</td>
							<td ng-if="tick.id == 17 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Bbls">0</td>
							<td ng-if="tick.id == 17 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Ft.">0</td>

					</tbody>
				</table>
			</div>
		</div>
		<div class="col-xs-2">
			<div class="table-responsive">
				<table style="width: auto;" align="center" class = "table-condensed">
					<thead>
						<tr>
							<th class="text-center">P19</th>
							<th class="text-center">Barrels</th>
							<th class="text-center">Ft.</th>
						</tr>
					</thead>
					<tbody ng-repeat='tick in ticket'>

							<td ng-if="tick.id == 18 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="P19">{{tick.type}}</td>
							<td ng-if="tick.id == 18 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Bbls">{{tick.fluid_amount}}</td>
							<td ng-if="tick.id == 18 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Ft.">{{tick.fluid_amount/20}}</td>
							<td ng-if="tick.id == 18 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="P19">EMPTY</td>
							<td ng-if="tick.id == 18 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Bbls">0</td>
							<td ng-if="tick.id == 18 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Ft.">0</td>

					</tbody>
				</table>
			</div>
		</div>
	</div>
<br>

<?php } elseif ( $rootScope["SWDCustomer"] == "trd") { ?>

	<div class="container">
		<div class="col-xs-2">
			<div class="table-responsive">
				<table style="width: auto;" align="center" class = "table-condensed">
					<thead>
						<tr>
							<th class="text-center">GB 1</th>
							<th class="text-center">Barrels</th>
							<th class="text-center">Ft.</th>
						</tr>
					</thead>
					<tbody ng-repeat='tick in ticket'>
						
								<td ng-if="tick.id == 1 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="GB 1">{{tick.type}}</td>
								<td ng-if="tick.id == 1 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Bbls">{{tick.fluid_amount}}</td>
								<td ng-if="tick.id == 1 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Ft.">{{tick.fluid_amount/20}}</td>
								<td ng-if="tick.id == 1 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="GB 1">EMPTY</td>
								<td ng-if="tick.id == 1 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Bbls">0</td>
								<td ng-if="tick.id == 1 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Ft.">0</td>
					
					</tbody>
				</table>
			</div>
		</div>
		<div class="col-xs-2">
			<div class="table-responsive">
				<table style="width: auto;" align="center" class = "table-condensed">
					<thead>
						<tr>
							<th class="text-center">GB 2</th>
							<th class="text-center">Barrels</th>
							<th class="text-center">Ft.</th>
						</tr>
					</thead>
					<tbody ng-repeat='tick in ticket'>

							<td ng-if="tick.id == 2 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="GB 2">{{tick.type}}</td>
							<td ng-if="tick.id == 2 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Bbls">{{tick.fluid_amount}}</td>
							<td ng-if="tick.id == 2 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Ft.">{{tick.fluid_amount/20}}</td>
							<td ng-if="tick.id == 2 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="GB 2">EMPTY</td>
							<td ng-if="tick.id == 2 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Bbls">0</td>
							<td ng-if="tick.id == 2 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Ft.">0</td>

					</tbody>
				</table>
			</div>
		</div>
		<div class="col-xs-2">
			<div class="table-responsive">
				<table style="width: auto;" align="center" class = "table-condensed">
					<thead>
						<tr>
							<th class="text-center">GB Oil</th>
							<th class="text-center">Barrels</th>
							<th class="text-center">Ft.</th>
						</tr>
					</thead>
					<tbody ng-repeat='tick in ticket'>

						    <td ng-if="tick.id == 3 &&  (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="GB Oil">{{tick.type}}</td>
							<td ng-if="tick.id == 3 &&  (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Bbls">{{tick.fluid_amount}}</td>
							<td ng-if="tick.id == 3 &&  (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Ft.">{{tick.fluid_amount/20}}</td>
							<td ng-if="tick.id == 3 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="GB Oil">EMPTY</td>
							<td ng-if="tick.id == 3 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Bbls">0</td>
							<td ng-if="tick.id == 3 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Ft.">0</td>

					</tbody>
				</table>
			</div>
		</div>
		<div class="col-xs-2">
			<div class="table-responsive">
				<table style="width: auto;" align="center" class = "table-condensed">
					<thead>
						<tr>
							<th class="text-center">H2O 1</th>
							<th class="text-center">Barrels</th>
							<th class="text-center">Ft.</th>
						</tr>
					</thead>
					<tbody ng-repeat='tick in ticket'>

							<td ng-if="tick.id == 4 &&  (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="H2O 1">{{tick.type}}</td>
							<td ng-if="tick.id == 4 &&  (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Bbls">{{tick.fluid_amount}}</td>
							<td ng-if="tick.id == 4 &&  (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Ft.">{{tick.fluid_amount/20}}</td>
							<td ng-if="tick.id == 4 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="H2O 1">EMPTY</td>
							<td ng-if="tick.id == 4 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Bbls">0</td>
							<td ng-if="tick.id == 4 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Ft.">0</td>						

					</tbody>
				</table>
			</div>
		</div>
		<div class="col-xs-2">
			<div class="table-responsive">
				<table style="width: auto;" align="center" class = "table-condensed">
					<thead>
						<tr>
							<th class="text-center">H2O 2</th>
							<th class="text-center">Barrels</th>
							<th class="text-center">Ft.</th>
						</tr>
					</thead>
					<tbody ng-repeat='tick in ticket'>

							<td ng-if="tick.id == 5 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="H2O 2">{{tick.type}}</td>
							<td ng-if="tick.id == 5 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Bbls">{{tick.fluid_amount}}</td>
							<td ng-if="tick.id == 5 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Ft.">{{tick.fluid_amount/20}}</td>
							<td ng-if="tick.id == 5 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="H2O 2">EMPTY</td>
							<td ng-if="tick.id == 5 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Bbls">0</td>
							<td ng-if="tick.id == 5 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Ft.">0</td>

					</tbody>
				</table>
			</div>
		</div>
		<div class="col-xs-2">
			<div class="table-responsive">
				<table style="width: auto;" align="center" class = "table-condensed">
					<thead>
						<tr>
							<th class="text-center">H2O 3</th>
							<th class="text-center">Barrels</th>
							<th class="text-center">Ft.</th>
						</tr>
					</thead>
					<tbody ng-repeat='tick in ticket'>

							<td ng-if="tick.id == 6 &&  (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="H2O 3">{{tick.type}}</td>
							<td ng-if="tick.id == 6 &&  (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Bbls">{{tick.fluid_amount}}</td>
							<td ng-if="tick.id == 6 &&  (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Ft.">{{tick.fluid_amount/20}}</td>
							<td ng-if="tick.id == 6 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="H2O 3">EMPTY</td>
							<td ng-if="tick.id == 6 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Bbls">0</td>
							<td ng-if="tick.id == 6 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Ft.">0</td>

					</tbody>
				</table>
			</div>
		</div>
	</div>
<br>
	<div class="container">
		<div class="col-xs-2">
			<div class="table-responsive">
				<table style="width: auto;" align="center" class = "table-condensed">
					<thead>
						<tr>
							<th class="text-center">Sales (Oil)</th>
							<th class="text-center">Barrels</th>
							<th class="text-center">Ft.</th>
						</tr>
					</thead>
					<tbody ng-repeat='tick in ticket'>

							<td ng-if="tick.id == 7 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Sales (Oil)">{{tick.type}}</td>
							<td ng-if="tick.id == 7 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Bbls">{{tick.fluid_amount}}</td>
							<td ng-if="tick.id == 7 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Ft.">{{tick.fluid_amount/20}}</td>
							<td ng-if="tick.id == 7 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Sales (Oil)">EMPTY</td>
							<td ng-if="tick.id == 7 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Bbls">0</td>
							<td ng-if="tick.id == 7 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Ft.">0</td>

					</tbody>
				</table>
			</div>
		</div>
		<div class="col-xs-2">
			<div class="table-responsive">
				<table style="width: auto;" align="center" class = "table-condensed">
					<thead>
						<tr>
							<th class="text-center">EM 1</th>
							<th class="text-center">Barrels</th>
							<th class="text-center">Ft.</th>
						</tr>
					</thead>
					<tbody ng-repeat='tick in ticket'>

							<td ng-if="tick.id == 8 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="EM 1">{{tick.type}}</td>
							<td ng-if="tick.id == 8 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Bbls">{{tick.fluid_amount}}</td>
							<td ng-if="tick.id == 8 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Ft.">{{tick.fluid_amount/300}}</td>
							<td ng-if="tick.id == 8 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="EM 1">EMPTY</td>
							<td ng-if="tick.id == 8 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Bbls">0</td>
							<td ng-if="tick.id == 8 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Ft.">0</td>

					</tbody>
				</table>
			</div>
		</div>
		<div class="col-xs-2">
			<div class="table-responsive">
				<table style="width: auto;" align="center" class = "table-condensed">
					<thead>
						<tr>
							<th class="text-center">EM 2</th>
							<th class="text-center">Barrels</th>
							<th class="text-center">Ft.</th>
						</tr>
					</thead>
					<tbody ng-repeat='tick in ticket'>

							<td ng-if="tick.id == 9 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="EM 2">{{tick.type}}</td>
							<td ng-if="tick.id == 9 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Bbls">{{tick.fluid_amount}}</td>
							<td ng-if="tick.id == 9 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Ft.">{{tick.fluid_amount/20}}</td>
							<td ng-if="tick.id == 9 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="EM 2">EMPTY</td>
							<td ng-if="tick.id == 9 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Bbls">0</td>
							<td ng-if="tick.id == 9 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Ft.">0</td>

					</tbody>
				</table>
			</div>
		</div>
		<div class="col-xs-2">
			<div class="table-responsive">
				<table style="width: auto;" align="center" class = "table-condensed">
					<thead>
						<tr>
							<th class="text-center">R1</th>
							<th class="text-center">Barrels</th>
							<th class="text-center">Ft.</th>
						</tr>
					</thead>
					<tbody ng-repeat='tick in ticket'>

							<td ng-if="tick.id == 10 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="R 1">{{tick.type}}</td>
							<td ng-if="tick.id == 10 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Bbls">{{tick.fluid_amount}}</td>
							<td ng-if="tick.id == 10 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Ft.">{{tick.fluid_amount/20}}</td>
							<td ng-if="tick.id == 10 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="R 1">EMPTY</td>
							<td ng-if="tick.id == 10 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Bbls">0</td>
							<td ng-if="tick.id == 10 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Ft.">0</td>

					</tbody>
				</table>
			</div>
		</div>
		<div class="col-xs-2">
			<div class="table-responsive">
				<table style="width: auto;" align="center" class = "table-condensed">
					<thead>
						<tr>
							<th class="text-center">R2</th>
							<th class="text-center">Barrels</th>
							<th class="text-center">Ft.</th>
						</tr>
					</thead>
					<tbody ng-repeat='tick in ticket'>

							<td ng-if="tick.id == 11 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title=" R2">{{tick.type}}</td>
							<td ng-if="tick.id == 11&& (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Bbls">{{tick.fluid_amount}}</td>
							<td ng-if="tick.id == 11&& (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Ft.">{{tick.fluid_amount/20}}</td>
							<td ng-if="tick.id == 11 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="R2">EMPTY</td>
							<td ng-if="tick.id == 11 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Bbls">0</td>
							<td ng-if="tick.id == 11 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Ft.">0</td>

					</tbody>
				</table>
			</div>
		</div>
		<div class="col-xs-2">
			<div class="table-responsive">
				<table style="width: auto;" align="center" class = "table-condensed">
					<thead>
						<tr>
							<th class="text-center">R3</th>
							<th class="text-center">Barrels</th>
							<th class="text-center">Ft.</th>
						</tr>
					</thead>
					<tbody ng-repeat='tick in ticket'>

							<td ng-if="tick.id == 12 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="R3">{{tick.type}}</td>
							<td ng-if="tick.id == 12 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Bbls">{{tick.fluid_amount}}</td>
							<td ng-if="tick.id == 12 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Ft.">{{tick.fluid_amount/20}}</td>
							<td ng-if="tick.id == 12 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="R3">EMPTY</td>
							<td ng-if="tick.id == 12 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Bbls">0</td>
							<td ng-if="tick.id == 12 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Ft.">0</td>

					</tbody>
				</table>
			</div>
		</div>
	</div>
<br>	
	<div class="container">
		<div class="col-xs-2">
			<div class="table-responsive">
				<table style="width: auto;" align="center" class = "table-condensed">
					<thead>
						<tr>
							<th class="text-center">R4</th>
							<th class="text-center">Barrels</th>
							<th class="text-center">Ft.</th>
						</tr>
					</thead>
					<tbody ng-repeat='tick in ticket'>

							<td ng-if="tick.id == 13 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="R4">{{tick.type}}</td>
							<td ng-if="tick.id == 13 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Bbls">{{tick.fluid_amount}}</td>
							<td ng-if="tick.id == 13 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Ft.">{{tick.fluid_amount/20}}</td>
							<td ng-if="tick.id == 13 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="R4">EMPTY</td>
							<td ng-if="tick.id == 13 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Bbls">0</td>
							<td ng-if="tick.id == 13 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Ft.">0</td>

					</tbody>
				</table>
			</div>
		</div>
		<div class="col-xs-2">
			<div class="table-responsive">
				<table style="width: auto;" align="center" class = "table-condensed">
					<thead>
						<tr>
							<th class="text-center">R5</th>
							<th class="text-center">Barrels</th>
							<th class="text-center">Ft.</th>
						</tr>
					</thead>
					<tbody ng-repeat='tick in ticket'>

							<td ng-if="tick.id == 14 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="R5">{{tick.type}}</td>
							<td ng-if="tick.id == 14 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Bbls">{{tick.fluid_amount}}</td>
							<td ng-if="tick.id == 14 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Ft.">{{tick.fluid_amount/20}}</td>
							<td ng-if="tick.id == 14 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="R5">EMPTY</td>
							<td ng-if="tick.id == 14 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Bbls">0</td>
							<td ng-if="tick.id == 14 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Ft.">0</td>

					</tbody>
				</table>
			</div>
		</div>
		<div class="col-xs-2">
			<div class="table-responsive">
				<table style="width: auto;" align="center" class = "table-condensed">
					<thead>
						<tr>
							<th class="text-center">P1</th>
							<th class="text-center">Barrels</th>
							<th class="text-center">Ft.</th>
						</tr>
					</thead>
					<tbody ng-repeat='tick in ticket'>

							<td ng-if="tick.id == 15 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="P1">{{tick.type}}</td>
							<td ng-if="tick.id == 15 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Bbls">{{tick.fluid_amount}}</td>
							<td ng-if="tick.id == 15 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Ft.">{{tick.fluid_amount/20}}</td>
							<td ng-if="tick.id == 15 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="P1">EMPTY</td>
							<td ng-if="tick.id == 15 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Bbls">0</td>
							<td ng-if="tick.id == 15 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Ft.">0</td>

					</tbody>
				</table>
			</div>
		</div>
		<div class="col-xs-2">
			<div class="table-responsive">
				<table style="width: auto;" align="center" class = "table-condensed">
					<thead>
						<tr>
							<th class="text-center">P2</th>
							<th class="text-center">Barrels</th>
							<th class="text-center">Ft.</th>
						</tr>
					</thead>
					<tbody ng-repeat='tick in ticket'>

							<td ng-if="tick.id == 16 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="P2">{{tick.type}}</td>
							<td ng-if="tick.id == 16 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Bbls">{{tick.fluid_amount}}</td>
							<td ng-if="tick.id == 16 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Ft.">{{tick.fluid_amount/20}}</td>
							<td ng-if="tick.id == 16 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="P2">EMPTY</td>
							<td ng-if="tick.id == 16 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Bbls">0</td>
							<td ng-if="tick.id == 16 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Ft.">0</td>

					</tbody>
				</table>
			</div>
		</div>
		<div class="col-xs-2">
			<div class="table-responsive">
				<table style="width: auto;" align="center" class = "table-condensed">
					<thead>
						<tr>
							<th class="text-center">P3</th>
							<th class="text-center">Barrels</th>
							<th class="text-center">Ft.</th>
						</tr>
					</thead>
					<tbody ng-repeat='tick in ticket'>

							<td ng-if="tick.id == 17 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="P3">{{tick.type}}</td>
							<td ng-if="tick.id == 17 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Bbls">{{tick.fluid_amount}}</td>
							<td ng-if="tick.id == 17 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Ft.">{{tick.fluid_amount/20}}</td>
							<td ng-if="tick.id == 17 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="P3">EMPTY</td>
							<td ng-if="tick.id == 17 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Bbls">0</td>
							<td ng-if="tick.id == 17 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Ft.">0</td>

					</tbody>
				</table>
			</div>
		</div>
		<div class="col-xs-2">
			<div class="table-responsive">
				<table style="width: auto;" align="center" class = "table-condensed">
					<thead>
						<tr>
							<th class="text-center">P4</th>
							<th class="text-center">Barrels</th>
							<th class="text-center">Ft.</th>
						</tr>
					</thead>
					<tbody ng-repeat='tick in ticket'>

							<td ng-if="tick.id == 18 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="P4">{{tick.type}}</td>
							<td ng-if="tick.id == 18 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Bbls">{{tick.fluid_amount}}</td>
							<td ng-if="tick.id == 18 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Ft.">{{tick.fluid_amount/20}}</td>
							<td ng-if="tick.id == 18 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="P4">EMPTY</td>
							<td ng-if="tick.id == 18 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Bbls">0</td>
							<td ng-if="tick.id == 18 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Ft.">0</td>

					</tbody>
				</table>
			</div>
		</div>
	</div>
<br>
	<div class="container">
		<div class="col-xs-2">
			<div class="table-responsive">
				<table style="width: auto;" align="center" class = "table-condensed">
					<thead>
						<tr>
							<th class="text-center">Catch 1</th>
							<th class="text-center">Barrels</th>
							<th class="text-center">Ft.</th>
						</tr>
					</thead>
					<tbody ng-repeat='tick in ticket'>

							<td ng-if="tick.id == 19 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Catch 1">{{tick.type}}</td>
							<td ng-if="tick.id == 19 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Bbls">{{tick.fluid_amount}}</td>
							<td ng-if="tick.id == 19 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Ft.">{{tick.fluid_amount/20}}</td>
							<td ng-if="tick.id == 19 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Catch 1">EMPTY</td>
							<td ng-if="tick.id == 19 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Bbls">0</td>
							<td ng-if="tick.id == 19 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Ft.">0</td>

					</tbody>
				</table>
			</div>
		</div>
		<div class="col-xs-2">
			<div class="table-responsive">
				<table style="width: auto;" align="center" class = "table-condensed">
					<thead>
						<tr>
							<th class="text-center">Catch 2</th>
							<th class="text-center">Barrels</th>
							<th class="text-center">Ft.</th>
						</tr>
					</thead>
					<tbody ng-repeat='tick in ticket'>

							<td ng-if="tick.id == 20 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Catch 2">{{tick.type}}</td>
							<td ng-if="tick.id == 20 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Bbls">{{tick.fluid_amount}}</td>
							<td ng-if="tick.id == 20 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Ft.">{{tick.fluid_amount/20}}</td>
							<td ng-if="tick.id == 20 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Catch 2">EMPTY</td>
							<td ng-if="tick.id == 20 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Bbls">0</td>
							<td ng-if="tick.id == 20 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Ft.">0</td>

					</tbody>
				</table>
			</div>
		</div>
		<div class="col-xs-2">
			<div class="table-responsive">
				<table style="width: auto;" align="center" class = "table-condensed">
					<thead>
						<tr>
							<th class="text-center">Catch 3</th>
							<th class="text-center">Barrels</th>
							<th class="text-center">Ft.</th>
						</tr>
					</thead>
					<tbody ng-repeat='tick in ticket'>

							<td ng-if="tick.id == 21 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Catch 3">{{tick.type}}</td>
							<td ng-if="tick.id == 21 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Bbls">{{tick.fluid_amount}}</td>
							<td ng-if="tick.id == 21 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Ft.">{{tick.fluid_amount/20}}</td>
							<td ng-if="tick.id == 21 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Catch 3">EMPTY</td>
							<td ng-if="tick.id == 21 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Bbls">0</td>
							<td ng-if="tick.id == 21 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Ft.">0</td>

					</tbody>
				</table>
			</div>
		</div>
		<div class="col-xs-2">
			<div class="table-responsive">
				<table style="width: auto;" align="center" class = "table-condensed">
					<thead>
						<tr>
							<th class="text-center">Catch 4</th>
							<th class="text-center">Barrels</th>
							<th class="text-center">Ft.</th>
						</tr>
					</thead>
					<tbody ng-repeat='tick in ticket'>

							<td ng-if="tick.id == 22 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Catch 4">{{tick.type}}</td>
							<td ng-if="tick.id == 22 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Bbls">{{tick.fluid_amount}}</td>
							<td ng-if="tick.id == 22 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Ft.">{{tick.fluid_amount/20}}</td>
							<td ng-if="tick.id == 22 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Catch 4">EMPTY</td>
							<td ng-if="tick.id == 22 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Bbls">0</td>
							<td ng-if="tick.id == 22 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Ft.">0</td>

					</tbody>
				</table>
			</div>
		</div>
		<div class="col-xs-2">
			<div class="table-responsive">
				<table style="width: auto;" align="center" class = "table-condensed">
					<thead>
						<tr>
							<th class="text-center">Catch 5</th>
							<th class="text-center">Barrels</th>
							<th class="text-center">Ft.</th>
						</tr>
					</thead>
					<tbody ng-repeat='tick in ticket'>

							<td ng-if="tick.id == 23 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Catch 5">{{tick.type}}</td>
							<td ng-if="tick.id == 23 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Bbls">{{tick.fluid_amount}}</td>
							<td ng-if="tick.id == 23 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Ft.">{{tick.fluid_amount/20}}</td>
							<td ng-if="tick.id == 23 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Catch 5">EMPTY</td>
							<td ng-if="tick.id == 23 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Bbls">0</td>
							<td ng-if="tick.id == 23 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Ft.">0</td>

					</tbody>
				</table>
			</div>
		</div>
		<div class="col-xs-2">
			<div class="table-responsive">
				<table style="width: auto;" align="center" class = "table-condensed">
					<thead>
						<tr>
							<th class="text-center">Catch 6</th>
							<th class="text-center">Barrels</th>
							<th class="text-center">Ft.</th>
						</tr>
					</thead>
					<tbody ng-repeat='tick in ticket'>

							<td ng-if="tick.id == 24 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Catch 6">{{tick.type}}</td>
							<td ng-if="tick.id == 24 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Bbls">{{tick.fluid_amount}}</td>
							<td ng-if="tick.id == 24 && (tick.fluid_amount != NULL && tick.fluid_amount != 0)" class="text-center" data-title="Ft.">{{tick.fluid_amount/20}}</td>
							<td ng-if="tick.id == 24 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Catch 6">EMPTY</td>
							<td ng-if="tick.id == 24 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Bbls">0</td>
							<td ng-if="tick.id == 24 && (tick.fluid_amount == NULL || tick.fluid_amount == 0)" class="text-center" data-title="Ft.">0</td>

					</tbody>
				</table>
			</div>
		</div>
	</div>



<?php } ?>	

&nbsp;  
						
	<div class="modal fade modal-wide" id="outgoingSelect" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <form name="frmOutgoingSelect">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Pick Outgoing Ticket Type</h4>
                    </div>
                    <div class="modal-body">
	                    <div class="row">
							<div class="form-group col-md-4">
								<select ng-model="SearchOutgoingTicketTypes" ng-change="Search()" class="form-control">
									<option selected value="0">---select ticket type---</option>
									<option ng-repeat="item in OutgoingTicketTypes" value="{{item.id}}" ng-selected="item.id==SearchOutgoingTicketTypes">{{item.common_name}}</option>
                				</select>                
							</div>
						</div>

						<div class="modal-footer">
                    		<button type="button" class="btn btn-primary" ng-click="outgoingSelect()" ng-disabled="SearchOutgoingTicketTypes==0"><i class="fa fa-arrow-right"></i>Submit</button>
							<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i>Close</button>
                    	</div>
                	</div>
                </div>
			</form>
        </div>
	</div><!-- END MODAL WINDOW -->


	<!--Add Popup Window-->
    <div class="modal fade modal-wide" id="tankAdjustment" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <form name="frmTankAdjustment">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Inventory Adjustment</h4>
                    </div>
                    <div class="modal-body">
	                    <div class="row">
							<div class="form-group col-md-4">
								<label class="control-label">Tank :</label><br />
								<select class='form-control' ng-model='adjustment.tank_to_id2' ng-options="tank.id as tank.name for tank in tankList"> 
									<option value="">Select a tank</option>
								</select>                           
							</div>
							<div class="form-group col-md-2">
								<label ng-if="adjustment.tank_to_id2 >= 1" class="control-label">+ or -</label><br />
								<select ng-if="adjustment.tank_to_id2 >= 1" class='form-control' ng-model='adjustment.AddorSubtract' ng-options="adjustment.value as adjustment.label for adjustment in AddorSubtract">
								</select>	                
							</div>
							<div class="form-group col-md-3">
								<label ng-if="adjustment.tank_to_id2 >= 1" class="control-label"># of Barrels:</label><br />
								<div ng-if="adjustment.tank_to_id2 >= 1" class="input-group">
									<input type="number" value="" ng-model="adjustment.barrels" class='form-control' ng-blur="barrels_to_feet_adjustment();">
								</div>
							</div>
							
							<div class="form-group col-md-3">
								<label ng-if="adjustment.tank_to_id2 >= 1" class="control-label">Ft.:</label><br />
								<div ng-if="adjustment.tank_to_id2 >= 1" class="input-group">
									<input type="number" value="" ng-model="adjustment.feet" class='form-control' ng-blur="feet_to_barrels_adjustment();">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-5">
                            	<label ng-if="adjustment.barrels >= 1" class="control-label">Fluid Type:</label><br />
								<select ng-if="adjustment.barrels >= 1" class='form-control' ng-model='adjustment.fluid_type_id' ng-options="fluidType.id as fluidType.type for fluidType in fluidTypeList">
									<option value="">Select a fluid type</option>
                    			</select>
                        	</div>
                        	<div class="form-group col-md-6">
								<label ng-if="adjustment.fluid_type_id >= 1" class="control-label">Notes:</label><br />
								<textarea ng-if="adjustment.fluid_type_id >= 1" type="text" name="notes" ng-model="adjustment.notes" value="" class='form-control' rows='2'></textarea>
            				</div>
					 	</div>                 
					 	<div class="modal-footer">
                        	<button ng-if="adjustment.fluid_type_id >= 1" type="button" class="btn btn-primary" ng-click="tankAdjustment()" ng-disabled="!frmTankAdjustment.$valid"><i class="fa fa-arrow-right"></i>Submit</button>
							<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i>Close</button>
                    	</div>
                	</div>
                </div>
			</form>
        </div>
	</div><!-- END MODAL WINDOW -->

	<div class="modal fade modal-wide" id="tankTransfer" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <form name="frmTankTransfer">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Tank Transfer</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
	                         <div class="form-group col-md-6">
		                        <label class="control-label">From Tank:</label><br />
								<select class='form-control' ng-model='transfer.tank_from_id' ng-options="tank.id as tank.name for tank in tankList" ng-blur="loadOtherTankList(transfer.tank_from_id);"> 
 									<option value="">Select a tank</option>
								</select>                           
							</div> <!--ng-if="recordAdd.tank_from_id >= 1" -->
                            <div class="form-group col-md-6">
                                <label ng-if="transfer.tank_from_id >= 1" class="control-label">To Tank:</label><br />
								<select ng-if="transfer.tank_from_id >= 1" class='form-control' ng-model='transfer.tank_to_id' ng-options="tank.id as tank.name for tank in tankList2" ng-blur="loadOtherFluidList(transfer.tank_from_id);">
									<option value="">Select a tank</option>
								</select>
                            </div>                  
                        </div>
                        <div class="row">
	                         <div class="form-group col-md-4">
                                <label ng-if="transfer.tank_to_id >= 1" class="control-label">Fluid Type:</label><br />
                               <select ng-if="transfer.tank_to_id >= 1" class='form-control' ng-model='transfer.fluid_type_id' ng-options="fluidType.id as fluidType.type for fluidType in fluidTypeList">
	                               <option value="">Select a fluid type</option>
                    			</select>
                            </div>
                             <div class="form-group col-md-3">
                                <label ng-if="transfer.fluid_type_id >= 1" class="control-label">Barrels:</label><br />
                                <div class="input-group">
									<input ng-if="transfer.fluid_type_id >= 1" type="number" value="" ng-model="transfer.barrels" class='form-control' ng-blur="barrels_to_feet_transfer();">
								</div>
                            </div>
                            <div class="form-group col-md-3">
                                <label ng-if="transfer.fluid_type_id >= 1" class="control-label">Ft.:</label><br />
                                <div class="input-group">
									<input ng-if="transfer.fluid_type_id >= 1" type="number" value="" ng-model="transfer.feet" class='form-control' ng-blur="feet_to_barrels_transfer();">
								</div>
                            </div>
                        </div>
                        <div class="row"> 
                        	<div class="form-group col-md-6">
								<label ng-if="transfer.barrels >= 1" class="control-label">Notes:</label><br />
								<textarea ng-if="transfer.barrels >= 1" type="text" name="notes" ng-model="transfer.notes" value="" class='form-control' rows='2'></textarea>
            				</div>
                        </div>
						<!--<div class="row">
	                        <div class="form-group col-md-6">
                                <label class="control-label">Trucking Company:</label><br />
								<select class='form-control' ng-model='recordAdd.trucking_company_id' ng-options="company.id as company.name for company in truckingCompanyList">
									<option value="">Select a trucking company</option>
								</select>
	                        </div>
                    </div>-->
                    	 <div class="modal-footer">
						 	<button ng-if="transfer.barrels >= 1" type="button" class="btn btn-primary" ng-click="tankTransfer()" ng-disabled="!frmTankTransfer.$valid"><i class="fa fa-arrow-right"></i>Submit</button>
						 	<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i>Close</button>
                    	</div>
                	</div>
           		</div>
           </form>
        </div>
	</div><!-- END MODAL WINDOW -->

<div class="modal fade modal-wide" id="tankLedger" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <form name="frmTankLedger">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Inventory Adjustment</h4>
                    </div>
                    <div class="modal-body">
	                    <div class="row">
		                    <div class="form-group col-md-6">
								<label class="control-label">Start Date:</label><br />
								<div class="input-group">
									<input type="text" class="form-control" uib-datepicker-popup="{{format}}" ng-model="ledger.start_date" is-open="opened" datepicker-options="dateOptions" ng-inputmask="99/99/9999" ng-required="true" close-text="Close" placeholder="Select a date"/>
									<span class="input-group-btn">
										<button type="button" class="btn btn-default" ng-click="open($event)"><i class="fa fa-calendar"></i></button>
										</span>
								</div>
		                    </div>
							<div class="form-group col-md-6">
								<label class="control-label">End Date:</label><br />
								<div class="input-group">
									<input type="text" class="form-control" uib-datepicker-popup="{{format}}" ng-model="ledger.end_date" is-open="opened" datepicker-options="dateOptions" ng-inputmask="99/99/9999" ng-required="true" close-text="Close" placeholder="Select a date"/>
									<span class="input-group-btn">
										<button type="button" class="btn btn-default" ng-click="open($event)"><i class="fa fa-calendar"></i></button>
										</span>
								</div>
		                    </div>
					 	</div>                 
					 	<div class="modal-footer">
                        	<button type="button" class="btn btn-primary" ng-click="tankLedger()" ng-disabled="!frmTankLedger.$valid"><i class="fa fa-arrow-right"></i>Submit</button>
							<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i>Close</button>
                    	</div>
                	</div>
                </div>
			</form>
        </div>
	</div><!-- END MODAL WINDOW -->

    <!--Add Popup Window End-->	
</div>


<script src="<?=$rootScope["RootUrl"]?>/includes/app/tanksCtrl.js"></script>

<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>