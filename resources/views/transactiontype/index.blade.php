@extends('layouts.app')
@section('content')
@include('error.error-notification')

	<div class="box">
		
		<div class="box-header">			
			@include('transactiontype.create')
			<a id="Create" data-target="#Create" data-toggle="modal" class="btn btn-sm btn-success">
				<i class="fa fa-plus" title="{{trans('general.new')}}"></i>
			</a>
		</div>
		<div class="box-body">
			<div class="table-responsive">
				<table class="table table-responsive table-striped">
					<thead>
						<tr>
							<th>No</th>
							<th>{{trans('general.transaction_type')}}</th>
							<th>{{trans('general.type')}}</th>
							<th>{{trans('general.account_number')}}</th>
							<th>{{trans('general.description')}}</th>
							<th colspan="3">{{trans('general.actions')}}</th>
						</tr>
					</thead>
					<tbody>
						@foreach($transactions as $key => $transaction)
						<tr>
							<td>{{$key+1}}</td>
							<td>{{$transaction->transaction_type}}</td>
							<td>{{$transaction->account_number}}</td>
							<td>
							@if($transaction->tipe == 'd')
								DEBET
							@endif
							@if($transaction->tipe == 'k')
								KREDIT
							@endif
							</td>
							<td>{{$transaction->description}}</td>
							<td>
								@include('transactiontype.edit')
								<a id="Edit-{{$transaction->id}}" data-target="#Edit-{{$transaction->id}}" data-toggle="modal" class="btn btn-sm btn-info">
									<i class="fa fa-edit" title="{{trans('general.edit')}}"></i>
								</a>
								<!--a href="{{ URL::to('transaction/type/create') }}" class="btn btn-success btn-sm">
									<span class="cil-note-add"></span> {{trans('general.new')}}
								</a-->
							</td>
							<td>
								@include('transactiontype.delete')
								<a id="Delete-{{$transaction->id}}" data-target="#Delete-{{$transaction->id}}" data-toggle="modal" class="btn btn-sm btn-danger">
									<i class="fa fa-trash" title="{{trans('general.delete')}}"></i>
								</a>
							</td>
						</tr>
						@endforeach
					</tbody>
					<tfoot>
					</tfoot>
				</table>
			</div>
		</div>
		<div class="box-footer">
			{{ $transactions->links('vendor.pagination.bootstrap-4') }}
		</div>
	</div>
	
@endsection