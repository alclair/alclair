function d3DarwStackGroup(layers, divid, grouped, stacked, labels, option) {
    var n = layers.length;
    var m = layers[0].length;
    var stack = d3.layout.stack();
    var yaxisleft_id = divid + "_yaxisleft";

    var x_label = [];
    for (var label = 1; label <= m; label++) {
        x_label.push(label);
    }

  	
    for (var i = 0; i < layers.length; i++) {
        for (var j = 0; j < layers[i].length; j++) {
            layers[i][j].x = j;
            if (i == 0)
                layers[i][j].y0 = 0;
            else
                layers[i][j].y0 = layers[i - 1][j].y + layers[i - 1][j].y0;
            layers[i][j].label = labels[i];
        }
    }

    var yGroupMax = d3.max(layers, function (layer) { return d3.max(layer, function (d) { return d.y; }); });
    var yStackMax = d3.max(layers, function (layer) { return d3.max(layer, function (d) { return d.y0 + d.y; }); });

    var margin = { top: 40, right: 10, bottom: 20, left: 60 };
    var width = document.getElementById(divid).offsetWidth - margin.left - margin.right;
    var height = 500 - margin.top - margin.bottom;

    var x = d3.scale.ordinal().domain(x_label).rangeRoundBands([0, width], .08);
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
	
// TYLER
var dataset2 = [];
//console.log("Layers is " + JSON.stringify(layers))
//for (var i = 0; i < layers.length; i++) {

   /* for (var j = 0; j < layers[0].length; j++) {
	    if(layers[1][j].y == 0 ) 
			dataset2.push("");
		else {
			console.log("L is " + layers.length)
		 	if (layers.length == 2) // JUST PASS OR FAIL 
				dataset2.push( Math.round( (layers[1][j].y / (layers[0][j].y + layers[1][j].y)) * 100)  );
			else // FAIL - READY TO SHIP - WAITING FOR ARTWORK
				dataset2.push( Math.round( ( (layers[1][j].y + layers[2][j].y) / (layers[0][j].y + layers[1][j].y + layers[2][j].y)) * 100)  );
		}
    }		*/
    
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
   .attr("font-size", "14px")
   .attr("fill", "black");
   //console.log("Width is " + width + margin.left + margin.right)

    //rect.transition().delay(function (d, i) { return i * 10; }).attr("y", function (d) { return y(d.y0 + d.y); })
    //                 .attr("height", function (d) { return y(d.y0) - y(d.y0 + d.y); });

    svg.append("g").attr("class", "x axis").attr("transform", "translate(0," + height + ")").call(xAxis);
    //svg.append("g").attr("class", "x axis").attr("transform", "translate(10,0)").attr("id", yaxisleft_id).call(yAxis);

    if (option != undefined && option.isgroup == true) {
        y.domain([0, yGroupMax]);
        rect.transition().delay(function (d, i) { return i * 10; }).attr("x", function (d, i, j) { return x(d.x + 1) + x.rangeBand() / n * j; })
                         .attr("width", x.rangeBand() / n).attr("y", function (d) { return y(d.y); })
                         .attr("height", function (d) { return height - y(d.y); });

        svg.append("g").attr("class", "x axis").attr("transform", "translate(10,0)").attr("id", yaxisleft_id).call(yAxisGroup);
    }
    else {
        rect.transition().delay(function (d, i) { return i * 10; }).attr("y", function (d) { return y(d.y0 + d.y); })
                         .attr("height", function (d) { return y(d.y0) - y(d.y0 + d.y); });
        svg.append("g").attr("class", "x axis").attr("transform", "translate(10,0)").attr("id", yaxisleft_id).call(yAxis);
    }

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
        svg.append("g").attr("class", "x axis").attr("transform", "translate(10,0)").attr("id", yaxisleft_id).call(yAxis);
    });
    
    
}


function d3DrawPieChart(data, divid, colorrange) {
    var width = document.getElementById(divid).offsetWidth;
    var height = 500;
    var radius = Math.min(width, height) / 2;

    var color = d3.scale.ordinal().range(colorrange);
    var arc = d3.svg.arc().outerRadius(radius - 10).innerRadius(0);
    var labelArc = d3.svg.arc().outerRadius(radius - 40).innerRadius(radius - 40);
    var pie = d3.layout.pie().sort(null).value(function (d) { return d.population; });

    var tip = d3.tip().attr('class', 'd3-tip').html(function (d) {
        console.log(d);
        return "<strong>" + d.data.label + ": " + d.data.population + "</strong>";
    });

    var svg = d3.select(document.getElementById(divid)).append("svg")
                .attr("width", width).attr("height", height)
                .append("g").attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

    svg.call(tip);

    var g = svg.selectAll(".arc").data(pie(data)).enter().append("g").attr("class", "arc")
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

    g.append("path").attr("d", arc).style("fill", function (d, i) { return color(i); });

    g.append("text")
        .attr("transform", function (d) { return "translate(" + labelArc.centroid(d) + ")"; })
        .attr("dy", ".35em")
        .text(function (d) { return d.data.age; });
}

