@extends('layouts.app')
@section('content')

@include('error.error-notification')	
	
	<div class="box">
		<div class="box-body">


		<form class="form-inline" action="{{ route('document') }}" method="GET">
                <div class="form-group">
                    <div class="input-group">

                        <input type="text" class="form-control" id="exampleInputAmount" placeholder="Searching.."
                            name="search" autocomplete="off">

                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Search</button>
        </form>


			<div class="table-responsive">
				<table class="table table-responsive-sm table-striped">
					<thead>
						<tr>
							<th>{{trans('general.name')}}</th>
							<th>{{trans('loan.marketing_name')}}</th>
							<th class="text-center" colspan="2">{{trans('general.actions')}}</th>
						</tr>
					</thead>
					<tbody>
						@forelse($documents as $document)
						<!--?php
						  $appoves = App\Models\Customer::where('customer_id',1)->get();
						?-->
						<tr>
							<td>{{$document->name}}</td>
							<td>{{$document->created_by}}</td>
							<td align="center"><a href="{{URL::to('customer/document/edit/'.$document->id)}}" class="btn btn-sm btn-info"><i class="fa fa-edit" title="{{trans('general.edit')}}"></i></a></td>
							<td align="center"><a href="{{URL::to('customer/document/create/'.$document->id)}}" class="btn btn-sm btn-default"><i class="fa fa-upload" title="{{trans('general.upload')}}"></i></a></td>
						</tr>
						@empty
						<tr>
							<td colspan="4">Data Not Found</td>
						</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>
	</div>

@endsection