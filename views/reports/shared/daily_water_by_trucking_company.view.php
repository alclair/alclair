<div ng-controller="reportDailyWaterByTruckingCompany">
    <div style="width: 100%; margin-left: auto; margin-right: auto;">
        <div style="text-align: center;">
            <h1 style="font-weight: bold;">Daily Water Totals by Trucking Company</h1>  
            Disposal Well:
            <select style="width: 100px; height: 30px;" ng-model="disposalwell" ng-options="disposalwell.id as disposalwell.common_name for disposalwell in disposalWellList"></select>                          
            Year:
            <select style="width: 100px; height: 30px;" ng-model="year_month" ng-options="year as year for year in yearRange"></select>
            Month:
            <select style="width: 100px; height: 30px;" ng-model="month_month" ng-options="month.value as month.label for month in monthRange"></select>            
            <!--ng-options="month as month for month in monthRange"-->
            <input type="button" class="btn btn-success btn-sm" ng-click="selectMonth();" value="Load Report" />
        </div>

        <div style="float: right;">
            <label>
                <input type="radio" name="daily_water_by_trucking_copany_mode" value="grouped" id="daily_water_by_trucking_copany_grouped">
                Grouped</label>
            <label>
                <input type="radio" name="daily_water_by_trucking_copany_mode" value="stacked" id="daily_water_by_trucking_copany_stacked" checked>
                Stacked</label>
        </div>
        <div class="col-md-12">            
            <div class="col-md-2" ng-repeat="label in labelRange">
                <span style="display:inline-block;width:10px;height:10px;background:{{label.color}};"></span><span>  {{label.text}}</span>
            </div>  
        </div>

        <div id="div_daily_water_by_trucking_company"></div>
    </div>
</div>

<script src="<?=$rootScope["RootUrl"]?>/includes/app/reportDailyWaterByTruckingCompany.js"></script>
