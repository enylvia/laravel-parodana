<?php $count = 1; ?>
@forelse($transactions as $key => $transaction)
<tr>
	<td> {{$count++}}</td>
	<td>{{ date('d-m-Y', strtotime($transaction->date_time))}}</td>
	<td>{{$transaction->transaction_type}}</td>
	<td align="right">Rp. {{ number_format($transaction->amount, 0, ',' , '.') }}</td>
	<td>{{$transaction->description}}</td>
	<td>Rp. {{ number_format($transaction->beginning_balance, 0, ',' , '.') }}</td>
	<td>Rp. {{ number_format($transaction->ending_balance, 0, ',' , '.') }}</td>
	<td>{{$transaction->created_by}}</td>
	<td>
		@if($transaction->approval_status == 0)
			<span class="label label-warning">Pending</span>
			<a href="#" class="btn btn-success btn-sm" style="display:none;">
				<span class="fa fa-file"></span> {{trans('general.journal')}}
			</a>
		@endif
		@if($transaction->approval_status == 1)
			<span class="label label-success">Approve</span>
		@endif
	</td>							
	<td>{{$transaction->approved_by}}</td>
	<td>
		@if($transaction->journal == 1 AND $transaction->approval_status == 0)
			<a href="#" class="btn btn-success btn-sm" style="display:none;">
				<span class="fa fa-file"></span> {{trans('general.journal')}}
			</a>
		@endif
		@if($transaction->journal == 0)
			<a href="{{ url('operational/journal/'.$transaction->transaction_no) }}" class="btn btn-success btn-sm">
				<span class="fa fa-file"></span> {{trans('general.journal')}}
			</a>
		@endif
	</td>
	@role('superadmin','pengawas','manager')
	<td>
		@if($transaction->approval_status == 0)
		<a href="{{ url('operational/approve/'.$transaction->transaction_no) }}" class="btn btn-success btn-sm">
			<span class="fa fa-thumbs-up"></span> {{trans('general.approved')}}
		</a>
		@endif
		@if($transaction->approval_status == 1)
		<a href="#" class="btn btn-danger btn-sm">
			<span class="fa fa-thumbs-down"></span>
		</a>
		@endif
	</td>
	@endrole							
	<td>							
		@include('operational.edit')
		@if($transaction->journal == 1)
		<a id="#" data-target="#Edit-{{$transaction->transaction_no}}" data-toggle="modal" class="btn btn-sm btn-info" style="display:none;">
			<i class="fa fa-edit" title="{{trans('general.edit')}}"></i>
		</a>
		<!--a href="{{ URL::to('transaction/type/create') }}" class="btn btn-success btn-sm">
			<span class="cil-note-add"></span> {{trans('general.new')}}
		</a-->
		@endif
		@if($transaction->journal == 0)
		<a id="Edit-{{$transaction->transaction_no}}" data-target="#Edit-{{$transaction->transaction_no}}" data-toggle="modal" class="btn btn-sm btn-info">
			<i class="fa fa-edit" title="{{trans('general.edit')}}"></i>
		</a>
		@endif
	</td>
	<td>
		@include('operational.delete')
		@if($transaction->journal == 1)
		<a id="#" data-target="#Delete-{{$transaction->transaction_no}}" data-toggle="modal" class="btn btn-sm btn-danger" style="display:none;">
			<i class="fa fa-trash" title="{{trans('general.delete')}}"></i>
		</a>
		@endif
		@if($transaction->journal == 0)
		<a id="Delete-{{$transaction->transaction_no}}" data-target="#Delete-{{$transaction->transaction_no}}" data-toggle="modal" class="btn btn-sm btn-danger">
			<i class="fa fa-trash" title="{{trans('general.delete')}}"></i>
		</a>
		@endif
	</td>
	<td>
		<a title="Journal" id="{{$transaction->transaction_no}}" class="journal" href="#">																													
			<img id="publish-image-{{$transaction->transaction_no}}" src="{{asset($transaction->journal!='' ?'images/publish_16x16.png':'images/not_publish_16x16.png')}}" style="height: 20px; width:20px;">
		</a>
	</td>
</tr>
@empty
<tr>
	<td colspan="7">Data not found</td>
</tr>
@endforelse