<div ng-controller="reportWellMonthlyByDeliveryMethodCtrl">
    <div style="width: 100%; margin-left: auto; margin-right: auto;">        
        <div style="text-align: center;">
            <h1 style="font-weight: bold;">Well Report by Delivery Method</h1>
			
            <br />

            <div class="col-md-12">
                <span ng-repeat="label in labelRange">
                    <span style="display: inline-block; width: 10px; height: 10px; background: {{label.color}};"></span><span>{{label.text}}</span>
                </span>
            </div>

            <br />
			Disposal Well:
            <select style="width: 100px; height: 30px;" ng-model="disposalwell" ng-options="disposalwell.id as disposalwell.common_name for disposalwell in disposalWellList"></select>
            Year:
            <select style="width: 100px; height: 30px;" ng-model="year_month" ng-options="year as year for year in yearRange"></select>
            Month:
            <select style="width: 100px; height: 30px;" ng-model="month_month" ng-options="month.value as month.label for month in monthRange"></select>
            <!--ng-options="month as month for month in monthRange"-->
            Type:
            <select style="width: 100px; height: 30px;" ng-model="type" ng-options="type as type for type in typeList"></select>
            
            <input type="button" class="btn btn-success btn-sm" ng-click="selectMonth_Month();" value="Load Report" />
        </div>
		<div style="float:right;">
            <label>
                <input type="radio" name="mode_month_delivery_method" value="grouped" id="grouped_month_delivery_method">
                Grouped</label>
            <label>
                <input type="radio" name="mode_month_delivery_method" value="stacked" id="stacked_month_delivery_method" checked>
                Stacked</label>
			</div>
        <div id="wellmonthlymonthly_delivery_method"></div>
    </div>
</div>

<script src="<?=$rootScope["RootUrl"]?>/includes/app/reportWellMonthlyByDeliveryMethodCtrl.js"></script>
