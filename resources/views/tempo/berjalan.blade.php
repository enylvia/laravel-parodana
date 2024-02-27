@extends('layouts.app')
@section('content')

@include('error.error-notification')	
	<div class="box">
		<div class="box-body">
			<div class="table-responsive">
				<table class="table table-responsive table-striped" style="width:100% !important; table-layout:fixed" id="tempo">
					<thead>
						<tr>
							<th>No</th>
							<th>No Pinjaman</th>
							<th>Nasabah</th>
							<th>Angsuran Ke</th>
							<th>Tanggal Pengajuan</th>
							<th>Pokok</th> 
							<th>Bunga</th> 
							<th>Total</th> 
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
@endsection

@section('js')	
	<script type="text/javascript">
		function formatNumber(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
	$(document).ready(function() {
            var dataTables = $('#tempo').DataTable({
                "serverSide": true,
                "deferRender": true,
                "ajax": {
                    url: "{{ route('tempo.berjalan') }}",
                    cache: true
                },

                "columns": [
                    {
                        render: function (data,type,row,meta){
                            return meta.row + meta.settings._iDisplayStart+1;
                        },
                    },
                    {data: "loan_number"},
                    {data: "name"},
                    {data: "inst_to"},
                    {data: "tempo_date"},
                    {data: "amount", render: $.fn.dataTable.render.number('.', ',', 0, 'Rp. ')},
                    {data: "rate_count", render: $.fn.dataTable.render.number('.', ',', 0, 'Rp. ')},
                    {data: "total_amount", render: $.fn.dataTable.render.number('.', ',', 0, 'Rp. ')},

                ],
                "order": [[1, 'asc']]
            });
	});
	</script>
@endsection