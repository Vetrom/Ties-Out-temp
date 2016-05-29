 google.charts.load('current', {'packages':['line', 'corechart']});
 google.charts.setOnLoadCallback(drawChart);
 function drawChart() {

	var chartDiv = document.getElementById('chart_div');
	var data = google.visualization.arrayToDataTable([
	  ['Curso', 'Inscritos'],
	  ['Árboles',     11],
	  ['Métodos de ordenamiento',      2],
	  ['Sintaxis básica de PHP',  2],
	  ['Algoritmos de búsqueda', 2],
	  ['Sintaxis básica de Java',    7]
	]);
	
	var options = {
	  title: 'Los cursos más populares'
	};
	
	var chart = new google.visualization.PieChart(document.getElementById('piechart'));
	
	chart.draw(data, options);

	var data2 = new google.visualization.DataTable();
    data2.addColumn('date', 'Por día');
    data2.addColumn('number', "Altas");
    data2.addColumn('number', "Bajas");

    data2.addRows([
        [new Date(2016, 1, 1),  5,  1],
        [new Date(2016, 1, 2),   8,  2],
        [new Date(2016, 1, 3),   15,   0],
        [new Date(2016, 1, 4),  35, 13],
        [new Date(2016, 1, 5),  15, 2],
        [new Date(2016, 1, 6), 11, 1],
        [new Date(2016, 1, 7), 26, 6],
        [new Date(2016, 1, 8),  7, 1],
        [new Date(2016, 1, 9),  4,  9],
        [new Date(2016, 1, 10), 1,  6],
        [new Date(2016, 1, 11), 2,  4],
        [new Date(2016, 1, 12),  5,  1],
        [new Date(2016, 1, 13),   15,   0],
        [new Date(2016, 1, 14),  35, 13],
        [new Date(2016, 1, 15),  15, 2],
        [new Date(2016, 1, 16), 11, 1],
        [new Date(2016, 1, 17), 26, 6],
        [new Date(2016, 1, 18),  7, 1],
        [new Date(2016, 1, 19),  4,  9],
        [new Date(2016, 1, 20), 1,  6],
        [new Date(2016, 1, 21), 2,  4],
		[new Date(2016, 1, 22),   8,  2],
        [new Date(2016, 1, 23),   15,   0],
        [new Date(2016, 1, 24),  35, 13],
        [new Date(2016, 1, 25),  15, 2],
        [new Date(2016, 1, 26), 11, 1],
        [new Date(2016, 1, 27), 26, 6],
        [new Date(2016, 1, 28),  7, 1],
        [new Date(2016, 1, 29),  4,  9],
        [new Date(2016, 1, 30), 1,  6]
    ]);

    var materialOptions = {
        chart: {
          title: 'Usuarios registrados y dados de baja al mes'
    	},
        series: {
          // Gives each series an axis name that matches the Y-axis below.
          0: {axis: 'Usuarios'}
        }
 	};
 	
    function drawMaterialChart() {
        var materialChart = new google.charts.Line(chartDiv);
        materialChart.draw(data2, materialOptions);
    }

    drawMaterialChart();
}	