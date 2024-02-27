<div class="modal fade in" id="create" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<form method="post" action="{{route('tempo.store')}}" enctype="multipart/form-data">
			{{ csrf_field() }}
				<div class="modal-header">
					<h4 class="modal-title">Tambah Tempo</h4>
					<button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="customer">Customer</label>
						<select name="customer" id="customer" class="form-control">
							@foreach($customer as $key => $value)
							<option value="{{$value->member_number}}">{{$value->member_number}} - {{$value->name}}</option>
							@endforeach
						</select>
					</div>		
					<div class="form-group">
						<label for="bunga">Bunga</label>
						<input type="text" name="bunga" class="form-control">
					</div>
					<div class="form-group">
						<label for="date">Tanggal Pembayaran</label>
						<input type="date" name="dates" class="form-control">
					</div>
					<div class="form-group">
						<label for="name" class="control-label">Jumlah Tempo</label>												
							<input class="form-control" name="amount" type="text" placeholder="Rp. 0" id="inputku" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">			
					</div>
					<div class="form-group">
						<label for="desc" class="control-label">Keterangan</label>
						<textarea name="desc" id="desc" class="form-control"></textarea>
					</div>
					<div class="modal-footer">
						<button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>					
						<button class="btn btn-success" type="submit" id="simpan">
							<span class="cil-save"></span> {{('Save')}}
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>