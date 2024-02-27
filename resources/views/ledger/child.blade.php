@foreach($children as $child)
	<div class="box-group" id="accordion-{{$child->id}}">
		
		<div class="panel box box-success">		
			<div class="box-header with-border">
				<h4 class="box-title">
				  <a data-toggle="collapse" data-parent="#accordion-{{$child->id}}" href="#{{$child->id}}" class="collapsed" aria-expanded="false">
					{{$child->account_number}} | {{$child->account_name}}
				  </a>
				  <a href="{{ url('ledger/'.$child->id) }}" class="btn btn-md btn-default"><i class="fa fa-search" title="{{$child->account_name}}"></i></a>
				</h4>
			</div>
			
			<div id="{{$child->id}}" class="panel-collapse collapse" aria-expanded="false">
			@if(count($child->children))
				<div class="box-body">
					@include('ledger.child',['children' => $child->children])
				</div>
			@endif
			</div>
					
		</div>
		
	</div>
@endforeach