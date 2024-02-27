@extends('layouts.app')
@section('content')
@include('error.error-notification')
<style type="text/css">
    h2{
        text-align: center;
        font-size:22px;
        margin-bottom:50px;
    }
    body{
        background:#f2f2f2;
    }
    .section{
        margin-top:150px;
        padding:50px;
        background:#fff;
    }
</style> 

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.7/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>	

	<div class="box">
	<form method="post" action="{{route('document.store')}}" enctype="multipart/form-data">
	{{ csrf_field() }}
		<div class="box-header">
		</div>
		<div class="box-body">
			<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="form-group col-sm-4">
					<label>{{trans('loan.document_name')}}</label>
					<input class="form-control" value="{{$getID}}" name="customer_id" type="hidden">
					<input class="form-control" value="{{$regNumber}}" name="reg_number" type="hidden">
					<input class="form-control" placeholder="Document Name" name="document_name" type="text" required>
				</div>
				<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<label for="city">{{trans('loan.document_category')}}</label>
					<select class="form-control" id="document_category" name="document_category" required>
						<option value="0">Please select</option>
						<option value="home">Home</option>
						<option value="identity">Identity</option>
						<option value="letter">Letter</option>
						<option value="image">Image</option>
					</select>
				</div>
			</div>
			<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<label for="image" class="control-label">Documents</label>
				<div class="file-loading">
					<input id="image-file" type="file" name="avatars[]" multiple>
				</div>
			</div>
		</div>
		<div class="box-footer">							
			<button type="submit" class="btn btn-primary">{{trans('general.submit')}}</button>
			<span class="new-button">
				<a href="{{ route('document')}}" class="btn btn-danger">
					<span class="cil-close"></span> {{trans('general.close')}}
				</a>
			</span>
		</div>
	</form>
	</div>
@endsection

@section('js')

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.7/js/fileinput.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.7/themes/fa/theme.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" type="text/javascript"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" type="text/javascript"></script>
<script type="text/javascript">
	$("#image-file").fileinput({
		theme: 'fa',
		showUpload: false,
		uploadUrl: "{{route('document.store')}}",
		uploadExtraData: function() {
			return {
				_token: "{{ csrf_token() }}",
			};
		},
		allowedFileExtensions: ['jpg', 'png', 'gif','jpeg','gif','bmp','doc','xls','pdf','docx'],
		overwriteInitial: false,
		maxFileSize:1024,		
		maxFilesNum: 10,
		fileActionSettings:{
			showUpload: false,
			showZoom: false
		}
	});
</script>
@endsection