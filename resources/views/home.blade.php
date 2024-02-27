@extends('layouts.app')

@section('content')	
		<div class="box-wrapper" style="min-height: 399px;">
			<div class="box-header">
			</div>
			<section class="content">
				<div class="row">
					<!-- <div class="form-group">
						<select class="input select2 select2-hidden-accessible" style="width:100%;" aria-hidden="true" name="companies" id="companies">
							@foreach($companies as $company)
								<option value="{{$company->id}}" data-id="{{$company->id}}" data-company="{{$company->company_id}}" selected="true">{{ $company->company_id }} | {{ $company->name }}</option>
							@endforeach
						</select>
					</div> -->
				</div>
				<div class="row">
          <div class="companyID" data-company-id="{{auth()->user()->companies[0]->id}}" hidden></div>
					<div class="col-lg-3 col-xs-6">
					  <!-- small box -->
					  <div class="small-box bg-aqua">
						<div class="inner">
						  <h4 id="totalMember"></h4>
						  <p>Anggota</p>
						</div>
						<div class="icon">
						  <i class="ion ion-bag"></i>
						</div>
						<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
					  </div>
					</div>
					<!-- ./col -->
					<div class="col-lg-3 col-xs-6">
					  <!-- small box -->
					  <div class="small-box bg-green">
						<div class="inner">
						  <h4 class="active" id="totalTabungan"></h4>

						  <p>Total Tabungan</p>
						</div>
						<div class="icon">
						  <i class="ion ion-stats-bars"></i>
						</div>
						<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
					  </div>
					</div>
					<!-- ./col -->
					<div class="col-lg-3 col-xs-6">
					  <!-- small box -->
					  <div class="small-box bg-yellow">
						<div class="inner">
						  <h4 id="totalPinjaman"></h4>

						  <p>Total Pinjaman</p>
						</div>
						<div class="icon">
						  <i class="ion ion-person-add"></i>
						</div>
						<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
					  </div>
					</div>
					<!-- ./col -->
					<div class="col-lg-3 col-xs-6">
					  <!-- small box -->
					  <div class="small-box bg-red">
						<div class="inner">
						  <h4 id="totalLaba"></h4>

						  <p>Laba</p>
						</div>
						<div class="icon">
						  <i class="ion ion-pie-graph"></i>
						</div>
						<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
					  </div>
					</div>
					<!-- ./col -->
				</div>
				<div class="row">
					<div id="interactive" style="height: 300px; padding: 0px; position: relative;">
						<canvas class="flot-base" width="983" height="300" style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 983px; height: 300px;"></canvas>
						<div class="flot-text" style="position: absolute; inset: 0px; font-size: smaller; color: rgb(84, 84, 84);">
							<div class="flot-x-axis flot-x1-axis xAxis x1Axis" style="position: absolute; inset: 0px;">
								<div class="flot-tick-label tickLabel" style="position: absolute; max-width: 89px; top: 283px; left: 21px; text-align: center;">0</div>
								<div class="flot-tick-label tickLabel" style="position: absolute; max-width: 89px; top: 283px; left: 114px; text-align: center;">10</div>
								<div class="flot-tick-label tickLabel" style="position: absolute; max-width: 89px; top: 283px; left: 210px; text-align: center;">20</div>
								<div class="flot-tick-label tickLabel" style="position: absolute; max-width: 89px; top: 283px; left: 306px; text-align: center;">30</div>
								<div class="flot-tick-label tickLabel" style="position: absolute; max-width: 89px; top: 283px; left: 402px; text-align: center;">40</div>
								<div class="flot-tick-label tickLabel" style="position: absolute; max-width: 89px; top: 283px; left: 498px; text-align: center;">50</div>
								<div class="flot-tick-label tickLabel" style="position: absolute; max-width: 89px; top: 283px; left: 594px; text-align: center;">60</div>
								<div class="flot-tick-label tickLabel" style="position: absolute; max-width: 89px; top: 283px; left: 690px; text-align: center;">70</div>
								<div class="flot-tick-label tickLabel" style="position: absolute; max-width: 89px; top: 283px; left: 786px; text-align: center;">80</div>
								<div class="flot-tick-label tickLabel" style="position: absolute; max-width: 89px; top: 283px; left: 882px; text-align: center;">90</div>
							</div>
							<div class="flot-y-axis flot-y1-axis yAxis y1Axis" style="position: absolute; inset: 0px;">
								<div class="flot-tick-label tickLabel" style="position: absolute; top: 270px; left: 13px; text-align: right;">0</div>
								<div class="flot-tick-label tickLabel" style="position: absolute; top: 216px; left: 7px; text-align: right;">20</div>
								<div class="flot-tick-label tickLabel" style="position: absolute; top: 162px; left: 7px; text-align: right;">40</div>
								<div class="flot-tick-label tickLabel" style="position: absolute; top: 108px; left: 7px; text-align: right;">60</div>
								<div class="flot-tick-label tickLabel" style="position: absolute; top: 54px; left: 7px; text-align: right;">80</div>
								<div class="flot-tick-label tickLabel" style="position: absolute; top: 0px; left: 1px; text-align: right;">100</div>
							</div>
						</div>
						<canvas class="flot-overlay" width="983" height="300" style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 983px; height: 300px;"></canvas>
					</div>
				</div>
				{{-- <div class="row">
					<div class="col-lg-12 col-md-12 col-xs-12">
						<div class="card card-success">
							<div class="card-header">
								<h3 class="card-title">Tabungan dan Pinjaman</h3>
							</div>
							<div class="card-body">
								<canvas id="canvas" height="280" width="980"></canvas>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="box box-success">
						<div class="box-header with-border">
							<h3 class="box-title">Bar Chart</h3>

							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
								</button>
								<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
							</div>
						</div>
						<div class="box-body">
							<div class="chart"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
								<canvas id="barChart" style="height: 230px; min-height: 230px; display: block; width: 444px;" width="444" height="230" class="chartjs-render-monitor"></canvas>
							</div>
						</div>
					  <!-- /.card-body -->
					</div> --}}
				</div>
			</section>
		</div>
