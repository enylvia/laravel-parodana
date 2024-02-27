@extends('layouts.app')
@section('content')
	
	<div class="box">
		<div class="box-header">
			<h3>Detail Buku Besar</h3>
		</div>
		<div class="box-body">
			<table class="table" style="width: 100%;">
				<thead>
					<tr>
						<th>Tanggal</th>
						<th>Debit</th>
						<th>Kredit</th>
						<th>Balance</th>
					</tr>
				</thead>
				<tbody>
					@foreach($group as $g)
					@php
						$balance = 0;
						$totalDebit = 0;
						$totalKredit = 0;
					@endphp
					<thead>
						<tr>
						@foreach($dataDebet as $key => $debet)
						@if($debet->account == $g->account_number)
							<th>{{$debet->type}}</th>
							<th class="text-right" colspan="3">{{number_format($debet->start_balance)}}</th>
							@php
								$balance = $debet->start_balance;
							@endphp
							@break
						@endif
						@endforeach
						</tr>
					</thead>
					@foreach($dataDebet as $key => $debet)
					@if($debet->account == $g->account_number)
					@php
						$totalDebit += $debet->debit;
						$totalKredit += $debet->kredit;
					@endphp
					<tr>
						<td>{{$debet->date_trx}}</td>
						<td>{{number_format($debet->debit)}}</td>
						<td>{{number_format($debet->kredit)}}</td>
						<td>{{number_format($balance = $balance + $debet->debit - $debet->kredit)}}</td>
					</tr>
					@endif
					@endforeach
					<tr>
						@foreach($dataDebet as $key => $debet)
						@if($debet->account == $g->account_number)
						<td><strong>TOTAL</strong></td>
						<td><strong>{{number_format($totalDebit)}}</strong></td>
						<td><strong>{{number_format($totalKredit)}}</strong></td>
						<td><strong>{{number_format($balance)}}</strong></td>
							@break
						@endif
						@endforeach
					</tr>

				@endforeach
				</tbody>
			</table>
		</div>
	</div>
	
@endsection