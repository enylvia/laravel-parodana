@extends('layouts.app')
@section('content')

@include('error.error-notification')
	
	<div class="box">
		<div class="box-header">
			Neraca Sistem
		</div>
		<div class="box-body">
			<div class="text-right">
				<a href="{{route('neracasaldo.history',['start_date' => $request->input('start_date'),'end_date' => $request->input('end_date')])}}" class="btn btn-sm btn-primary">Lihat Neraca Saldo</a>
			</div>
			<br>
				<table class="table text-center">
					<thead>
						<tr>
							<th class="text-center" >Sandi</th>
							<th class="text-center" >Uraian</th>
							<th class="text-center" >Saldo Awal</th>
							<th class="text-center" >Mutasi Debet</th>
							<th class="text-center" >Mutasi Kredit</th>
							<th class="text-center" >Saldo Akhir</th>
						</tr>
					</thead>
					<tbody>
					@php
						$balance = 0;
						$totalDebit = 0;
						$totalKredit = 0;
						$saldoAwalA = 0;
						$saldoAwalB = 0;
						$saldoAwalC = 0;
						$saldoAwalD = 0;
						$saldoAkhirA = 0;
						$saldoAkhirB = 0;
						$saldoAkhirC = 0;
						$saldoAkhirD = 0;
						$saldoDebitA = 0;
						$saldoDebitB = 0;
						$saldoDebitC = 0;
						$saldoDebitD = 0;
						$saldoKreditA = 0;
						$saldoKreditB = 0;
						$saldoKreditC = 0;
						$saldoKreditD = 0;
						
					@endphp
					
					@foreach($aktiva as $d)
					@php
						$saldoAwal = $d->start_balance;
						$saldoDebit = $d->debit;
						$saldoKredit = $d->kredit;
						$saldoAkhirs = $saldoAwal + $saldoDebit - $saldoKredit;
					@endphp
					<tr>
						<td>{{$d->account_number}}</td>
						<td>{{$d->type}}</td>
						<td>{{number_format($d->start_balance)}}</td>
						<td>{{number_format($d->debit)}}</td>
						<td>{{number_format($d->kredit)}}</td>
						<td>{{number_format($saldoAkhirs)}}</td>
					</tr>
					@php
					$saldoAwalA += $saldoAwal;
					$saldoAkhirA += $saldoAkhirs;
					$saldoDebitA += $saldoDebit;
					$saldoKreditA += $saldoKredit;
					@endphp
					@endforeach


					@foreach($pasiva as $d)
					@php
						$saldoAwal2 = $d->start_balance;
						$saldoDebit = $d->debit;
						$saldoKredit = $d->kredit;
						$saldoAkhirs = $saldoAwal2 + $saldoKredit  - $saldoDebit;
					@endphp
					<tr>
						<td>{{$d->account_number}}</td>
						<td>{{$d->type}}</td>
						<td>{{number_format($d->start_balance)}}</td>
						<td>{{number_format($d->debit)}}</td>
						<td>{{number_format($d->kredit)}}</td>
						<td>{{number_format($saldoAkhirs)}}</td>
					</tr>
					@php
					$saldoAwalB += $saldoAwal2;
					$saldoAkhirB += $saldoAkhirs;
					$saldoDebitB += $saldoDebit;
					$saldoKreditB += $saldoKredit;
					@endphp
					@endforeach




					@foreach($dataPendapatan as $d)
					@php
						$saldoAwal3 = $d->start_balance;
						$saldoDebit = $d->debit;
						$saldoKredit = $d->kredit;
						$saldoAkhirs = $saldoAwal + $saldoKredit  - $saldoDebit;
					@endphp
					<tr>
						<td>{{$d->account_number}}</td>
						<td>{{$d->type}}</td>
						<td>{{number_format($d->start_balance)}}</td>
						<td>{{number_format($d->debit)}}</td>
						<td>{{number_format($d->kredit)}}</td>
						<td>{{number_format($saldoAkhirs)}}</td>
					</tr>
					@php
					$saldoAwalC += $saldoAwal3;
					$saldoAkhirC += $saldoAkhirs;
					$saldoDebitC += $saldoDebit;
					$saldoKreditC += $saldoKredit;
					@endphp
					@endforeach


					@foreach($biaya as $d)
					@php
						$saldoAwal4 = $d->start_balance;
						$saldoDebit = $d->debit;
						$saldoKredit = $d->kredit;
						$saldoAkhirs = $saldoAwal + $saldoDebit - $saldoKredit;
					@endphp
					<tr>
						<td>{{$d->account_number}}</td>
						<td>{{$d->type}}</td>
						<td>{{number_format($d->start_balance)}}</td>
						<td>{{number_format($d->debit)}}</td>
						<td>{{number_format($d->kredit)}}</td>
						<td>{{number_format($saldoAkhirs)}}</td>
					</tr>
					@php
					$saldoAwalD += $saldoAwal4;
					$saldoAkhirD += $saldoAkhirs;
					$saldoDebitD += $saldoDebit;
					$saldoKreditD += $saldoKredit;
					@endphp
					@endforeach


					<tr>
						<td></td>
						<td><b>TOTAL</b></td>
						<td></td>
						<td> <b> {{number_format($saldoDebitA + $saldoDebitB + $saldoDebitC + $saldoDebitD)}} </b></td>
						<td> <b>{{number_format($saldoKreditA + $saldoKreditB + $saldoKreditC + $saldoKreditD)}}</b></td>
						<td> <b>{{number_format($saldoAkhirA + $saldoAkhirB + $saldoAkhirC + $saldoAkhirD)}}</b></td>
					</tr>
					</tbody>
				</table>
			</div>
	</div>
	
@endsection