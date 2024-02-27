@extends('layouts.app')
@section('title', 'Menu')
@section('content')	   

	<div class="box">
		<div class="box-header">
			@include('menu.create')
			<span class="new-button">				
				<a id="create" data-target="#create" data-toggle="modal" class="btn btn-success btn-sm">
					<span class="fa fa-plus"></span> {{trans('general.new')}}
				</a>
			</span>	
		</div>
		<div class="box-body">
			<div class="col-md-12">
				<label for="provinsi">{{trans('general.role')}}</label>
				<select class="input select2 select2-hidden-accessible" onchange="role" style="width: 100%;" aria-hidden="true" name="role" id="role">
					<option value="">=== Pilih Role ===</option>
					@foreach($roles as $role)
					  <option value="{{$role->id}}">{{ $role->name }}</option>
					@endforeach
				</select>
			</div>
			<div class="col-md-12">
				<table class="table table-responsive-sm table-striped">
					<thead>
						<tr>
							<th>Menu Name</th>
							<th>Role Name</th>
							<th class="text-center">Menu Access</th>
							<th class="text-center">{{trans('general.actions')}}</th>
						</tr>
					</thead>
					<tbody id="menuside">
						@foreach($items as $item)
						<!--tr>
							<td>{{$item->display}}</td>
							<td>{{$item->name}}</td>
							@if($item->menu_access==1)
							<td align="center">True</td>
							@else
							<td align="center">False</td>
							@endif
							<td><a id="Delete" data-target="#Delete-{{$item->sidemenu_id}}" data-toggle="modal" class="btn btn-sm btn-danger"><i class="fa fa-trash" title="{{trans('general.delete')}}"></i></a></td>
						</tr-->
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	@include('menu.delete')
@endsection

@section('js')
<script>
	$(document).ready(function(){
		$("select[name='role']").on('change', function(e) {     
			var id = $(this).val();      
			//var role = $('option:selected').val();
			//var role = id;
			//alert(role);
			$.get('{{ url('menu/all')}}/'+id, function(data){
				console.log(id);
				console.log(data);
				$('#menuside').empty();
				$.each(data, function(index, element){
					$('#menuside').append("<tr><td>"+element.display+"</td><td>"+element.name+"</td>"+(element.menu_access == 1 ? '<td align="center">True</td>' : '<td align="center">False</td>')+"<td align='center'><a id='Delete' data-target='#Delete-"+element.sidemenu_id+"' data-toggle='modal' class='btn btn-sm btn-danger'><i class='fa fa-trash' title='{{trans('general.delete')}}'></i></a></td></tr>");
				});
			});
		});
	});
</script>
<script>
<?php 
	foreach($items as $item)
	{
		$ids = $item->id;
	}
?>
	$(document).ready(function(){		
		$('#Delete').click(function() {			
			var id = $(this).val();			
				$("select[name='role']").on('change', function(e) {     
				var role = $('option:selected').val();
				alert(role);
			});
			<?php
				echo $role ="<script>document.writeln(role);</script>";
			?>
				$.ajax({
					url: "{!! url('/menu/management/delete/" + id + "/" + role + "/') !!}",
					type: 'POST',
					success: function(){
						alert("success");
					}
				});
			
		});
	});
</script>
@endsection