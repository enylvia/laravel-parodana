@extends('layouts.app')
@section('content')

@include('error.error-notification')
	
	<div class="box">
		<div class="box-body">
			<!-- Implement here -->
			<h4>DAFTAR TRANSAKSI</h4>
			<hr>
			<div class="container">
					<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="start_date">Waktu Awal</label>
									<input type="date" name="s_date" id="start_date" class="form-control">
								</div>
								<div class="form-group">
									<label for="akun">Akun</label>
									<select name="akun" id="akun" class="form-control">
										<option value=""></option>
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="end_date">Waktu Awal</label>
									<input type="date" name="e_date" id="end_date" class="form-control">
								</div>
							</div>
						</div>
					<button type="submit" class="btn btn-sm btn-primary" id="submit-button">Kirim</button>
					<button type="submit" class="btn btn-sm btn-primary" id="clear-button">Clear</button>
					<i><p style="font-size: 10px;color: #000;color: rgba(0, 0, 0, 0.5);">Please Click Clear Before Search!</p></i>
					<hr>
					<div class="row">
						<div class="col-md-11">
						<table class="table table-responsive-sm table-striped" id="trx">
						<thead>
						<tr>
								<th>Trx No</th>
								<th>Waktu Transaksi</th>
								<th>Akun</th>
								<th>Debit</th>
								<th>Kredit</th>
								<th>Keterangan</th>
								<th>Di Acc Oleh</th>
								<th>Tipe Transaksi</th>
						</tr>
						</thead>
                </table>
						</div>
					</div>
				</div>
			</div>
		</div>	
	
@endsection
@section('js')
<script>
	$("#submit-button").on("click", function(){
		var table = $('#trx').DataTable({
			"processing": true,
			"serverSide": true,
			"order": [[ 0, "desc" ]],
			"ajax": {
				"url": "{{route('transaction.list')}}",
				"data": function ( d ) {
					d.start_date = $('#start_date').val();
					d.end_date = $('#end_date').val();
					d.akun = $('#akun').val();
				}
			},
			"columns": [
				{ "data": "trx_no" },
				{ "data": "date_trx" },
				{ "data": "account" },
				{
					render: function (data, type,row){
						if (row.status == 'd'){
							return row.amount;
						}else{
							return '-';
						}
					}
				},
				{
					render: function (data, type,row){
						if (row.status == 'k'){
							return row.amount;
						}else{
							return '-';
						}
					}
				},
				{ "data": "description" },
				{ "data": "acc_by" },
				{"data": "jenis"},
			]
		});
		$('#search-form').on('submit', function(e) {
			table.draw();
			e.preventDefault();
		});

    });
	$("#clear-button").on("click", function(){
		$('#trx').DataTable().destroy();
	});
</script>
@endsection