swdApp.controller('Manufacturing_Screen_For_Phil', ['$http', '$scope', 'AppDataService', '$upload',  '$cookies', function ($http, $scope, AppDataService, $upload, $cookies) {
	$scope.OrdersList = {};
	$scope.labels5 = [];
	$scope.labelRange5 = [];    		
	$scope.monthRange = AppDataService.monthRange;
	//$scope.month_month = '07';
		
    if (window.cfg.Id > 0)
        $scope.PageIndex = window.cfg.Id;
	$scope.openStart = function ($event) {        
        $scope.openedStart = true;
    };
	$scope.openEnd = function ($event) {        
        $scope.openedEnd = true;
    };
	$scope.dateOptions = {
        formatYear: 'yy',
        startingDay: 1
    };
    $scope.disabled = function (date, mode) {
        return (mode === 'day' && (date.getDay() === 0 || date.getDay() === 6));
    };

    $scope.formats = ['MM/dd/yyyy'];
    $scope.format = $scope.formats[0];
    $scope.ClearSearch=function()
    {
        $scope.SearchText = "";
        $scope.cust_name = "";
		$scope.qc_form = {
	        cust_name: "",
    		};
        $scope.PageIndex = 1;
        $scope.LoadData();
        $cookies.put("SearchText", "");
    }
    
    $scope.RefreshPage = function () {
	    var store_the_month = $scope.month_month;
		localStorage.setItem("store_the_month", store_the_month);
	    console.log("The month is " + store_the_month)
	     setTimeout(function(){
		    location.reload();
		    //console.log("Inside timeout " + month)
		 	//$scope.init(month);   
		}, 1000);    
    }
    $scope.CustomBarChart = function (store_the_month) {
       myblockui();
       if(store_the_month) {
	       $scope.month_month = store_the_month;
       }
      	//console.log("The month selected is" + the_month_selected_is)	
		//console.log("Year is " + $scope.year_month + " and Month is " + $scope.month_month)
        var api_url = window.cfg.apiUrl + "reports/bargraph_for_phil.php?year=" + $scope.year_month + "&month=" + $scope.month_month;

        $http.get(api_url).success(function (result5) {
		console.log("THE TEST " + JSON.stringify(result5.test))
            $.unblockUI();
           
           if($scope.month_month == '01') {
	            $scope.THE_MONTH = 'JANUARY';
            } else if($scope.month_month == '02') {
	            $scope.THE_MONTH = 'FEBRUARY';
	        } else if($scope.month_month == '03') {
	            $scope.THE_MONTH = 'MARCH';
	        } else if($scope.month_month == '04') {
	            $scope.THE_MONTH = 'APRIL';
	        } else if($scope.month_month == '05') {
	            $scope.THE_MONTH = 'MAY';
	        } else if($scope.month_month == '06') {
	            $scope.THE_MONTH = 'JUNE';
	        } else if($scope.month_month == '07') {
	            $scope.THE_MONTH = 'JULY';
	        } else if($scope.month_month == '08') {
	            $scope.THE_MONTH = 'AUGUST';
	        } else if($scope.month_month == '09') {
	            $scope.THE_MONTH = 'SEPTEMBER';
	        } else if($scope.month_month == '10') {
	            $scope.THE_MONTH = 'OCTOBER';
	        } else if($scope.month_month == '11') {
	            $scope.THE_MONTH = 'NOVEMBER';
	        } else {
	            $scope.THE_MONTH = 'DECEMBER';
	        }

            var dataset = result5.num_in_day;
            //var the_x_axis = result5.the_month_name;
            var the_x_axis = result5.the_day;
            console.log("Stuff is " + the_x_axis)
            
           var svgWidth = 1200, svgHeight = 600, barPadding = 10;
           var shift_x_from_0 = 75;
			var barWidth = svgWidth / dataset.length;
			var svg = d3.select('svg')
		    	.attr("width", svgWidth+300)
				.attr("height", svgHeight+300);
			
			var xScale = d3.scaleLinear()
				.domain([0, d3.max(dataset)])
				.range([0, svgWidth]);
				
			var xScale_axis = d3.scaleLinear()
				.domain([0, 24])
				.range([0, svgWidth]);
				    
			var yScale_axis = d3.scaleLinear()
				.domain([0, d3.max(dataset)])
				.range([svgHeight, 0]);
			
			var yScale = d3.scaleLinear()
				.domain([0, d3.max(dataset)])
				.range([0, svgHeight]);
				
			var x_axis = d3.axisBottom().scale(xScale_axis);
			var y_axis = d3.axisLeft().scale(yScale_axis);
					  	
			var d = new Date();  
			var day_number = result5.num_in_day.length;
			//var month_number = d.getMonth();
			var barChart = svg.selectAll("rect")
			    .data(dataset)
			    .enter()
			    .append("rect")
			    .attr("y", function(d) {
			         return svgHeight - yScale(d)  
			    })
			    .attr("height", function(d) { 
			        return yScale(d); 
			    })
			    .attr("width", barWidth - barPadding)
			    .attr("class", "bar")
			    //.style("fill","teal")
			    .style("fill", function(d, i) {
				    var bar_num = 12/i;
				    //console.log("I is " + i*255/month_number)
					return "rgb(0, 0," + (255-(i*255/day_number))+" )";
				})
			    .attr("transform", function (d, i) {
			        var translate = [barWidth * i + shift_x_from_0, 0]; 
			        return "translate("+ translate +")";
			    });
			
			// TEXT ON TOP OF BARS
			var text = svg.selectAll("text")
			    .data(dataset)
			    .enter()
			    .append("text")
			    .text(function(d) {
			        return d;
			    })
			    .attr("y", function(d, j) {
			        return svgHeight - yScale(d) + 30; // -6 MAKES TEXT SIT 2 PIXELS ABOVE BAR
			    })
			    .attr("x", function(d, j) {
			        return barWidth * j  + 55 + barWidth/2; // 15 TO CENTER TEXT ON BAR
			    })
			    .attr("fill", "white")
			    .attr("font-size", 30);	 
			    
			var data = the_x_axis;
			var xScaleLabels = d3
			  .scalePoint()
			  .domain(data)
			  .rangeRound([40, svgWidth - barWidth/2]); // In pixels
			
			var axisTop2 = d3
			  .axisBottom()
			  .scale(xScaleLabels)
			  .ticks(data.length);
			
			var xAxisTranslate = svgHeight + 0;
			svg
			  .append("g")
			  .call(axisTop2)
			 .attr("transform", "translate(" + 60 + "," + xAxisTranslate + ")")
			   .selectAll("text")	
			  .style("text-anchor", "end")
			  .attr("dx", -8)
			  .attr("dy", barWidth/4+4)
             .attr("font-size", 30) // X-AXIS FONT SIZE
			  .attr("transform", "rotate(-65)");

			// Y-AXIS    
			svg.append("g")
				.attr("transform", "translate(" + shift_x_from_0 + ", 0)")
				.call(y_axis)
				.attr("font-size", 30);
				
        }).error(function () {
            $.unblockUI();
            toastr.error("Get error.");
        });
    };
    $scope.LoadData = function (store_the_month) {
        myblockui();
        //$cookies.put("SearchText", $scope.SearchText);
        //$cookies.put("SearchText", $scope.cust_name);
        
		//$cookies.put("SearchStartDate",$scope.SearchStartDate);
		//$cookies.put("SearchEndDate",$scope.SearchEndDate);
		
		if($scope.use_impression_date != 1) {
			$scope.use_impression_date = 0;
		} else {
			console.log("DEFINED Impression checked " + $scope.use_impression_date)	
			$scope.use_impression_date = 1;
		}
		
        var api_url = window.cfg.apiUrl + "alclair/manufacturing_screen_1.php";
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
	            console.log("Test is " + result.test)
	            
              $scope.OrdersList = result.data;
              $scope.Shipped_Last_Year = result.Shipped_Last_Year;
  			 $scope.Shipped_Last_Year_This_Month = result.Shipped_Last_Year_This_Month;
			 $scope.Shipped_This_Year = result.Shipped_This_Year;
			 $scope.Shipped_This_Month = result.Shipped_This_Year_This_Month;
		  	 $scope.Shipped_Last_Month = result.Shipped_This_Year_Last_Month;
				
			$scope.this_year = result.this_year;
			$scope.last_year = result.last_year;
			$scope.this_month =  result.this_month.toUpperCase();
			$scope.last_month =  result.last_month.toUpperCase();
				
			$scope.avg = result.avg;           
             $scope.avg_repairs = result.avg_repairs;  
             $scope.orders_shipped_yesterday = result.orders_shipped_yesterday;
             $scope.orders_shipped_today = result.orders_shipped_today;
                
                //$scope.QC_Form = result.customer_name;
                $scope.TotalPages = result.TotalPages;
                //console.log("Num of pages " + result.TotalPages)
                $scope.TotalRecords = result.TotalRecords;
                $scope.Printed = result.Printed;
                console.log("WHAT IS GOING ON")
                
                $scope.CustomBarChart(store_the_month);   
                //$scope.PageRange = [];
                //$scope.PageWindowStart = (Math.ceil($scope.PageIndex / $scope.PageWindowSize) - 1) * $scope.PageWindowSize + 1;
                //$scope.PageWindowEnd = $scope.PageWindowStart + $scope.PageWindowSize - 1;
                //if ($scope.PageWindowEnd > $scope.TotalPages) {
                //    $scope.PageWindowEnd = $scope.TotalPages;
                //}
                //for (var i = $scope.PageWindowStart; i <= $scope.PageWindowEnd; i++) {
                //    $scope.PageRange.push(i);
                //}

                $.unblockUI();
            }).error(function (result) {
                toastr.error("Get QC Form error.");
            });
    };    
    
    $scope.OpenWindow=function(filepath)
	{
		window.open(window.cfg.rootUrl+'/data/'+filepath,'Invoice #'+$scope.customer_name,'width=760,height=600,menu=0,scrollbars=1');
	}
       
    $scope.init = function () {
	    $scope.labels5.push('2018');
	    $scope.labels5.push('2019');
	    var store_the_month = localStorage.getItem("store_the_month");
        $scope.LoadData(store_the_month);
    }
    			        
    $scope.openDone = function ($event) {        
        $scope.openedDone = true;
    };

    $scope.LoadSelectDateModal=function(id) {
        $('#SelectDateModal').modal("show");   
        $scope.id_to_make_done = id;
      
    }
            
    $scope.init();
}]);