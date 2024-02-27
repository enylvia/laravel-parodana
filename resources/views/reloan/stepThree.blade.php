@extends('layouts.app')
@section('content')

@include('error.error-notification')

	<div class="box">
		<div class="box-header">
		</div>
		<div class="box-body">
			<table class="table table-responsive-sm table-striped">
				<thead>
					<tr>
						<th>{{trans('general.name')}}</th>
						<th>{{trans('survey.loan_to')}}</th>
						<th>{{trans('loan.loan_amount')}}</th>
						<th>{{trans('loan.time_period')}}</th>
						<th>{{trans('loan.interest_rate')}}</th>
						<th>{{trans('loan.marketing_name')}}</th>
						<th class="text-center" colspan="2">{{trans('general.actions')}}</th>
					</tr>
				</thead>
				<tbody>
					@forelse($customers as $customer)
					<?php 
						$approves = App\Models\CustomerApprove::where('customer_id',$customer->id)->where('approve',0)->first();
						$approveAmount = str_replace('.', '', $approves->approve_amount);
						$approvesId = $approves->id;
					?>
					<tr>
						<td><a href="{{URL::to('customer/reloan/stepfour/'.$customer->id) }}">{{$customer->name}}</a></td>
						<td>{{$customer->loan_to}}</td>
						@if(!$approves)
							<td colspan="4">Data Not Found</td>
						@else
						<td>Rp. {{ number_format($approveAmount, 0, ',' , '.') }}</td>
						<td>{{$approves->time_period}}</td>
						<td>{{$approves->interest_rate}}</td>
						@endif
						<td>{{$customer->created_by}}</td>
						<td><a href="{{URL::to('customer/contract/signature') }}">Signature</a></td>
					</tr>
					@empty
					<tr>
						<td colspan="4">Data Not Found</td>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
		<div class="box-footer">
			{{ $customers->links('vendor.pagination.bootstrap-4') }}
		</div>
	</div>
	
@endsection