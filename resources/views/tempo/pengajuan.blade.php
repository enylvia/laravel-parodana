@extends('layouts.app')
@section('content')

@include('error.error-notification')	
<div class="createTempo modal fade" id="createTempo" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
  <div class="modal-content">
			<form method="post" action="{{route('tempo.store')}}" enctype="multipart/form-data">
			{{ csrf_field() }}
				<div class="modal-header">
					<h4 class="modal-title">Tambah Tempo</h4>
					<button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="customer">Customer</label> <br>
						<select name="customer" id="customer" class="customer form-control" style="width: 100%">
							@foreach($customer as $key => $value)
							<option value="{{$value->member_number}}">{{$value->member_number}} - {{$value->name}}</option>
							@endforeach
						</select>
					</div>		
					<div class="form-group">
						<label for="bunga">Bunga</label>
						<input type="text" name="bunga" class="form-control" id="bunga" onchange="hitungTotalBunga()">
					</div>
					<div class="form-group">
						<label for="name" class="control-label">Jumlah Tempo</label>												
						<input class="form-control" name="amount" type="text" placeholder="Rp. 0" id="amount" onchange="hitungTotalBunga()" onkeyup="tandaPemisahTitik(this)">			
					</div>
					<div class="form-group">
						<label for="jml_bunga" class="control-label">Jumlah Bunga</label>
						<input class="form-control" readonly name="jml_bunga" type="text" placeholder="Rp. 0" id="jml_bunga">			
					</div>
					<div class="form-group">
						<label for="total_tempo" class="control-label">Total Tempo</label>
						<input class="form-control" readonly name="total_tempo" type="text" placeholder="Rp. 0" id="total_tempo">			
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

	<div class="box">
		<div class="box-header">	
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createTempo">
				{{trans('general.new')}}
			</button>	
		</div>
		<div class="box-body">
			<div class="table-responsive">
				<table class="table table-responsive table-striped" style="width:100% !important; table-layout:fixed" id="tempo">
					<thead>
						<tr>
							<th>No</th>
							<th>No Pinjaman</th>
							<th>Nasabah</th>
							<th>Tanggal Pengajuan</th>
							<th>Jumlah</th> 
							<th>Dibuat Oleh</th>
							<th>Keterangan</th>
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
                    url: "{{ route('tempo') }}",
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
                    {data: "tempo_date"},
                    {data: "amount", render: $.fn.dataTable.render.number('.', ',', 0, 'Rp. ')},
                    {data: "created_by"},
                    {data: "keterangan"},

                ],
                "order": [[1, 'asc']]
            });
			$('.customer').select2({
    			dropdownParent: $("#createTempo")
			});
	});
	</script>
	<script>
		function hitungTotalBunga() {
		var bunga = parseInt(document.getElementById('bunga').value);
		var amount = parseInt(document.getElementById('amount').value.replace(/\./g,''));
		var jml_bunga = ((bunga / 100) * amount);
		var total_tempo = amount + jml_bunga;

		document.getElementById('jml_bunga').value = jml_bunga;
		document.getElementById('total_tempo').value = total_tempo;
	}

	function tandaPemisahTitik(angka){
    var number_string = angka.value.replace(/[^,\d]/g, '').toString(),
        split   = number_string.split(','),
        sisa    = split[0].length % 3,
        rupiah  = split[0].substr(0, sisa),
        ribuan  = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    angka.value = rupiah;
}

	</script>

@endsection