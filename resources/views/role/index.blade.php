@extends('layouts.app')

@section('content')
	<div class="box">
		@include('error.error-notification')
		<div class="box-header">
			@include('role.create')
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
						<th>{{trans('role.decription')}}</th>
						<th class="text-center" colspan="2">{{trans('general.actions')}}</th>
					</tr>
				</thead>
				<tbody>
					@forelse($roles as $role)
					<tr>
						<td>{{$role->name}}</td>
						<td>{{$role->description}}</td>
						<td style="width:2px;" align="center">
							@include('role.edit')
							<a id="Edit" data-target="#Edit-{{$role->id}}" data-toggle="modal" class="btn btn-sm btn-info">
								<i class="fa fa-save" title="{{trans('general.edit')}}"></i>
							</a>
						</td>				                		                       
						<td style="width:2px;" align="center">
							@include('role.delete')
							<a id="Delete" data-target="#Delete-{{$role->id}}" data-toggle="modal" class="btn btn-sm btn-danger">
								<i class="fa fa-trash" title="{{trans('general.delete')}}"></i>
							</a>
						</td>
						<!--td style="width:2px;" align="center">
							<a id="Print" data-target="#Print-{{$role->id}}" data-toggle="modal" class="btn btn-sm btn-warning" href="{{URL::to('/role/print/' .$role->id)}}"><i class="cil-print" title="{{trans('role.print')}}"></i></a>
						</td-->
					</tr>
					@empty
					<tr>
						<td colspan="4">Data Not Found</td>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
		<div class="box-footer">
			{{ $roles->links('vendor.pagination.bootstrap-4') }}
		</div>
	</div>
@endsection