@extends('layouts.app')
@section('content')

@include('error.error-notification')
	
	<div class="box">
		<div class="box-header">
			@include('interestrate.create')
			<span class="new-button">				
				<a id="create" data-target="#create" data-toggle="modal" class="btn btn-success btn-sm">
					<span class="cil-note-add"></span> {{trans('general.new')}}
				</a>
			</span>
		</div>
		<div class="box-body">
			<table class="table table-responsive-sm table-striped">
				<thead>
					<tr>
						<th>{{trans('general.name')}}</th>
						<th>{{trans('general.rate')}}</th>
						<th class="text-center" colspan="2">{{trans('general.actions')}}</th>
					</tr>
				</thead>
				<tbody>
				@forelse($rates as $rate)
					<tr>
						<td>{{$rate->name}}</td>
						<td>{{$rate->rate}}</td>
						<td style="width:2px;" align="center">
							@include('interestrate.edit')
							<a id="Edit" data-target="#Edit-{{$rate->id}}" data-toggle="modal" class="btn btn-sm btn-info">
								<i class="fa fa-save" title="{{trans('general.edit')}}"></i>
							</a>
						</td>				                		                       
						<td style="width:2px;" align="center">
							@include('interestrate.delete')
							<a id="Delete" data-target="#Delete-{{$rate->id}}" data-toggle="modal" class="btn btn-sm btn-danger">
								<i class="fa fa-trash" title="{{trans('general.delete')}}"></i>
							</a>
						</td>
					</tr>
				@empty
					<td>Data not found</td>
				@endforelse
				</tbody>
			</table>
		</div>
	</div>	
		
@endsection