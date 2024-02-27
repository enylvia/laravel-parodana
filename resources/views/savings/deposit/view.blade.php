@extends('layouts.app')
@section('content')
	
	<meta name="csrf-token" content="{{ csrf_token() }}">	
	@include('error.error-notification')
	<div class="box">
	<div class="alert alert-warning alert-dismissible col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<h4><i class="icon fa fa-warning"></i> Perhatian</h4>
		Data yang sudah di Journal tidak dapat di hapus atau di edit!
	</div> 
		<div class="box-body">
			<div class="table-responsive">
				<table class="table table-responsive">
					<tr>
						<td>Total Tab. Pokok</td> <td align="right">Rp. {{ number_format($pokok, 0, ',' , '.') }}</td> <td>Total Tab. Sukarela</td> <td align="right">Rp. {{ number_format($sukarela, 0, ',' , '.') }}</td> 
					</tr>
					<tr>
						<td>Total Tab. Wajib</td> <td align="right">Rp. {{ number_format($wajib, 0, ',' , '.') }}</td> <td>Total Tabungan</td> <td align="right">Rp. {{ number_format($tabungan, 0, ',' , '.') }}</td> 
					</tr>
				</table>
			</div> 
		</div>
	</div>
	<div class="box">
		<div class="box-header">
			@include('savings.deposit.create')
			<span class="new-button">				
				<a id="create" data-target="#create" data-toggle="modal" class="btn btn-success btn-sm">
					<span class="cil-note-add"></span> {{trans('general.new')}}
				</a>
			</span>	
		</div>
		<div class="box-body">
			<div class="table-responsive">
				<table class="table table-responsive-sm table-striped">
					<thead>
						<tr>
							<th>No</th>
							<th>{{trans('installment.date')}}</th>
							<th>Tipe</th>
							<th>Debet</th>
							<th>Kredit</th>
							<th>Saldo</th>
							<th>Deskripsi</th>
						</tr>
					</thead>
					<tbody id="tbody">
						@foreach($setorans as $setoran)
						<tr>
							<td>{{ $loop->iteration }}</td>
							<td>{{ $setoran->tr_date }}</td>
							<td>{{ $setoran->tipe }}</td>
							@if($setoran->status == 'tarik')
							<td>{{ number_format($setoran->amount, 0, ',' , '.') }}</td>
							@else
							<td>-</td>
							@endif
							@if($setoran->status == 'setor')
							<td>{{ number_format($setoran->amount, 0, ',' , '.') }}</td>
							@else
							<td>-</td>
							@endif
							<td>{{ number_format($setoran->end_balance, 0, ',' , '.') }}</td>
							<td>{{ $setoran->description }}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
		<div class="box-footer">
			{{ $setorans->links('vendor.pagination.bootstrap-4') }}
		</div>
	</div>
	
@endsection

@section('js')
<script>
</script>
@endsection