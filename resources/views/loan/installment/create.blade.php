@forelse($angsurans as $angsuran)
<div id="Create-{{$angsuran->id}}" class="modal fade in"  aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<form method="post" action="{{URL::to('/installment/update', $angsuran->id)}}" enctype="multipart/form-data">
			{{ csrf_field() }}
				<div class="modal-header">
					<h4 class="modal-title">{{trans('installment.installment')}}</h4>
					<button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
				</div>
				<div class="modal-body">
					<div class="form-group col-md-12 {!! $errors->has('tr_date') ? 'has-error' : '' !!} required ">
						<label for="name" class="control-label">{{ trans('installment.date') }}</label>													
							<div class="input-group">
								<div class="input-group-addon"><i class="fa fa-id-box-o"></i></div>
								<input class="form-control" name="pay_date" type="date" id="pay_date" required>
							</div>
							<input name="inst_to" type="hidden" id="inst_to" value="{{$angsuran->inst_to}}">
					</div>
					<div class="form-group col-md-12 {!! $errors->has('name') ? 'has-error' : '' !!} required ">
						<label for="name" class="control-label">{{ trans('installment.customer_number') }}</label>
						<input class="form-control" name="member_number" type="text" id="member_number" value="{{$angsuran->member_number}}" required>
					</div>
					<!--div class="form-group col-md-12 {!! $errors->has('amount') ? 'has-error' : '' !!} required ">
						<label for="name" class="control-label">{{ trans('installment.customer_name') }}</label>												
							<input class="form-control" name="customer_name" type="text" placeholder="Nama Nasabah" id="customer_name" disabled>	
					</div>
					<div class="form-group col-md-12 {!! $errors->has('amount') ? 'has-error' : '' !!} required ">
						<label for="name" class="control-label">{{ trans('loan.address') }}</label>												
							<input class="form-control" name="customer_address" type="text" placeholder="Alamat" id="customer_address" disabled>						
					</div-->
					<div class="form-group col-md-12 {!! $errors->has('name') ? 'has-error' : '' !!} required ">
						<label for="name" class="control-label">{{ trans('installment.payment_method') }}</label>						
							<select name="payment_method" class="form-control" required>
								<option value="Tunai">{{ trans('installment.cash')}}</option>
								<option value="Kartu Debet">{{ trans('installment.debit_card')}}</option>
								<option value="Kartu Debet OCBC">{{ trans('installment.debit_ocbc')}}</option>
								<option value="Kartu Debet Permata">{{ trans('installment.debit_permata')}}</option>
							</select>
					</div>
					<div class="form-group col-md-12 {!! $errors->has('name') ? 'has-error' : '' !!} required ">
						<label for="name" class="control-label">Status</label>						
							<select name="status" class="form-control" required>
								<!--option value="0">Status</option-->
								<option value="UNPAID">UNPAID</option>
								<option value="PAID">PAID</option>								
								<option value="PARTIAL">PARTIAL</option>
								<option value="CORRUPT">CORRUPT</option>
							</select>						
					</div>
					<div class="form-group col-md-12 {!! $errors->has('amount') ? 'has-error' : '' !!} required ">
						<label for="name" class="control-label">{{ trans('installment.amount') }}</label>												
							<input class="form-control" type="text" value="Rp. {{ number_format($loan->pay_month, 0, ',' , '.') }}" disabled>
					</div>
					<div class="form-group col-md-12 {!! $errors->has('amount') ? 'has-error' : '' !!} required ">
						<label for="name" class="control-label">{{ trans('installment.amount') }}</label>												
							<input class="form-control" name="amount" type="text" placeholder="Rp. 0" id="inputku" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" required>
							@if ($errors->first('amount'))
							<span class="help-block">{!! $errors->first('amount') !!}</span>
							@endif						
					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>					
					<button class="btn btn-success" type="submit" id="simpan">
						<span class="cil-save"></span> {{('Save')}}
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
@empty

@endforelse

@section('js')
<script type="text/javascript">
 window.onload = function(){
	$("#member_number").change(function () {
		var ambilNama = $(this).find(':selected').attr('data-nama')
		var ambilAlamat = $(this).find(':selected').attr('data-alamat')
		$("#customer_name").val(ambilNama);
		$("#customer_address").val(ambilAlamat);
	});
}
</script>
<script>
	var date = new Date();
	var day = ("0" + date.getDate()).slice(-2);
	var month = ("0" + (date.getMonth() + 1)).slice(-2);
	var today = date.getFullYear() + "-" + (month) + "-" + (day);

	$("#pay_date").val(today);
</script>
@endsection