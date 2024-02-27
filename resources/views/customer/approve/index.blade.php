@extends('layouts.app')
@section('content')

@include('error.error-notification')
	
	<div class="box">
		<div class="box-body">
			<div class="table-responsive">
				<table class="table table-responsive-sm table-striped">
					<thead>
						<tr>
							<th>{{trans('general.name')}}</th>
							<th>{{trans('general.reg_number')}}</th>
							<th>{{trans('loan.marketing_name')}}</th>
							<th class="text-center">{{trans('survey.loan_to')}}</th>
							<th class="text-center">{{trans('survey.loan_amount')}}</th>
							<th class="text-center">{{trans('survey.time_period')}}</th>
						</tr>
					</thead>
					<tbody>
						@forelse($customers as $customer)
						<tr>
							<td><a href="{{URL::to('customer/view/'.$customer->id) }}">{{$customer->name}}</a></td>
							<td>{{$customer->reg_number}}</td>
							<td>{{$customer->created_by}}</td>
							<td align="center">{{$customer->loan_to}}</td>
							<td align="right">Rp. {{ number_format($customer->loan_amount, 2, ',' , '.') }}</td>
							<td align="center">{{$customer->time_period}} {{trans('general.month')}}</td>
						</tr>
						@empty
						<tr>
							<td colspan="6">Data Not Found</td>
						</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>
	</div>
@endsection