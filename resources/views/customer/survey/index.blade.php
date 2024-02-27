@extends('layouts.app')
@section('content')

@include('error.error-notification')	
	
	<div class="box">
		<div class="box-body">
			<table class="table table-responsive-sm table-striped">
				<thead>
					<tr>
						<th>{{trans('general.name')}}</th>
						<th>{{trans('survey.loan_to')}}</th>
						<th>{{trans('survey.loan_amount')}}</th>
						<th>{{trans('loan.marketing_name')}}</th>
						<th class="text-center" colspan="2">{{trans('general.actions')}}</th>
					</tr>
				</thead>
				<tbody>
					@forelse($surveys as $survey)
					<!--?php
					  $appoves = App\Models\Customer::where('customer_id',1)->get();
					?-->
					<tr>
						<td>{{$survey->name}}</td>
						<td>{{$survey->loan_to}}</td>
						<td>Rp. {{ number_format($survey->loan_amount, 0, ',' , '.') }}</td>
						<td>{{$survey->created_by}}</td>												
						<td><a href="{{URL::to('customer/survey/create/'.$survey->id)}}">Survey</a></td>
					</tr>
					@empty
					<tr>
						<td colspan="4">Data Not Found</td>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>

@endsection
@section('js')

    <script type="text/javascript"
        src="https://maps.google.com/maps/api/js?key=AIzaSyCK1iN3Pv2ZNnVJHIlgj-vLDHH-ub1lUHw=places&callback=initAutocomplete"></script>
    <script>
        $(document).ready(function () {
            $("#latitudeArea").addClass("d-none");
            $("#longtitudeArea").addClass("d-none");
        });
    </script>
    <script>
        google.maps.event.addDomListener(window, 'load', initialize);

        function initialize() {
            var input = document.getElementById('autocomplete');
            var autocomplete = new google.maps.places.Autocomplete(input);

            autocomplete.addListener('place_changed', function () {
                var place = autocomplete.getPlace();
                $('#latitude').val(place.geometry['location'].lat());
                $('#longitude').val(place.geometry['location'].lng());

                $("#latitudeArea").removeClass("d-none");
                $("#longtitudeArea").removeClass("d-none");
            });
        }
    </script>	
@endsection