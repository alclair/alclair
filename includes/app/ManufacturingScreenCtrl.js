swdApp.controller('Manufacturing_Screen_1', ['$http', '$scope', 'AppDataService', '$upload',  '$cookies', function ($http, $scope, AppDataService, $upload, $cookies) {
	$scope.OrdersList = {};
    		
		
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
    $scope.LoadData = function () {
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
             
            // RIGHT HERE
            $scope.outdoor_shipped_today = result.outdoor_shipped_today;
            $scope.ifb_shipped_today = result.ifb_shipped_today;
            $scope.moto_shipped_today = result.moto_shipped_today;
            $scope.hp_shipped_today = result.hp_shipped_today;
            
            $scope.all_shipped_today = $scope.orders_shipped_today + $scope.outdoor_shipped_today + $scope.ifb_shipped_today + $scope.moto_shipped_today + $scope.hp_shipped_today;
                
                //$scope.QC_Form = result.customer_name;
                $scope.TotalPages = result.TotalPages;
                //console.log("Num of pages " + result.TotalPages)
                $scope.TotalRecords = result.TotalRecords;
                $scope.Printed = result.Printed;
                //console.log("Pass or Fail is " + result.testing1)
                
                setTimeout(function(){
				  	window.location.href = window.cfg.rootUrl + "/admin/manufacturing_screen_2";
				  }, 15000); 

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
		//window.open(window.cfg.rootUrl+'/data/'+filepath,'Invoice #'+$scope.customer_name,'width=760,height=600,menu=0,scrollbars=1');
	}
       
    $scope.init = function () {
        $scope.LoadData();
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

swdApp.controller('Manufacturing_Screen_2', ['$http', '$scope', 'AppDataService', '$upload',  '$cookies', function ($http, $scope, AppDataService, $upload, $cookies) {
	$scope.OrdersList = {};
	
	$scope.labels5 = [];
	$scope.labelRange5 = [];
   
   $scope.monthRange = AppDataService.monthRange;
		
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
        
    $scope.loadImpressionsReceived = function () {
       myblockui();
		
		$scope.year_month = '2020';
		$scope.year_month = '2021';
		$scope.year_month = '2022';
		$scope.month_month = '05';
		$scope.month_month = '01';
		//$scope.year_month = moment().format("YYYY");
		//$scope.month_month = moment().format("MM")
		console.log("Year is " + $scope.year_month + " and Month is " + $scope.month_month)
        var api_url = window.cfg.apiUrl + "reports/manufacturing_screen2.php?year=" + $scope.year_month + "&month=" + $scope.month_month;

        $http.get(api_url).success(function (result5) {
				
            $.unblockUI();
            //console.log(JSON.stringify(result5.data));
            //console.log("data length is " + result.data.length)
            if (result5.data.length == 0)
                return;
            $scope.labelRange5 = [];
            
            console.log("TESTING is " + result5.data.length)

            var monthday = ["01", "03", "05", "07", "08", "10", "12"];
            var totalmonthday = 31;
            if (monthday.indexOf($scope.month_month) == -1)
                totalmonthday = 30;
          
			 //$scope.labels5 = "Count"; 
            var layers5 = [];
            /*for (var i = 0; i < $scope.labels5.length; i++) {
                var layer5 = [];
                for (var j = 0; j < totalmonthday; j++) {
                    layer5.push({ y: 0 });
                }
                layers5.push(layer5);
            }*/
            for (var i = 0; i < 24; i++) { //$scope.labels5.length; i++) {
                var layer5 = [];
                for (var j = 0; j < 13; j++) {
                    layer5.push({ y: 0 });
                }
                layers5.push(layer5);
            }
			//console.log("LAYERS " + layers5[1][1].y + " and J is " +j)
            //var layers5 = [];
            //layers5 = ['1', '2' , '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'];
            
            console.log("Length is " + result5.data.length)
            var step = 0;
            for (var i = 0; i < result5.data.length; i++) {
				//console.log("TESTING " + result5.data[i].the_month_name)
				//if(i == result5.data.length-1 && result5.data[i].the_year != '2019') {
				if(i == result5.data.length-1 && result5.data[i].the_year != '2021') {
					var created5 = parseInt(result5.data[i].the_month);
					//var created5 = (result5.data[i].the_month_name);
					var pass_or_fail5 = result5.data[i].the_year;
					var num_status5 = result5.data[i].num_in_month;
					console.log("I is " + i + " and " + created5+"-"+pass_or_fail5+"-"+num_status5 )
					layers5[$scope.labels5.indexOf(pass_or_fail5)][created5].y = num_status5;
					
					var created5 = parseInt(result5.data[i].the_month);
					//var created5 = (result5.data[i].the_month_name);
					//var pass_or_fail5 = '2019';
					var pass_or_fail5 = '2021';
					var num_status5 = 0;
					console.log("I is " + i + " and " + created5+"-"+pass_or_fail5+"-"+num_status5 )
					layers5[$scope.labels5.indexOf(pass_or_fail5)][created5].y = num_status5;
				//} else if(i > 0 && result5.data[i].the_year == '2018' && result5.data[i-1].the_year == '2018') {
				} else if(i > 0 && result5.data[i].the_year == '2019' && result5.data[i-1].the_year == '2019') {
	              var created5 = parseInt(result5.data[i-1].the_month);
	              //var created5 = (result5.data[i-1].the_month_name);
					var pass_or_fail5 = '2019';
					var num_status5 = 0;
					console.log("I is " + i + " and " + created5+"-"+pass_or_fail5+"-"+num_status5 )
					layers5[$scope.labels5.indexOf(pass_or_fail5)][created5].y = num_status5;
					
					var created5 = parseInt(result5.data[i].the_month);
					//var created5 = (result5.data[i].the_month_name)
					var pass_or_fail5 = result5.data[i].the_year;
					var num_status5 = result5.data[i].num_in_month;
					console.log("I is " + i + " and " + created5+"-"+pass_or_fail5+"-"+num_status5 )
					layers5[$scope.labels5.indexOf(pass_or_fail5)][created5].y = num_status5;
                } else {
	               var created5 = parseInt(result5.data[i].the_month);
	               //var created5 = (result5.data[i].the_month_name);
					var pass_or_fail5 = result5.data[i].the_year;
					var num_status5 = result5.data[i].num_in_month;
					console.log("I is " + i + " and " + created5+" year is "+pass_or_fail5+" num is "+num_status5 )
					layers5[$scope.labels5.indexOf(pass_or_fail5)][created5].y = num_status5;
                }
            }

            var color = d3.scale.category20();
            for (var i = 0; i < $scope.labels5.length; i++) {
                $scope.labelRange5.push({ text: $scope.labels5[i], color: color(i) });
            }
			//layers5 = [{'month':'Jan', '2018':11, '2019': 55}, {'month':'Jan', '2018':33, '2019': 2}] 			
            d3DarwStackGroup(layers5, "impressions_received_date", "grouped_impression_date", "stacked_impression_date", $scope.labels5, { category20: true });

        }).error(function () {
            $.unblockUI();
            toastr.error("Get error.");
        });
    };
    
    $scope.CustomBarChart = function () {
       myblockui();
		
		console.log("Year is " + $scope.year_month + " and Month is " + $scope.month_month)
        var api_url = window.cfg.apiUrl + "reports/manufacturing_screen2.php?year=" + $scope.year_month + "&month=" + $scope.month_month;

        $http.get(api_url).success(function (result5) {

            $.unblockUI();
            
            var dataset = result5.num_in_month;
            var the_x_axis = result5.the_month_name;
            console.log("Stuff is " + the_x_axis)
            
            	var svgWidth = 1600, svgHeight = 800, barPadding = 10;
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
			var month_number = d.getMonth();
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
				    console.log("I is " + i*255/month_number)
					return "rgb(0, 0," + (255-(i*255/month_number))+" )";
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
			        return svgHeight - yScale(d) + 60; // -6 MAKES TEXT SIT 2 PIXELS ABOVE BAR
			    })
			    .attr("x", function(d, j) {
			        return barWidth * j  + 15 + barWidth/2; // 15 TO CENTER TEXT ON BAR
			    })
			    .attr("fill", "white")
			    .attr("font-size", 60);	 
			    
			var data = the_x_axis;
			var xScaleLabels = d3
			  .scalePoint()
			  .domain(data)
			  .rangeRound([50, svgWidth - barWidth/2]); // In pixels
			
			var axisTop2 = d3
			  .axisBottom()
			  .scale(xScaleLabels)
			  .ticks(data.length);
			
			var xAxisTranslate = svgHeight + 0;
			svg
			  .append("g")
			  .call(axisTop2)
			  .attr("transform", "translate(" + 54 + "," + xAxisTranslate + ")")
			  .selectAll("text")	
			  .style("text-anchor", "end")
			  .attr("dx", 0)
			  .attr("dy", barWidth/4)
             .attr("font-size", 40)
			  .attr("transform", "rotate(-65)");

         
			// X-AXIS
			/*
			svg.append("g")
				.attr("transform", "translate(54, " + xAxisTranslate   +")")
				.call(x_axis)
				.attr("font-size", 40);
			*/
			// Y-AXIS    
			svg.append("g")
				.attr("transform", "translate(" + shift_x_from_0 + ", 0)")
				.call(y_axis)
				.attr("font-size", 40);
				
        }).error(function () {
            $.unblockUI();
            toastr.error("Get error.");
        });
    };
$scope.LoadData = function () {
        myblockui();
        //$cookies.put("SearchText", $scope.SearchText);
        //$cookies.put("SearchText", $scope.cust_name);

		//$cookies.put("SearchStartDate",$scope.SearchStartDate);
		//$cookies.put("SearchEndDate",$scope.SearchEndDate);

		if($scope.use_impression_date != 1) {
			$scope.use_impression_date = 0;
		} else {
			$scope.use_impression_date = 1;
		}

        var api_url = window.cfg.apiUrl + "alclair/manufacturing_screen_2.php";
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {

	          //$scope.loadImpressionsReceived(); 
	          $scope.CustomBarChart();        
              $scope.OrdersList = result.data;     
              $scope.avg = result.avg;           
              $scope.avg_repairs = result.avg_repairs;  
              $scope.orders_shipped_yesterday = result.orders_shipped_yesterday;
              console.log("Day is " + result.minus_day);   
              console.log("Minute is " + result.minus_minute);


                setTimeout(function(){
				  	window.location.href = window.cfg.rootUrl + "/admin/manufacturing_screen_1";
				  }, 15000); 

                $scope.PageRange = [];
                $scope.PageWindowStart = (Math.ceil($scope.PageIndex / $scope.PageWindowSize) - 1) * $scope.PageWindowSize + 1;
                $scope.PageWindowEnd = $scope.PageWindowStart + $scope.PageWindowSize - 1;
                if ($scope.PageWindowEnd > $scope.TotalPages) {
                    $scope.PageWindowEnd = $scope.TotalPages;
                }
                for (var i = $scope.PageWindowStart; i <= $scope.PageWindowEnd; i++) {
                    $scope.PageRange.push(i);
                }

                $.unblockUI();
            }).error(function (result) {
                toastr.error("Get QC Form error.");
            });
    };    


    $scope.init = function () {
	    //$scope.labels5.push('2018');
	    $scope.labels5.push('2020');
	    $scope.labels5.push('2021');
	    $scope.labels5.push('2022');
	    /*
	    AppDataService.loadStatusTypeList_orders(null, null, function (result) {
        		for (var i = 0; i < result.data.length; i++) {
				$scope.labels5.push(result.data[i].type);
        		}    
    		}, function () { });
    		*/

        //$scope.loadImpressionsReceived(); 
        //$("#impressions_received_date").html("");
        $scope.LoadData();
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

swdApp.controller('Manufacturing_Screen_3', ['$http', '$scope', 'AppDataService', '$upload',  '$cookies', function ($http, $scope, AppDataService, $upload, $cookies) {
	$scope.OrdersList = {};
    		
		
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
    $scope.LoadData = function () {
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
		
        var api_url = window.cfg.apiUrl + "alclair/manufacturing_screen_3.php";
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
	            console.log("Test is " + result.test)
	     
				$scope.comment = result.comment;
				$scope.after_comment = result.after_comment;
				$scope.Shipped_Last_Year = result.Shipped_Last_Year;
				$scope.Shipped_This_Year = result.Shipped_This_Year;
		        
                setTimeout(function(){
				  	window.location.href = window.cfg.rootUrl + "/admin/manufacturing_screen_1";
				  }, 15000); 

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
        $scope.LoadData();
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

