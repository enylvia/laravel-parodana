@extends('layouts.app')

@section('content')
	<div class="box">
		@include('error.error-notification')
		
		<div class="box-header">
			@include('accountgroup.create')
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
						<th>Account Number</th>
						<th>Account Name</th>
						<th>Group</th>
						<th class="text-center">{{trans('general.actions')}}</th>
					</tr>
				</thead>
				<tbody>
				@include('accountgroup.edit')
				@include('accountgroup.delete')
				{!! $group !!}
				@if($group === null)
					<td colspan="4">No results found</td>
				@endif
				</tbody>
			</table>
							
		</div>
		
		<div class="box-footer">
			{{ $accounts->links('vendor.pagination.bootstrap-4') }}
		</div>
	</div>
	
@endsection