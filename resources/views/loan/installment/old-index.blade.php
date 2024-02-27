@extends('layouts.app')
@section('content')

@include('error.error-notification')	
	
	<div class="box">
		<div class="box-header">			
			<!--span class="new-button">				
				<a id="create" data-target="#create" data-toggle="modal" class="btn btn-success btn-sm">
					<span class="cil-note-add"></span> {{trans('general.new')}}
				</a>
			</span-->
			<div class="box-tools">
				<form method="get" action="{{route('installment.search')}}" enctype="multipart/form-data">
				 <div class="input-group input-group-sm" style="width: 350px;">
					<input type="search" name="search" class="form-control pull-right" placeholder="Search" id="search">

					<div class="input-group-btn">
						<button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
					</div>
				</div>
				</form>
			</div>
		</div>
		<div class="box-body">
			<div class="table-responsive">
				<table class="table table-responsive-sm table-striped">
					<thead>
						<tr>
							<th>No</th>
							<th>{{trans('installment.customer_name')}}</th>
							<th>{{trans('installment.customer_number')}}</th>
							<th>{{trans('general.contract_number')}}</th>
							<th>{{trans('installment.contract_date')}}</th>
							<th>{{trans('installment.start_month')}}</th>
							<th>{{trans('installment.pay_date')}}</th>
							<th>{{trans('loan.loan_amount')}}</th>
							<th>{{trans('loan.time_period')}}</th>
							<th>{{trans('loan.interest_rate')}}</th>
							<th>{{trans('loan.pay_principal')}}</th>
							<th>{{trans('loan.pay_interest')}}</th>
							<th>{{trans('loan.pay_month')}}</th>
							<th>{{trans('installment.loan_remaining')}}</th>
							<th class="text-center" colspan="3">{{trans('general.actions')}}</th>
						</tr>
					</thead>
					<tbody>
						@forelse($loans as $key => $loan)
						<?php 
							$customer = App\Models\Customer::where('member_number',$loan->member_number)->first();
						?>
							<tr>
								<td>{{$key+1}}</td>
								<td>{{ !empty($customer->name) ? $customer->name : '' }}</td>
								<td>{{$loan->member_number}}</td>
								<td>{{$loan->contract_number}}</td>
								<td>{{ date('d-m-Y', strtotime($loan->contract_date))}}</td>
								<td>{{ date('d-m-Y', strtotime($loan->start_month))}}</td>
								<td>{{$loan->pay_date}}</td>
								<td>Rp. {{ number_format($loan->loan_amount, 0, ',' , '.') }}</td>
								<td align="center">{{$loan->time_period}} {{trans('general.month')}}</td>
								<td align="center">{{$loan->interest_rate}} % /tahun</td>
								<td>Rp. {{ number_format($loan->pay_principal, 0, ',' , '.') }}</td>
								<td>Rp. {{ number_format($loan->pay_interest, 0, ',' , '.') }}</td>
								<td>Rp. {{ number_format($loan->pay_month, 0, ',' , '.') }}</td>
								<td align="center">Rp. {{ number_format($loan->loan_remaining, 0, ',' , '.') }}</td>
								<!--td>
									<a href="{{URL::to('installment/create') }}">Angsuran</a>
								</td-->
								<td>
									<a class="btn btn-sm btn-warning" href="{{URL::to('installment/view/'.$loan->member_number) }}">
										<i class="fa fa-eye" title="Lihat Angsuran"></i>  
									</a>
								</td>
								<td>
									<a class="btn btn-sm btn-warning" href="{{URL::to('installment/edit/'.$loan->member_number) }}">
										<i class="fa fa-edit" title="Edit"></i>  
									</a>
								</td>
								<td>
									<a class="btn btn-sm btn-success" href="{{URL::to('installment/create/pay/'.$loan->member_number) }}">
										<i class="fa fa-plus" title="Pay"></i>  
									</a>
								</td>
							</tr>
						@empty
						@endforelse
					</tbody>
				</table>
			</div>
		</div>
		<div class="box-footer">
			<div class="pull-left">
				{{ $loans->links('vendor.pagination.bootstrap-4') }}
			</div>
		</div>
	</div>
	
@endsection

@section('js')
<script type="text/javascript">
 window.onload = function(){
   $("#member_number").change(function () {
     var ambilNama = $("#member-"+this.value).data('nama');
     //var ambilStatus = $("#alat-"+this.value).data('status');
     //var ambilKondisi = $("#alat-"+this.value).data('kondisi');
     $("#custome_name").val(ambilNama);
     //$("#status").val(ambilStatus);
     //$("#kondisi").val(ambilKondisi);
   });
}
</script>
@endsection