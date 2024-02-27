@extends('layouts.app')
@section('content')

@include('error.error-notification')
	
	<div class="box">
		<div class="box-header">
			@include('tax.create')
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
						<th>{{trans('general.tax')}}</th>
						<th class="text-center" colspan="2">{{trans('general.actions')}}</th>
					</tr>
				</thead>
				<tbody>
				@forelse($taxes as $tax)
					<tr>
						<td>{{$tax->name}}</td>
						<td>{{$tax->tax}}</td>
						<td style="width:2px;" align="center">
							@include('tax.edit')
							<a id="Edit" data-target="#Edit-{{$tax->id}}" data-toggle="modal" class="btn btn-sm btn-info">
								<i class="fa fa-save" title="{{trans('general.edit')}}"></i>
							</a>
						</td>				                		                       
						<td style="width:2px;" align="center">
							@include('tax.delete')
							<a id="Delete" data-target="#Delete-{{$tax->id}}" data-toggle="modal" class="btn btn-sm btn-danger">
								<i class="fa fa-trash" title="{{trans('general.delete')}}"></i>
							</a>
						</td>
					</tr>
				@empty
					<td colspan="3">Data not found</td>
				@endforelse
				</tbody>
			</table>
		</div>
	</div>
	
@endsection