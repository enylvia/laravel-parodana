@extends('layouts.app')

@section('content')
	<div class="box">
		@include('error.error-notification')
		<div class="box-header">
			<a id="create" data-target="#create" data-toggle="modal" class="btn btn-success btn-sm">
				<span class="cil-note-add"></span> {{trans('general.new')}}
			</a>
		</div>
		<div class="box-body">
			<div class="table-responsive">
				<table class="table table-responsive-sm table-striped">
					<thead>
						<tr>
							<th>Photo</th>
							<th>Name</th>
							<th>Email</th>
							<th>Branch</th>
							<!--th>Role</th-->
							<th>Status</th>
							<th class="text-center" colspan="3">{{trans('general.actions')}}</th>
						</tr>
					</thead>
					<tbody>
						@forelse($users as $user)					
						<tr>
							<td align="center"> <img src="{{asset($user->avatar!='' ?'uploads/photo/'.$user->avatar:'uploads/photo/noimage.jpg')}}" style="height: 60px; width:60px;"></td>
							<td>{{$user->name}}</td>
							<td>{{$user->email}}</td>
							<td>							
								@foreach($user->companies as $company)
									{{$company->branch}} | {{$company->name}}
								@endforeach
							</td>
							<!--td>
								@foreach($user->roles as $role)
									<span class="badge badge-success">{{ $role->name }}</span>
								@endforeach
							</td-->
							<td>
							</td>
							<td style="width:2px;" align="center">
								<a class="btn btn-sm btn-info" href="{{URL::to('/employee/edit/' .$user->id)}}">
									<i class="fa fa-save"></i>  
								</a>
							</td>				                		                       
							<td style="width:2px;" align="center">
								@include('employee.delete')
								<a id="Delete" data-target="#Delete-{{$user->id}}" data-toggle="modal" class="btn btn-sm btn-danger">
									<i class="fa fa-trash" title="{{trans('user.delete')}}"></i>
								</a>
							</td>
							<td style="width:2px;" align="center">
								<a class="btn btn-sm btn-warning" href="{{URL::to('/employee/print/' .$user->id)}}">
									<i class="fa fa-print"></i>  
								</a>
							</td>
						</tr>
						@empty
						<tr>
						</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>
		
		<div class="box-footer">
			<div class="pull-left">			
				<!--a class="btn btn-md btn-warning" href="{{URL::to('/employee/printAll/' .$user->id)}}">
					<i class="fa fa-print"></i>  
				</a-->
				{{ $users->links('vendor.pagination.bootstrap-4') }}
			</div>
		</div>
		
	</div>
@endsection