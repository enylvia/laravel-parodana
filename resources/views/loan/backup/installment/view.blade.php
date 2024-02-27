@extends('layouts.app')
@section('content')
	@include('error.error-notification')
	
	<div class="box">		
		<div class="box-header">
			@foreach($customers as $customer)
				<table class="table">
					<tbody>
						<tr>
							<th align="left" width="15%;">No. Anggota</th>
							<td width="5%;">:</td>
							<td>{{$customer->member_number}}</td>
						</tr>
						<tr>
							<th align="left" width="15%;">Nama Anggota</th>
							<td width="5%;">:</td>
							<td>{{$customer->name}}</td>
						</tr>
						<tr>
							<th align="left" width="15%;">Alamat</th>
							<td width="5%;">:</td>
							<td>{{$customer->address}}</td>
						</tr>
					</tbody>
				</table>
			@endforeach
		</div>
		<div class="box-body">
			<a href="{{URL::to('/installment/printAll/' .$memberNumber)}}" class="btn btn-sm btn-success" target="_blank">
				Cetak Semua
			</a>
			<div class="table-responsive">
				@foreach($loans as $loan)
				<h3><strong>Rp. {{ number_format($loan->loan_remaining, 0, ',' , '.') }}</strong></h3>
				@endforeach
				<table class="table table-responsive table-striped">
					<thead>
						<tr>
							<th>No</th>
							<th>Tgl. Jatuh Temp</th>
							<th>Tanggal Bayar</th>
							<th>Metode Bayar</th>
							<th class="text-center">Status</th>
							<th class="text-center">Saldo</th>
							<th class="text-center">Sisa</th>
							<th class="text-center">{{trans('general.actions')}}</th>
							<th class="text-center">Journal</th>
						</tr>
					</thead>
					<tbody>
					@foreach($loans as $loan)
					<?php 
						$angsurans = App\Models\Installment::where('member_number',$loan->member_number)->orderBy('due_date','asc')->orderBy('inst_to','asc')->get();
					?>
						@forelse($angsurans as $key => $angsuran)
						<tr>
							<td>{{$angsuran->inst_to}}</td>							
							<td>{{ date('d-m-Y', strtotime($angsuran->due_date))}}</td>
							<td>{{ $angsuran->pay_date ? date('d-m-Y', strtotime($angsuran->pay_date)) : '' }}</td>
							<td>{{$angsuran->pay_method}}</td>
							<td align="center">
								@if($angsuran->status === 'PAID')
								<span class="label label-success">{{$angsuran->status}}</span>
								@endif
								@if($angsuran->status === 'UNPAID')
								<span class="label label-info">{{$angsuran->status}}</span>
								@endif
								@if($angsuran->status === 'PARTIAL')
								<span class="label label-warning">{{$angsuran->status}}</span>
								@endif
								@if($angsuran->status === 'CORRUPT')
								<span class="label label-danger">{{$angsuran->status}}</span>
								@endif
							</td>
							<td align="right">Rp. {{ !empty($angsuran->amount) ? number_format($angsuran->amount, 0, ',' , '.') : 0 }}</td>
							<td align="center">								
								@if($angsuran->reminder == 0 AND $angsuran->status == 'PAID')
									<span>LUNAS</span>
								@else
									<span>SISA: </span> {{$angsuran->reminder}}
								@endif
							</div>
							<td align="center">
								@include('loan.installment.create')
								@include('loan.installment.full')
								@include('loan.installment.free')
								@if($angsuran->status === 'PAID')
								<a id="full" data-target="#full-{{$angsuran->id}}" data-toggle="modal" class="btn btn-success btn-sm" style="display:none;">
									Bayar Penuh
								</a>								
								<a id="free" data-target="#free-{{$angsuran->id}}" data-id="{!!$angsuran->id!!}" data-toggle="modal" class="btn btn-warning btn-sm free" style="display:none;">
									Bayar Bebas
								</a>
								@endif
								@if($angsuran->status === 'UNPAID')
								<a id="full" data-target="#full-{{$angsuran->id}}" data-toggle="modal" class="btn btn-success btn-sm">
									Bayar Penuh
								</a>								
								<a id="free" data-target="#free-{{$angsuran->id}}" data-id="{!!$angsuran->id!!}" data-toggle="modal" class="btn btn-warning btn-sm free">
									Bayar Bebas
								</a>
								<!--input type="hidden" class="form-control" name="ins_to" value="{{$angsuran->inst_to}}" id="ins_to" required-->
								@endif
								@if($angsuran->status === 'PARTIAL' and $angsuran->journal === 0)
								<a id="full" data-target="#full-{{$angsuran->id}}" data-toggle="modal" class="btn btn-success btn-sm">
									Bayar Penuh
								</a>								
								<a id="free" data-target="#free-{{$angsuran->id}}" data-id="{!!$angsuran->id!!}" data-toggle="modal" class="btn btn-warning btn-sm free">
									Bayar Bebas
								</a>
								@endif
								@if($angsuran->status === 'PARTIAL' and $angsuran->journal === 1)
								<a id="full" data-target="#full" data-toggle="modal" class="btn btn-success btn-sm">
									Bayar Penuh
								</a>								
								<a id="free" data-target="#free" data-id="{!!$angsuran->id!!}" data-toggle="modal" class="btn btn-warning btn-sm free" style="display:none;">
									Bayar Bebas
								</a>
								@endif
								<a href="{{URL::to('/installment/print/' .$angsuran->id)}}" class="btn btn-sm btn-default" target="_blank">
									<i class="fa fa-print"></i>
								</a>
							</td>
							<td align="center">
								@if($angsuran->journal === 0 and $angsuran->status === 'UNPAID')
									<a class="btn btn-sm btn-warning posting" href="{{URL::to('/installment/journal/' .$angsuran->trans_number)}}" style="display:none;">
										<i class="fa fa-columns" title="Journal"></i>
									</a>
								@endif
								@if($angsuran->status === 'PAID' and $angsuran->journal === 0)
									<a class="btn btn-sm btn-warning posting" href="{{URL::to('/installment/journal/' .$angsuran->trans_number)}}">
										<i class="fa fa-columns" title="Journal"></i>
									</a>
								@endif								
								@if($angsuran->status === 'PARTIAL' and $angsuran->journal === 0)
									<a class="btn btn-sm btn-warning posting" href="{{URL::to('/installment/journal/' .$angsuran->trans_number)}}" style="display:none;">
										<i class="fa fa-columns" title="Journal"></i>
									</a>
								@endif
								@if($angsuran->status === 'PARTIAL' and $angsuran->journal === 0 and $angsuran->amount == $loan->pay_month)
									<a class="btn btn-sm btn-warning posting" href="{{URL::to('/installment/journal/' .$angsuran->trans_number)}}"  style="display:none;">
										<i class="fa fa-columns" title="Journal"></i>
									</a>
								@endif
							</td>
						</tr>
						@empty
						<td>Data not found</td>
						@endforelse
					</tbody>
					@endforeach
				</table>
			</div>
		</div>
		<div class="box-footer">
			<a href="{{ URL::to('installment') }}" class="btn btn-success btn-md">
				<span class="fa fa-close"></span> {{trans('general.close')}}
			</a>
			<a href="{{URL::to('/installment/printAll/' .$memberNumber)}}" class="btn btn-default btn-md">
				<span class="fa fa-print"></span> {{trans('general.print')}}
			</a>
		</div>		
	</div>
	
	<div class="alert alert-warning alert-dismissible col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<h4><i class="icon fa fa-warning"></i> Perhatian</h4>
		Data yang sudah di Journal tidak dapat di hapus atau di edit!
	</div>
	
@endsection

@section('js')
	<script>
		$(document).ready(function () {
			$('.free').on('click', function () {

				var prop_id = $(this).data('id');
				alert(prop_id);
				console.log(prop_id);

				$.ajaxSetup({
					headers: {
						'X-CSRF-Token': $('meta[name="_token"]').attr('content')
					}
				});

				$.ajax({
						type: 'GET',
						url: '/installment/free_store/' + prop_id,
						dataType: 'HTML',

						success: function (data) {

						},
					}).then(data => {
						$('.modal-content').html(data);
						$('#free-' + prop_id).modal("show");
					})
					.catch(error => {
						var xhr = $.ajax();
						console.log(xhr);
						console.log(error);
					})

			});
		});
	</script>
@endsection