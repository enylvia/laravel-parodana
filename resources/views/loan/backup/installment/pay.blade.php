@extends('layouts.app')
@section('content')

	@include('error.error-notification')
	
	<div class="box">
				
		<div class="box-header">
			@include('loan.installment.full')
			<a id="full" data-target="#full" data-toggle="modal" class="btn btn-success btn-sm">
				Bayar Penuh
			</a>
			@include('loan.installment.free')
			<a id="free" data-target="#free" data-toggle="modal" class="btn btn-success btn-sm">
				Bayar Bebas
			</a>
			<a href="{{URL::to('/installment/printAll/' .$memberNumber)}}" class="btn btn-sm btn-success" target="_blank">
				Cetak Semua
			</a>
			<div class="box-tools">
				
				<div class="input-group input-group-sm" style="width: 350px;">					
					<input type="search" name="query" class="form-control pull-right" placeholder="{{trans('general.search')}}" id="search">

					<div class="input-group-btn">
						<button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
					</div>
				</div>
			</div>
		</div>
		<div class="box-body">									
			<table class="table table-responsive table-striped">
				<thead>
					<tr>
						<th>No.</th>
						<th>Kode</th>
						<th>Tgl. Bayar</th>
						<th>Jumlah</th>
						<th>Keterangan</th>
						<th>{{trans('general.actions')}}</th>
					</tr>
				</thead>
				<tbody>
					@foreach($installments as $key => $installment)
					<tr>
						<td>{{$key+1}}</td>
						<td>{{$installment->trans_number}}</td>
						<td>{{$installment->pay_date}}</td>
						<td>{{$installment->amount}}</td>
						<td>
							@if($installment->reminder == 0)
								<span>LUNAS</span>
							@else
								<span>SISA: </span> {{$installment->reminder}}
							@endif
						</td>
						<td>
							<a href="{{URL::to('/installment/print/' .$installment->id)}}" class="btn btn-xs btn-default" target="_blank">
								<i class="fa fa-print"></i>
							</a>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		<div class="box-footer">
			
		</div>
		
	</div>
	
	
@endsection

@section('js')
	
@endsection