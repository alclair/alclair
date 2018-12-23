<div ng-controller="reportFirstPassYield">
    <div style="width: 100%; margin-left: auto; margin-right: auto;">        
        <div style="text-align: center;">
            <h1 style="font-weight: bold;mar	gin-left:60px">First Pass Yield 
	            <input style="margin-bottom:10px;margin-left:40px;" type="button" class="btn btn-primary btn-md" ng-click="makeExcel();" value="Download Excel" />
	            <h2>Waiting for Artwork & Ready to Ship not included </h2></h1>
			
            <br />

            <div class="col-md-12">
                <span ng-repeat="label in labelRange">
                    <span style="display: inline-block; width: 10px; height: 10px; background: {{label.color}};"></span><span>{{label.text}}</span>
                </span>
            </div>

            <br />
			Monitor:
			<select style="width: 100px; height: 30px;" ng-model='IEM' ng-options="IEM.id as IEM.name for IEM in monitorList"></select>
            Year:
            <select style="width: 100px; height: 30px;" ng-model="year_month" ng-options="year as year for year in yearRange"></select>
            Month:
            <select style="width: 100px; height: 30px;" ng-model="month_month" ng-options="month.value as month.label for month in monthRange" ></select>
            <!--ng-options="month as month for month in monthRange"-->
            
            <input type="button" class="btn btn-success btn-sm" ng-click="selectMonth_Month();" value="Load Report" />
        </div>
		<div style="float:right;">
            <label>
                <input type="radio" name="mode_month" value="grouped" id="grouped_month">
                Grouped</label>
            <label>
                <input type="radio" name="mode_month" value="stacked" id="stacked_month" checked>
                Stacked</label>
			</div>
        <div id="firstpassyield"></div>
    </div>
    
     <div style="width: 100%; margin-left: auto; margin-right: auto;">        
        <div style="text-align: center;">
            <h1 style="font-weight: bold;"> <h2>1st Stop at QC</h2>
			
            <br />

            <div class="col-md-12">
                <span ng-repeat="label3 in labelRange3">
                    <span style="display: inline-block; width: 10px; height: 10px; background: {{label3.color}};"></span><span>{{label3.text}}</span>
                </span>
            </div>
			
            <br />
            
        </div>
		<div style="float:right;">
            <label>
                <input type="radio" name="mode_month_initial_PassFail" value="grouped" id="grouped_month_initial_PassFail">
                Grouped</label>
            <label>
                <input type="radio" name="mode_month_initial_PassFail" value="stacked" id="stacked_month_initial_PassFail" checked>
                Stacked</label>
			</div>
        <div id="firstpassyield_initial_PassFail"></div>
    </div>

    
     <div style="width: 100%; margin-left: auto; margin-right: auto;">        
        <div style="text-align: center;">
            <!--<h1 style="font-weight: bold;">First Pass Yield</h1>-->
             <h2>Includes all States</h2>
			
            <br />

            <div class="col-md-12">
                <span ng-repeat="label2 in labelRange2">
                    <span style="display: inline-block; width: 10px; height: 10px; background: {{label2.color}};"></span><span>{{label2.text}}</span>
                </span>
            </div>
			
            <br />
            
        </div>
		<div style="float:right;">
            <label>
                <input type="radio" name="mode_month_initial" value="grouped" id="grouped_month_initial">
                Grouped</label>
            <label>
                <input type="radio" name="mode_month_initial" value="stacked" id="stacked_month_initial" checked>
                Stacked</label>
			</div>
        <div id="firstpassyield_initial"></div>
    </div>

	 <div style="width: 100%; margin-left: auto; margin-right: auto;">        
        <div style="text-align: center;">
            <!--<h1 style="font-weight: bold;">First Pass Yield</h1>-->
             <h2>Failure Modes</h2>
			
            <br />

            <div class="col-md-12">
                <span ng-repeat="label4 in labelRange4">
                    <span style="display: inline-block; width: 10px; height: 10px; background: {{label4.color}};"></span><span>{{label4.text}}</span>
                </span>
            </div>
			
            <br />
            
        </div>
		<div style="float:right;">
            <label>
                <input type="radio" name="mode_month_failure" value="grouped" id="grouped_month_failure">
                Grouped</label>
            <label>
                <input type="radio" name="mode_month_failure" value="stacked" id="stacked_month_failure" checked>
                Stacked</label>
			</div>
        <div id="firstpassyield_failure"></div>
    </div>
    
    
</div>

<script src="<?=$rootScope["RootUrl"]?>/includes/app/alclairReportFirstPassYield.js"></script>