@endsection

@section('js')
<script>	
function formatCurrency(number) {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(number);
  }
	//window.onload = function(){
		$(document).ready(function() {
			// ambil id company dari auth user yang login
      // Mengambil elemen dengan class "companyID"
      var companyIDElement = document.querySelector('.companyID');

      // Mendapatkan nilai data-company-id dari elemen
      var id = companyIDElement.getAttribute('data-company-id');
			var x = document.getElementById('totalMember');
			//x.style.display = 'block';
			$.ajax({
				type: "GET",
				url: "{!! url('/totalbox/" + id + "/') !!}",
				success: function (response) {
          console.log(response);
					if (response['result'] == 'success') {												
						var member = (response['totalMember']);
						var tabungan = (formatCurrency(response['totalTabungan']));
						var pinjaman = (formatCurrency(response['totalPinjaman']));
						var laba = (formatCurrency(response['totalLaba']));
						document.getElementById("totalMember").innerHTML = member;
						document.getElementById("totalTabungan").innerHTML = tabungan;
						document.getElementById("totalPinjaman").innerHTML = pinjaman;
						document.getElementById("totalLaba").innerHTML = laba;
					}
				},
				error: function () {
					alert("error");
				}
			});
		});
	//}
</script>
<script language="javascript" type="text/javascript">
$(function () {
    var data = [ [[2003, 10882],
        [2002, 10383],
        [2001, 10020],
        [2000, 9762],
        [1999, 9213],
        [1998, 8720]] ];
     
    var plotarea = $("#plotarea");
    plotarea.css("height", "250px");
    plotarea.css("width", "500px");
    $.plot( plotarea , data );
});
</script>
<script>
  $(function () {
    /*
     * Flot Interactive Chart
     * -----------------------
     */
    // We use an inline data source in the example, usually data would
    // be fetched from a server
    var data = [], totalPoints = 100

    function getRandomData() {

      if (data.length > 0)
        data = data.slice(1)

      // Do a random walk
      while (data.length < totalPoints) {

        var prev = data.length > 0 ? data[data.length - 1] : 50,
            y    = prev + Math.random() * 10 - 5

        if (y < 0) {
          y = 0
        } else if (y > 100) {
          y = 100
        }

        data.push(y)
      }

      // Zip the generated y values with the x values
      var res = []
      for (var i = 0; i < data.length; ++i) {
        res.push([i, data[i]])
      }

      return res
    }

    var interactive_plot = $.plot('#interactive', [getRandomData()], {
      grid  : {
        borderColor: '#f3f3f3',
        borderWidth: 1,
        tickColor  : '#f3f3f3'
      },
      series: {
        shadowSize: 0, // Drawing is faster without shadows
        color     : '#3c8dbc'
      },
      lines : {
        fill : true, //Converts the line chart to area chart
        color: '#3c8dbc'
      },
      yaxis : {
        min : 0,
        max : 100,
        show: true
      },
      xaxis : {
        show: true
      }
    })

    var updateInterval = 500 //Fetch data ever x milliseconds
    var realtime       = 'on' //If == to on then fetch data every x seconds. else stop fetching
    function update() {

      interactive_plot.setData([getRandomData()])

      // Since the axes don't change, we don't need to call plot.setupGrid()
      interactive_plot.draw()
      if (realtime === 'on')
        setTimeout(update, updateInterval)
    }

    //INITIALIZE REALTIME DATA FETCHING
    if (realtime === 'on') {
      update()
    }
    //REALTIME TOGGLE
    $('#realtime .btn').click(function () {
      if ($(this).data('toggle') === 'on') {
        realtime = 'on'
      }
      else {
        realtime = 'off'
      }
      update()
    })
    /*
     * END INTERACTIVE CHART
     */

    /*
     * LINE CHART
     * ----------
     */
    //LINE randomly generated data

    var sin = [], cos = []
    for (var i = 0; i < 14; i += 0.5) {
      sin.push([i, Math.sin(i)])
      cos.push([i, Math.cos(i)])
    }
    var line_data1 = {
      data : sin,
      color: '#3c8dbc'
    }
    var line_data2 = {
      data : cos,
      color: '#00c0ef'
    }
    $.plot('#line-chart', [line_data1, line_data2], {
      grid  : {
        hoverable  : true,
        borderColor: '#f3f3f3',
        borderWidth: 1,
        tickColor  : '#f3f3f3'
      },
      series: {
        shadowSize: 0,
        lines     : {
          show: true
        },
        points    : {
          show: true
        }
      },
      lines : {
        fill : false,
        color: ['#3c8dbc', '#f56954']
      },
      yaxis : {
        show: true
      },
      xaxis : {
        show: true
      }
    })
    //Initialize tooltip on hover
    $('<div class="tooltip-inner" id="line-chart-tooltip"></div>').css({
      position: 'absolute',
      display : 'none',
      opacity : 0.8
    }).appendTo('body')
    $('#line-chart').bind('plothover', function (event, pos, item) {

      if (item) {
        var x = item.datapoint[0].toFixed(2),
            y = item.datapoint[1].toFixed(2)

        $('#line-chart-tooltip').html(item.series.label + ' of ' + x + ' = ' + y)
          .css({ top: item.pageY + 5, left: item.pageX + 5 })
          .fadeIn(200)
      } else {
        $('#line-chart-tooltip').hide()
      }

    })
    /* END LINE CHART */

    /*
     * FULL WIDTH STATIC AREA CHART
     * -----------------
     */
    var areaData = [[2, 88.0], [3, 93.3], [4, 102.0], [5, 108.5], [6, 115.7], [7, 115.6],
      [8, 124.6], [9, 130.3], [10, 134.3], [11, 141.4], [12, 146.5], [13, 151.7], [14, 159.9],
      [15, 165.4], [16, 167.8], [17, 168.7], [18, 169.5], [19, 168.0]]
    $.plot('#area-chart', [areaData], {
      grid  : {
        borderWidth: 0
      },
      series: {
        shadowSize: 0, // Drawing is faster without shadows
        color     : '#00c0ef'
      },
      lines : {
        fill: true //Converts the line chart to area chart
      },
      yaxis : {
        show: false
      },
      xaxis : {
        show: false
      }
    })

    /* END AREA CHART */

    /*
     * BAR CHART
     * ---------
     */

    var bar_data = {
      data : [['January', 10], ['February', 8], ['March', 4], ['April', 13], ['May', 17], ['June', 9]],
      color: '#3c8dbc'
    }
    $.plot('#bar-chart', [bar_data], {
      grid  : {
        borderWidth: 1,
        borderColor: '#f3f3f3',
        tickColor  : '#f3f3f3'
      },
      series: {
        bars: {
          show    : true,
          barWidth: 0.5,
          align   : 'center'
        }
      },
      xaxis : {
        mode      : 'categories',
        tickLength: 0
      }
    })
    /* END BAR CHART */

    /*
     * DONUT CHART
     * -----------
     */

    var donutData = [
      { label: 'Series2', data: 30, color: '#3c8dbc' },
      { label: 'Series3', data: 20, color: '#0073b7' },
      { label: 'Series4', data: 50, color: '#00c0ef' }
    ]
    $.plot('#donut-chart', donutData, {
      series: {
        pie: {
          show       : true,
          radius     : 1,
          innerRadius: 0.5,
          label      : {
            show     : true,
            radius   : 2 / 3,
            formatter: labelFormatter,
            threshold: 0.1
          }

        }
      },
      legend: {
        show: false
      }
    })
    /*
     * END DONUT CHART
     */

  })

  /*
   * Custom Label formatter
   * ----------------------
   */
  function labelFormatter(label, series) {
    return '<div style="font-size:13px; text-align:center; padding:2px; color: #fff; font-weight: 600;">'
      + label
      + '<br>'
      + Math.round(series.percent) + '%</div>'
  }
