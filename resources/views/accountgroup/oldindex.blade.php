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
						<th>Number</th>
						<th>Name</th>
						<th>Group</th>
						<th class="text-center">{{trans('general.actions')}}</th>
					</tr>
				</thead>
				<tbody>
				<?php $i = 1 ?>
				@if(count($items) > 0)
					@foreach($items as $item)
					<tr>
						<td>{{$i++}}</td>
						<td>{{$item->account_number}}</td>
						<td>{{$item->account_name}}</td>
						@include('accountgroup.delete')						
						<td>						
							<a id="Delete-{{$item->id}}" data-target="#Delete-{{$item->id}}" data-toggle="modal" class="btn btn-sm btn-danger">
								<i class="fa fa-trash" title="{!!trans('account.delete')!!}"></i>
							</a>
						</td>
						@if(count($item->children))
							@foreach ($item->children as $childs)
								@include('accountgroup.child', ['sub_parent' => $childs, 'j' => $i++])
							@endforeach
						@endif
					</tr>
					@endforeach
				@else
					<td colspan="4">No results found</td>
				@endif
				</tbody>
			</table>
							
		</div>
		
		<div class="box-footer">
			{{ $items->links('vendor.pagination.bootstrap-4') }}
		</div>
	</div>
	
@endsection