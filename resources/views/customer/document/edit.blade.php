@extends('layouts.app')
@section('content')
@include('error.error-notification')
<meta name="_token" content="{!! csrf_token() !!}"/>
<style type="text/css">
    .gallery-title
	{
		font-size: 36px;
		color: #42B32F;
		text-align: center;
		font-weight: 500;
		margin-bottom: 70px;
	}
	.gallery-title:after {
		content: "";
		position: absolute;
		width: 7.5%;
		left: 46.5%;
		height: 45px;
		border-bottom: 1px solid #5e5e5e;
	}
	.filter-button
	{
		font-size: 18px;
		border: 1px solid #42B32F;
		border-radius: 5px;
		text-align: center;
		color: #42B32F;
		margin-bottom: 30px;

	}
	.filter-button:hover
	{
		font-size: 18px;
		border: 1px solid #42B32F;
		border-radius: 5px;
		text-align: center;
		color: #ffffff;
		background-color: #42B32F;

	}
	.btn-default:active .filter-button:active
	{
		background-color: #42B32F;
		color: white;
	}

	.port-image
	{
		width: 100%;
	}

	.gallery_product
	{
		margin-bottom: 30px;
	}	
</style>  

	<div class="box">			
		<div class="box-header">
			<div align="center">
				<button class="btn btn-default filter-button" data-filter="all">All</button>
				<button class="btn btn-default filter-button" data-filter="home">Home</button>
				<button class="btn btn-default filter-button" data-filter="identity">Identity</button>
				<button class="btn btn-default filter-button" data-filter="letter">Letter</button>
				<button class="btn btn-default filter-button" data-filter="image">Image</button>
			</div>
		</div>
		<div class="box-body">
		@foreach($documents as $document)
			<div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter {{$document->document_category}}">
				<a id="View" data-target="#View-{{$document->id}}" data-toggle="modal" class="btn btn-default">
					<img src="{{asset('uploads/documents/' .$document->document_file)}}" class="img-responsive">
				</a>
				<div class="input-button">						
					<button class="deleteRecord btn btn-sm btn-danger" data-id="{{ $document->id }}">
						{{trans('general.delete')}}
					</button>
				</div> 
			</div>				
		@endforeach						
		</div>
		<div class="box-footer">										
			<span class="new-button">
				<a href="{{ route('document')}}" class="btn btn-danger">
					<span class="cil-close"></span> {{trans('general.close')}}
				</a>
			</span>
		</div>
	</div>
	
	@foreach($documents as $document)
	<div id="View-{{$document->id}}" class="modal fade" aria-labelledby="my-modalLabel" aria-hidden="true" tabindex="-1" role="dialog">
		<div class="modal-dialog" data-dismiss="modal">
			<div class="modal-content"  >              
				<div class="modal-body">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<img src="{{asset('uploads/documents/' .$document->document_file)}}" class="img-responsive" style="width: 100%;">
				</div> 
			</div>
		</div>
	</div>
	@endforeach	
	
@endsection

@section('js')

<script>
$(document).ready(function(){

    $(".filter-button").click(function(){
        var value = $(this).attr('data-filter');
        
        if(value == "all")
        {
            //$('.filter').removeClass('hidden');
            $('.filter').show('1000');
        }
        else
        {
//            $('.filter[filter-item="'+value+'"]').removeClass('hidden');
//            $(".filter").not('.filter[filter-item="'+value+'"]').addClass('hidden');
            $(".filter").not('.'+value).hide('3000');
            $('.filter').filter('.'+value).show('3000');
            
        }
    });
    
    if ($(".filter-button").removeClass("active")) {
	$(this).removeClass("active");
	}
	$(this).addClass("active");

});
</script>

<script>
$(".deleteRecord").click(function(){
	
    var id = $(this).data("id");
	var status = confirm("Are you sure you want to delete ?");  
	var page = $(this).attr('href');   	
	
	if(status==true)
	{   
		$.ajax(
		{
			type: 'POST',
			url: '/customer/document/delete/' + id,
			headers: {
				'X-CSRF-Token': $('meta[name="_token"]').attr('content')
			},        
			success: function (response) {
				location.reload(page);
			},
			error: function (response, textStatus, errorThrown) {
				console.log(response);
			}
		});
	} else {
       return false;
	}
   
});
</script>

@endsection