<div>
    <div class="col-md-12" ng-controller="exportAll">
        <div class="form-group col-md-12">
            <h2>Export Trucking Company</h2>
        </div>
        <div class="form-group col-md-6">
            <label class="control-label">Start date:</label><br />
            <div class="input-group">
                <input type="text" class="form-control" uib-datepicker-popup="{{format}}" ng-model="startdate"
                     is-open="opened_startdate" datepicker-options="dateOptions" ng-inputmask="9999-99-99"
                     ng-required="true" close-text="Close" />
                <span class="input-group-btn">
                    <button type="button" class="btn btn-default" ng-click="openStartDate($event)"><i class="fa fa-calendar"></i></button>
                </span>
            </div>
        </div>
        <div class="form-group col-md-6">
            <label class="control-label">End date:</label><br />
            <div class="input-group">
                <input type="text" class="form-control" uib-datepicker-popup="{{format}}" ng-model="enddate"
                     is-open="opened_enddate" datepicker-options="dateOptions" 
                    ng-inputmask="9999-99-99" ng-required="true" close-text="Close" />
                <span class="input-group-btn">
                    <button type="button" class="btn btn-default" ng-click="openEndDate($event)"><i class="fa fa-calendar"></i></button>
                </span>
            </div>
        </div>
        <div class="form-group col-md-12">
            <button type="button" class="btn btn-primary" ng-click="export('truckingcompany');">
                <i class="fa fa-save"></i>Export 
            </button>
        </div>
    </div>
</div>

<script src="<?=$rootScope["RootUrl"]?>/includes/app/exportAll.js"></script>