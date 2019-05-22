 d3.chart('BaseChart').extend('GroupedBarChart', {
  initialize : function() {
    var chart = this;

    chart.margin = {top: 40, right: 20, bottom: 40, left: 60};
    chart.xScale = d3.scale.ordinal().rangeRoundBands([0, chart.width()], 0.1);
    chart.x1Scale = d3.scale.ordinal();
    chart.yScale = d3.scale.linear().range([chart.height(), 0]);
    chart.color = d3.scale.category10();
    chart.duration = 500;

    chart.on("change:width", function(newWidth) {
      chart.xScale.rangeRoundBands([0, newWidth], 0.1);
    });
    chart.on("change:height", function(newHeight) {
      chart.yScale.range([newHeight, 0]);
    });

    var barsLayerBase = this.base.append('g')
      .classed('bars', true);

    this.layer('bars', barsLayerBase, {
      dataBind: function(data) {
        var chart = this.chart();

        // Prepare the data
        var _data = data;
        var values = d3.keys(_data[0]).filter(function(key) { return key !== "category"; });
        _data.forEach(function(d) {
          d.values = values.map(function(name) { return {name: name, value: +d[name]}; });
        });

        // Update the scales
        chart.xScale.domain(_data.map(function(d) { return d.category; }));
        chart.x1Scale.domain(values).rangeRoundBands([0, chart.xScale.rangeBand()]);
        chart.yScale.domain([0, d3.max(_data, function(d) { return d3.max(d.values, function(d) { return d.value; }); })]);

        // Bind the data
        return this.selectAll('.category')
          .data(_data);
      },

      insert: function() {
        var chart = this.chart();

        // Append the bars
        return this.append('g')
          .attr('class', 'category');
      },

      events: {

        "enter": function() {
          var chart = this.chart();

          this.attr("transform", function(d, i) {return "translate(" + chart.xScale(d.category) + ",0)"; })
            .selectAll(".bar")
            .data(function(d) {return d.values;})
            .enter()
          .append("rect")
            .attr('class', 'bar')
            .attr("width", chart.x1Scale.rangeBand())
            .style("fill", function(d) { return chart.color(d.name); })
            .attr("x", function(d) { return chart.x1Scale(d.name); })
            .attr("y", chart.height())
            .attr("height", 0)
            ;
        },

        "merge:transition": function() {
          var chart = this.chart();

          this.duration(chart.duration)
            .attr("transform", function(d, i) {return "translate(" + chart.xScale(d.category) + ",0)"; })
            .selectAll(".bar")
            .attr("width", chart.x1Scale.rangeBand())
            .attr("x", function(d) { return chart.x1Scale(d.name); })
            .attr("y", function(d, i) { return chart.yScale(d.value); })
            .attr("height", function(d, i) { return chart.height() - chart.yScale(d.value); });
        }
      }

    });

  }


});