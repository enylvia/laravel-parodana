@extends('layouts.app')
@section('content')

@include('error.error-notification')

	<div class="box">
		<div class="box-header">
			<span class="new-button">				
				<a id="create" data-target="#create" data-toggle="modal" class="btn btn-success btn-sm">
					<span class="fa fa-plus"></span> {{trans('general.new')}}
				</a>
			</span>
		</div>
		<div class="box-body">
			<table class="table table-responsive-sm table-striped" id="user_role">
				<thead>
					<tr>
						<th>User</th>
						<th>Role</th>
					</tr>
				</thead>
				<tbody>										
					@foreach($users as $user)
					@foreach($user->roles as $role)
					<tr>
						<td class="form-group user">
							{{$user->name}}
						</td>
						<td>
							<select class="input select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" id="role" name="role" required>
								<?php
									$roll = [];								                       
									$roll[] = $role->id;
								?>
								<option value="">-- Role --</option>
								@foreach($roles as $role)									
									@if(in_array($role->id, $roll))
									  <option value="{{ $role->id }}" selected="true">{{ $role->name }}</option>
									@else
									  <option value="{{ $role->id }}" data-role_id="{{$role->id}}" >{{ $role->name }}</option>
									@endif 
								@endforeach
							</select>
						</td>
						<td class="form-group style="width:2px;" align="center">
							<a href="javascript:void(0)" class="btn btn-sm btn-info edit" data-id="{{ $user->id }}" data-role="{{ $user->roles->first()->id }}">
								<i class="fa fa-edit" title="{{trans('user.edit')}}"></i>
							</a>
						</td>
						<td class="form-group style="width:2px;" align="center">
							<a id="Delete" data-target="#Delete-{{$user->id}}" data-toggle="modal" class="btn btn-sm btn-danger">
								<i class="fa fa-trash" title="{{trans('user.delete')}}"></i>
							</a>						
						</td>
					</tr>
					@endforeach
					@endforeach					
					<td>
						
					</td>					
				</tbody>
			</table>
		</div>
	</div>				

	@endsection
	
	@section('js')
		<script type="text/javascript">
			$(document).ready(function() {
				$('#user_role tbody tr').each(function(i, element) {						
					var html = $(this).html();					
					if(html!='')
					{
						$("select[name^='role']").change(function() {
							var role = $(this).data('role_id');
							var test = $(this).closest('tr').find("input[name^='user']").val($(this[this.selectedIndex]).attr('data-role_id'));									
							var role = $(this).data('role_id');
							alert(test);
							var role = $('#role option:selected').val();
							alert(role);
						});
					}
				});
				
				$('.edit').click(function(){                  
					//var currentValue = $(this).attr("value");
					var currentValue = $(this).data('id');
					var role = $('#role option:selected').val();
					//var role = $(this).data('role');
					alert(role);
					$.ajax({
						url: "{{ route('userrole.update') }}",
						method: 'post',             
						data: {id: currentValue, _token: $('input[name="_token"]').val(), role:role},
						success: function(data){
							alert(data);
						},
						error: function (data, textStatus, errorThrown) {
							console.log(data);
						}
					});
				});         
			});
		</script>
	@endsection
	
	<div class="modal fade in" id="create" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true" role="dialog">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<form method="post" action="{{route('userrole.store')}}" enctype="multipart/form-data">
				{{ csrf_field() }}
					<div class="modal-header">
						<h4 class="modal-title">{{trans('general.role')}}</h4>
						<button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					</div>
					<div class="modal-body">
						<div class="form-group {!! $errors->has('name') ? 'has-error' : '' !!} required ">
							<label for="name" class="control-label">{{ trans('general.name') }}</label>							
							<select class="input select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" id="user" name="user" required>
								<option value="0">Please select</option>
								@foreach($users as $user)						
									<option value="{{$user->id}}">{{$user->name}}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group {!! $errors->has('description') ? 'has-error' : '' !!} required ">
							<label for="name" class="control-label">{{ trans('general.description') }}</label>							
							<select class="input select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" id="role" name="role" required>
								<option value="0">Please select</option>
								@foreach($roles as $role)
								<option value="{{$role->id}}">{{$role->name}}</option>
								@endforeach
							</select>
						</div>						
					</div>
					<div class="modal-footer">
						<button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>					
						<button class="btn btn-success" type="submit" id="simpan">
							<span class="cil-save"></span> {{('Save')}}
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>