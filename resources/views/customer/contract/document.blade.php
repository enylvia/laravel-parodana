@extends('layouts.document_layout')
@section('content')

<div class="container py-3">
		<div class="card shadow p-3 mb-5 bg-white rounded" id="btn-cetak">
			<div class="card-body">
			<table border="0" width="100%">
			<tr>
				<td class="py-2" style="height: 250px;"><img src="{{asset('img/logo/logo-small.png')}}" width="155"/></td>
				<td class="text-end" style="line-height:100%;" width="32%">
					<p>Kepada Yth.</p>
					<p>Koperasi Simpan Pinjam PARODANA-M</p>
					<p>Jl. Raya Serang - Jakarta km 72, Ruko Sembilan kav 4</p>
					<p>Kecamatan Kibin PT. Nikomas</p>
					<p>Serang - Banten</p>
				</td>
			</tr>
			<tr>
				<td class="text-center py-3" colspan="2"><b>SURAT PERMOHONAN PENGAJUAN KREDIT</b><br>
				<b>DI KOPERASI SIMPAN PINJAM "PARODANA M"</b>
				</td>
			</tr>
			<tr>
				<td>Yang bertanda tangan di bawah ini:</td>
			</tr>
			<tr>
				<td>Nama</td>
				<td style="width:70%;">: {{$customer->name}}</td>
			</tr>
			<tr>
				<td>Alamat Tinggal</td>
				<td style="width:70%;">: {{$customer->address}}</td>
			</tr>
			<tr>
				<td>Umur</td>
				@php
					$birthday = $customer->date_of_birth;
					$age = Carbon\Carbon::parse($birthday)->diff(Carbon\Carbon::now())->format('%y Tahun');
				@endphp
				<td style="width:70%;">: {{$age}} </td>
			</tr>
			<tr>
				<td>Tempat Tgl Lahir</td>
				<td style="width:70%;">: {{$customer->birth_place}}</td>
			</tr>
			<tr>
				<td>Pekerjaan</td>
				<td style="width:70%;">: {{$customer->part}}</td>
			</tr>
			<tr>
				<td>Alamat Email</td>
				<td style="width:70%;">: - </td>
			</tr>

			<tr>
				<td colspan="2">
				<p>
					Benar - benar ingin mengajukan kredit pinjaman dan menjadi nasbah di Koperasi Simpan Pinjam PARODANA-M dengan ketentuan
					taat kepada peraturan-peraturan yang berlaku di Koperasi Simpan Pinjam PARODANA-M dengan permohonan sebesar {{$approveCustomer->approve_amount}} dan sanggup mengembalikan sebesar {{$approveCustomer->installment}} selama jangka waktu {{$approveCustomer->time_period}} BULAN.
				</p>
				<p>
					Pinjaman dipakai sendiri untuk keperluan : {{$customer->necessity_for}}
				</p>
				</td>
			</tr>
			<tr>
				<td colspan="2" class="text-center">PARODANA-M, {{date_format(now(),"d-m-Y")}}</td>
			</tr>

			<tr>
				<td class="text-center py-5">
					<p>
					( {{$customer->name}} )
					</p>
					<p>Pemohon</p>
				</td>
				<td class="text-center" style="width:50% ;">
					<p>(............................)</p>
					<p>Istri/Suami/Orang Tua/Saudara </p>
				</td>
			</tr>
			<tr>
				<td>

				</td>
				<td style="width:30% ;" id="action" class="text-end">
					<a href="" onclick="Cetak()" class="btn btn-sm btn-primary">Cetak</a>
					<a href="/customer/contract/detail/{{$customer->id}}/?page=1" class="btn btn-sm btn-primary">Next</a>
				</td>
			</tr>
		</table>
			</div>
		</div>
	</div>
@endsection
@section('script')
<script>
	function Cetak(){
		var prtContent = document.getElementById("btn-cetak");
		var action = document.getElementById("action");
		action.style.display = "none";
		prtContent.className = "card p-3 mb-5 bg-white rounded";
		window.print();
		prtContent.className = "card shadow p-3 mb-5 bg-white rounded";

	}
</script>
@endsection