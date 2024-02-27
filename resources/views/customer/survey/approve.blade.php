@extends('layouts.app')
@section('content')

	@include('error.error-notification');
	
		<div class="box">
			<div class="box-header">
				
			<div>
			</div class="box-boady">
				<div class="table-responsive">
					<table class="table table-responsive-sm table-striped">
						<thead>
							<tr>
								<th>No</th>
								<th>{{trans('general.customer')}}</th>
								<th>No. Register</th>
								<th>{{trans('general.branch')}}</th>
								<th>{{trans('loan.loan_to')}}</th>
								<th>{{trans('loan.loan_amount')}}</th>
								<th>{{trans('loan.time_period')}}</th>
								<th>{{trans('loan.marketing_name')}}</th>
								<th>{{trans('loan.surveyor_name')}}</th>
							</tr>
						</thead>
						<tbody>
						@forelse($customers as $key => $customer)
						<?php 
							$surveys = App\Models\CustomerSurvey::where('id',$customer->id)->get();
							$companies = App\Models\Company::where('id',$customer->branch)->get();
						?>
							<tr>
								<td>{{$key+1}}</td>
								<td>{{$customer->name}}</td>
								<td><a href="{{URL::to('/customer/survey/plan', $customer->reg_number)}}">{{$customer->reg_number}}</a></td>
								<td align="center">
								@foreach($companies as $company)
								{{$company->branch}}
								@endforeach
								</td>
								<td align="center">{{$customer->loan_to}}</td>
								<td>Rp. {{ number_format($customer->loan_amount, 2, ',' , '.') }}</td>
								<td>Rp. {{ empty($customer->loan_amount) ? 0 : number_format($customer->loan_amount, 0, ',' , '.') }}</td>
								<td align="center">{{$customer->time_period}}</td>
								<td>{{$customer->created_by}}</td>
								<td>{{$customer->created_by}}</td>
							</tr>
						@empty
							<tr>
								<td colspan="8">Data Not Found</td>
							</tr>
						@endforelse
						</tbody>
					</table>
				</div>
			</div>
		</div>
		
@endsection