</script>

<script>
    var year = ['2021','2020', '2019','2018','2017','2016'];
	//var year = <?php echo $tahun; ?>;
    var data_tabungan = <?php echo $chart_tabungan; ?>;
    var data_pinjaman = <?php echo $chart_pinjaman; ?>;


    var barChartData = {
        labels: year,
        datasets: [{
            label: 'Tabungan',
            backgroundColor: "rgba(220,220,220,0.5)",
            data: data_tabungan
        }, {
            label: 'Pinjaman',
            backgroundColor: "rgba(151,187,205,0.5)",
            data: data_pinjaman
        }]
    };


    window.onload = function() {
        var ctx = document.getElementById("canvas").getContext("2d");
        window.myBar = new Chart(ctx, {
            type: 'bar',
            data: barChartData,
            options: {
                elements: {
                    rectangle: {
                        borderWidth: 2,
                        borderColor: 'rgb(0, 255, 0)',
                        borderSkipped: 'bottom'
                    }
                },
                responsive: true,
                title: {
                    display: true,
                    text: 'Tabungan dan Pinjaman'
                }
            }
        });


    };
	
	//-------------
    //- BAR CHART -
    //-------------
	
	var areaChartData = {
        labels: year,
        datasets: [{
            label: 'Tabungan',
            backgroundColor: "rgba(220,220,220,0.5)",
            data: data_tabungan
        }, {
            label: 'Pinjaman',
            backgroundColor: "rgba(151,187,205,0.5)",
            data: data_pinjaman
        }]
    };
	
    var barChartCanvas = $('#barChart').get(0).getContext('2d')
    var barChartData = jQuery.extend(true, {}, areaChartData)
    var temp0 = areaChartData.datasets[0]
    var temp1 = areaChartData.datasets[1]
    barChartData.datasets[0] = temp1
    barChartData.datasets[1] = temp0

    var barChartOptions = {
      responsive              : true,
      maintainAspectRatio     : false,
      datasetFill             : false
    }

    var barChart = new Chart(barChartCanvas, {
      type: 'bar', 
      data: barChartData,
      options: barChartOptions
    })

    //---------------------
    //- STACKED BAR CHART -
    //---------------------
    var stackedBarChartCanvas = $('#stackedBarChart').get(0).getContext('2d')
    var stackedBarChartData = jQuery.extend(true, {}, barChartData)

    var stackedBarChartOptions = {
      responsive              : true,
      maintainAspectRatio     : false,
      scales: {
        xAxes: [{
          stacked: true,
        }],
        yAxes: [{
          stacked: true
        }]
      }
    }

    var stackedBarChart = new Chart(stackedBarChartCanvas, {
      type: 'bar', 
      data: stackedBarChartData,
      options: stackedBarChartOptions
    })
</script>

@endsection