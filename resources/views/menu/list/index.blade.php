@extends('layouts.app')
@section('title', 'Menu')
@section('content')	   
<meta name="_token" content="{!! csrf_token() !!}"/>
	<div class="row">    
		<div class="col-md-7">
            <div class="box box-solid">
            	<div class="box-header with-border">
	              <h3 class="box-title">List Menu</h3>

					<div class="box-tools">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
						</button>
					</div>
					<div class="media">
						<select class="form-control" style="width: 100%;" name="role" id="role" required>
							<option value="">=== Pilih Role ===</option>
							@foreach($roles as $role)
							  <option value="{{$role->id}}">{{ $role->name }}</option>
							@endforeach
						</select>
					</div>
	            </div>
                <div class="box-body">                    
       				<div class="media">
       					<div class="pull-right">
                            <div id="msg"></div>
                        </div>
                        <div class="dd" id="nestable">
					        {!! $menu !!}
				    	</div>
				    	@if($menu === null)
				            <div class="alert alert-danger">No results found</div>
				        @endif 
				    </div>
                </div>
            </div>
    </div>
    <div class="row">
    <form action="{{ url('/menu/list/store') }}" method="POST" enctype="multipart/form-data">
      {{ csrf_field() }}
        <div class="col-md-5">
        	<div class="box box-solid">
	            <div class="box-header with-border">
	              <h3 class="box-title">New Menu</h3>

	              <div class="box-tools">
	                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
	                </button>
	              </div>
	            </div>
	            <div class="box-body">
	            	<div class="form-group">	                    
	                    <div class="input-group">
	                        <span class="input-group-addon"><i class="fa fa-list"></i></span>
	                        <input type="text" class="form-control" name="name" placeholder="Enter Category Name">
	                    </div>
	                </div>
					<div class="form-group">	                    
	                    <div class="input-group">
	                        <span class="input-group-addon"><i class="fa fa-list"></i></span>
	                        <input type="text" class="form-control" name="display" placeholder="Enter Display Name">
	                    </div>
	                </div>
	                <div class="form-group">
	                	<div class="input-group">
	                		<span class="input-group-addon"><i class="fa fa-list"></i></span>                  
			                <select class="form-control" name="parents" id="parents">
                              <option value="0" disable="true" selected="true">=== All Categories ===</option>
                              <option value="0">Root</option>
                                @foreach ($categories as $value)
                                  <option value="{{$value->id}}">{{ $value->display }}</option>
                                @endforeach
                            </select>
	                  	</div>
	                </div>
	                <div class="form-group">	                    
	                    <div class="input-group">
	                        <span class="input-group-addon"><i class="fa fa-info"></i></span>
	                        <input type="text" class="form-control" name="icon" value="-">
	                    </div>
	                </div>           
	                <div class="form-group">	                    
	                    <div class="input-group">
	                        <span class="input-group-addon"><i class="fa fa-globe"></i></span>
	                        <input type="text" class="form-control" name="url" value="/">
	                    </div>
	                </div>
	                <div class="form-group">
	                	<div class="input-group">
							<label><input type="checkbox" name="active" value="1"> Active </label>
	                	</div>
	                </div>                
	            </div>
	            <!-- /.box-body -->
                <div class="box-footer with-border">
                    <button class="btn btn-success" type="submit" id="add">
                        <span class="fa fa-save"></span> Save
                  </button>
                </div>
          	</div>            
        </div>
    </form>
	</div>

	<!-- Edit Modal -->
	@foreach ($categories as $item)
	<div id="Edit-{{$item->id}}" class="modal fade" role="dialog">
	  <div class="modal-dialog modal-lg">
	    
	    <form class="form-horizontal" role="form" method="POST" action="{{ url('/menu/list/update', $item->id) }}">
	      {{ csrf_field() }}
	    
	    <!-- Modal content-->
	    <div class="modal-content">

	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title">Edit Data</h4>
	      </div>
	            
	      <div class="modal-body">
	        
	            <label for="name" class="control-label">Name</label>                    
	            <div class="input-group">
	                <span class="input-group-addon"><i class="fa fa-list"></i></span>
	                <input type="text" class="form-control" name="name" value="{{$item->title}}">
	            </div>
	            <div class="input-group">
	                <span class="input-group-addon"><i class="fa fa-list"></i></span>
	                <input type="text" class="form-control" name="display" value="{{$item->display}}">
	            </div>
	            <label for="name" class="control-label">Parent</label>
	            <div class="input-group">
	                <span class="input-group-addon"><i class="fa fa-list"></i></span>                  
	                <select class="form-control" name="parents" id="parents">
	                    <?php $roll = [];                                  
	                      $roll[] = $item->id_parent;
	                    ?>
	                  <option value="0" disable="true" selected="true">Root</option>
	                  <option value="0">Root</option>
	                        @foreach ($categories as $value)
	                            @if(in_array($value->id, $roll))
	                            <option value="{{ $value->id }}" selected="true">{{ $value->display }}</option>
	                            @else
	                            <option value="{{$value->id}}">{{ $value->display }}</option>
	                            @endif
	                        @endforeach
	                </select>
	            </div>

	            <label for="icon" class="control-label">Icon</label>                    
	            <div class="input-group">
	                <span class="input-group-addon"><i class="fa fa-list"></i></span>
	                <input type="text" class="form-control" name="icon" value="{{$item->icon}}">
	            </div>

	            <label for="url" class="control-label">URL</label>                    
	            <div class="input-group">
	                <span class="input-group-addon"><i class="fa fa-list"></i></span>
	                <input type="text" class="form-control" name="url" value="{{$item->url}}">
	            </div>
	            
            	<div class="input-group">					
						@if($item->status==1)						
						    <label><input type="checkbox" name="active" value="1" checked="true" > Active </label>		
						@else						
						    <label><input type="checkbox" name="active" value="0"> Active </label>
						@endif					
            	</div>                
	                       
	        </div>

	      <div class="modal-footer">
	         <button class="btn btn-success" type="submit" id="edit">
	            <span class="fa fa-save"></span> Save
	          </button>
	        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times"></span>Cancel</button>
	      </div>
	    
	    </div>
	    </form>
	    
	  </div>
	</div>
	@endforeach

	<!--Delete Modal-->
	@foreach ($categories as $item)
	<div id="Hapus-{{$item->id}}" class="modal fade" role="dialog">
	  <div class="modal-dialog">
	    <form class="form-horizontal" role="form" method="GET" action="{{ url('/menu/list/delete', $item->id) }}">
	      {{ csrf_field() }}
	    <!-- Modal content-->
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title">Delete Data {{$item->id}}</h4>
	      </div>
	      <div class="modal-body">
	        <p>Name  : <b>{{$item->display}}</b>, are you sure to delete ?</p>
	      </div>
	      <div class="modal-footer">
	        <button class="btn btn-success" type="submit" id="hapus">
	            <span class="fa fa-trash"></span> Delete
	          </button>
	        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times"></span>Cancel</button>
	      </div>
	    </div>
	  </form>
	  </div>
	</div> 
	@endforeach

