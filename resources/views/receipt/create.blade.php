<div id="Create" class="modal fade in" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<form method="post" action="{{URL::to('/receipt/store')}}" enctype="multipart/form-data">
			{{ csrf_field() }}
				<div class="modal-header">
					<h4 class="modal-title">{{trans('general.receipt')}}</h4>
					<button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				</div>
				<div class="modal-body">
					<div class="form-group col-md-12 {!! $errors->has('tr_date') ? 'has-error' : '' !!} required ">
						<label for="name" class="control-label">{{ trans('installment.date') }}</label>						
							<input class="form-control" name="trans_date" type="date" required>						
					</div>
					<div class="form-group col-md-12 {!! $errors->has('tr_date') ? 'has-error' : '' !!} required ">
						<label for="name" class="control-label">{{ trans('general.contract_number') }}</label>						
							<select name="contract_number" class="input select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" id="contract_number">
								<option value="0">=======PILIH No Kontrak=======</option>
								@foreach($customers as $customer)								
								<option value="{{$customer->contract_number}}" data-provisi="{{$customer->provision}}" data-asuransi="{{$customer->insurance}}" data-materai="{{$customer->stamp}}">{{$customer->contract_number}}</option>
								@endforeach
							</select>
					</div>
					<div class="form-group col-md-12 {!! $errors->has('tr_date') ? 'has-error' : '' !!} required ">
						<label for="name" class="control-label">{{ trans('general.provision') }}</label>						
						<input class="form-control" name="provision" type="number" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" title="This should be a number with up to 2 decimal places." id="provisi" required>						
					</div>
					<div class="form-group col-md-12 {!! $errors->has('tr_date') ? 'has-error' : '' !!} required ">
						<label for="name" class="control-label">{{ trans('general.insurance') }}</label>
						<select class="form-control" name="insurance" id="asuransi">
							<option value="0.5">6 Bulan 0.5%</option>
							<option value="1">9 Bulan 1%</option>
							<option value="1.25">12 Bulan 1.25%</option>
							<option value="1.50">15 Bulan 1.50%</option>
							<option value="1.75">18 Bulan 1.75%</option>
							<option value="2">24 Bulan 2%</option>
						</select>
					</div>
					<div class="form-group col-md-12 {!! $errors->has('tr_date') ? 'has-error' : '' !!} required ">
						<label for="name" class="control-label">{{ trans('general.stamp') }}</label>						
						<input class="form-control" name="stamp" type="text" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" id="materai" required>						
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

@section('js')
<script>	
	window.onload = function(){
		$("#contract_number").change(function () {
			var ambilProvisi = $(this).find(':selected').attr('data-provisi')
			var ambilAsuransi = $(this).find(':selected').attr('data-asuransi')
			var ambilMaterai = $(this).find(':selected').attr('data-materai')
			$('#provisi').val(ambilProvisi);
			$('#asuransi').val(ambilAsuransi);
			$('#materai').val(ambilMaterai);
		});
	}
</script>
@endsection