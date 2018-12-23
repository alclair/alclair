<div ng-controller="reportFlowrate_LNG">
    <div style="width: 100%; margin-left: auto; margin-right: auto;">
        <div style="text-align: center;">
            <h1 style="font-weight: bold;">Daily Flowrate</h1>          
            Queen:
            <!--<select style="width: 100px; height: 30px;" ng-model="disposalwell" ng-options="disposalwell.id as disposalwell.common_name for disposalwell in disposalWellList"></select>-->                       
            <!--<select style="width: 100px; height: 30px;" ng-model="lng_queens" ng-options="lng_queens.id as lng_queens.name for lng_queens in LngQueensList"></select>-->                       
            
            <!--Year:
            <select style="width: 100px; height: 30px;" ng-model="year_month" ng-options="year as year for year in yearRange"></select>
            Month:
            <select style="width: 100px; height: 30px;" ng-model="month_month" ng-options="month.value as month.label for month in monthRange" ></select>    -->  
            <!--ng-options="deliverymethod.value as deliverymethod.label for deliverymethod in DeliveryMethodList">     
            ng-options="month as month for month in monthRange"-->
            <!--<input type="button" class="btn btn-success btn-sm" ng-click="selectMonth();" value="Load Report" />-->
        </div>
        <div style="text-align:center;">
            <span ng-repeat="label in labelRange">
                <span style="display:inline-block;width:10px;height:10px;background:{{label.color}};"></span><span>  {{label.text}}</span>
            </span>
        </div>

        <div id="div_flowrate"></div>        
    </div>
</div>

<script src="<?=$rootScope["RootUrl"]?>/includes/app/reportFlowrate_LNG.js"></script>
