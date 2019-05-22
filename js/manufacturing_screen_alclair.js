function d3DarwStackGroup(layers, divid, grouped, stacked, labels, option) {
    var n = layers.length;
    var m = layers[0].length;
    console.log("M IS " + m)
    var stack = d3.layout.stack();
    var yaxisleft_id = divid + "_yaxisleft";
    console.log("layers is " + layers.length)

    var alpha = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm'];
    var alpha = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13'];
    var x_label = [];
    //x_label = ["JAN", "FEB", "FEB", "FEB", "FEB", "FEB", "FEB", "FEB", "FEB", "FEB", "FEB", "FEB", "FEB"]
    for (var label = 1; label <= m; label++) {
       //x_label.push(label);
       //x_label.push(alpha[label-1]);
    }
   
    x_label.push("1");
    x_label.push("2");
    x_label.push('3');
    x_label.push("4");
    x_label.push("5");
    x_label.push("6");
    x_label.push("7");
    x_label.push("8");
    x_label.push("9");
    x_label.push("10");
    x_label.push("11");
    x_label.push("12");
    x_label.push("13");
  
  /*
    x_label.push("january");
    x_label.push("feburary");
    x_label.push("march");
    x_label.push("april");
    x_label.push("may");
    x_label.push("june");
    x_label.push("july");
    x_label.push("august");
    x_label.push("september");
    x_label.push("october");
    x_label.push("november");
    x_label.push("december");
    x_label.push("m");
*/
    for (var i = 0; i < layers.length; i++) {
        for (var j = 0; j < layers[i].length; j++) {
            layers[i][j].x = j;
            if (i == 0)
                layers[i][j].y0 = 0;
            else
                layers[i][j].y0 = layers[i - 1][j].y + layers[i - 1][j].y0;
            layers[i][j].label = labels[i];
            //layers[i][j].label = "AA";
        }
    }

    var yGroupMax = d3.max(layers, function (layer) { return d3.max(layer, function (d) { return d.y; }); });
    var yStackMax = d3.max(layers, function (layer) { return d3.max(layer, function (d) { return d.y0 + d.y; }); });

	// TYLER - 80 MADE THE BOTTOM GO FURTHER DOWN SO THE ENTIRE X-AXIS COULD BE SEEN
    var margin = { top: 40, right: 10, bottom: 80, left: 60 };
    var width = document.getElementById(divid).offsetWidth - margin.left - margin.right;
    var height = 750 - margin.top - margin.bottom;

    // TYLER - 0.4 CENTERS THE BARS - VALUE USED TO BE 0.08
    //	var x = d3.scale.ordinal().domain(x_label).rangeRoundBands([0, width], 0.4);
    
    //var x = d3.scale.ordinal().rangeRoundBands([0, width], 0.4);
	//var xAxis = d3.svg.axis().scale(x).orient("bottom");
	//x.domain(data.map(function(d) { return d.country; }));
	var x = d3.scale.ordinal().rangeRoundBands([0, width], 0.4);
	x.domain(x_label);
    
    console.log("XLB " + x_label)
    var y = d3.scale.linear().domain([0, yStackMax]).range([height, 0]);
    var y_group = d3.scale.linear().domain([0, yGroupMax]).range([height, 0]);
    var color = d3.scale.linear().domain([0, n - 1]).range(["#aad", "#556"]);

    if (option != undefined && option.category20 == true) {
        color = d3.scale.category20();
    }

    var xAxis = d3.svg.axis().scale(x).tickSize(0).tickPadding(6).orient("bottom");
    var yAxis = d3.svg.axis().scale(y).tickSize(0).tickPadding(6).tickFormat(function (d) {
	    
        if (option != undefined && option.prefix != undefined)
            return option.prefix + d;
        else
            return d;
    }).orient("left");
    var yAxisGroup = d3.svg.axis().scale(y_group).tickSize(0).tickPadding(6).tickFormat(function (d) {
        if (option != undefined && option.prefix != undefined)
            return option.prefix + d;
        else
            return d;
    }).orient("left");

    var tip = d3.tip().attr('class', 'd3-tip').html(function (d) {
        //console.log(d);
        if (option != undefined && option.prefix != undefined)
            return "<strong>" + d.label + ": " + option.prefix + d.y + "</strong>";
        else
            return "<strong>" + d.label + ": " + d.y + "</strong>";
    });

    var svg = d3.select(document.getElementById(divid)).append("svg")
                .attr("width", width + margin.left + margin.right).attr("height", height + margin.top + margin.bottom)                
                .append("g").attr("transform", "translate(" + margin.left + "," + margin.top + ")");


    svg.call(tip);

    var layer = svg.selectAll(".layer").data(layers).enter().append("g").attr("class", "layer")
                   .style("fill", function (d, i) { return color(i); });

    var rect = layer.selectAll("rect").data(function (d) { return d; }).enter().append("rect")
                    .attr("x", function (d) { return x(d.x+1); }).attr("y", height)
                    .attr("width", x.rangeBand()).attr("height", 0)
                    .on("mousemove", function (d) {
                        var node = tip.getnode();
                        if (node.style.opacity == 0) {
                            tip.show(d);
                            //console.log("show-" + new Date().getTime());
                        }

                        tip.style({ left: d3.event.pageX - node.offsetWidth / 2 + "px", top: d3.event.pageY - node.offsetHeight - 15 + "px" });
                    }).on("mouseout", function () {
                        tip.hide();
                        //console.log("hide-" + new Date().getTime());
                    });
	
// TYLER - PRINTS TEXT ON TOP OF BAR GRAPH
	var dataset2 = [];
    for (var j = 0; j < layers[0].length; j++) {
	    if (layers.length == 2) { // JUST PASS OR FAIL 
	    	if(layers[1][j].y == 0 ) 
				dataset2.push("");
			else 
				dataset2.push( Math.round( (layers[1][j].y / (layers[0][j].y + layers[1][j].y)) * 100)  );
	    } else if (layers.length != 3) {
		    if(layers[1][j].y == 0 ) 
		    	dataset2.push("");
	    	else
	    		dataset2.push("");
		    	//dataset2.push( Math.round( ( (layers[1][j].y + layers[2][j].y) / (layers[0][j].y + layers[1][j].y + layers[2][j].y)) * 100)  );
	    } else {
		    if(layers[1][j].y == 0 && layers[2][j].y == 0 ) 
		    	dataset2.push("");
		    else
		    	dataset2.push( Math.round( ( (layers[1][j].y + layers[2][j].y) / (layers[0][j].y + layers[1][j].y + layers[2][j].y)) * 100)  );
		    	if (j==5) {
		    	}
	    }
	}
	
//}
      
svg.selectAll("text")
   .data(dataset2)
   .enter()
   .append("text")
   .text(function(d) { 
	   if(!d)
	   	return d;
	   else
	   	return d+"%"; 
	   	})
   .attr("x", function(d, i) {
	   return (i+1) * ( width / dataset2.length) - (i+5)-13	;
        //return (i+1) * (27.8) - (3/(i+1));//( (width + margin.left + margin.right) / dataset2.length) ; //- 10*( (i+1)/2); //- 2*(i+1)/2-2;
   })
   .attr("y", function(d, i) {
        return height - 300 - 50*(i%2) ;
   })
   .attr("font-family", "sans-serif")
   .attr("font-size", "0px")
   .attr("fill", "black");
   //console.log("Width is " + width + margin.left + margin.right)

    //rect.transition().delay(function (d, i) { return i * 10; }).attr("y", function (d) { return y(d.y0 + d.y); })
    //                 .attr("height", function (d) { return y(d.y0) - y(d.y0 + d.y); });

	// TYLER - 55 MOVES THE BARS
    svg.append("g").attr("class", "x axis").attr("transform", "translate(0," + height + ")").call(xAxis);
    /*.selectAll("text")
    .attr("y", 0)
    .attr("x", 9)
    .attr("dy", ".35em")
    .attr("transform", "rotate(90)")
    .style("text-anchor", "start");
	*/
    //svg.append("g").attr("class", "x axis").attr("transform", "translate(10,0)").attr("id", yaxisleft_id).call(yAxis);

	// TYLER - HERE IS WHERE THE PLOT IS CREATED FOR THE FIRST TIME
    if (option != undefined && option.isgroup == true) {        
         rect.transition().delay(function (d, i) { return i * 10; }).attr("y", function (d) { return y(d.y0 + d.y); })
                         .attr("height", function (d) { return y(d.y0) - y(d.y0 + d.y); });
        svg.append("g").attr("class", "x axis").attr("transform", "translate(0,0)").attr("id", yaxisleft_id).call(yAxis);
    }
    else { // THIS IS WHERE THE PLOT IS CREATED THE FIRST TIME - IT IS IN GROUPED FORM
         y.domain([0, yGroupMax]);
         // TYLER - THE -170 SHIFTS THE BARS TO THE LEFT
         // TYLER - THE -22 MAKES THE BARS APPEAR NEXT TO ONE ANOTHER
         // TYLER - THE 50 MAKES THE BARS WIDER
        rect.transition().delay(function (d, i) { return i * 10; }).attr("x", function (d, i, j) { return x(d.x + 1) + x.rangeBand() / (n-22) * j -170; })
                         .attr("width", x.rangeBand() / n+50).attr("y", function (d) { return y(d.y); })
                         .attr("height", function (d) { return height - y(d.y); });
                         console.log("Range " + x.rangeBand() + " and n " + n + " j is " + j)

	    // TYLER - THE 10 SHIFTS THE X-AXIS LINE LEFT AND RIGHT
        svg.append("g").attr("class", "x axis").attr("transform", "translate(10,0)").attr("id", yaxisleft_id).call(yAxisGroup);
    }





/*
    d3.select(document.getElementById(grouped)).on("change", function () {
        y.domain([0, yGroupMax]);

        rect.transition()
            .duration(500)
            .delay(function (d, i) { return i * 10; })
            .attr("x", function (d, i, j) { return x(d.x+1) + x.rangeBand() / n * j; })
            .attr("width", x.rangeBand() / n)
          .transition()
            .attr("y", function (d) { return y(d.y); })
            .attr("height", function (d) { return height - y(d.y); });

        d3.select(document.getElementById(yaxisleft_id)).remove();
        svg.append("g").attr("class", "x axis").attr("transform", "translate(10,0)").attr("id", yaxisleft_id).call(yAxisGroup);
    });

    d3.select(document.getElementById(stacked)).on("change", function () {
        y.domain([0, yStackMax]);
	
        rect.transition()
            .duration(500)
            .delay(function (d, i) { return i * 10; })
            .attr("y", function (d) { return y(d.y0 + d.y); })
            .attr("height", function (d) { return y(d.y0) - y(d.y0 + d.y); })
          .transition()
            .attr("x", function (d) { return x(d.x+1); })
            .attr("width", x.rangeBand());

        d3.select(document.getElementById(yaxisleft_id)).remove();
        svg.append("g").attr("class", "x axis").attr("transform", "translate(0,0)").attr("id", yaxisleft_id).call(yAxis);
        
    });
    */
    
}
