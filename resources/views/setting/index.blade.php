@extends('layouts.app')

@section('content')
    	
	<div class="box">
		<form method="post" action="{{route('application.store')}}" enctype="multipart/form-data">
		{!! csrf_field() !!}
			
				@if(count(config('setting', [])) )
					@foreach(config('setting') as $section => $fields)
						<div class="box-header">
							<div class="col-sm-12 col-sm-12 col-md-12 col-lg-12">
								<i class="{{ Arr::get($fields, 'icon', 'glyphicon glyphicon-flash') }}"></i>
								{{ $fields['title'] }}
							</div>
						</div>
						<div class="box-body">
						<p class="text-muted">{{ $fields['desc'] }}</p>
						</div>
						<div class="box-body">
							<div class="col-sm-12 col-sm-12 col-md-12 col-lg-12">								
								@foreach($fields['elements'] as $field)
									@includeIf('setting.fields.' . $field['type'] )
								@endforeach														
							</div>
						</div>					
					@endforeach
				@endif
			
			<div class="box-footer">
				<button class="btn-primary btn">
					Save Settings
				</button>
			</div>
		</form>
	</div>
	
@endsection