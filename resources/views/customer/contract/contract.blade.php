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
						$approves = App\Models\CustomerApprove::where('customer_id',$customer->id)->first();
					?>
					<tr>
						<td><a href="{{URL::to('customer/contract/create/'.$customer->reg_number) }}">{{$customer->name}}</a></td>
						<td>{{$customer->loan_to}}</td>
						@if(!$approves)
							<td colspan="4">Data Not Found</td>
						@else
						<td>Rp. {{ number_format($approves->approve_amount, 0, ',' , '.') }}</td>
						<td>{{$approves->time_period}}</td>
						<td>{{$approves->interest_rate}}</td>
						@endif
						<td>{{$customer->created_by}}</td>
						<td class="text-center"><a href="{{URL::to('customer/contract/signature') }}">Signature</a> | <a onClick="ShowModalProcess(this)" data-togle="modal" data-members="{{$customer->reg_number}}" data-id="{{$customer->id}}">Proses</a> | <a href="">Delete</a></td>
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
	<!-- Modal Proses -->

	<div class="modal fade" id="proses" tabindex="-1">
	    <div class="modal-dialog">
	        <div class="modal-content">
	           <div class="modal-body py-3" id="bodyForm" >
				<form method="POST" action="" id="bodyFormSubmit" enctype="multipart/form-data">
				{{ csrf_field() }}
					<div class="form-group">
						<label for="regnumber">REG NUMBER</label>
						<input type="text" class="form-control" name="nopin" id="regnumber" value="">
					</div>
					<input type="hidden" class="form-control" name="customer_id" id="customer_id" value="">
					<div class="form-group">
						<label for="provision">Persen Provision</label>
						<input type="text" class="form-control" placeholder="min. 0% max. 5%" name="provision" id="provision">
					</div>
					<div class="form-group">
						<label for="insuranceName">Perusahaan Asuransi</label>
						<select name="insuranceName" id="insuranceName" class="form-control">
							<option value="ASURANSI PARODANA-M">ASURANSI PARODANA-M</option>
						</select>
					</div>
					<div class="form-group">
						<label for="insurance">Persen Asuransi</label>
						<select name="insurance" id="insurance" class="form-control">
						<option value="0.5">0.5%</option>
						<option value="1">1%</option>
						<option value="1.25">1.25%</option>
						<option value="1.50">1.50%</option>
						<option value="1.75">1.75%</option>
						<option value="2">2%</option>
						<option value="2.25">2.25%</option>
						<option value="2.50">2.50%</option>
						<option value="2.75">2.75%</option>
						</select>
					</div>
					<div class="form-group">
							<label for="materai">Materai</label>
							<input type="text" placeholder="Rp. " class="form-control" name="materai" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" id="materai">
					</div>
					<div class="form-group">
						<label for="deskripsi">Deskripsi</label>
						<textarea name="deskripsi" id="deskripsi" class="form-control"></textarea>
					</div>
					<button class="btn btn-sm btn-success" onclick="storeNasabah()">Submit</button>
				</div>
				</form>
				<div class="modal-footer">
					<button type="button" class="btn btn-default btnAngsur" data-dismiss="modal">Tutup</button>
				</div>
	        </div>
	    </div>
	</div>
@endsection
@section('js')
<script>
function ShowModalProcess(elem){
	var regNumber = $(elem).data("members");
	var cust_id = $(elem).data("id");
	// change value
	$("#regnumber").val(regNumber);
	$("#customer_id").val(cust_id);
	$('#proses').modal('show');
}

function storeNasabah(elem){
	var action = "{{URL::to('/customer/contract/store')}}";
	$('#bodyFormSubmit').attr('action', action);
}
</script>
@endsection