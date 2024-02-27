@extends('layouts.app')
@section('content')
	
	<div class="box">
		@include('error.error-notification')
		<div class="box-header">
			<h3>Buku Besar {{ $akun->account_name }}</h3>
		</div>
		<div class="box-body">
			{!! Form::open(['url' => 'ledger/'.$akun->id.'/search', 'method' => 'get', 'class' => 'form-inline text-center']) !!}
                <div class="form-group">
                    <label for="name">Bulan</label>
                    {!! Form::selectMonth('bulan', null, ['class' => 'form-control', 'placeholder' => '-- Bulan --']) !!}
                </div>
                <div class="form-group">
                    <label for="name">Tahun</label>
                    {!! Form::selectRange('tahun', 2018, 2050, null, ['class' => 'form-control', 'placeholder' => '-- Tahun --']) !!}
                    <div class="form-group">
                        <button type="submit" class="btn btn-default btn-md">Cari</button>
                    </div>
                </div>
			{!! Form::close() !!}
			<br>
			<h4>Total Data : <strong>{{ $total_buku }}</strong> </h4>
			<table class="table table-striped text-center">
				<caption class="text-center"> <strong>Daftar Buku Besar {{ $akun->account_name }}</strong></caption>
				<tr>
					<th class="text-center">No</th>
					<th class="text-center">Waktu</th>
					<th class="text-center">Action</th>
				</tr>
			  <?php $i = 1 ?>
			  @foreach($daftar_buku as $data)
				<tr>
					<td>{{ $i++ }}</td>
					<td>{{ date('F Y', strtotime('1-'.$data->waktu)) }}</td>
					<td>
					<a href="{{ url('ledger/detail/'.$akun->id.'/'.date('Y-m-d', strtotime('1-'.$data->waktu))) }}" class="btn btn-info">
						Detail
					</a>
					</td>
				</tr>
			@endforeach
		</table>
		</div>
	</div>
	
@endsection