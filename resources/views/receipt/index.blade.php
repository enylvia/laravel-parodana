@extends('layouts.app')
@section('content')

@include('error.error-notification')

	<div class="box">
		<div class="box-header">
			@include('receipt.create')
			<a id="Create" data-target="#Create" data-toggle="modal" class="btn btn-sm btn-success">
				<i class="fa fa-plus" title="{{trans('general.new')}}"></i>
			</a>
		</div>
		<div class="box-body">
			<div class="table-responsive">
				<table class="table table-responsive table-striped">
					<thead>
						<tr>
							<th>No. Bukti</th>
							<th>Tanggal</th>
							<th>No. Kontrak</th>
							<th>Provisi</th>
							<th>Asuransi</th>
							<th>Materai</th>
							<th colspan="3" class="text-center">{{trans('general.actions')}}</th>
							<th align="center">Journal</th>
						</tr>
					</thead>
					<tbody>
						@foreach($receipts as $receipt)
							<tr>
								<td>{{$receipt->trans_number}}</td>
								<td>{{ date('d-m-Y', strtotime($receipt->trans_date))}}</td>
								<td>{{$receipt->contract_number}}</td>
								<td align="right">{{$receipt->provision}} %</td>
								<td align="right">{{$receipt->insurance}} %</td>
								<td align="right">Rp. {{ number_format($receipt->stamp, 0, ',' , '.') }}</td>
								<td style="width:2px;" align="center">
								@include('receipt.edit')									
									@if ($receipt->journal==1)
										<a id="Edit" data-target="#Edit-{{$receipt->id}}" data-toggle="modal" class="btn btn-sm btn-info" style="display:none;">
											<i class="fa fa-edit" title="{{trans('general.edit')}}"></i>
										</a>
									@else
										<a id="Edit" data-target="#Edit-{{$receipt->id}}" data-toggle="modal" class="btn btn-sm btn-info">
											<i class="fa fa-edit" title="{{trans('general.edit')}}"></i>
										</a>
									@endif
								</td>
								<td style="width:2px;" align="center">
								@include('receipt.delete')									
									@if ($receipt->journal==1)
										<a id="Delete" data-target="#Delete-{{$receipt->id}}" data-toggle="modal" class="btn btn-sm btn-danger" style="display:none;">
											<i class="fa fa-trash" title="{{trans('general.delete')}}"></i>
										</a>
									@else
										<a id="Delete" data-target="#Delete-{{$receipt->id}}" data-toggle="modal" class="btn btn-sm btn-danger">
											<i class="fa fa-trash" title="{{trans('general.delete')}}"></i>
										</a>
									@endif
								</td>
								<td style="width:2px;" align="center">
									<a class="btn btn-sm btn-success" href="{{URL::to('/receipt/print/' .$receipt->trans_number)}}" target="_blank">
										<i class="fa fa-print" title="Cetak Formulir"></i>  
									</a>
								</td>
								<td align="center">
									@if ($receipt->journal==1)
									<a class="btn btn-sm btn-warning" href="{{URL::to('receipt/journal/'.$receipt->trans_number) }}" style="display:none;">
										<i class="fa fa-eye" title="Journal"></i>  
									</a>
									@else
									<a class="btn btn-sm btn-warning" href="{{URL::to('receipt/journal/'.$receipt->trans_number) }}">
										<i class="fa fa-eye" title="Journal"></i>
									</a>
									@endif
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	
	<div class="alert alert-warning alert-dismissible col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<h4><i class="icon fa fa-warning"></i> Perhatian</h4>
		Data yang sudah di Journal tidak dapat di hapus atau di edit!
	</div>
	
@endsection