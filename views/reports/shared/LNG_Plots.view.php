<div ng-controller="ProducePlots_LNG">
    <div style="width: 100%; margin-left: auto; margin-right: auto;">
        <div style="text-align: center;">
            <h1 style="font-weight: bold;">Tank Level (LNGg)</h1>      
            <div class="row">      
	        	<div class="form-group col-sm-3">   
            <!--Queen:-->
            <!--<select style="width: 100px; height: 30px;" ng-model="disposalwell" ng-options="disposalwell.id as disposalwell.common_name for disposalwell in disposalWellList"></select>-->          
                	<label class="control-label" style="padding-left:160px;">Queen:</label><br />
					<select style="width: 100px; height: 30px; float:right;"  ng-model="lng_queens" ng-options="lng_queens.queens_id as lng_queens.name for lng_queens in LngQueensList"></select>                       
	      
            	</div>
            <!--Year:
            <select style="width: 100px; height: 30px;" ng-model="year_month" ng-options="year as year for year in yearRange"></select>
            Month:
            <select style="width: 100px; height: 30px;" ng-model="month_month" ng-options="month.value as month.label for month in monthRange" ></select>    -->  
            <!--ng-options="deliverymethod.value as deliverymethod.label for deliverymethod in DeliveryMethodList">     
            ng-options="month as month for month in monthRange"-->
        
             	<div class="form-group col-sm-2">
			 		<label class="control-label">Start date:</label><br />
			 		<div class="input-group input-group-sm">
			 			<input type="text" class="form-control" uib-datepicker-popup="{{format}}" ng-model="startdate" is-open="opened_startdate" datepicker-options="dateOptions" ng-inputmask="9999-99-99" ng-required="true" close-text="Close" />
			 			<span class="input-group-btn">
			 				<button type="button" class="btn btn-default btn-sm" ng-click="openStartDate($event)"><i class="fa fa-calendar"></i></button>
			 			</span>
			 			
            		</div>
        		</div>
				<div class="form-group col-sm-2">
					<label class="control-label">End date:</label><br />
					<div class="input-group input-group-sm">
                		<input type="text" class="form-control" uib-datepicker-popup="{{format}}" ng-model="enddate"
							is-open="opened_enddate" datepicker-options="dateOptions"  
							ng-inputmask="9999-99-99" ng-required="true" close-text="Close" />
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-sm" ng-click="openEndDate($event)"><i class="fa fa-calendar"></i></button>
							</span>
            		</div>
        		</div>
            
				<div class="form-group col-sm-1">
					<label class="control-label"></label><br />
					<input type="button" class="btn btn-success btn-sm" style="float:left;" ng-click="selectDates();" value="Load Dates" />
        		</div>
        		<div class="form-group col-sm-1">
					<label class="control-label"></label><br />
					<input type="button" class="btn btn-success btn-sm" style="float:left;" ng-click="exportData();" value="Export Data" />
        		</div>
        	</div>
        
        <div class="row">   

        </div>
        <div id="chartTankLevel"></div>        
    </div>
    
    <div style="text-align: center;">
    	<h1 style="font-weight: bold;">Flow Rate (SCFH)</h1>      
			<div class="row">   
			</div>
        <div id="chartFlowRate"></div>        
    </div>
	
	<div style="text-align: center;">
    	<h1 style="font-weight: bold;">Totalizer (LNGg)</h1>      
			<div class="row">   
			</div>
        <div id="chartTotalizer"></div>        
    </div>
    
</div>

<script src="<?=$rootScope["RootUrl"]?>/includes/app/LNG_Plots.js"></script>
