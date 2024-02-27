@extends('layouts.app')
@section('content')
	
	<div class="box">
		<div class="box-header">
		
		</div>
		<div class="box-body">
			
				<h1 class="display-3 mb-3 text-center">Simulasi Kredit</h1>
				
				<div class="form-group">
					<label for="jumlahKredit">Jumlah Kredit <em>(rupiah)</em>: </label>
					<input type="text" class="form-control" id="jumlahKredit" name="jumlahKredit"
						placeholder="Contoh: 150000000">
				</div>
				<div class="form-group">
					<label for="jangkaWaktu">Jangka Waktu <em>(bulan)</em>: </label>
					<input type="number" class="form-control" id="jangkaWaktu" name="jangkaWaktu"
						placeholder="Contoh: 12">
				</div>
				<div class="form-group">
					<label for="bungaPertahun">Bunga Pertahun <em>(%)</em>: </label>
					<input type="number" class="form-control" id="bungaPertahun" name="bungaPertahun"
						placeholder="Contoh: 36">
				</div>
				
				<div class="form-group">
					<table class="table table-responsive">
						<thead>
							<tr>
							<th width="5px;">No</th>
							<th width="5px;">Bulan</th>
							<th width="5px;">Pokok</th>
							<th width="5px;">Bunga</th>
							<th width="5px;">Angsuran</th>
							<th width="5px;">Sisa Pinjaman</th>
							</tr>
						</thead>
						<tbody id="tableAngsuran" style="display:none">
							<tr>
							<td id="no"></td>
							<td id="pokok"></td>
							<td id="jangkaWaktu"></td>
							<td id="bunga"></td>
							<td id="jumlahAngsuran"></td>
							<td id="sisaPinjaman"></td>							
							</tr>
						</tbody>
					</table>
				</div>
			
		</div>
	</div>

@endsection
@section('js')
<script>	
	$(document).ready(function(){
		var jumlahKredit = 0;
		var bungaPertahun = 0;		  
		var jangkaWaktu = 0;		
		
		$('#jumlahKredit').on('change',function(e){
		//$('#jumlahKredit').keyup(function(){
			hapus();
			simulation();			
		});
		$('#bungaPertahun').on('change',function(e){
			hapus();
			simulation();			
		});
		$('#jangkaWaktu').on('change',function(e){
			hapus();
			simulation();			
		});
		
		function simulation() {
			var x = document.getElementById('tableAngsuran');
			var jumlahKredit = document.getElementById('jumlahKredit').value;
			var bungaPertahun = document.getElementById('bungaPertahun').value;		  
			var jangkaWaktu = document.getElementById('jangkaWaktu').value;
						//"{!! url('/menu/management/delete/" + id + "/" + role + "/') !!}"
			$.ajax({
				url: "{!! url('/installment/flat/" + jumlahKredit + "/" + jangkaWaktu +"/" + bungaPertahun + "') !!}",
				type: 'GET',
				success: function(data){
					x.style.display = 'block';
					//alert("success");
					$.each(data, function(index, element){
						//$('tbody').empty();
						//$('#tableAngsuran').empty();
						$('#tableAngsuran').append('<tr><td width="5px;">' +element.no+ '</td><td width="5px;">' +element.pokok+ '</td><td width="5px;">' +element.bunga+ '</td><td width="5px;">' +element.jumlahAngsuran+ '</td><td>' +element.sisaPinjaman+ '</td></tr>');
						var no = element.no;
						var pokok = element.pokok;
						var jangkaWaktu = element.jangkaWaktu;
						var bunga = element.bunga;
						var jumlahAngsuran = element.jumlahAngsuran;
						var sisaPinjaman = element.sisaPinjaman;
						document.getElementById("no").innerHTML = no;
						document.getElementById("pokok").innerHTML = pokok;
						document.getElementById("jangkaWaktu").innerHTML = jangkaWaktu;
						document.getElementById("bunga").innerHTML = bunga;
						jumlah.oument.getElementById("pokok").innerHTML = pokok;
					});
				},
				error: function(data){
					x.style.display = 'none';
					console.log(data);
					//$('#tableAngsuran').remove();
				}
			});
		}
		function hapus(){
			//document.getElementById("tableAngsuran").innerHTML = "<tr><td></td><td></td><td></td><td></td><td></td></tr>";
			$('#tableAngsuran').empty();
		} 
	});
</script>
<!--script type="text/javascript">
		
		var rupiah = document.getElementById('jumlahKredit');
		rupiah.addEventListener('keyup', function(e){
			// tambahkan 'Rp.' pada saat form di ketik
			// gunakan fungsi formatRupiah() untuk mengubah angka yang di ketik menjadi format angka
			rupiah.value = formatRupiah(this.value, 'Rp. ');
		});
 
		/* Fungsi formatRupiah */
		function formatRupiah(angka, prefix){
			var number_string = angka.replace(/[^,\d]/g, '').toString(),
			split   		= number_string.split(','),
			sisa     		= split[0].length % 3,
			rupiah     		= split[0].substr(0, sisa),
			ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);
 
			// tambahkan titik jika yang di input sudah menjadi angka ribuan
			if(ribuan){
				separator = sisa ? '.' : '';
				rupiah += separator + ribuan.join('.');
			}
 
			rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
			return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
		}
	</script>
@endsection