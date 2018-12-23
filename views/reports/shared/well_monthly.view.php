<div ng-controller="reportWellMonthlyCtrl">
    <div style="width: 70%; margin-left: auto; margin-right: auto;">
        <div style="text-align: center;">
            <h1 style="font-weight: bold;">Well Report by Disposal Well</h1>
            
            <br />
            <span style="display:inline-block;width:10px;height:10px;background:#99dcf2;"></span><span>  CARTWRIGHT</span>
            <span style="display:inline-block;width:10px;height:10px;background:#5896ab;"></span><span>  EAST FORK</span>
            <span style="display:inline-block;width:10px;height:10px;background:#175063;"></span><span>  KILLDEER WEST</span>
            <br />
            Year:
            <select style="width: 100px; height: 30px;" ng-model="year" ng-options="year as year for year in yearRange"></select>
            Month:
            <select style="width: 100px; height: 30px;" ng-model="month" ng-options="month as month for month in monthRange"></select>
            <input type="button" class="btn btn-success" ng-click="selectMonth();" value="Search" />
        </div>
		<div style="float:right;">
			<label>
                <input type="radio" name="mode" value="grouped" id="grouped">
                Grouped</label>
            <label>
                <input type="radio" name="mode" value="stacked" id="stacked" checked>
                Stacked</label>
			</div>
        <div id="wellmonthlydisposal"></div>
        <div>
            <hr />
        </div>
        <div style="text-align: center;">
            <h1 style="font-weight: bold;">Well Report by Water Type</h1>
			
            <br />
            <span style="display:inline-block;width:10px;height:10px;background:#99dcf2;"></span><span>  FLOWBACK</span>
            <span style="display:inline-block;width:10px;height:10px;background:#5896ab;"></span><span>  PRODUCTION</span>
            <span style="display:inline-block;width:10px;height:10px;background:#175063;"></span><span>  DIRTY WATER</span>
            <br />
			Disposal Well:
            <select style="width: 100px; height: 30px;" ng-model="disposalwell" ng-options="disposalwell.id as disposalwell.common_name for disposalwell in disposalWellList"></select>
            Year:
            <select style="width: 100px; height: 30px;" ng-model="year_month" ng-options="year as year for year in yearRange"></select>
            Month:
            <select style="width: 100px; height: 30px;" ng-model="month_month" ng-options="month as month for month in monthRange"></select>
            
            <input type="button" class="btn btn-success" ng-click="selectMonth_Month();" value="Load Report" />
        </div>
		<div style="float:right;">
            <label>
                <input type="radio" name="mode_month" value="grouped" id="grouped_month">
                Grouped</label>
            <label>
                <input type="radio" name="mode_month" value="stacked" id="stacked_month" checked>
                Stacked</label>
			</div>
        <div id="wellmonthlymonthly"></div>
    </div>
</div>

<script src="<?=$rootScope["RootUrl"]?>/includes/app/reportWellMonthlyCtrl.js"></script>