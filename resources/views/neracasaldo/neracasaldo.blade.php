@extends('layouts.app')
@section('content')

@include('error.error-notification')
	
	<div class="box">
		<div class="box-header">
			Neraca Saldo
		</div>
		<div class="box-body">
			<div class="text-right">
				<a href="{{route('neracasaldo.saveToBalanceHistory',['start_date' => $request->input('start_date'),'end_date' => $request->input('end_date')])}}" class="btn btn-primary">Simpan Transaksi Bulanan</a>
				<i>
					<p><small>Note: Simpan setiap 1 bulan untuk riwayat saldo</small></p>
				</i>
			</div>
				<table class="table text-center">
					<thead>
						<tr>
							<th class="text-center" >Sandi</th>
							<th class="text-center" >Uraian</th>
							<th class="text-center" >Mutasi Debet</th>
							<th class="text-center" >Mutasi Kredit</th>
						</tr>
					</thead>
					<tbody>
						@php 
							$sumAktiva = 0;
							$sumBiaya = 0;
							$sumPendapatan = 0;
							$sumPasiva = 0;
							$aktivaMinus = 0;
						@endphp
						@foreach($aktiva as $d)
						<tr>
							<td>{{$d->account_number}}</td>
							<td>{{$d->type}}</td>
							@if ($d->end_balance > 0)
							<td>{{number_format(($d->start_balance + $d->debit) - $d->kredit)}}</td>
							@else
							<td>0</td>
							@endif
							@if ($d->end_balance < 0)
							<td>{{number_format(abs(($d->start_balance + $d->debit) - $d->kredit))}}</td>
							@else
							<td>0</td>
							@endif
						</tr>
						@php 
							if ($d->end_balance > 0) {
								$sumAktiva += (($d->start_balance + $d->debit) - $d->kredit);
							}
							if ($d->end_balance < 0) {
								$aktivaMinus += abs(($d->start_balance + $d->debit) - $d->kredit);
							}
						@endphp
						@endforeach

						@foreach($pasiva as $p) 
						<tr>
							<td>{{$p->account_number}}</td>
							<td>{{$p->type}}</td>
							<td>0</td>
							<td>{{number_format(($p->start_balance + $p->kredit) - $p->debit)}}</td>
						</tr>
						@php 
							$sumPasiva += (($p->start_balance + $p->kredit) - $p->debit);
						@endphp
						@endforeach

						@foreach($dataPendapatan as $dp) 
						<tr>
							<td>{{$dp->account_number}}</td>
							<td>{{$dp->type}}</td>
							<td>0</td>
							<td>{{number_format((abs($dp->kredit) - $dp->debit))}}</td>
						</tr>
						@php 
							$sumPendapatan += ($dp->kredit - $dp->debit);
						@endphp
						@endforeach
						@foreach($biaya as $b) 
						<tr>
							<td>{{$b->account_number}}</td>
							<td>{{$b->type}}</td>
							<td>{{number_format(($b->debit - $b->kredit))}}</td>
							<td>0</td>
						</tr>
						@php 
							$sumBiaya += ($b->debit - $b->kredit);
						@endphp
						@endforeach
						<tr>
							<td></td>
							<td></td>
							<td><b>{{number_format($sumAktiva + $sumBiaya)}}</b></td>
							<td><b>{{number_format($sumPasiva + $sumPendapatan + abs($aktivaMinus))}}</b></td>
						</tr>
					</tbody>
				</table>
			</div>
	</div>
	
@endsection