@extends('layouts.app')
@section('content')

	<div class="box">
		<div class="box-header">
			<a href="/transaction/purchase/create" class="btn btn-xs btn-success" target="_blank">
				<i class="fa fa-plus" title="Add"></i>
			</a>
		</div>
		<div class="box-body">
			<div class="table-responsive">
				<table class="table table-responsive table-striped" id="purchase">
					<thead>
						<tr>
							<th class="text-center">No. Transaksi</th>
							<th class="text-center">Tgl. Transaksi</th>
							<th class="text-center">{{trans('general.to')}}</th>
							<th class="text-center">Tipe Transaksi</th>
							<th class="text-center">{{trans('general.description')}}</th>
							<th class="text-center">Satuan</th>
							<th class="text-center">Jumlah</th>
							<th class="text-center">Harga</th>
							<th class="text-center">Total</th>
							<th class="text-center" colspan="3">{{trans('general.actions')}}</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
		<div class="box-footer">
		</div>
	</div>
	
	@include('purchase.delete')
								
@endsection

@section('js')
	<script type="text/javascript">
	$(document).ready( function () {
		$.ajaxSetup({
		headers: {
		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
		});
	
		$('#purchase').DataTable(
		{	
			processing: false,
			serverSide: true,
			//responsive: true,
			ajax: "{{ route('purchase') }}",		
			columns: [
				{ data: 'trans_code', name: 'trans_code' },
				{ data: 'trans_date', name: 'trans_date' },
				{ data: 'to', name: 'to' },
				{ data: 'trans_type', name: 'trans_type'},
				{ data: 'description', name: 'description'},				
				{ data: 'unit', name: 'unit' },				
				{ data: 'qty', name: 'qty'},
				{ data: 'amount', render: $.fn.dataTable.render.number( '.' , ',', 0, 'Rp. ') },
				{ data: 'total', render: $.fn.dataTable.render.number( '.' , ',', 0, 'Rp. ') },
				{ data: 'btnEdit', name: 'btnEdit' },
				{ data: 'btnDelete', name: 'btnDelete' },
				{ data: 'btnPrint', name: 'btnPrint' }
			],
			order: [[0, 'desc']]
		});
	});
</script>
@endsection