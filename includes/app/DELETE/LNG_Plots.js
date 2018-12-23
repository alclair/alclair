swdApp.controller('ProducePlots_LNG', ['$http', '$scope', 'AppDataService', function ($http, $scope, AppDataService) {    
    
	$scope.LngQueensList = [];
    $scope.lng_queens = 0;
    
    $scope.customers_ID = 0;
    $scope.queens = [];
	
	$scope.startdate = new Date();
    $scope.enddate = new Date();
    
    $scope.opened_startdate = false;
    $scope.opened_enddate = false;
    $scope.openStartDate = function ($event) {
        $scope.opened_startdate = true;
    };

    $scope.openEndDate = function ($event) {
        $scope.opened_enddate = true;
    };

    $scope.dateOptions = {
        formatYear: 'yy',
        startingDay: 1
    };

    $scope.disabled = function (date, mode) {
        return (mode === 'day' && (date.getDay() === 0 || date.getDay() === 6));
    };

    $scope.formats = ['yyyy-MM-dd'];
    $scope.format = $scope.formats[0];
    
    $scope.loadTankLevel = function () {
        myblockui();
        var api_url = window.cfg.apiUrl + "reports/lng_tank_level_data.php?lng_queens=" + $scope.lng_queens +"&StartDate="+moment($scope.startdate).format("MM/DD/YYYY")+"&EndDate="+moment($scope.enddate).format("MM/DD/YYYY");
        $http.get(api_url).success(function (result) {
            //$.unblockUI();
		
			var today = new Date();

			Date.prototype.stdTimezoneOffset = function() {
				var jan = new Date(this.getFullYear(), 0, 1);
				var jul = new Date(this.getFullYear(), 6, 1);
				return Math.max(jan.getTimezoneOffset(), jul.getTimezoneOffset());
			}

			Date.prototype.dst = function() {
				return this.getTimezoneOffset() < this.stdTimezoneOffset();
			}

			var utc_offset = 6;
			var midnight = '06:00';
			if (today.dst()) {
				utc_offset = 5;
				midnight = '05:00';
			}
			console.log("Start is " + result.start + " End is " + result.end + " UTC is " + utc_offset + " the answer is " + result.answer)
			var Array1 = [];
			var Array2 = [];
			var Array3 = [];
			var Array4 = [];
			var Array5 = [];
			var Array6 = [];
			var Array7 = [];
			var Array8 = [];
			var Array9 = [];
			var Array10 = [];
			var Array31 = [];
			var Array33 = [];
			var Array34 = [];
			var Array39 = [];
			var Array66 = [];
			var ArrayQueens = [];
			var x_axis = [];
			var x_axis2 = [];
			var Dates2Keep = [];
			for (var i = 0; i < result.data.length; i++) {
                var item = result.data[result.data.length-i-1];

				//if ( ( (i+1) % 4) == 0) {
					if ($scope.lng_queens == 1) {
						if(Array1.length < 1) { Array1.push(result.queens[0].queen_name); x_axis.push('x') }
						if(item.inox_q1 < 0 || item.inox_q1 == null) { Array1.push(0) }
						else { Array1.push(item.inox_q1_volume_gal) }
						
						// STORING THE TIME STAMP
						var time_stamp_defined = new Date(item.time_stamp);  // CONVERT TIME STAMP FROM SQL INTO DATE FORMAT
						var time_stamp_CentralTime = new Date(time_stamp_defined - utc_offset*60*60*1000);  // SUBTRACT HOURS TO CONVERT UTC TO CENTRAL TIME
						x_axis.push(time_stamp_CentralTime); // STORE THE TIME STAMP AS CENTRAL TIME
						if( time_stamp_CentralTime.getHours() == 0 && time_stamp_CentralTime.getMinutes() == 0 ) {Dates2Keep.push(time_stamp_CentralTime)}
						//x_axis.push(item.time_stamp)
						//if(item.time_stamp.slice(-5) == "00:00") {Dates2Keep.push(item.time_stamp)}
					}
					else if ($scope.lng_queens == 2) {
						if(Array1.length < 1) { Array1.push(result.queens[1].queen_name); x_axis.push('x') }
						if(item.chart_q2 < 0 || item.chart_q2 == null) { Array1.push(0) }
						else { Array1.push(item.chart_q2_volume_gal) }
						// STORING THE TIME STAMP
						var time_stamp_defined = new Date(item.time_stamp);  // CONVERT TIME STAMP FROM SQL INTO DATE FORMAT
						var time_stamp_CentralTime = new Date(time_stamp_defined - utc_offset*60*60*1000);  // SUBTRACT HOURS TO CONVERT UTC TO CENTRAL TIME
						x_axis.push(time_stamp_CentralTime); // STORE THE TIME STAMP AS CENTRAL TIME
						if( time_stamp_CentralTime.getHours() == 0 && time_stamp_CentralTime.getMinutes() == 0 ) {Dates2Keep.push(time_stamp_CentralTime)}
					}	
					else if ($scope.lng_queens == 3) {
						if(Array1.length < 1) { Array1.push(result.queens[2].queen_name); x_axis.push('x') }
						if(item.inox_q3 < 0 || item.inox_q3 == null) { Array1.push(0) }
						else { Array1.push(item.inox_q3_volume_gal) }
						// STORING THE TIME STAMP
						var time_stamp_defined = new Date(item.time_stamp);  // CONVERT TIME STAMP FROM SQL INTO DATE FORMAT
						var time_stamp_CentralTime = new Date(time_stamp_defined - utc_offset*60*60*1000);  // SUBTRACT HOURS TO CONVERT UTC TO CENTRAL TIME
						x_axis.push(time_stamp_CentralTime); // STORE THE TIME STAMP AS CENTRAL TIME
						if( time_stamp_CentralTime.getHours() == 0 && time_stamp_CentralTime.getMinutes() == 0 ) {Dates2Keep.push(time_stamp_CentralTime)}						
					}	
					else if ($scope.lng_queens == 4) {
						if(Array1.length < 1) { Array1.push(result.queens[3].queen_name); x_axis.push('x') }
						if(item.inox_q4 < 0 || item.inox_q4 == null) { Array1.push(0) }
						else { Array1.push(item.inox_q4_volume_gal) }
						// STORING THE TIME STAMP
						var time_stamp_defined = new Date(item.time_stamp);  // CONVERT TIME STAMP FROM SQL INTO DATE FORMAT
						var time_stamp_CentralTime = new Date(time_stamp_defined - utc_offset*60*60*1000);  // SUBTRACT HOURS TO CONVERT UTC TO CENTRAL TIME
						x_axis.push(time_stamp_CentralTime); // STORE THE TIME STAMP AS CENTRAL TIME
						if( time_stamp_CentralTime.getHours() == 0 && time_stamp_CentralTime.getMinutes() == 0 ) {Dates2Keep.push(time_stamp_CentralTime)}
					}	
					else if ($scope.lng_queens == 5) {
						if(Array1.length < 1) { Array1.push(result.queens[4].queen_name); x_axis.push('x') }
						if(item.chart_q5 < 0 || item.chart_q5 == null) { Array1.push(0) }
						else { Array1.push(item.chart_q5_volume_gal) }
						
						// STORING THE TIME STAMP
						var time_stamp_defined = new Date(item.time_stamp);  // CONVERT TIME STAMP FROM SQL INTO DATE FORMAT
						var time_stamp_CentralTime = new Date(time_stamp_defined - utc_offset*60*60*1000);  // SUBTRACT HOURS TO CONVERT UTC TO CENTRAL TIME
						x_axis.push(time_stamp_CentralTime); // STORE THE TIME STAMP AS CENTRAL TIME
						if( time_stamp_CentralTime.getHours() == 0 && time_stamp_CentralTime.getMinutes() == 0 ) {Dates2Keep.push(time_stamp_CentralTime)}					
					}
					else if ($scope.lng_queens == 6) {
						if(Array1.length < 1) { Array1.push(result.queens[5].queen_name); x_axis.push('x') }
						if(item.inox_q6 < 0 || item.inox_q6 == null) { Array1.push(0) }
						else { Array1.push(item.inox_q6_volume_gal) }
						// STORING THE TIME STAMP
						var time_stamp_defined = new Date(item.time_stamp);  // CONVERT TIME STAMP FROM SQL INTO DATE FORMAT
						var time_stamp_CentralTime = new Date(time_stamp_defined - utc_offset*60*60*1000);  // SUBTRACT HOURS TO CONVERT UTC TO CENTRAL TIME
						x_axis.push(time_stamp_CentralTime); // STORE THE TIME STAMP AS CENTRAL TIME
						if( time_stamp_CentralTime.getHours() == 0 && time_stamp_CentralTime.getMinutes() == 0 ) {Dates2Keep.push(time_stamp_CentralTime)}
					}
					else if ($scope.lng_queens == 7) {
						if(Array1.length < 1) { Array1.push(result.queens[6].queen_name); x_axis.push('x') }
						if(item.chart_q7 < 0 || item.chart_q7 == null) { Array1.push(0) }
						else { Array1.push(item.chart_q7_volume_gal) }
						// STORING THE TIME STAMP
						var time_stamp_defined = new Date(item.time_stamp);  // CONVERT TIME STAMP FROM SQL INTO DATE FORMAT
						var time_stamp_CentralTime = new Date(time_stamp_defined - utc_offset*60*60*1000);  // SUBTRACT HOURS TO CONVERT UTC TO CENTRAL TIME
						x_axis.push(time_stamp_CentralTime); // STORE THE TIME STAMP AS CENTRAL TIME
						if( time_stamp_CentralTime.getHours() == 0 && time_stamp_CentralTime.getMinutes() == 0 ) {Dates2Keep.push(time_stamp_CentralTime)}
					}		
					else if ($scope.lng_queens == 8) {
						if(Array1.length < 1) { Array1.push(result.queens[7].queen_name); x_axis.push('x') }
						if(item.chart_q8 < 0 || item.chart_q8 == null) { Array1.push(0) }
						else { Array1.push(item.chart_q8_volume_gal) }
						// STORING THE TIME STAMP
						var time_stamp_defined = new Date(item.time_stamp);  // CONVERT TIME STAMP FROM SQL INTO DATE FORMAT
						var time_stamp_CentralTime = new Date(time_stamp_defined - utc_offset*60*60*1000);  // SUBTRACT HOURS TO CONVERT UTC TO CENTRAL TIME
						x_axis.push(time_stamp_CentralTime); // STORE THE TIME STAMP AS CENTRAL TIME
						if( time_stamp_CentralTime.getHours() == 0 && time_stamp_CentralTime.getMinutes() == 0 ) {Dates2Keep.push(time_stamp_CentralTime)}
					}		
					else if ($scope.lng_queens == 9) {
						if(Array1.length < 1) { Array1.push(result.queens[8].queen_name); x_axis.push('x') }
						if(item.chart_q9 < 0 || item.chart_q9 == null) { Array1.push(0) }
						else { Array1.push(item.chart_q9_volume_gal) }
						// STORING THE TIME STAMP
						var time_stamp_defined = new Date(item.time_stamp);  // CONVERT TIME STAMP FROM SQL INTO DATE FORMAT
						var time_stamp_CentralTime = new Date(time_stamp_defined - utc_offset*60*60*1000);  // SUBTRACT HOURS TO CONVERT UTC TO CENTRAL TIME
						x_axis.push(time_stamp_CentralTime); // STORE THE TIME STAMP AS CENTRAL TIME
						if( time_stamp_CentralTime.getHours() == 0 && time_stamp_CentralTime.getMinutes() == 0 ) {Dates2Keep.push(time_stamp_CentralTime)}
					}				
					else if ($scope.lng_queens == 10) {
						if(Array1.length < 1) { Array1.push(result.queens[9].queen_name); x_axis.push('x') }
						if(item.chart_q10 < 0 || item.chart_q10 == null) { Array1.push(0) }
						else { Array1.push(item.chart_q10_volume_gal) }
						// STORING THE TIME STAMP
						var time_stamp_defined = new Date(item.time_stamp);  // CONVERT TIME STAMP FROM SQL INTO DATE FORMAT
						var time_stamp_CentralTime = new Date(time_stamp_defined - utc_offset*60*60*1000);  // SUBTRACT HOURS TO CONVERT UTC TO CENTRAL TIME
						x_axis.push(time_stamp_CentralTime); // STORE THE TIME STAMP AS CENTRAL TIME
						if( time_stamp_CentralTime.getHours() == 0 && time_stamp_CentralTime.getMinutes() == 0 ) {Dates2Keep.push(time_stamp_CentralTime)}
					}	
					else if ($scope.lng_queens == 17) {
						if(Array1.length < 1) { Array1.push(result.queens[16].queen_name); x_axis.push('x') } // 11TH VALUE IN THE ARRAY.  ARRAY STARTS WITH ZERO
						if(item.chart_pq31 < 0 || item.chart_pq31 == null) { Array1.push(0) }
						else { Array1.push(item.chart_pq31_volume_gal) }
						// STORING THE TIME STAMP
						var time_stamp_defined = new Date(item.time_stamp);  // CONVERT TIME STAMP FROM SQL INTO DATE FORMAT
						var time_stamp_CentralTime = new Date(time_stamp_defined - utc_offset*60*60*1000);  // SUBTRACT HOURS TO CONVERT UTC TO CENTRAL TIME
						x_axis.push(time_stamp_CentralTime); // STORE THE TIME STAMP AS CENTRAL TIME
						if( time_stamp_CentralTime.getHours() == 0 && time_stamp_CentralTime.getMinutes() == 0 ) {Dates2Keep.push(time_stamp_CentralTime)}
					}
					else if ($scope.lng_queens == 18) {
						if(Array1.length < 1) { Array1.push(result.queens[17].queen_name); x_axis.push('x') } // 11TH VALUE IN THE ARRAY.  ARRAY STARTS WITH ZERO
						if(item.chart_pq33 < 0 || item.chart_pq33 == null) { Array1.push(0) }
						else { Array1.push(item.chart_pq33_volume_gal) }
						// STORING THE TIME STAMP
						var time_stamp_defined = new Date(item.time_stamp);  // CONVERT TIME STAMP FROM SQL INTO DATE FORMAT
						var time_stamp_CentralTime = new Date(time_stamp_defined - utc_offset*60*60*1000);  // SUBTRACT HOURS TO CONVERT UTC TO CENTRAL TIME
						x_axis.push(time_stamp_CentralTime); // STORE THE TIME STAMP AS CENTRAL TIME
						if( time_stamp_CentralTime.getHours() == 0 && time_stamp_CentralTime.getMinutes() == 0 ) {Dates2Keep.push(time_stamp_CentralTime)}
					}
					else if ($scope.lng_queens == 13) {
						if(Array1.length < 1) { Array1.push(result.queens[12].queen_name); x_axis.push('x') } // 11TH VALUE IN THE ARRAY.  ARRAY STAYS WITH ZERO
						if(item.chart_pq34 < 0 || item.chart_pq34 == null) { Array1.push(0) }
						else { Array1.push(item.chart_pq34_volume_gal) }
						// STORING THE TIME STAMP
						var time_stamp_defined = new Date(item.time_stamp);  // CONVERT TIME STAMP FROM SQL INTO DATE FORMAT
						var time_stamp_CentralTime = new Date(time_stamp_defined - utc_offset*60*60*1000);  // SUBTRACT HOURS TO CONVERT UTC TO CENTRAL TIME
						x_axis.push(time_stamp_CentralTime); // STORE THE TIME STAMP AS CENTRAL TIME
						if( time_stamp_CentralTime.getHours() == 0 && time_stamp_CentralTime.getMinutes() == 0 ) {Dates2Keep.push(time_stamp_CentralTime)}
					}	
					else if ($scope.lng_queens == 15) {
						if(Array1.length < 1) { Array1.push(result.queens[14].queen_name); x_axis.push('x') } // 11TH VALUE IN THE ARRAY.  ARRAY STAYS WITH ZERO
						if(item.chart_pq39 < 0 || item.chart_pq39 == null) { Array1.push(0) }
						else { Array1.push(item.chart_pq39_volume_gal) }
						// STORING THE TIME STAMP
						var time_stamp_defined = new Date(item.time_stamp);  // CONVERT TIME STAMP FROM SQL INTO DATE FORMAT
						var time_stamp_CentralTime = new Date(time_stamp_defined - utc_offset*60*60*1000);  // SUBTRACT HOURS TO CONVERT UTC TO CENTRAL TIME
						x_axis.push(time_stamp_CentralTime); // STORE THE TIME STAMP AS CENTRAL TIME
						if( time_stamp_CentralTime.getHours() == 0 && time_stamp_CentralTime.getMinutes() == 0 ) {Dates2Keep.push(time_stamp_CentralTime)}
					}	
					else if ($scope.lng_queens == 16) {
						if(Array1.length < 1) { Array1.push(result.queens[15].queen_name); x_axis.push('x') } // 11TH VALUE IN THE ARRAY.  ARRAY STAYS WITH ZERO
						if(item.chart_pq66 < 0 || item.chart_pq66 == null) { Array1.push(0) }
						else { Array1.push(item.chart_pq66_volume_gal) }
						// STORING THE TIME STAMP
						var time_stamp_defined = new Date(item.time_stamp);  // CONVERT TIME STAMP FROM SQL INTO DATE FORMAT
						var time_stamp_CentralTime = new Date(time_stamp_defined - utc_offset*60*60*1000);  // SUBTRACT HOURS TO CONVERT UTC TO CENTRAL TIME
						x_axis.push(time_stamp_CentralTime); // STORE THE TIME STAMP AS CENTRAL TIME
						if( time_stamp_CentralTime.getHours() == 0 && time_stamp_CentralTime.getMinutes() == 0 ) {Dates2Keep.push(time_stamp_CentralTime)}
					}	
					else {
						if(Array1.length < 1) { // STORE THE NAME OF THE QUEENS				
							if($scope.customers_ID == $scope.queens.data[0].customer_id || $scope.customers_ID == null)
								Array1.push(result.queens[0].queen_name)
							if($scope.customers_ID == $scope.queens.data[1].customer_id || $scope.customers_ID == null)
								Array2.push(result.queens[1].queen_name)
							if($scope.customers_ID == $scope.queens.data[2].customer_id || $scope.customers_ID == null)
								Array3.push(result.queens[2].queen_name)
							if($scope.customers_ID == $scope.queens.data[3].customer_id || $scope.customers_ID == null)
								Array4.push(result.queens[3].queen_name)			
							if($scope.customers_ID == $scope.queens.data[4].customer_id || $scope.customers_ID == null)
								Array5.push(result.queens[4].queen_name)																		
							if($scope.customers_ID == $scope.queens.data[5].customer_id || $scope.customers_ID == null)
								Array6.push(result.queens[5].queen_name)		
							if($scope.customers_ID == $scope.queens.data[6].customer_id || $scope.customers_ID == null)
								Array7.push(result.queens[6].queen_name)		
							if($scope.customers_ID == $scope.queens.data[7].customer_id || $scope.customers_ID == null)
								Array8.push(result.queens[7].queen_name)		
							if($scope.customers_ID == $scope.queens.data[8].customer_id || $scope.customers_ID == null)
								Array9.push(result.queens[8].queen_name)			
							if($scope.customers_ID == $scope.queens.data[9].customer_id || $scope.customers_ID == null)
								Array10.push(result.queens[9].queen_name)	
							if($scope.customers_ID == $scope.queens.data[16].customer_id || $scope.customers_ID == null)
								Array31.push(result.queens[16].queen_name)	
							if($scope.customers_ID == $scope.queens.data[17].customer_id || $scope.customers_ID == null)
								Array33.push(result.queens[17].queen_name)	
							if($scope.customers_ID == $scope.queens.data[12].customer_id || $scope.customers_ID == null)
								Array34.push(result.queens[12].queen_name)	
							if($scope.customers_ID == $scope.queens.data[14].customer_id || $scope.customers_ID == null)
								Array39.push(result.queens[14].queen_name)	
							if($scope.customers_ID == $scope.queens.data[15].customer_id || $scope.customers_ID == null)
								Array66.push(result.queens[15].queen_name)	

							x_axis.push('x')				
						}
						// CHECKING IF VALUE IS LESS THAN ZERO OR NULL
						if($scope.customers_ID == $scope.queens.data[0].customer_id || $scope.customers_ID == null)
							if(item.inox_q1 < 0 || item.inox_q1 == null) { Array1.push(0) } else { Array1.push(item.inox_q1_volume_gal) }
						if($scope.customers_ID == $scope.queens.data[1].customer_id || $scope.customers_ID == null)
							if(item.chart_q2 < 0 || item.chart_q2 == null) { Array2.push(0) } else { Array2.push(item.chart_q2_volume_gal) }
						if($scope.customers_ID == $scope.queens.data[2].customer_id || $scope.customers_ID == null)
							if(item.inox_q3 < 0 || item.inox_q3 == null) { Array3.push(0) } else { Array3.push(item.inox_q3_volume_gal) }
						if($scope.customers_ID == $scope.queens.data[3].customer_id || $scope.customers_ID == null)
							if(item.inox_q4 < 0 || item.inox_q4 == null) { Array4.push(0) } else { Array4.push(item.inox_q4_volume_gal) }
						if($scope.customers_ID == $scope.queens.data[4].customer_id || $scope.customers_ID == null)
							if(item.chart_q5 < 0 || item.chart_q5 == null) { Array5.push(0) } else { Array5.push(item.chart_q5_volume_gal) }
						if($scope.customers_ID == $scope.queens.data[5].customer_id || $scope.customers_ID == null)
							if(item.inox_q6 < 0 || item.inox_q6 == null) { Array6.push(0) } else { Array6.push(item.inox_q6_volume_gal) }
						if($scope.customers_ID == $scope.queens.data[6].customer_id || $scope.customers_ID == null)
							if(item.chart_q7 < 0 || item.chart_q7 == null) { Array7.push(0) } else { Array7.push(item.chart_q7_volume_gal) }
						if($scope.customers_ID == $scope.queens.data[7].customer_id || $scope.customers_ID == null)
							if(item.chart_q8 < 0 || item.chart_q8 == null) { Array8.push(0) } else { Array8.push(item.chart_q8_volume_gal) }
						if($scope.customers_ID == $scope.queens.data[8].customer_id || $scope.customers_ID == null)
							if(item.chart_q9 < 0 || item.chart_q9 == null) { Array9.push(0) } else { Array9.push(item.chart_q9_volume_gal) }
						if($scope.customers_ID == $scope.queens.data[9].customer_id || $scope.customers_ID == null)
							if(item.chart_q10 < 0 || item.chart_q10 == null) { Array10.push(0) } else { Array10.push(item.chart_q10_volume_gal) }
						if($scope.customers_ID == $scope.queens.data[16].customer_id || $scope.customers_ID == null)
							if(item.chart_pq31 < 0 || item.chart_pq31 == null) { Array31.push(0) } else { Array31.push(item.chart_pq31_volume_gal) }
						if($scope.customers_ID == $scope.queens.data[17].customer_id || $scope.customers_ID == null)
							if(item.chart_pq33 < 0 || item.chart_pq33 == null) { Array33.push(0) } else { Array33.push(item.chart_pq33_volume_gal) }
						if($scope.customers_ID == $scope.queens.data[12].customer_id || $scope.customers_ID == null)
							if(item.chart_pq34 < 0 || item.chart_pq34 == null) { Array34.push(0) } else { Array34.push(item.chart_pq34_volume_gal) }
						if($scope.customers_ID == $scope.queens.data[14].customer_id || $scope.customers_ID == null)
							if(item.chart_pq39 < 0 || item.chart_pq39 == null) { Array39.push(0) } else { Array39.push(item.chart_pq39_volume_gal) }
						if($scope.customers_ID == $scope.queens.data[15].customer_id || $scope.customers_ID == null)
							if(item.chart_pq66 < 0 || item.chart_pq66 == null) { Array66.push(0) } else { Array66.push(item.chart_pq66_volume_gal) }
						
						// STORING THE TIME STAMP
						var time_stamp_defined = new Date(item.time_stamp);  // CONVERT TIME STAMP FROM SQL INTO DATE FORMAT
						var time_stamp_CentralTime = new Date(time_stamp_defined - utc_offset*60*60*1000);  // SUBTRACT HOURS TO CONVERT UTC TO CENTRAL TIME
						x_axis.push(time_stamp_CentralTime); // STORE THE TIME STAMP AS CENTRAL TIME

						// IF THE TIME STAMP IS 00:00 (MIDNIGHT) SAVE INTO A SECOND ARRAY FOR A CLEANER X-AXIS FOR THE PLOT
						// WANT TO PRINT ONLY THE DAY ONE TIME ON THE X-AXIS
						if( time_stamp_CentralTime.getHours() == 0 && time_stamp_CentralTime.getMinutes() == 0 ) {Dates2Keep.push(time_stamp_CentralTime)}

						if(i == result.data.length - 1) { // SMARTS TO KNOW WHEN TO BUILD THE FINAL ARRAY FROM THE OTHER ARRAYS
							ArrayQueens.push(x_axis)
							if($scope.customers_ID == $scope.queens.data[0].customer_id || $scope.customers_ID == null)
								ArrayQueens.push(Array1)
							if($scope.customers_ID == $scope.queens.data[1].customer_id || $scope.customers_ID == null)
								ArrayQueens.push(Array2)
							if($scope.customers_ID == $scope.queens.data[2].customer_id || $scope.customers_ID == null)
								ArrayQueens.push(Array3)
							if($scope.customers_ID == $scope.queens.data[3].customer_id || $scope.customers_ID == null)
								ArrayQueens.push(Array4)
							if($scope.customers_ID == $scope.queens.data[4].customer_id || $scope.customers_ID == null)
								ArrayQueens.push(Array5)
							if($scope.customers_ID == $scope.queens.data[5].customer_id || $scope.customers_ID == null)
								ArrayQueens.push(Array6)
							if($scope.customers_ID == $scope.queens.data[6].customer_id || $scope.customers_ID == null)
								ArrayQueens.push(Array7)
							if($scope.customers_ID == $scope.queens.data[7].customer_id || $scope.customers_ID == null)
								ArrayQueens.push(Array8)
							if($scope.customers_ID == $scope.queens.data[8].customer_id || $scope.customers_ID == null)
								ArrayQueens.push(Array9)
							if($scope.customers_ID == $scope.queens.data[9].customer_id || $scope.customers_ID == null)
								ArrayQueens.push(Array10)
							if($scope.customers_ID == $scope.queens.data[16].customer_id || $scope.customers_ID == null)
								ArrayQueens.push(Array31)
							if($scope.customers_ID == $scope.queens.data[17].customer_id || $scope.customers_ID == null)
								ArrayQueens.push(Array33)
							if($scope.customers_ID == $scope.queens.data[12].customer_id || $scope.customers_ID == null)
								ArrayQueens.push(Array34)
							if($scope.customers_ID == $scope.queens.data[14].customer_id || $scope.customers_ID == null)
								ArrayQueens.push(Array39)
							if($scope.customers_ID == $scope.queens.data[15].customer_id || $scope.customers_ID == null)
								ArrayQueens.push(Array66)

						}
					} // CLOSE ELSE
				//}
			}	// CLOSE FOR LOOP	

			if($scope.lng_queens != 0) {
				ArrayQueens.push(x_axis)
				ArrayQueens.push(Array1)
				//ArrayQueens=(ArrayQueens)
			}
			//console.log("ArrayQueens is " + JSON.stringify(ArrayQueens))
			var chartTankLevel = c3.generate({
				bindto:'#chartTankLevel',
				size: { height: 480, width: 1200},
				padding: { top: 20, right: 40, bottom: 40, left: 40},
				data: {
					x: 'x',
					xFormat: '%Y-%m-%d %H:%M',
					columns: 
						ArrayQueens,
					//axes: {
					//	JSON.stringify(ArrayQueens[1][0]): 'y'
        			//}	
    			},
    			grid: {
					x: { show: true},
					y: { show: true}
    			},
    			point: {
					show: false
    			},
				/*tooltip: {
					format: {
						value: function (name, ratio, id, index) {
							return name+" - "+id;
                		}
      				}
				},*/
				axis: {
					x: {
						type: 'timeseries',
						tick: {
							// count: 4
							format: '%m/%d %H:%M', //%H-%M-%S
							rotate: 0,
							multiline: false,
							// this also works for non timeseries data
							values:  Dates2Keep
							//values: ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23']
            			}
            		}, 
            		/*y: {
						label: {
							text: 'Y Label',
							position: 'outer-middle'
							// inner-top : default
							// inner-middle
							// inner-bottom
							// outer-top
							// outer-middle
							// outer-bottom
            			}
            		}*/
    			} // CLOSE AXIS
			}); // CLOSE C3 . GENERATE

			$.unblockUI();
        }).error(function (result) {
            $.unblockUI();
            toastr.error("Get error.");
        });  // CLOSE HTTP . GET
    }; // CLOSE LOAD TANK LEVEL

	$scope.loadFlowRate = function () {
	console.log("HERE NOW")
       myblockui();
       var api_url = window.cfg.apiUrl + "reports/lng_flowrate_data.php?lng_queens=" + $scope.lng_queens +"&StartDate="+moment($scope.startdate).format("MM/DD/YYYY")+"&EndDate="+moment($scope.enddate).format("MM/DD/YYYY");
        $http.get(api_url).success(function (result) {
            //$.unblockUI();
			console.log("THIS FAR")
			var today = new Date();

			Date.prototype.stdTimezoneOffset = function() {
				var jan = new Date(this.getFullYear(), 0, 1);
				var jul = new Date(this.getFullYear(), 6, 1);
				return Math.max(jan.getTimezoneOffset(), jul.getTimezoneOffset());
			}

			Date.prototype.dst = function() {
				return this.getTimezoneOffset() < this.stdTimezoneOffset();
			}

			var utc_offset = 6;
			var midnight = '06:00';
			if (today.dst()) {
				utc_offset = 5;
				midnight = '05:00';
			}

			var Array1 = [];
			var Array2 = [];
			var Array3 = [];
			var Array4 = [];
			var Array5 = [];
			var Array6 = [];
			var Array7 = [];
			var Array8 = [];
			var Array9 = [];
			var Array10 = [];
			var Array31 = []; // DOES NOT HAVE FLOW RATE OR TOTALIZER READINGS
			var Array33 = []; // DOES NOT HAVE FLOW RATE OR TOTALIZER READINGS
			var Array34 = []; // DOES NOT HAVE FLOW RATE OR TOTALIZER READINGS
			var Array39 = []; // DOES NOT HAVE FLOW RATE OR TOTALIZER READINGS
			var Array66 = []; // DOES NOT HAVE FLOW RATE OR TOTALIZER READINGS
			var ArrayQueens = [];
			var x_axis = [];
			var Dates2Keep = [];
			for (var i = 0; i < result.data.length; i++) {
                var item = result.data[result.data.length-i-1];

				//if ( ( (i+1) % 4) == 0) {
					if ($scope.lng_queens == 1) {
						if(Array1.length < 1) { Array1.push(result.queens[0].queen_name); x_axis.push('x') }
						if(item.inox_q1 < 0 || item.inox_q1 == null) { Array1.push(0) }
						else { Array1.push(item.inox_q1*60) }
						
						// STORING THE TIME STAMP
						var time_stamp_defined = new Date(item.time_stamp);  // CONVERT TIME STAMP FROM SQL INTO DATE FORMAT
						var time_stamp_CentralTime = new Date(time_stamp_defined - utc_offset*60*60*1000);  // SUBTRACT HOURS TO CONVERT UTC TO CENTRAL TIME
						x_axis.push(time_stamp_CentralTime); // STORE THE TIME STAMP AS CENTRAL TIME
						if( time_stamp_CentralTime.getHours() == 0 && time_stamp_CentralTime.getMinutes() == 0 ) {Dates2Keep.push(time_stamp_CentralTime)}
					}
					else if ($scope.lng_queens == 2) {
						if(Array1.length < 1) { Array1.push(result.queens[1].queen_name); x_axis.push('x') }
						if(item.chart_q2 < 0 || item.chart_q2 == null) { Array1.push(0) }
						else { Array1.push(item.chart_q2) }
						// STORING THE TIME STAMP
						var time_stamp_defined = new Date(item.time_stamp);  // CONVERT TIME STAMP FROM SQL INTO DATE FORMAT
						var time_stamp_CentralTime = new Date(time_stamp_defined - utc_offset*60*60*1000);  // SUBTRACT HOURS TO CONVERT UTC TO CENTRAL TIME
						x_axis.push(time_stamp_CentralTime); // STORE THE TIME STAMP AS CENTRAL TIME
						if( time_stamp_CentralTime.getHours() == 0 && time_stamp_CentralTime.getMinutes() == 0 ) {Dates2Keep.push(time_stamp_CentralTime)}
					}	
					else if ($scope.lng_queens == 3) {
						if(Array1.length < 1) { Array1.push(result.queens[2].queen_name); x_axis.push('x') }
						if(item.inox_q3 < 0 || item.inox_q3 == null) { Array1.push(0) }
						else { Array1.push(item.inox_q3*60) }
						// STORING THE TIME STAMP
						var time_stamp_defined = new Date(item.time_stamp);  // CONVERT TIME STAMP FROM SQL INTO DATE FORMAT
						var time_stamp_CentralTime = new Date(time_stamp_defined - utc_offset*60*60*1000);  // SUBTRACT HOURS TO CONVERT UTC TO CENTRAL TIME
						x_axis.push(time_stamp_CentralTime); // STORE THE TIME STAMP AS CENTRAL TIME
						if( time_stamp_CentralTime.getHours() == 0 && time_stamp_CentralTime.getMinutes() == 0 ) {Dates2Keep.push(time_stamp_CentralTime)}
						
					}	
					else if ($scope.lng_queens == 4) {
						if(Array1.length < 1) { Array1.push(result.queens[3].queen_name); x_axis.push('x') }
						if(item.inox_q4 < 0 || item.inox_q4 == null) { Array1.push(0) }
						else { Array1.push(item.inox_q4*60) }
						// STORING THE TIME STAMP
						var time_stamp_defined = new Date(item.time_stamp);  // CONVERT TIME STAMP FROM SQL INTO DATE FORMAT
						var time_stamp_CentralTime = new Date(time_stamp_defined - utc_offset*60*60*1000);  // SUBTRACT HOURS TO CONVERT UTC TO CENTRAL TIME
						x_axis.push(time_stamp_CentralTime); // STORE THE TIME STAMP AS CENTRAL TIME
						if( time_stamp_CentralTime.getHours() == 0 && time_stamp_CentralTime.getMinutes() == 0 ) {Dates2Keep.push(time_stamp_CentralTime)}

					}	
					else if ($scope.lng_queens == 5) {
						if(Array1.length < 1) { Array1.push(result.queens[4].queen_name); x_axis.push('x') }
						if(item.chart_q5 < 0 || item.chart_q5 == null) { Array1.push(0) }
						else { Array1.push(item.chart_q5) }
						// STORING THE TIME STAMP
						var time_stamp_defined = new Date(item.time_stamp);  // CONVERT TIME STAMP FROM SQL INTO DATE FORMAT
						var time_stamp_CentralTime = new Date(time_stamp_defined - utc_offset*60*60*1000);  // SUBTRACT HOURS TO CONVERT UTC TO CENTRAL TIME
						x_axis.push(time_stamp_CentralTime); // STORE THE TIME STAMP AS CENTRAL TIME
						if( time_stamp_CentralTime.getHours() == 0 && time_stamp_CentralTime.getMinutes() == 0 ) {Dates2Keep.push(time_stamp_CentralTime)}
					}
					else if ($scope.lng_queens == 6) {
						if(Array1.length < 1) { Array1.push(result.queens[5].queen_name); x_axis.push('x') }
						if(item.inox_q6 < 0 || item.inox_q6 == null) { Array1.push(0) }
						else { Array1.push(item.inox_q6*60) }
						// STORING THE TIME STAMP
						var time_stamp_defined = new Date(item.time_stamp);  // CONVERT TIME STAMP FROM SQL INTO DATE FORMAT
						var time_stamp_CentralTime = new Date(time_stamp_defined - utc_offset*60*60*1000);  // SUBTRACT HOURS TO CONVERT UTC TO CENTRAL TIME
						x_axis.push(time_stamp_CentralTime); // STORE THE TIME STAMP AS CENTRAL TIME
						if( time_stamp_CentralTime.getHours() == 0 && time_stamp_CentralTime.getMinutes() == 0 ) {Dates2Keep.push(time_stamp_CentralTime)}
					}		
					else if ($scope.lng_queens == 7) {
						if(Array1.length < 1) { Array1.push(result.queens[6].queen_name); x_axis.push('x') }
						if(item.chart_q7 < 0 || item.chart_q7 == null) { Array1.push(0) }
						else { Array1.push(item.chart_q7) }
						// STORING THE TIME STAMP
						var time_stamp_defined = new Date(item.time_stamp);  // CONVERT TIME STAMP FROM SQL INTO DATE FORMAT
						var time_stamp_CentralTime = new Date(time_stamp_defined - utc_offset*60*60*1000);  // SUBTRACT HOURS TO CONVERT UTC TO CENTRAL TIME
						x_axis.push(time_stamp_CentralTime); // STORE THE TIME STAMP AS CENTRAL TIME
						if( time_stamp_CentralTime.getHours() == 0 && time_stamp_CentralTime.getMinutes() == 0 ) {Dates2Keep.push(time_stamp_CentralTime)}
					}		
					else if ($scope.lng_queens == 8) {
						if(Array1.length < 1) { Array1.push(result.queens[7].queen_name); x_axis.push('x') }
						if(item.chart_q8 < 0 || item.chart_q8 == null) { Array1.push(0) }
						else { Array1.push(item.chart_q8) }
						// STORING THE TIME STAMP
						var time_stamp_defined = new Date(item.time_stamp);  // CONVERT TIME STAMP FROM SQL INTO DATE FORMAT
						var time_stamp_CentralTime = new Date(time_stamp_defined - utc_offset*60*60*1000);  // SUBTRACT HOURS TO CONVERT UTC TO CENTRAL TIME
						x_axis.push(time_stamp_CentralTime); // STORE THE TIME STAMP AS CENTRAL TIME
						if( time_stamp_CentralTime.getHours() == 0 && time_stamp_CentralTime.getMinutes() == 0 ) {Dates2Keep.push(time_stamp_CentralTime)}
					}		
					else if ($scope.lng_queens == 9) {
						if(Array1.length < 1) { Array1.push(result.queens[8].queen_name); x_axis.push('x') }
						if(item.chart_q9 < 0 || item.chart_q9 == null) { Array1.push(0) }
						else { Array1.push(item.chart_q9) }
						// STORING THE TIME STAMP
						var time_stamp_defined = new Date(item.time_stamp);  // CONVERT TIME STAMP FROM SQL INTO DATE FORMAT
						var time_stamp_CentralTime = new Date(time_stamp_defined - utc_offset*60*60*1000);  // SUBTRACT HOURS TO CONVERT UTC TO CENTRAL TIME
						x_axis.push(time_stamp_CentralTime); // STORE THE TIME STAMP AS CENTRAL TIME
						if( time_stamp_CentralTime.getHours() == 0 && time_stamp_CentralTime.getMinutes() == 0 ) {Dates2Keep.push(time_stamp_CentralTime)}
					}
					else if ($scope.lng_queens == 10) {
						if(Array1.length < 1) { Array1.push(result.queens[9].queen_name); x_axis.push('x') }
						if(item.chart_q10 < 0 || item.chart_q10 == null) { Array1.push(0) }
						else { Array1.push(item.chart_q10) }
						// STORING THE TIME STAMP
						var time_stamp_defined = new Date(item.time_stamp);  // CONVERT TIME STAMP FROM SQL INTO DATE FORMAT
						var time_stamp_CentralTime = new Date(time_stamp_defined - utc_offset*60*60*1000);  // SUBTRACT HOURS TO CONVERT UTC TO CENTRAL TIME
						x_axis.push(time_stamp_CentralTime); // STORE THE TIME STAMP AS CENTRAL TIME
						if( time_stamp_CentralTime.getHours() == 0 && time_stamp_CentralTime.getMinutes() == 0 ) {Dates2Keep.push(time_stamp_CentralTime)}
					}		
					/*else if ($scope.lng_queens == 13) {
						if(Array1.length < 1) { Array1.push(result.queens[12].queen_name); x_axis.push('x') }
						if(item.chart_pq34 < 0 || item.chart_pq34 == null) { Array1.push(0) }
						else { Array1.push(item.chart_pq34) }
						// STORING THE TIME STAMP
						var time_stamp_defined = new Date(item.time_stamp);  // CONVERT TIME STAMP FROM SQL INTO DATE FORMAT
						var time_stamp_CentralTime = new Date(time_stamp_defined - utc_offset*60*60*1000);  // SUBTRACT HOURS TO CONVERT UTC TO CENTRAL TIME
						x_axis.push(time_stamp_CentralTime); // STORE THE TIME STAMP AS CENTRAL TIME
						if( time_stamp_CentralTime.getHours() == 0 && time_stamp_CentralTime.getMinutes() == 0 ) {Dates2Keep.push(time_stamp_CentralTime)}
					}		
					else if ($scope.lng_queens == 16) {
						if(Array1.length < 1) { Array1.push(result.queens[15].queen_name); x_axis.push('x') }
						if(item.chart_pq66 < 0 || item.chart_pq66 == null) { Array1.push(0) }
						else { Array1.push(item.chart_pq66) }
						// STORING THE TIME STAMP
						var time_stamp_defined = new Date(item.time_stamp);  // CONVERT TIME STAMP FROM SQL INTO DATE FORMAT
						var time_stamp_CentralTime = new Date(time_stamp_defined - utc_offset*60*60*1000);  // SUBTRACT HOURS TO CONVERT UTC TO CENTRAL TIME
						x_axis.push(time_stamp_CentralTime); // STORE THE TIME STAMP AS CENTRAL TIME
						if( time_stamp_CentralTime.getHours() == 0 && time_stamp_CentralTime.getMinutes() == 0 ) {Dates2Keep.push(time_stamp_CentralTime)}
					}*/		

					else {
						if(Array1.length < 1) { // STORE THE NAME OF THE QUEENS
							if($scope.customers_ID == $scope.queens.data[0].customer_id || $scope.customers_ID == null)
								Array1.push(result.queens[0].queen_name)
							if($scope.customers_ID == $scope.queens.data[1].customer_id || $scope.customers_ID == null)
								Array2.push(result.queens[1].queen_name)
							if($scope.customers_ID == $scope.queens.data[2].customer_id || $scope.customers_ID == null)
								Array3.push(result.queens[2].queen_name)
							if($scope.customers_ID == $scope.queens.data[3].customer_id || $scope.customers_ID == null)
								Array4.push(result.queens[3].queen_name)			
							if($scope.customers_ID == $scope.queens.data[4].customer_id || $scope.customers_ID == null)
								Array5.push(result.queens[4].queen_name)																		
							if($scope.customers_ID == $scope.queens.data[5].customer_id || $scope.customers_ID == null)
								Array6.push(result.queens[5].queen_name)			
							if($scope.customers_ID == $scope.queens.data[6].customer_id || $scope.customers_ID == null)
								Array7.push(result.queens[6].queen_name)			
							if($scope.customers_ID == $scope.queens.data[7].customer_id || $scope.customers_ID == null)
								Array8.push(result.queens[7].queen_name)			
							if($scope.customers_ID == $scope.queens.data[8].customer_id || $scope.customers_ID == null)
								Array9.push(result.queens[8].queen_name)	
							if($scope.customers_ID == $scope.queens.data[9].customer_id || $scope.customers_ID == null)
								Array10.push(result.queens[9].queen_name)	
							/*if($scope.customers_ID == $scope.queens.data[12].customer_id || $scope.customers_ID == null)
								Array34.push(result.queens[12].queen_name)	
							if($scope.customers_ID == $scope.queens.data[15].customer_id || $scope.customers_ID == null)
								Array66.push(result.queens[15].queen_name)	*/			
							x_axis.push('x')				
						}
						// CHECKING IF VALUE IS LESS THAN ZERO OR NULL
						if($scope.customers_ID == $scope.queens.data[0].customer_id || $scope.customers_ID == null)
							if(item.inox_q1 < 0 || item.inox_q1 == null) { Array1.push(0) } else { Array1.push(item.inox_q1*60) }
						if($scope.customers_ID == $scope.queens.data[1].customer_id || $scope.customers_ID == null)
							if(item.chart_q2 < 0 || item.chart_q2 == null) { Array2.push(0) } else { Array2.push(item.chart_q2) }
						if($scope.customers_ID == $scope.queens.data[2].customer_id || $scope.customers_ID == null)
							if(item.inox_q3 < 0 || item.inox_q3 == null) { Array3.push(0) } else { Array3.push(item.inox_q3*60) }
						if($scope.customers_ID == $scope.queens.data[3].customer_id || $scope.customers_ID == null)
							if(item.inox_q4 < 0 || item.inox_q4 == null) { Array4.push(0) } else { Array4.push(item.inox_q4*60) }
						if($scope.customers_ID == $scope.queens.data[4].customer_id || $scope.customers_ID == null)
							if(item.chart_q5 < 0 || item.chart_q5 == null) { Array5.push(0) } else { Array5.push(item.chart_q5) }
						if($scope.customers_ID == $scope.queens.data[5].customer_id || $scope.customers_ID == null)
							if(item.inox_q6 < 0 || item.inox_q6 == null) { Array6.push(0) } else { Array6.push(item.inox_q6*60) }
						if($scope.customers_ID == $scope.queens.data[6].customer_id || $scope.customers_ID == null)
							if(item.chart_q7 < 0 || item.chart_q7 == null) { Array7.push(0) } else { Array7.push(item.chart_q7) }
						if($scope.customers_ID == $scope.queens.data[7].customer_id || $scope.customers_ID == null)
							if(item.chart_q8 < 0 || item.chart_q8 == null) { Array8.push(0) } else { Array8.push(item.chart_q8) }
						if($scope.customers_ID == $scope.queens.data[8].customer_id || $scope.customers_ID == null)
							if(item.chart_q9 < 0 || item.chart_q9 == null) { Array9.push(0) } else { Array9.push(item.chart_q9) }
						if($scope.customers_ID == $scope.queens.data[9].customer_id || $scope.customers_ID == null)
							if(item.chart_q10 < 0 || item.chart_q10 == null) { Array10.push(0) } else { Array10.push(item.chart_q10) }
						/*if($scope.customers_ID == $scope.queens.data[12].customer_id || $scope.customers_ID == null)
							if(item.chart_pq34 < 0 || item.chart_pq34 == null) { Array34.push(0) } else { Array34.push(item.chart_pq34) }
						if($scope.customers_ID == $scope.queens.data[15].customer_id || $scope.customers_ID == null)
							if(item.chart_pq66 < 0 || item.chart_pq66 == null) { Array66.push(0) } else { Array66.push(item.chart_pq66) }*/
													
						// STORING THE TIME STAMP
						var time_stamp_defined = new Date(item.time_stamp);  // CONVERT TIME STAMP FROM SQL INTO DATE FORMAT
						var time_stamp_CentralTime = new Date(time_stamp_defined - utc_offset*60*60*1000);  // SUBTRACT HOURS TO CONVERT UTC TO CENTRAL TIME
						x_axis.push(time_stamp_CentralTime); // STORE THE TIME STAMP AS CENTRAL TIME

						// IF THE TIME STAMP IS 00:00 (MIDNIGHT) SAVE INTO A SECOND ARRAY FOR A CLEANER X-AXIS FOR THE PLOT
						// WANT TO PRINT ONLY THE DAY ONE TIME ON THE X-AXIS
						if( time_stamp_CentralTime.getHours() == 0 && time_stamp_CentralTime.getMinutes() == 0 ) {Dates2Keep.push(time_stamp_CentralTime)}

						if(i == result.data.length - 1) { // SMARTS TO KNOW WHEN TO BUILD THE FINAL ARRAY FROM THE OTHER ARRAYS
							ArrayQueens.push(x_axis)
							if($scope.customers_ID == $scope.queens.data[0].customer_id || $scope.customers_ID == null)
								ArrayQueens.push(Array1)
							if($scope.customers_ID == $scope.queens.data[1].customer_id || $scope.customers_ID == null)
								ArrayQueens.push(Array2)
							if($scope.customers_ID == $scope.queens.data[2].customer_id || $scope.customers_ID == null)
								ArrayQueens.push(Array3)
							if($scope.customers_ID == $scope.queens.data[3].customer_id || $scope.customers_ID == null)
								ArrayQueens.push(Array4)
							if($scope.customers_ID == $scope.queens.data[4].customer_id || $scope.customers_ID == null)
								ArrayQueens.push(Array5)
							if($scope.customers_ID == $scope.queens.data[5].customer_id || $scope.customers_ID == null)
								ArrayQueens.push(Array6)
							if($scope.customers_ID == $scope.queens.data[6].customer_id || $scope.customers_ID == null)
								ArrayQueens.push(Array7)
							if($scope.customers_ID == $scope.queens.data[7].customer_id || $scope.customers_ID == null)
								ArrayQueens.push(Array8)
							if($scope.customers_ID == $scope.queens.data[8].customer_id || $scope.customers_ID == null)
								ArrayQueens.push(Array9)
							if($scope.customers_ID == $scope.queens.data[9].customer_id || $scope.customers_ID == null)
								ArrayQueens.push(Array10)
							/*if($scope.customers_ID == $scope.queens.data[13].customer_id || $scope.customers_ID == null)
								ArrayQueens.push(Array34)
							if($scope.customers_ID == $scope.queens.data[15].customer_id || $scope.customers_ID == null)
								ArrayQueens.push(Array66)*/

						}
					} // CLOSE ELSE
				//}
			}	// CLOSE FOR LOOP	
			
			if($scope.lng_queens != 0) {
				ArrayQueens.push(x_axis)
				ArrayQueens.push(Array1)
				//ArrayQueens=(ArrayQueens)
			}
			//console.log("ArrayQueens is " + JSON.stringify(ArrayQueens))
			var chartFlowRate = c3.generate({
				bindto:'#chartFlowRate',
				size: { height: 480, width: 1200},
				padding: { top: 20, right: 40, bottom: 40, left: 40},
				data: {
					x: 'x',
					xFormat: '%Y-%m-%d %H:%M',
					columns: 
						ArrayQueens

    			},
    			grid: {
					x: { show: true },
					y: { show: true }
    			},
    			point: {
					show: false
    			},
				/*tooltip: {
					format: {
						value: function (name, ratio, id, index) {
							return name+" - "+id;
                		}
      				}
				},*/
				axis: {
					x: {
						type: 'timeseries',
						tick: {
							// count: 4
							format: '%m/%d %H:%M', //%H-%M-%S
							rotate: 0,
							multiline: false,
							// this also works for non timeseries data
							values:  Dates2Keep
							//values: ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23']
            			}
        			}
    			} // CLOSE AXIS
			}); // CLOSE C3 . GENERATE

			$.unblockUI();
        }).error(function (result) {
            $.unblockUI();
            toastr.error("Get error.");
        }); // CLOSE HTTP . GET
    }; // CLOSE LOAD FLOW RATE	
    
    $scope.loadTotalizer = function () {
        myblockui();
        var api_url = window.cfg.apiUrl + "reports/lng_totalizer_data.php?lng_queens=" + $scope.lng_queens +"&StartDate="+moment($scope.startdate).format("MM/DD/YYYY")+"&EndDate="+moment($scope.enddate).format("MM/DD/YYYY");
        $http.get(api_url).success(function (result) {
            //$.unblockUI();
		
			var today = new Date();

			Date.prototype.stdTimezoneOffset = function() {
				var jan = new Date(this.getFullYear(), 0, 1);
				var jul = new Date(this.getFullYear(), 6, 1);
				return Math.max(jan.getTimezoneOffset(), jul.getTimezoneOffset());
			}

			Date.prototype.dst = function() {
				return this.getTimezoneOffset() < this.stdTimezoneOffset();
			}

			var utc_offset = 6;
			var midnight = '06:00';
			if (today.dst()) {
				utc_offset = 5;
				midnight = '05:00';
			}
			console.log("Start is " + result.start + " End is " + result.end + " UTC is " + utc_offset + " the answer is " + result.answer)
			var Array1 = [];
			var Array2 = [];
			var Array3 = [];
			var Array4 = [];
			var Array5 = [];
			var Array6 = [];
			var Array7 = [];
			var Array8 = [];
			var Array9 = [];
			var Array10 = [];
			var ArrayQueens = [];
			var x_axis = [];
			var x_axis2 = [];
			var Dates2Keep = [];
			for (var i = 0; i < result.data.length; i++) {
				if(i < result.data.length - 1) {
                var item 	= result.data[result.data.length-i-1];
				var item2 	= result.data[result.data.length-i-2];
				
				//if ( ( (i+1) % 4) == 0) {
					if ($scope.lng_queens == 1) {
						if(Array1.length < 1) { Array1.push(result.queens[0].queen_name); x_axis.push('x') }
						if( (item2.inox_q1 - item.inox_q1) < 0 || item2.inox_q1 == -1 || item.inox_q1 == -1   ) { Array1.push(0) }
						//if(item.inox_q1 < 0 || item.inox_q1 == null) { Array1.push(0) }
						else { Array1.push((item2.inox_q1 - item.inox_q1)*1024/82644) }
						// STORING THE TIME STAMP
						var time_stamp_defined = new Date(item.time_stamp);  // CONVERT TIME STAMP FROM SQL INTO DATE FORMAT
						var time_stamp_CentralTime = new Date(time_stamp_defined - utc_offset*60*60*1000);  // SUBTRACT HOURS TO CONVERT UTC TO CENTRAL TIME
						x_axis.push(time_stamp_CentralTime); // STORE THE TIME STAMP AS CENTRAL TIME
						if( time_stamp_CentralTime.getHours() == 0 && time_stamp_CentralTime.getMinutes() == 0 ) {Dates2Keep.push(time_stamp_CentralTime)}
						//x_axis.push(item.time_stamp)
						//if(item.time_stamp.slice(-5) == "00:00") {Dates2Keep.push(item.time_stamp)}
					}
					else if ($scope.lng_queens == 2) {
						if(Array1.length < 1) { Array1.push(result.queens[1].queen_name); x_axis.push('x') }
						if( (item2.chart_q2 - item.chart_q2) < 0 || item2.chart_q2 == -1 || item.chart_q2 == -1  ) { Array1.push(0) }
						else { Array1.push((item2.chart_q2 - item.chart_q2)*1024/82644) }
						// STORING THE TIME STAMP
						var time_stamp_defined = new Date(item.time_stamp);  // CONVERT TIME STAMP FROM SQL INTO DATE FORMAT
						var time_stamp_CentralTime = new Date(time_stamp_defined - utc_offset*60*60*1000);  // SUBTRACT HOURS TO CONVERT UTC TO CENTRAL TIME
						x_axis.push(time_stamp_CentralTime); // STORE THE TIME STAMP AS CENTRAL TIME
						if( time_stamp_CentralTime.getHours() == 0 && time_stamp_CentralTime.getMinutes() == 0 ) {Dates2Keep.push(time_stamp_CentralTime)}
					}	
					else if ($scope.lng_queens == 3) {
						if(Array1.length < 1) { Array1.push(result.queens[2].queen_name); x_axis.push('x') }
						if( (item2.inox_q3 - item.inox_q3) < 0 || item2.inox_q3 == -1 || item.inox_q3 == -1  ) { Array1.push(0) }
						else { Array1.push((item2.inox_q3 - item.inox_q3)*1024/82644) }
						// STORING THE TIME STAMP
						var time_stamp_defined = new Date(item.time_stamp);  // CONVERT TIME STAMP FROM SQL INTO DATE FORMAT
						var time_stamp_CentralTime = new Date(time_stamp_defined - utc_offset*60*60*1000);  // SUBTRACT HOURS TO CONVERT UTC TO CENTRAL TIME
						x_axis.push(time_stamp_CentralTime); // STORE THE TIME STAMP AS CENTRAL TIME
						if( time_stamp_CentralTime.getHours() == 0 && time_stamp_CentralTime.getMinutes() == 0 ) {Dates2Keep.push(time_stamp_CentralTime)}						
					}	
					else if ($scope.lng_queens == 4) {
						if(Array1.length < 1) { Array1.push(result.queens[3].queen_name); x_axis.push('x') }
						if( (item2.inox_q4 - item.inox_q4) < 0 || item2.inox_q4 == -1 || item.inox_q4 == -1  ) { Array1.push(0) }
						else { Array1.push((item2.inox_q4 - item.inox_q4)*1024/82644) }
						// STORING THE TIME STAMP
						var time_stamp_defined = new Date(item.time_stamp);  // CONVERT TIME STAMP FROM SQL INTO DATE FORMAT
						var time_stamp_CentralTime = new Date(time_stamp_defined - utc_offset*60*60*1000);  // SUBTRACT HOURS TO CONVERT UTC TO CENTRAL TIME
						x_axis.push(time_stamp_CentralTime); // STORE THE TIME STAMP AS CENTRAL TIME
						if( time_stamp_CentralTime.getHours() == 0 && time_stamp_CentralTime.getMinutes() == 0 ) {Dates2Keep.push(time_stamp_CentralTime)}
					}	
					else if ($scope.lng_queens == 5) {
						if(Array1.length < 1) { Array1.push(result.queens[4].queen_name); x_axis.push('x') }
						if( (item2.chart_q5 - item.chart_q5) < 0 || item2.chart_q5 == -1 || item.chart_q5 == -1 ) { Array1.push(0) }
						else { Array1.push((item2.chart_q5 - item.chart_q5)*1024/82644) }
						
						// STORING THE TIME STAMP
						var time_stamp_defined = new Date(item.time_stamp);  // CONVERT TIME STAMP FROM SQL INTO DATE FORMAT
						var time_stamp_CentralTime = new Date(time_stamp_defined - utc_offset*60*60*1000);  // SUBTRACT HOURS TO CONVERT UTC TO CENTRAL TIME
						x_axis.push(time_stamp_CentralTime); // STORE THE TIME STAMP AS CENTRAL TIME
						if( time_stamp_CentralTime.getHours() == 0 && time_stamp_CentralTime.getMinutes() == 0 ) {Dates2Keep.push(time_stamp_CentralTime)}					
					}
					else if ($scope.lng_queens == 6) {
						if(Array1.length < 1) { Array1.push(result.queens[5].queen_name); x_axis.push('x') }
						if( (item2.inox_q6 - item.inox_q6) < 0 || item2.inox_q6 == -1 || item.inox_q6 == -1  ) { Array1.push(0) }
						else { Array1.push((item2.inox_q6 - item.inox_q6)*1024/82644) }
						// STORING THE TIME STAMP
						var time_stamp_defined = new Date(item.time_stamp);  // CONVERT TIME STAMP FROM SQL INTO DATE FORMAT
						console.log("Time is " + time_stamp_defined);
						var time_stamp_CentralTime = new Date(time_stamp_defined - utc_offset*60*60*1000);  // SUBTRACT HOURS TO CONVERT UTC TO CENTRAL TIME
						x_axis.push(time_stamp_CentralTime); // STORE THE TIME STAMP AS CENTRAL TIME
						if( time_stamp_CentralTime.getHours() == 0 && time_stamp_CentralTime.getMinutes() == 0 ) {Dates2Keep.push(time_stamp_CentralTime)}
					}		
					else if ($scope.lng_queens == 7) {
						if(Array1.length < 1) { Array1.push(result.queens[6].queen_name); x_axis.push('x') }
						if( (item2.chart_q7 - item.chart_q7) < 0 || item2.chart_q7 == -1 || item.chart_q7 == -1  ) { Array1.push(0) }
						else { Array1.push((item2.chart_q7 - item.chart_q7)*1024/82644) }
						// STORING THE TIME STAMP
						var time_stamp_defined = new Date(item.time_stamp);  // CONVERT TIME STAMP FROM SQL INTO DATE FORMAT
						var time_stamp_CentralTime = new Date(time_stamp_defined - utc_offset*60*60*1000);  // SUBTRACT HOURS TO CONVERT UTC TO CENTRAL TIME
						x_axis.push(time_stamp_CentralTime); // STORE THE TIME STAMP AS CENTRAL TIME
						if( time_stamp_CentralTime.getHours() == 0 && time_stamp_CentralTime.getMinutes() == 0 ) {Dates2Keep.push(time_stamp_CentralTime)}
					}		
					else if ($scope.lng_queens == 8) {
						if(Array1.length < 1) { Array1.push(result.queens[7].queen_name); x_axis.push('x') }
						if( (item2.chart_q8- item.chart_q8) < 0 || item2.chart_q8 == -1 || item.chart_q8 == -1  ) { Array1.push(0) }
						else { Array1.push((item2.chart_q8- item.chart_q8)*1024/82644) }
						// STORING THE TIME STAMP
						var time_stamp_defined = new Date(item.time_stamp);  // CONVERT TIME STAMP FROM SQL INTO DATE FORMAT
						var time_stamp_CentralTime = new Date(time_stamp_defined - utc_offset*60*60*1000);  // SUBTRACT HOURS TO CONVERT UTC TO CENTRAL TIME
						x_axis.push(time_stamp_CentralTime); // STORE THE TIME STAMP AS CENTRAL TIME
						if( time_stamp_CentralTime.getHours() == 0 && time_stamp_CentralTime.getMinutes() == 0 ) {Dates2Keep.push(time_stamp_CentralTime)}
					}		
					else if ($scope.lng_queens == 9) {
						if(Array1.length < 1) { Array1.push(result.queens[8].queen_name); x_axis.push('x') }
						if( (item2.chart_q9 - item.chart_q9) < 0 || item2.chart_q9 == -1 || item.chart_q9 == -1  ) { Array1.push(0) }
						else { Array1.push((item2.chart_q9 - item.chart_q9)*1024/82644) }
						// STORING THE TIME STAMP
						var time_stamp_defined = new Date(item.time_stamp);  // CONVERT TIME STAMP FROM SQL INTO DATE FORMAT
						var time_stamp_CentralTime = new Date(time_stamp_defined - utc_offset*60*60*1000);  // SUBTRACT HOURS TO CONVERT UTC TO CENTRAL TIME
						x_axis.push(time_stamp_CentralTime); // STORE THE TIME STAMP AS CENTRAL TIME
						if( time_stamp_CentralTime.getHours() == 0 && time_stamp_CentralTime.getMinutes() == 0 ) {Dates2Keep.push(time_stamp_CentralTime)}
					}		
					else if ($scope.lng_queens == 10) {
						if(Array1.length < 1) { Array1.push(result.queens[9].queen_name); x_axis.push('x') }
						if( (item2.chart_q10 - item.chart_q10) < 0 || item2.chart_q10 == -1 || item.chart_q10 == -1  ) { Array1.push(0) }
						else { Array1.push((item2.chart_q10 - item.chart_q10)*1024/82644) }
						// STORING THE TIME STAMP
						var time_stamp_defined = new Date(item.time_stamp);  // CONVERT TIME STAMP FROM SQL INTO DATE FORMAT
						var time_stamp_CentralTime = new Date(time_stamp_defined - utc_offset*60*60*1000);  // SUBTRACT HOURS TO CONVERT UTC TO CENTRAL TIME
						x_axis.push(time_stamp_CentralTime); // STORE THE TIME STAMP AS CENTRAL TIME
						if( time_stamp_CentralTime.getHours() == 0 && time_stamp_CentralTime.getMinutes() == 0 ) {Dates2Keep.push(time_stamp_CentralTime)}
					}	

					else {
						if(Array1.length < 1) { // STORE THE NAME OF THE QUEENS				
							if($scope.customers_ID == $scope.queens.data[0].customer_id || $scope.customers_ID == null)
								Array1.push(result.queens[0].queen_name)
							if($scope.customers_ID == $scope.queens.data[1].customer_id || $scope.customers_ID == null)
								Array2.push(result.queens[1].queen_name)
							if($scope.customers_ID == $scope.queens.data[2].customer_id || $scope.customers_ID == null)
								Array3.push(result.queens[2].queen_name)
							if($scope.customers_ID == $scope.queens.data[3].customer_id || $scope.customers_ID == null)
								Array4.push(result.queens[3].queen_name)			
							if($scope.customers_ID == $scope.queens.data[4].customer_id || $scope.customers_ID == null)
								Array5.push(result.queens[4].queen_name)																		
							if($scope.customers_ID == $scope.queens.data[5].customer_id || $scope.customers_ID == null)
								Array6.push(result.queens[5].queen_name)	
							if($scope.customers_ID == $scope.queens.data[6].customer_id || $scope.customers_ID == null)
								Array7.push(result.queens[6].queen_name)	
							if($scope.customers_ID == $scope.queens.data[7].customer_id || $scope.customers_ID == null)
								Array8.push(result.queens[7].queen_name)	
							if($scope.customers_ID == $scope.queens.data[8].customer_id || $scope.customers_ID == null)
								Array9.push(result.queens[8].queen_name)			
							if($scope.customers_ID == $scope.queens.data[9].customer_id || $scope.customers_ID == null)
								Array10.push(result.queens[9].queen_name)	
							x_axis.push('x')				
						}
						// CHECKING IF VALUE IS LESS THAN ZERO OR NULL
						// 1 SCF = 1024 BTU -> TOTALIZER IS IN SCF
						// 1 LNGg = 82644 BTU 
						if($scope.customers_ID == $scope.queens.data[0].customer_id || $scope.customers_ID == null)
							if( (item2.inox_q1 - item.inox_q1) < 0 || item2.inox_q1 == -1 || item.inox_q1 == -1  ) { Array1.push(0) } else { Array1.push((item2.inox_q1 - item.inox_q1)*1024/82644) }
						if($scope.customers_ID == $scope.queens.data[1].customer_id || $scope.customers_ID == null)
							if( (item2.chart_q2 - item.chart_q2) < 0 || item2.chart_q2 == -1 || item.chart_q2 == -1  ) { Array2.push(0) } else { Array2.push((item2.chart_q2 - item.chart_q2)*1024/82644) }
						if($scope.customers_ID == $scope.queens.data[2].customer_id || $scope.customers_ID == null)
							if( (item2.inox_q3 - item.inox_q3) < 0 || item2.inox_q3 == -1 || item.inox_q3 == -1  ) { Array3.push(0) } else { Array3.push((item2.inox_q3 - item.inox_q3)*1024/82644) }
						if($scope.customers_ID == $scope.queens.data[3].customer_id || $scope.customers_ID == null)
							if( (item2.inox_q4 - item.inox_q4) < 0 || item2.inox_q4 == -1 || item.inox_q54 == -1  ) { Array4.push(0) } else { Array4.push((item2.inox_q4 - item.inox_q4)*1024/82644) }
						if($scope.customers_ID == $scope.queens.data[4].customer_id || $scope.customers_ID == null)
							if( (item2.chart_q5 - item.chart_q5) < 0 || item2.chart_q5 == -1 || item.chart_q5 == -1) { Array5.push(0) } else { Array5.push((item2.chart_q5 - item.chart_q5)*1024/82644) }
						if($scope.customers_ID == $scope.queens.data[5].customer_id || $scope.customers_ID == null)
							if( (item2.inox_q6 - item.inox_q6) < 0 || item2.inox_q6 == -1 || item.inox_q6 == -1  ) { Array6.push(0) } else { Array6.push((item2.inox_q6 - item.inox_q6)*1024/82644) }
						if($scope.customers_ID == $scope.queens.data[6].customer_id || $scope.customers_ID == null)
							if( (item2.chart_q7 - item.chart_q7) < 0 || item2.chart_q7 == -1 || item.chart_q7 == -1  ) { Array7.push(0) } else { Array7.push((item2.chart_q7 - item.chart_q7)*1024/82644) }
						if($scope.customers_ID == $scope.queens.data[7].customer_id || $scope.customers_ID == null)
							if( (item2.chart_q8 - item.chart_q8) < 0 || item2.chart_q8 == -1 || item.chart_q8 == -1  ) { Array8.push(0) } else { Array8.push((item2.chart_q8 - item.chart_q8)*1024/82644) }
						if($scope.customers_ID == $scope.queens.data[8].customer_id || $scope.customers_ID == null)
							if( (item2.chart_q9 - item.chart_q9) < 0 || item2.chart_q9 == -1 || item.chart_q9 == -1  ) { Array9.push(0) } else { Array9.push((item2.chart_q9 - item.chart_q9)*1024/82644) }
						if($scope.customers_ID == $scope.queens.data[9].customer_id || $scope.customers_ID == null)
							if( (item2.chart_q10 - item.chart_q10) < 0 || item2.chart_q10 == -1 || item.chart_q10 == -1  ) { Array10.push(0) } else { Array10.push((item2.chart_q10 - item.chart_q10)*1024/82644) }
						
						// STORING THE TIME STAMP
						var time_stamp_defined = new Date(item.time_stamp);  // CONVERT TIME STAMP FROM SQL INTO DATE FORMAT
						var time_stamp_CentralTime = new Date(time_stamp_defined - utc_offset*60*60*1000);  // SUBTRACT HOURS TO CONVERT UTC TO CENTRAL TIME
						x_axis.push(time_stamp_CentralTime); // STORE THE TIME STAMP AS CENTRAL TIME

						// IF THE TIME STAMP IS 00:00 (MIDNIGHT) SAVE INTO A SECOND ARRAY FOR A CLEANER X-AXIS FOR THE PLOT
						// WANT TO PRINT ONLY THE DAY ONE TIME ON THE X-AXIS
						if( time_stamp_CentralTime.getHours() == 0 && time_stamp_CentralTime.getMinutes() == 0 ) {Dates2Keep.push(time_stamp_CentralTime)}

						if(i == result.data.length - 2) { // SMARTS TO KNOW WHEN TO BUILD THE FINAL ARRAY FROM THE OTHER ARRAYS
							ArrayQueens.push(x_axis)
							if($scope.customers_ID == $scope.queens.data[0].customer_id || $scope.customers_ID == null)
								ArrayQueens.push(Array1)
							if($scope.customers_ID == $scope.queens.data[1].customer_id || $scope.customers_ID == null)
								ArrayQueens.push(Array2)
							if($scope.customers_ID == $scope.queens.data[2].customer_id || $scope.customers_ID == null)
								ArrayQueens.push(Array3)
							if($scope.customers_ID == $scope.queens.data[3].customer_id || $scope.customers_ID == null)
								ArrayQueens.push(Array4)
							if($scope.customers_ID == $scope.queens.data[4].customer_id || $scope.customers_ID == null)
								ArrayQueens.push(Array5)
							if($scope.customers_ID == $scope.queens.data[5].customer_id || $scope.customers_ID == null)
								ArrayQueens.push(Array6)
							if($scope.customers_ID == $scope.queens.data[6].customer_id || $scope.customers_ID == null)
								ArrayQueens.push(Array7)
							if($scope.customers_ID == $scope.queens.data[7].customer_id || $scope.customers_ID == null)
								ArrayQueens.push(Array8)
							if($scope.customers_ID == $scope.queens.data[8].customer_id || $scope.customers_ID == null)
								ArrayQueens.push(Array9)
							if($scope.customers_ID == $scope.queens.data[9].customer_id || $scope.customers_ID == null)
								ArrayQueens.push(Array10)
						}
					} // CLOSE ELSE
				}
			}	// CLOSE FOR LOOP	

			if($scope.lng_queens != 0) {
				ArrayQueens.push(x_axis)
				console.log("X is " + JSON.stringify(x_axis))
				ArrayQueens.push(Array1)
				//ArrayQueens=(ArrayQueens)
			}
			//console.log("ArrayQueens is " + JSON.stringify(ArrayQueens))
			var chartTotalizer = c3.generate({
				bindto:'#chartTotalizer',
				size: { height: 480, width: 1200},
				padding: { top: 20, right: 40, bottom: 40, left: 40},
				data: {
					x: 'x',
					xFormat: '%Y-%m-%d %H:%M',
					columns: 
						ArrayQueens

    			},
    			grid: {
					x: { show: true},
					y: { show: true}
    			},
    			point: {
					show: false
    			},
				/*tooltip: {
					format: {
						value: function (name, ratio, id, index) {
							return name+" - "+id;
                		}
      				}
				},*/
				axis: {
					x: {
						type: 'timeseries',
						tick: {
							// count: 4
							format: '%m/%d %H:%M', //%H-%M-%S
							rotate: 0,
							multiline: false,
							// this also works for non timeseries data
							values:  Dates2Keep
							//values: ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23']
            			}
        			}
    			} // CLOSE AXIS
			}); // CLOSE C3 . GENERATE

			$.unblockUI();
        }).error(function (result) {
            $.unblockUI();
            toastr.error("Get error.");
        });  // CLOSE HTTP . GET
    }; // CLOSE LOAD TOTALIZER

	// OBTAIN THE USERS CUSTOMER ID IF THEY ARE A CUSTOMER
	$scope.LoadQueens_1 = function () {
		myblockui();
    	var api_url = window.cfg.apiUrl + "queens/get.php?find_customer_id=" + 1;
        $http.get(api_url).success(function (result) {
            $scope.customers_ID = result.data.customer_id;
			console.log("1. Customer ID is " + JSON.stringify($scope.customers_ID))
			$.unblockUI();
			$scope.LoadQueens_2();
        }).error(function (result) {
            $.unblockUI();
            toastr.error("Get error.");
        });
    };
     
    $scope.LoadQueens_2 = function () {    
	    myblockui();
        var api_url = window.cfg.apiUrl + "queens/get.php";
        $http.get(api_url).success(function (result) {

            $scope.queens = result;
			 //console.log("Queens is " + JSON.stringify($scope.queens))
			 console.log("2. QUEEN is " + ($scope.queens.data[3].customer_id))
			 $.unblockUI();
			AppDataService.loadLngQueensList($scope.customers_ID, null, function (result) { // FIRST NULL NEEDS TO CONTAIN THE CUSTOMER ID
	    		console.log("4. Inside loadLNGQueensList is " + $scope.customers_ID);
				$scope.LngQueensList = result.data;
				$scope.LngQueensList.unshift({ queens_id: 0, name: "All" });
				$scope.loadTankLevel();
				$scope.loadFlowRate();
				$scope.loadTotalizer();
       		}, function (result) {
    		});
			 //console.log("Testing is " + result.test)
        }).error(function (result) {
            $.unblockUI();
            toastr.error("Get error.");
        });
    };
               
    $scope.init = function () {
	    $scope.LoadQueens_1();
	    //$scope.LoadQueens_2();
	    //$.unblockUI();
	    
	    console.log("3. The id after Queens 1 is " + $scope.customers_ID)
	    
	    /*AppDataService.loadLngQueensList($scope.customers_ID, null, function (result) { // FIRST NULL NEEDS TO CONTAIN THE CUSTOMER ID
	    	console.log("4. Inside loadLNGQueensList is " + $scope.customers_ID);
			$scope.LngQueensList = result.data;
			$scope.LngQueensList.unshift({ queens_id: 0, name: "All" });
       	}, function (result) {
    	});*/

        //$scope.loadTankLevel();
		//$scope.loadFlowRate();
    }
    
    $scope.selectDates = function () {
        //$("#div_tanklevel_extended").html("");
        $scope.loadTankLevel();
        $scope.loadFlowRate();
        $scope.loadTotalizer();
    };
    
     $scope.exportData = function () {
	   myblockui();
       var api_url = window.cfg.apiUrl + "export/lng_export_button.php?lng_queens=" + $scope.lng_queens +"&StartDate="+moment($scope.startdate).format("MM/DD/YYYY")+"&EndDate="+moment($scope.enddate).format("MM/DD/YYYY");
        $http.get(api_url).success(function (result) {
            $.unblockUI();     
            $scope.data =  result.data;
            console.log("Data is " + JSON.stringify(result.data));
            console.log("Name is " + JSON.stringify(result.data2));
            console.log("Code is " + JSON.stringify(result.code));
        }).error(function (result) {
            $.unblockUI();
            toastr.error("Get error.");
        });
    };

    
    $scope.init();

}]);
