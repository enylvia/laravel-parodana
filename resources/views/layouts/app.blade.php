<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="{{ \setting('locale', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="_token" content="{!! csrf_token() !!}" />
    <title>PARODANA-M</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="icon" href="{{ asset('img/logo/favicon.ico') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Bootstrap 3.3.2 -->
    <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('/css/select2.min.css') }}">
    <!-- Nestable -->
    <link rel="stylesheet" href="{{ asset('/css/nestable.css') }}">
    <!-- Font Awesome Icons -->
    <link href="{{ asset('/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Ionicons -->
    <link href="{{ asset('/css/ionicons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="{{ asset('/css/AdminLTE.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
          page. However, you can choose any other skin. Make sure you
          apply the skin class to the body tag so the changes take effect.
    -->
    <link href="{{ asset('/css/menu-management.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/css/skin-blue.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('/css/skin-green-light.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/bootstrap-fancyfile.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/daterangepicker.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('/css/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/bootstrap-datepicker3.css') }}">
    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="{{ asset('/css/bootstrap-colorpicker.min.css') }}">
    <!-- Bootstrap time Picker -->
    <link rel="stylesheet" href="{{ asset('/css/bootstrap-timepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/bootstrap-switch.css') }}">

    <link rel="stylesheet" href="{{ asset('/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/plugins/iCheck/all.css') }}">
    <link rel="stylesheet" href="{{ asset('/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}">
    <link href="https://datatables.yajrabox.com/css/datatables.bootstrap.css" rel="stylesheet">
    @yield('css')
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <!-- CkEditor -->
    <!--script src="{{ asset('/backend/js/ckeditor.js') }}" type="text/javascript"></script-->

    <!--script src="{{ asset('backend/js/jquery.cleditor.min.js') }}"></script-->

</head>

<body class="skin-green-light">
    <div class="wrapper">

        <!-- Header -->
        @include('layouts.header')

        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header content-center">

                <!-- You can dynamically generate breadcrumbs here -->
                <!--ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
                <li class="active">Here</li>
            </ol-->
                <h1>
                    @yield('title')
                    @yield('new_button')
                </h1>
            </section>

            <!-- Main content -->
            <section class="content content-center">
                <!-- Your Page Content Here -->
                @yield('content')
            </section><!-- /.content -->
        </div><!-- /.content-wrapper -->

        <!-- Footer -->
        @include('layouts.footer')

    </div><!-- ./wrapper -->

    <!-- REQUIRED JS SCRIPTS -->

    <!--script src="{{ asset('/js/adminlte.min.js') }}"></script-->
    <!-- jQuery 2.1.3 -->
    <!--script src="{{ asset('/js/jQuery.2.0.3.js') }}"></script-->
    <script src="{{ asset('/js/jquery.min.js') }}"></script>
    <script src="{{ asset('/js/jquery-ui.min.js') }}"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="{{ asset('/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <!-- date-range-picker -->
    <script src="{{ asset('/js/daterangepicker.js') }}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('/js/bootstrap-datepicker.min.js') }}"></script>
    <!-- bootstrap color picker -->
    <script src="{{ asset('/js/bootstrap-colorpicker.min.js') }}"></script>
    <!-- bootstrap time picker -->
    <script src="{{ asset('/js/bootstrap-timepicker.min.js') }}"></script>
    <!-- SlimScroll -->
    <script src="{{ asset('/js/jquery.slimscroll.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('/js/select2.full.min.js') }}" type="text/javascript"></script>
    <!-- FastClick -->
    <script src="{{ asset('/js/fastclick.js') }}" type="text/javascript"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('/js/app.min.js') }}" type="text/javascript"></script>
    <!-- Nestable -->
    <script src="{{ asset('/js/jquery.nestable.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/js/adminlte.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/js/numerik.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/plugins/ckeditor/ckeditor.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/js/bootstrap-switch.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/js/jquery.flot.js') }}"></script>
    <script src="{{ asset('/js/chart.min.js') }}" type="text/javascript"></script>
    <!-- FLOT RESIZE PLUGIN - allows the chart to redraw when the window is resized -->
    <script src="{{ asset('/js/jquery.flot.resize.js') }}"></script>
    <!-- FLOT PIE PLUGIN - also used to draw donut charts -->
    <script src="{{ asset('/js/jquery.flot.pie.js') }}"></script>
    <!-- FLOT CATEGORIES PLUGIN - Used to draw bar charts -->
    <script src="{{ asset('/js/jquery.flot.categories.js') }}"></script>
    <script src="{{ asset('/plugins/iCheck/icheck.min.js') }}"></script>
    <script src="{{ asset('/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>
    <!-- Optionally, you can add Slimscroll and FastClick plugins.
      Both of these plugins are recommended to enhance the
      user experience -->
    <script src="https://datatables.yajrabox.com/js/handlebars.js"></script>
    <script src="{{ asset('/js/moment.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2()

            //Datemask dd/mm/yyyy
            //$('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
            //Datemask2 mm/dd/yyyy
            //$('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
            //Money Euro
            //$('[data-mask]').inputmask()

            //Date range picker
            $('#reservation').daterangepicker()
            //Date range picker with time picker
            $('#reservationtime').daterangepicker({
                timePicker: true,
                timePickerIncrement: 30,
                format: 'MM/DD/YYYY h:mm A'
            })
            //Date range as a button
            $('#daterange-btn').daterangepicker({
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                            'month').endOf('month')]
                    },
                    startDate: moment().subtract(29, 'days'),
                    endDate: moment()
                },
                function(start, end) {
                    $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format(
                        'MMMM D, YYYY'))
                }
            )

            //Date picker
            //$('#datepicker').datepicker({
            //  autoclose: true
            //})

            //iCheck for checkbox and radio inputs
            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass: 'iradio_minimal-blue'
            })
            //Red color scheme for iCheck
            $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
                checkboxClass: 'icheckbox_minimal-red',
                radioClass: 'iradio_minimal-red'
            })
            //Flat red color scheme for iCheck
            $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green'
            })

            //Colorpicker
            $('.my-colorpicker1').colorpicker()
            //color picker with addon
            $('.my-colorpicker2').colorpicker()

            //Timepicker
            $('.timepicker').timepicker({
                showInputs: false
            })
        })
    </script>

    <script>
        $(document).ready(function() {
            var date_input = $('input[name="date"]');
            var container = $('.bootstrap-iso form').length > 0 ? $('.bootstrap-iso form').parent() : "body";
            var options = {
                format: 'mm/dd/yyyy',
                container: container,
                todayHighlight: true,
                autoclose: true,
            };
            date_input.datepicker(options);
        })
    </script>

    @yield('js')

</body>

</html>
