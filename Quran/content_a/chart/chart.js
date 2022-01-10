

function chartDrawer()
{
  if($("#chartdiv").length == 1)
  {
    amChartF();
  }
}


function amChartF()
{

/**
 * ---------------------------------------
 * This demo was created using amCharts 4.
 *
 * For more information visit:
 * https://www.amcharts.com/
 *
 * Documentation is available at:
 * https://www.amcharts.com/docs/v4/
 * ---------------------------------------
 */

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

var chart = am4core.create("chartdiv", am4charts.XYChart);
chart.maskBullets = false;

var xAxis = chart.xAxes.push(new am4charts.CategoryAxis());
var yAxis = chart.yAxes.push(new am4charts.CategoryAxis());

yAxis.dataFields.category = "weekday";
xAxis.renderer.minGridDistance = 40;
xAxis.dataFields.category = "hour";

xAxis.renderer.grid.template.disabled = true;
yAxis.renderer.grid.template.disabled = true;
xAxis.renderer.axisFills.template.disabled = true;
yAxis.renderer.axisFills.template.disabled = true;
yAxis.renderer.ticks.template.disabled = true;
xAxis.renderer.ticks.template.disabled = true;

yAxis.renderer.inversed = true;

var series = chart.series.push(new am4charts.ColumnSeries());
series.dataFields.categoryY = "weekday";
series.dataFields.categoryX = "hour";
series.dataFields.value = "value";
series.columns.template.disabled = true;
series.sequencedInterpolation = true;
//series.defaultState.transitionDuration = 3000;

var bullet = series.bullets.push(new am4core.Circle());
bullet.tooltipText = "{weekday}, {hour}: {value.workingValue.formatNumber('#.')}";
bullet.strokeWidth = 3;
bullet.stroke = am4core.color("#ffffff");
bullet.strokeOpacity = 0;

bullet.adapter.add("tooltipY", function(tooltipY, target) {
  return -target.radius + 1;
})

series.heatRules.push({property:"radius", target:bullet, min:2, max:40});

bullet.hiddenState.properties.scale = 0.01;
bullet.hiddenState.properties.opacity = 1;

var hoverState = bullet.states.create("hover");
hoverState.properties.strokeOpacity = 1;

chart.data = {{chartData| raw}};



}


