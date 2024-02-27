<tr>
	<td>{{$j++}}</td>
	<td>{{ $sub_parent->account_number }}</td>
	<td>{{ $sub_parent->account_name }}</td>
	@include('accountgroup.delete')
	<td>						
		<a id="Delete-{{$sub_parent->id}}" data-target="#Delete-{{$sub_parent->id}}" data-toggle="modal" class="btn btn-sm btn-danger">
			<i class="fa fa-trash" title="{!!trans('account.delete')!!}"></i>
		</a>
	</td>
</tr>
@if ($sub_parent->items)
    <tr>
        @if(count($sub_parent->items) > 0)
            @foreach ($sub_parent->items as $key => $childs)
                @include('accountgroup.child', ['sub_parent' => $childs])
            @endforeach
        @endif
    </tr>
@endif