function d3DrawMultiLineChart(data, divid, colors, labels) {
    var n = data[0].length; //31 days, n = 31
    var linecount = data.length; // 7 lines
    var yMax = d3.max(data, function (dat) { return d3.max(dat, function (d) { return d }); });

    var margin = { top: 20, right: 80, bottom: 30, left: 50 };
    var width = document.getElementById(divid).offsetWidth - margin.left - margin.right;
    var height = 500 - margin.top - margin.bottom;

    var x = d3.scale.ordinal().domain(d3.range(1, n + 1)).rangeRoundBands([0, width], .08);
    var y = d3.scale.linear().domain([0, yMax]).range([height, 0]);
    var color = d3.scale.ordinal().range(colors);

    var tip = d3.tip().attr('class', 'd3-tip').offset([-10, 0]).html(function (d) {
        console.log(d);
        return "<strong>" + d.label + ": " + d.value + "</strong>";
    });

    var xAxis = d3.svg.axis().scale(x).orient("bottom");
    var yAxis = d3.svg.axis().scale(y).orient("left");

    var line = d3.svg.line().interpolate("monotone").x(function (d) {
        return x(d.date) + x(1) + x.rangeBand() / 2;
    }).y(function (d) { return y(d.value); });

    var svg = d3.select(document.getElementById(divid)).append("svg")
        .attr("width", width + margin.left + margin.right).attr("height", height + margin.top + margin.bottom)
        .append("g").attr("transform", "translate(" + margin.left + "," + margin.top + ")");

    svg.call(tip);

    svg.append("g").attr("class", "x axis").attr("transform", "translate(10," + height + ")").call(xAxis);
    svg.append("g").attr("class", "y axis").attr("transform", "translate(10,0)").call(yAxis);

    var total_data = [];
    for (var i = 0; i < data.length; i++) {
        var line_data = [];
        for (var j = 1; j <= data[i].length; j++) {
            line_data.push({ date: j, value: data[i][j - 1], label: labels[i] });

            total_data.push({ date: j, value: data[i][j - 1], label: labels[i] });
        }
        svg.append("path").attr("class", "line").attr("d", line(line_data)).style("stroke", color(i));
    }

    svg.selectAll('circle').data(total_data).enter().append('circle').attr('class', 'linecircle')
                  .attr('cx', line.x()).attr('cy', line.y()).attr('r', 2)
                  .on('mouseover', function () {
                      d3.select(this).transition().duration(500).attr('r', 4);
                  })
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
                    d3.select(this).transition().duration(500).attr('r', 2);
                });
}

function d3DrawMultiLineChartExtended(data, divid, colors, labels) {
 	var n = data[0].length; //31 days, n = 31
    var linecount = data.length; // 7 lines
    var yMax = d3.max(data, function (dat) { return d3.max(dat, function (d) { return d }); });

    var margin = { top: 20, right: 80, bottom: 30, left: 50 };
    var width = document.getElementById(divid).offsetWidth - margin.left - margin.right;
    var height = 500 - margin.top - margin.bottom;

    var x = d3.scale.ordinal().domain(d3.range(1, n + 1)).rangeRoundBands([0, width], .08);
    var y = d3.scale.linear().domain([0, yMax]).range([height, 0]);
    var color = d3.scale.ordinal().range(colors);

    var tip = d3.tip().attr('class', 'd3-tip').offset([-10, 0]).html(function (d) {
        console.log(d);
        return "<strong>" + d.label + ": " + d.value + "</strong>";
    });

    var xAxis = d3.svg.axis().scale(x).orient("bottom");
    var yAxis = d3.svg.axis().scale(y).orient("left");

    var line = d3.svg.line().interpolate("monotone").x(function (d) {
        return x(d.date) + x(1) + x.rangeBand() / 2;
    }).y(function (d) { return y(d.value); });

	var line = d3.svg.line()
    	//.curve(d3.curveBasis)
		.x(function(d) { return x(d.date); })
		.y(function(d) { return y(d.value); });
    
    var svg = d3.select(document.getElementById(divid)).append("svg")
        .attr("width", width + margin.left + margin.right).attr("height", height + margin.top + margin.bottom)
        .append("g").attr("transform", "translate(" + margin.left + "," + margin.top + ")");

    svg.call(tip);

    svg.append("g").attr("class", "x axis").attr("transform", "translate(10," + height + ")").call(xAxis);
    svg.append("g").attr("class", "y axis").attr("transform", "translate(10,0)").call(yAxis);

    var total_data = [];
    for (var i = 0; i < data.length; i++) {
        var line_data = [];
        for (var j = 1; j <= data[i].length; j++) {
            line_data.push({ date: j, value: data[i][j - 1], label: labels[i] });

            total_data.push({ date: j, value: data[i][j - 1], label: labels[i] });
            
        }
        svg.append("path").attr("class", "line").attr("d", line(line_data)).style("stroke", color(i));
    }

    svg.selectAll('circle').data(total_data).enter().append('circle').attr('class', 'linecircle')
                  .attr('cx', line.x()).attr('cy', line.y()).attr('r', 2)
                  .on('mouseover', function () {
                      d3.select(this).transition().duration(500).attr('r', 4);
                  })
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
                    d3.select(this).transition().duration(500).attr('r', 2);
                });
}


