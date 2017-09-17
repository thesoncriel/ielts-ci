requirejs.config({
	baseUrl: "/js/lib",
	paths: {
		jquery: "jquery-1.11.3.min",
		angular: "angular.min",
		bootstrap: "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min",
		
		mediaelement: "mediaelement-and-player.min",
		
		util:	"/js/common/util",
		timer:	"/js/common/timer",
		
		'amcharts': '//cdn.amcharts.com/lib/3/amcharts',
		'amcharts.funnel': '//cdn.amcharts.com/lib/3/funnel',
		'amcharts.gauge': '//cdn.amcharts.com/lib/3/gauge',
		'amcharts.pie': '//cdn.amcharts.com/lib/3/pie',
		'amcharts.radar': '//cdn.amcharts.com/lib/3/radar',
		'amcharts.serial': '//cdn.amcharts.com/lib/3/serial',
		'amcharts.xy': '//cdn.amcharts.com/lib/3/xy',
		'amcharts.gantt': '//cdn.amcharts.com/lib/3/gantt'
	},
	shim: {
		bootstrap:	{deps: ["jquery"]},
		mediaelement:	{
			deps: ["jquery"]
		},
		
		'amcharts.funnel': {
			deps: [ 'amcharts' ],
			exports: 'AmCharts',
			init: function() {
				AmCharts.isReady = true;
			}
		},
		'amcharts.gauge': {
			deps: [ 'amcharts' ],
			exports: 'AmCharts',
			init: function() {
				AmCharts.isReady = true;
			}
		},
		'amcharts.pie': {
			deps: [ 'amcharts' ],
			exports: 'AmCharts',
			init: function() {
				AmCharts.isReady = true;
			}
		},
		'amcharts.radar': {
			deps: [ 'amcharts' ],
			exports: 'AmCharts',
			init: function() {
				AmCharts.isReady = true;
			}
		},
		'amcharts.serial': {
			deps: [ 'amcharts' ],
			exports: 'AmCharts',
			init: function() {
				AmCharts.isReady = true;
			}
		},
		'amcharts.xy': {
			deps: [ 'amcharts' ],
			exports: 'AmCharts',
			init: function() {
				AmCharts.isReady = true;
			}
		},
		'amcharts.gantt': {
			deps: [ 'amcharts', 'amcharts.serial' ],
			exports: 'AmCharts',
			init: function() {
				AmCharts.isReady = true;
			}
		}
	}
});

requirejs.onError = function(err){
	console.log(err);
};

requirejs([
	"jquery",
	"bootstrap",
	"/js/ielts/report.js",
	"/js/ielts/test.js",
	//"js/main.js"
]);