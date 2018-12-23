//var matrixsurveyApp = angular.module('matrixsurveyApp', ['ngAnimate', 'angularFileUpload', 'ngSanitize', 'ui.bootstrap', 'ui.bootstrap.datetimepicker']);
var swdApp = angular.module('swdApp', ['ngAnimate', 'ngSanitize', 'ui.bootstrap', 'angularFileUpload', 'ngCookies', 'moment-picker', 'ngPatternRestrict']);

swdApp.filter('nl2br', function () {

    return function (input) {

        if (isEmpty(input)) return "";

        var v = input.replace(/(?:\r\n|\r|\n)/g, '<br />');

        v = v.replace(/(?:\r\n|\r|\n)/g, '<br />');

        return v;

    }

});
swdApp.directive('ngEnter', function() {
        return function(scope, element, attrs) {
            element.bind("keydown keypress", function(event) {
                if(event.which === 13) {
                        scope.$apply(function(){
                                scope.$eval(attrs.ngEnter);
                        });
                        
                        event.preventDefault();
                }
            });
        };
});

swdApp.directive('ngResizable', function ($document) {

    return function (scope, element) {

        element.resizable();

    }

});



swdApp.directive('ngDraggable', function ($document) {

    return function (scope, element) {

        element.draggable({ handle: ".modal-header", cursor: "move" });

    }

});



swdApp.directive('ngPopover', function () {

    return {

        restrict: 'A',

        replace: false,

        transclude: false,



        link: function (scope, element, attrs) {

            element.popover();

        }

    }

});

swdApp.directive('ngInputmask', function () {

    return {

        restrict: 'A',

        replace: false,

        transclude: false,



        link: function (scope, element, attrs) {

            element.mask(attrs.ngInputmask);

        }

    }

});

swdApp.directive('ngTimepicker', function ($parse) {

    return {

        restrict: 'A',

        replace: false,

        transclude: false,



        link: function (scope, element, attrs) {

            element.datetimepicker({

                pickDate: false,

                useCurrent: false

            }).on("dp.change", function (e) {

                var d = new Date(e.date);

                var hour = d.getHours();

                if (hour < 10) hour = "0" + hour;

                var minute = d.getMinutes();

                if (minute < 10) minute = "0" + minute;

                //alert(hour + ":" + minute);

                var g = $parse(attrs.ngTimepicker);

                g.assign(scope, hour + ":" + minute);

                scope.$apply();

                if (attrs.ngChange != undefined) {

                    scope.$apply(attrs.ngChange);

                }

            });;

        }

    }

});

swdApp.directive('ngDatepicker', function ($parse) {

    return {

        restrict: 'A',

        replace: false,

        transclude: false,



        link: function (scope, element, attrs) {

            element.datetimepicker({

                pickTime: false,

                useCurrent: false



            }).on("dp.change", function (e) {

                var d = new Date(e.date);

                var month = d.getMonth() + 1;

                if (month < 10) month = "0" + month;

                var dt = d.getDate();

                if (dt < 10) dt = "0" + dt;

                var g = $parse(attrs.ngDatepicker);

                g.assign(scope, month + "/" + dt + "/" + d.getFullYear());

                scope.$apply();

                if (attrs.ngChange != undefined) {

                    scope.$apply(attrs.ngChange);

                }

            });

        }

    }

});



swdApp.controller('appGlobalController', ['$http', '$scope', '$interval', function ($http, $scope, $interval) {

    //$interval(function () {

    //    AppDataService.checkUserPermission("", "").success(function (data) {

    //        //toastr.info("check login");

    //        if (data.code == "logout") {

    //            bootbox.alert("You are now logged out.", function () {

    //                window.location.href = window.root_url + "/account/login";

    //            });



    //        }

    //    });

    //}, 10000);
    
    

}]);


function reload(url) {

    window.location.href = url;

}

function isEmpty(str) {

    return str == "" || str == null || str == undefined;

}

function nextTabIndex(id) {

    var found = 0;

    $("input[type!='hidden'],select,textarea,button").each(function () {

        if (found == 1) {

            $(this).focus();

            $(this).select();

            return false;

        }

        if (id == $(this).attr("id") && found == 0) {

            found = 1;

        }



    });

}

var arrMonthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

function formatDate(d) {

    var year = d.getFullYear();

    var month = d.getMonth() + 1;

    if (month < 10) month = "0" + month;

    var date = d.getDate();

    if (date < 10) date = "0" + date;

    return year + "-" + month + "-" + date;

}

function dateText(d) {

    var year = d.getFullYear();

    var month = arrMonthNames[d.getMonth()];

    var date = d.getDate();

    return month + " " + date + ", " + year;

}

function isInteger(value) {

    var valid = true;

    if (!isEmpty(value)) {

        var value2 = parseInt(value);



        if (isNaN(value2)) {

            valid = false;

        }

    }

    return valid;

}

function isMoney(value) {

    var valid = true;

    if (!isEmpty(value)) {

        var value2 = parseFloat(value);



        if (isNaN(value2)) {

            valid = false;

        }

    }

    return valid;

}

function isPercentage(value) {

    var valid = true;

    if (!isEmpty(value)) {

        var value2 = parseInt(value);



        if (isNaN(value2) || value2 <= 0 || value2 > 100) {

            valid = false;

        }

    }

    return valid;

}

function isEmail(value) {

    var valid = true;

    if (!isEmpty(value)) {

        var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        valid = re.test(value);

    }



    return valid;

}
function myblockui()
{
    $.blockUI({
        message: '<img src="' + window.cfg.rootUrl + '/images/ajax-loader.gif" />',
        css: { width: '4%', border: '0px solid #ffffff', cursor: 'wait',backgroundColor:'#999999'},
        overlayCSS: { backgroundColor: '#999999',opacity:0.50, cursor: 'wait' }
    });
}