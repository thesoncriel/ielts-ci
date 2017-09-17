define(["jquery"], function($){
	if ($(".test-report").length === 0) return;
	
	$(function(){
		var jqClose = $("[data-rule='windowclose'], [data-rule='close']");
		var jqPrint = $("[data-rule='print']");
		
		jqClose.click(function(){
			self.close();
			
			return false;
		});
		
		jqPrint.click(function(){
			window.print();
			
			return false;
		});
	});
	
	require(["jquery"], function($){
		
		/*
		//
		, "amcharts.serial"
		, amCharts
		var chart = amCharts.makeChart("chart_report1", {
			"type": "serial",
			"categoryField": "category",
			"columnSpacing3D": 3,
			"angle": 30,
			"depth3D": 30,
			"colors": [
				"#a0cc60",
				"#6db8da"
			],
			"startDuration": 0,
			"categoryAxis": {
				"gridPosition": "start"
			},
			"trendLines": [],
			"graphs": [
				{
					"balloonText": "[[title]] of [[category]]:[[value]]",
					"columnWidth": 0.35,
					"fillAlphas": 1,
					"id": "AmGraph-1",
					"title": "graph 1",
					"type": "column",
					"valueField": "column-1"
				}
			],
			"guides": [],
			"valueAxes": [
				{
					"id": "ValueAxis-1",
					"title": "Axis title"
				}
			],
			"allLabels": [],
			"balloon": {},
			"titles": [
				{
					"id": "Title-1",
					"size": 15,
					"text": "Chart Title"
				}
			],
			"dataProvider": [
				{
					"category": "category 1",
					"column-1": 8
				},
				{
					"category": "category 2",
					"column-1": 6
				},
				{
					"category": "category 3",
					"column-1": 2
				},
				{
					"category": "category4",
					"column-1": "4"
				}
			]
		});// AmCharts.makeChart[end]
		*/
	});// require [end]
});// define [end]
