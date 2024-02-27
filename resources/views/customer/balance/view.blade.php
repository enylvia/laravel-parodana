@extends('layouts.app')
@section('content')

@include('error.error-notification')

	<div class="box">
		<div class="box-header">
		</div>
		<div class="box-body">
			<div class="table-responsive">
				<table class="table table-responsive table-striped" style="width:100%;">
					<thead>
						<tr>
							<th class="text-center">No</th>
							<th class="text-center">No. Transaksi</th>
							<th class="text-center">Tgl. Transaksi</th>
							<th class="text-center">Keterangan</th>
							<th class="text-center">Saldo</th>
							<th class="text-center" colspan="3">{{trans('general.actions')}}</th>
						</tr>
					</thead>
					<tbody>
						@forelse($balances as $ey => $balance)
							<tr>
								<td>{{$ey+1}}</td>
								<td>{{$balance->transaction_no}}</td>
								<td>{{ date('d-m-Y', strtotime($balance->mutation_date))}}</td>
								<td>{{$balance->description}}</td>
								<td align="right">Rp. {{ number_format($balance->amount, 0, ',' , '.') }}</td>								
								<td><a class="btn btn-sm btn-info" id="Edit" data-target="#Edit-{{$balance->id}}" data-toggle="modal"><i class="fa fa-edit"></i></a></td>
								<td><a class="btn btn-sm btn-danger" id="Delete" data-target="#Delete-{{$balance->id}}" data-toggle="modal"><i class="fa fa-trash"></i></a></td>
								<td><a href="" class="btn btn-sm btn-warning"><i>Journal</i></a></td>
							</tr>
						@empty
							<tr>
								<td colspan="6"> Data Not Found </td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>
		<div class="box-footer">
		</div>
	</div>

	@include('customer.balance.edit')
	@include('customer.balance.delete')	

	@endsection