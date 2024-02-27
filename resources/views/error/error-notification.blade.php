@if( Session::has('errors') )
  <div class="alert alert-danger alert-dismissible" role="alert" align="center">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
  {{ Session::get('errors') }}
  </div>
@endif
@if( Session::has('error') )
  <div class="alert alert-danger alert-dismissible" role="alert" align="center">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
  {{ Session::get('error') }}
  </div>
@endif
@if( Session::has('success') )
  <div class="alert alert-success" role="alert" align="center">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
  {{ Session::get('success') }}
  </div>
@endif