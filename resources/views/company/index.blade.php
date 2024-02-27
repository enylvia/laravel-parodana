@extends('layouts.app')

@section('content')
	<div class="box">
		@include('error.error-notification')
		<div class="box-header">
			<a href="{{ URL::to('company/create') }}" class="btn btn-success btn-sm">
				<span class="cil-note-add"></span> {{trans('general.new')}}
			</a>
		</div>
		<div class="box-body">
			<table class="table table-responsive-sm table-striped">
				<thead>
					<tr>
						<th>{{trans('general.name')}}</th>
						<th>SIUP</th>
						<th>{{trans('loan.branch')}}</th>
						<th>Status</th>
						<th class="text-center" colspan="2">{{trans('general.actions')}}</th>
					</tr>
				</thead>
				<tbody>
					@forelse($companies as $company)
					<tr>
						<td>{{$company->name}}</td>
						<td>{{$company->siup}}</td>
						<td>{{$company->branch}}</td>
						<td><span class="badge badge-success">Active</span></td>
						<td style="width:2px;" align="center">
							<a class="btn btn-sm btn-info" href="{{URL::to('/company/edit/' .$company->id)}}">
								<i class="fa fa-save"></i>  
							</a>
						</td>				                		                       
						<td style="width:2px;" align="center">
							@include('company.delete')
							<a id="Delete" data-target="#Delete-{{$company->id}}" data-toggle="modal" class="btn btn-sm btn-danger">
								<i class="fa fa-trash" title="{{trans('general.delete')}}"></i>
							</a>
						</td>
						<!--td style="width:2px;" align="center">
							<a id="Print" data-target="#Print-{{$company->id}}" data-toggle="modal" class="btn btn-sm btn-warning" href="{{URL::to('/company/print/' .$company->id)}}"><i class="fa fa-print" title="{{trans('company.print')}}"></i></a>
						</td-->
					</tr>
					@empty
					<tr>
						<td colspan="5">Data Not Found</td>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
		<div class="box-footer">
			{{ $companies->links('vendor.pagination.bootstrap-4') }}
		</div>
	</div>
@endsection