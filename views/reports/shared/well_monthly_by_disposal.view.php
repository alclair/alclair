<div ng-controller="reportWellMonthlyByDisposalCtrl">
    <div style="width: 70%; margin-left: auto; margin-right: auto;">
        <div style="text-align: center;">
            <h1 style="font-weight: bold;">Well Report by Disposal Well</h1>
            
            <br />

            <div class="col-md-12">
                <span ng-repeat="label in labelRange">
                    <span style="display: inline-block; width: 10px; height: 10px; background: {{label.color}};"></span><span>{{label.text}}</span>
                </span>
            </div>
                        
            <br />
            Year:
            <select style="width: 100px; height: 30px;" ng-model="year" ng-options="year as year for year in yearRange"></select>
            Month:
            <select style="width: 100px; height: 30px;" ng-model="month" ng-options="month as month for month in monthRange"></select>
            <input type="button" class="btn btn-success btn-sm" ng-click="selectMonth();" value="Load Report" />
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
    </div>
</div>
<script src="<?=$rootScope["RootUrl"]?>/includes/app/reportWellMonthlyByDisposalCtrl.js"></script>
