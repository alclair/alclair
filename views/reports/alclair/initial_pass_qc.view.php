<div ng-controller="reportFirstPassYield">
    <div style="width: 100%; margin-left: auto; margin-right: auto;">        
        <div style="text-align: center;">
            <!--<h1 style="font-weight: bold;">First Pass Yield</h1>-->
			
            <br />

            <div class="col-md-12">
                <span ng-repeat="label in labelRange">
                    <span style="display: inline-block; width: 10px; height: 10px; background: {{label.color}};"></span><span>{{label.text}}</span>
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
</div>

<script src="<?=$rootScope["RootUrl"]?>/includes/app/alclairReportFirstPassYield.js"></script>