@endsection

@section('js') 
<!--script>
 $(document).ready(function() {    
    
});
</script-->
<!--script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script-->
<script type="text/javascript">
	$(document).ready(function () {

		$('#notification').show().delay(4000).fadeOut(700);
		
		$("select[name='role']").on('change', function(e) {     
		  var role = $(this).val();      
		  //alert(role);
		})
		
		// publish settings
		$(".publish").bind("click", function (e) {
			var id = $(this).attr('id');
			var role = $('#role option:selected').val();
			e.preventDefault();

			$.ajax({
				type: "POST",
				url: "{!! url('/menu/list/" + id + "/" + role + "/') !!}",
				//url: "{!! url('/menu/list/" + id + "/') !!}",
				headers: {
					'X-CSRF-Token': $('meta[name="_token"]').attr('content')
				},
				success: function (response) {
					if (response['result'] == 'success') {
						var imagePath = (response['changed'] == 1) ? "{!! url('/') !!}/images/publish_16x16.png" : "{!!url('/')!!}/images/not_publish_16x16.png";
						$("#publish-image-" + id).attr('src', imagePath);
					}
				},
				error: function () {
					alert("error");
				}
			});
		});
	});
</script>
<script type="text/javascript">
	$(document).ready(function () {

		var updateOutput = function (e) {
			var list = e.length ? e : $(e.target),
					output = list.data('output');
			if (window.JSON) {

				var jsonData = window.JSON.stringify(list.nestable('serialize'));
				//console.log(window.JSON.stringify(list.nestable('serialize')));
				//alert(jsonData);
				$.ajax({
					type: "POST",
					url: "{!! URL::route('menu.save') !!}",
					//url: "{!! url('/menu/management/save') !!}",
					data: {'getData': jsonData},
					headers: {
						'X-CSRF-Token': $('meta[name="_token"]').attr('content')
					},
					success: function (response) {

						//$("#msg").append('<div class="alert alert-success msg-save">Saved!</div>');
						$("#msg").append('<div class="msg-save" style="float:right; color:red;">Saving!</div>');
						$('.msg-save').delay(1000).fadeOut(500);
					},
					error: function () {
						alert('error');
					}
				});

			} else {
				alert('error');
			}
		};

		$('#nestable').nestable({
			group: 1
		}).on('change', updateOutput);
	});
</script>
@endsection