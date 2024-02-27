@extends('layouts.app')
@section('content')
	
	<div class="box">		
		<div class="box-header">
			<h3>Neraca Sistem</h3>
		</div>
		<div class="box-body">			
		<div class="row">
									<form action="{{route('neracasaldo.detail')}}" method="get" enctype="multipart/form-data">
										@csrf 
										<div class="col-md-5">
											<div class="form-group">
												<label for="start_date">Start Date</label>
												<input type="date" class="form-control" name="start_date" id="start_date" value="{{ old('start_date') }}">
											</div>
										</div>
										<div class="col-md-5">
										<div class="form-group">
											<label for="end_date">Start Date</label>
											<input type="date" class="form-control" name="end_date" id="end_date" value="{{ old('end_date') }}">
										</div>
										</div>
										<div class="col-md-2">
											<div class="form-group">
												<label for="">&nbsp;</label>
												<button type="submit" class="btn btn-primary btn-block">Submit</button>
											</div>
										</div>
									</form>
								</div>
		</div>
	</div>
	
@endsection

@section('js')
<script type="text/javascript">
</script>
@endsection