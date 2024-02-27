<!DOCTYPE html>
<?php use App\Helper\Helpers; ?>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>PARODANA-M | Koperasi Simpan Pinjam </title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="{{ asset('img/logo/favicon.ico') }}" type="image/x-icon">
  <!-- Bootstrap 3.3.2 -->
    <link href="{!! url('css/bootstrap.min.css') !!}" rel="stylesheet" type="text/css" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('css/font-awesome.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{asset('css/ionicons.min.css')}}">	
    <!-- Theme style -->
    <link href="{!! url('css/AdminLTE.min.css') !!}" rel="stylesheet" type="text/css" />
    <!-- iCheck -->
    <link href="{!! url('plugins/iCheck/square/blue.css') !!}" rel="stylesheet" type="text/css" />
	<!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  
<style type="text/css">
.login-page {
background: url("../img/background-login.jpg") no-repeat center center fixed;
background-size: cover;	
}
</style>
</head>
<body class="hold-transition login-page">

<!-- /.login-box -->

<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>PARODANA-M</b></a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Sign in to start your session</p>

    <form method="POST" action="{{ route('login') }}">
	@csrf
  @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
  @endif
      <div class="form-group has-feedback">
                        <label for=""><strong>Email</strong></label>
                        <input type="text" name="email" class="form-control" placeholder="Email">
                    </div>
      <div class="form-group has-feedback">
		<input id="password" type="password" class="form-control" name="password" placeholder="password" required autocomplete="current-password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              <div class="icheckbox_square-blue" aria-checked="false" aria-disabled="false" style="position: relative;"><input type="checkbox" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div> Remember Me
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

    <a href="#">I forgot my password</a>

  </div>
  <!-- /.login-box-body -->
</div>

<!-- jQuery 2.1.3 -->
<script src="{{asset('plugins/jQuery/jQuery-2.1.3.min.js') }}"></script>
<!-- Bootstrap 3.3.2 JS -->
<script src="{{ asset('js/bootstrap.min.js') }}" type="text/javascript"></script>
<!-- iCheck -->
<script src="{{ asset('plugins/iCheck/icheck.min.js') }}" type="text/javascript"></script>
<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });
</script>
</body>
</html>
