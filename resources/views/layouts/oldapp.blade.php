<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="_token" content="{!! csrf_token() !!}" />
    <title>PARODANA-M</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
	<link href="{{ asset('/css/style.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('/css/free.min.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="https://unpkg.com/@coreui/icons/css/all.min.css">
    
    @yield('css')
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <!-- CkEditor -->
    <!--script src="{{ asset ('/backend/js/ckeditor.js') }}" type="text/javascript"></script-->
    
    <!--script src="{{asset('backend/js/jquery.cleditor.min.js')}}"></script-->

</head>
<body class="c-app">

    <!-- Sidebar -->
    @include('layouts.sidebar')
	<div class="c-wrapper c-fixed-components">
		<!-- Header -->
		@include('layouts.header')
		<div class="c-body">
			<main class="c-main">
				@yield('content')
			</main>
			@include('layouts.footer')
		</div>
    </div><!-- /.content-wrapper -->
   
<!-- REQUIRED JS SCRIPTS -->

<script src="{{ asset ('/js/coreui.bundle.min.js') }}" type="text/javascript"></script>
<script src="{{ asset ('/js/svgxuse.min.js') }}" type="text/javascript"></script>
<script src="{{ asset ('/js/coreui-chartjs.bundle.js') }}" type="text/javascript"></script>
<script src="{{ asset ('/js/coreui-utils.js') }}" type="text/javascript"></script>
<script src="{{ asset ('/js/select2.full.min.js') }}" type="text/javascript"></script>
<script src="{{ asset ('/js/main.js') }}" type="text/javascript"></script>
<!--script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()
  })
</script-->

@yield('js')

</body>
</html>
