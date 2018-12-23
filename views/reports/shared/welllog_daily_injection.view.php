<div ng-controller="reportWellLogDailyInjection">
    <div style="width: 100%; margin-left: auto; margin-right: auto;">
        <div style="text-align: center;">
            <h1 style="font-weight: bold;">Daily Injection Rate and Pressure</h1>          
            Disposal Well:
            <select style="width: 100px; height: 30px;" ng-model="disposalwell" ng-options="disposalwell.id as disposalwell.common_name for disposalwell in disposalWellList"></select>                       
            Year:
            <select style="width: 100px; height: 30px;" ng-model="year_month" ng-options="year as year for year in yearRange"></select>
            Month:
            <select style="width: 100px; height: 30px;" ng-model="month_month" ng-options="month.value as month.label for month in monthRange" ></select>      
            <!--ng-options="deliverymethod.value as deliverymethod.label for deliverymethod in DeliveryMethodList">     
            ng-options="month as month for month in monthRange"-->
            <input type="button" class="btn btn-success btn-sm" ng-click="selectMonth();" value="Load Report" />
        </div>
        <div style="text-align:center;">
            <span ng-repeat="label in labelRange">
                <span style="display:inline-block;width:10px;height:10px;background:{{label.color}};"></span><span>  {{label.text}}</span>
            </span>
        </div>

        <div id="div_welllog_daily_injection"></div>        
    </div>
</div>

<script src="<?=$rootScope["RootUrl"]?>/includes/app/reportWellLogDailyInjection.js"></script>
