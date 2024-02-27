@extends('layouts.app')
@section('content')
	<div class="box">
		<div class="box-header">
		<form method="post" action="{{URL::to('report/member/print')}}" enctype="multipart/form-data">
		{{ csrf_field() }}
			<div class="form-group col-sm-4">
				<select class="input select2 select2-hidden-accessible" style="width:100%;" aria-hidden="true" name="fStatus" id="fStatus">
					<option value="">{{trans('general.choice')}} {{trans('general.customer')}}</option>
					<option value="LUNAS">Lunas</option>
					<option value="BELUM LUNAS">Belum Lunas</option>
					<option value="MACET">Macet</option>
				</select>
			</div>
			<div class="form-group col-sm-4">				
				<button type="submit" formtarget="_blank" class="btn btn-default" ><i class="fa fa-search"></i></button>
				<!--input name="submit" type="button" value="History" onclick="location.href='installment/printPdf/' + document.getElementById('customer').value" target="_blank" />
				<a href="{{URL::to('report/installment/printPdf/') }}" class="btn btn-default" target="_blank" id="btnSearch">
					<i class="fa fa-search"></i>
				</a-->				
			</div>
		</form>
		</div>
		<div class="box-body">
		</div>
	</div>
@endsection