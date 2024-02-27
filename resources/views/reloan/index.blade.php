@extends('layouts.app')
@section('content')
	
	<div class="box">
		<div class="box-header">
			<ul class="nav nav-pills">
				<li class="nav-item"><a class="nav-link active" href="#customer" data-toggle="tab">{{trans('loan.customer')}}</a></li>
				<li class="nav-item"><a class="nav-link" href="#contract" data-toggle="tab">{{trans('loan.contract')}}</a></li>
				<li class="nav-item"><a class="nav-link" href="#approve" data-toggle="tab">{{trans('loan.approve')}}</a></li>
			</ul>
		</div>
		<div class="box-body">
			<div class="tab-content">
				<div class="tab-pan active" id="customer">
						
					<div class="box-header">
						<strong>{{trans('loan.personal_data')}}</strong> 
						<small>{{trans('loan.form')}}</small>
					</div>
					
					<div class="box-body">
						<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
							<label for="customer">{{trans('loan.customer')}}</label>									
							<input name="customer_name" class="form-control" id="customer_name" type="text" >
						</div>
						
					</div>
					
					<div class="box-footer">
					
					</div>
						
				</div>
				
				<div class="tab-pane" id="contract">
					<div class="box-header">
						<strong>{{trans('loan.contract')}}</strong> 
						<small>{{trans('loan.form')}}</small>
					</div>
					
					<div class="box-body">
					
						<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
							<label for="customer">{{trans('loan.amount')}}</label>									
							<input type="text" name="amount" class="form-control" id="amount">
						</div>
						
					</div>
					
					<div class="box-footer">
					
					</div>
				</div>
				
				<div class="tab-pane" id="approve">
					<div class="box-header">
						<strong>{{trans('loan.approve')}}</strong> 
						<small>{{trans('loan.form')}}</small>
					</div>
					
					<div class="box-body">
									
						
					</div>
					
					<div class="box-footer">
					
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('js')
<script>
	//redirect to specific tab
	$(document).ready(function () {
	$('#tabMenu a[href="#{{ old('tab') }}"]').tab('show')
	});
</script>
@endsection