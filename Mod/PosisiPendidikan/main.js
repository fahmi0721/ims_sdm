$(document).ready(function(){
	LoadDataPie();
});

function LoadDataPie(){
	$.ajax({
		type : "POST",
		url: "Mod/PosisiPendidikan/proses.php?proses=LoadData",
		data :"rule=LoadData",
		success: function(res){
			var r = JSON.parse(res);
			CahrData(r['Jp'], "pieJp");
			CahrData(r['Jurusan'], "pieJurusan");
			CahrData(r['Sertifikasi'], "pieSertifikasi");
		},
		error : function(er){
			console.log(er);
		}
	})
}

function CahrData(data,Id){
	// Get context with jQuery - using jQuery's .get() method.
	var pieChartCanvas = $('#' + Id).get(0).getContext('2d')
	var pieChart = new Chart(pieChartCanvas)
	var PieData = data;
	
	var pieOptions = {
		//Boolean - Whether we should show a stroke on each segment
		segmentShowStroke: true,
		//String - The colour of each segment stroke
		segmentStrokeColor: '#fff',
		//Number - The width of each segment stroke
		segmentStrokeWidth: 2,
		//Number - The percentage of the chart that we cut out of the middle
		percentageInnerCutout: 50, // This is 0 for Pie charts
		//Number - Amount of animation steps
		animationSteps: 100,
		//String - Animation easing effect
		animationEasing: 'easeOutBounce',
		//Boolean - Whether we animate the rotation of the Doughnut
		animateRotate: true,
		//Boolean - Whether we animate scaling the Doughnut from the centre
		animateScale: false,
		//Boolean - whether to make the chart responsive to window resizing
		responsive: true,
		// Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
		maintainAspectRatio: true,
		//String - A legend template
		legendTemplate: '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<segments.length; i++){%><li><span style="background-color:<%=segments[i].fillColor%>"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>'
	}
	//Create pie or douhnut chart
	// You can switch between pie and douhnut using the method below.
		pieChart.Doughnut(PieData, pieOptions)
}