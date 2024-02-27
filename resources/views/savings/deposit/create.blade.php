<div class="modal fade in" id="create" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<form method="post" action="{{route('deposit.store')}}" enctype="multipart/form-data">
			{{ csrf_field() }}
				<div class="modal-header">
					<h4 class="modal-title">{{trans('installment.savings')}}</h4>
					<button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				</div>
				<div class="modal-body">
					<div class="form-group {!! $errors->has('tr_date') ? 'has-error' : '' !!} required col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<label for="name" class="control-label">{{ trans('installment.date') }}</label>
						<div class="input-group">
							<div class="input-group-addon"><i class="fa fa-id-box-o"></i></div>
							<input class="form-control" name="tr_date" type="date" id="tr_date">
						</div>
					</div>
					<div class="form-group {!! $errors->has('name') ? 'has-error' : '' !!} required col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<label for="name" class="control-label">{{ trans('installment.customer_number') }}</label>
						<div class="input-group">
							<!--input class="form-control" id="city" type="text" placeholder="Enter your city"-->
							<select class="input select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" name="member_number" id="member_number" required>
								<option value="#">#</option>
							</select>
						</div>
					</div>
					<div class="form-group {!! $errors->has('name') ? 'has-error' : '' !!} required col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<label for="name" class="control-label">{{ trans('installment.savings_type') }}</label>
						<div class="input-group">
							<div class="input-group-addon"><i class="fa fa-id-box-o"></i></div>
							<select class="input select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" name="tipe" id="tipe" required>								
								<option value="wajib">WAJIB</option>
								<option value="pokok">POKOK</option>
								<option value="sukarela">SUKARELA</option>
							</select>
						</div>
					</div>
					<div class="form-group {!! $errors->has('status_type') ? 'has-error' : '' !!} required col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<label for="status_type" class="control-label">Type</label>
						<div class="input-group">
							<div class="input-group-addon"><i class="fa fa-id-box-o"></i></div>
							<select class="input select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" name="status" id="status" required>								
								<option value="setor">SETOR</option>
								<option value="tarik">TARIK</option>
								<option value="transfer">TRANSFER</option>
							</select>
						</div>
					</div>
					<div class="form-group {!! $errors->has('amount') ? 'has-error' : '' !!} required col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<label for="name" class="control-label">{{ trans('installment.amount') }}</label>
						<div class="input-group">
							<div class="input-group-addon"><i class="fa fa-id-box-o"></i></div>
							<input class="form-control" name="amount" type="text" placeholder="Rp. 0" id="inputku" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
							@if ($errors->first('amount'))
							<span class="help-block">{!! $errors->first('amount') !!}</span>
							@endif
						</div>
					</div>
					<div class="form-group">
						<label for="desc" class="control-label">Deskripsi Tabungan</label>
						<textarea name="desc" id="desc" class="form-control"></textarea>
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