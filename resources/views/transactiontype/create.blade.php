<div id="Create" class="modal fade in" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<form method="post" action="{{URL::to('/transaction/type/store')}}" enctype="multipart/form-data">
			{{ csrf_field() }}
				<div class="modal-header">
					<h4 class="modal-title">{{trans('general.transaction_type')}}</h4>
					<button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				</div>
				<div class="modal-body">
					<div class="form-group col-md-12 {!! $errors->has('transaction_type') ? 'has-error' : '' !!} required ">
						<label for="name" class="control-label">{{ trans('general.transaction_type') }}</label>						
						<input class="form-control" name="transaction_type" type="text" id="transaction_type" required>						
					</div>
					<div class="form-group col-md-12 {!! $errors->has('account_number') ? 'has-error' : '' !!} required ">
						<label for="name" class="control-label">{{ trans('general.account_number') }}</label>						
							<select name="account_number" class="input select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" id="contract_number">
								<option value="0">=======PILIH=======</option>
								@foreach($accounts as $account)								
								<option value="{{$account->account_number}}">{{$account->account_number}} | {{$account->account_name}}</option>
								@endforeach
							</select>
					</div>
					<div class="form-group col-md-12 {!! $errors->has('tipe') ? 'has-error' : '' !!} required ">
						<label for="name" class="control-label">{{ trans('installment.type') }}</label>
						<select class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true" name="tipe" id="tipe" required>
							<option value="0" disable="true" selected="true">=== TIPE ===</option>
							<option value="d" disable="true">DEBET</option>
							<option value="k" disable="true">KREDIT</option>
						</select>
					</div>										
					<div class="form-group col-md-12 {!! $errors->has('description') ? 'has-error' : '' !!} required ">
						<label for="name" class="control-label">{{ trans('general.description') }}</label>						
						<input class="form-control" name="description" type="text" id="description" required>						
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