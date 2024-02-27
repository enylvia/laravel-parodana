@extends('layouts.app')
@section('content')

@include('error.error-notification')	
	
	<div class="box">
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
			<span class="new-button">	
				<a class="btn btn-sm btn-warning" href="{{URL::to('/withdrawal/create')}}">
					<i class="fa fa-plus" title="Create"></i>  
				</a>
			</span>	
			<div class="box-tools">
				<form method="get" action="{{route('installment.search')}}" enctype="multipart/form-data">
				 <div class="input-group input-group-sm" style="width: 350px;">
					<input type="search" name="search" class="form-control pull-right" placeholder="Search" id="search">

					<div class="input-group-btn">
						<button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
					</div>
				</div>
				</form>
			</div>
		</div>
		<div class="box-body">
			<div class="table-responsive">
				<table class="table table-responsive-sm table-striped">
					<thead>
						<tr>
							<th>No</th>
							<th>{{trans('installment.date')}}</th>
							<th>{{trans('installment.customer_number')}}</th>
							<th>{{trans('installment.customer_name')}}</th>
							<th>{{trans('installment.savings_type')}}</th>
							<th>{{trans('installment.start_balance')}}</th>
							<th>{{trans('installment.end_balance')}}</th>
							<th>{{trans('installment.amount')}}</th>					
							<th class="text-center" colspan="2">{{trans('general.actions')}}</th>
							<th class="text-center">Posting</th>
						</tr>
					</thead>
					<tbody>
					@foreach($setorans as $key => $setoran)					
						<tr>
							<td>{{$key+1}}</td>
							<td>{{ date('d-m-Y', strtotime($setoran->tr_date))}}</td>
							<td>{{$setoran->member_number}}</td>
							<td>{{$setoran->name}}</td>
							<td>{{$setoran->tipe}}</td>
							<td>Rp. {{ number_format($setoran->start_balance, 0, ',' , '.') }}</td>
							<td>Rp. {{ number_format($setoran->end_balance, 0, ',' , '.') }}</td>
							<td>Rp. {{ number_format($setoran->amount, 0, ',' , '.') }}</td>							
							<td style="width:2px;" align="center">
								<!--a class="btn btn-sm btn-info" href="{{URL::to('/deposit/edit/' .$setoran->member_number)}}">
									<i class="fa fa-save"></i>  
								</a-->
								@include('savings.deposit.edit')
								<a id="Edit" data-target="#Edit-{{$setoran->id}}" data-toggle="modal" class="btn btn-sm btn-info">
									<i class="fa fa-save" title="{{trans('general.edit')}}"></i>
								</a>
							</td>				                		                       
							<td style="width:2px;" align="center">
								@include('savings.deposit.delete')
								<a id="Delete" data-target="#Delete-{{$setoran->id}}" data-toggle="modal" class="btn btn-sm btn-danger">
									<i class="fa fa-trash" title="{{trans('general.delete')}}"></i>
								</a>
							</td>
							<td align="center">
								@if ($setoran->posting==1)
								<a class="btn btn-sm btn-warning posting" href="{{URL::to('/deposit/posting')}}" style="display:none;">
									<i class="fa fa-columns" title="Posting"></i>  
								</a>
								@else
									<a class="btn btn-sm btn-warning posting" href="{{URL::to('/deposit/posting/' .$setoran->id)}}">
									<i class="fa fa-columns" title="Posting"></i>  
								</a>
								@endif
							</td>
						</tr>
						<!--?php 
						if ($setoran->tipe == 'WAJIB')
						{
							App\Models\Savings::select(DB::raw('sum(start_balance)'))
							->where('member_number', '=', $setoran->member_number)
							->where('tipe','=','WAJIB')
							->update(['end_balance' => $wajib]);
						}
						if ($setoran->tipe == 'POKOK')
						{
							App\Models\Savings::select(DB::raw('max(end_balance)'))
							->where('member_number', '=', $setoran->member_number)
							->where('tipe','=','POKOK')
							->update(['end_balance' => $pokok]);
						}
						if ($setoran->tipe == 'SUKARELA')
						{
							App\Models\Savings::select(DB::raw('max(end_balance)'))
							->where('member_number', '=', $setoran->member_number)
							->where('tipe','=','SUKARELA')
							->update(['end_balance' => $sukarela]);
						}
						?-->
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