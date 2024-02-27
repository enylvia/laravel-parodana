@extends('layouts.app')
@section('content')

@include('error.error-notification')
	
	<div class="box">
		<div class="box-body">
			<!-- Implement here -->
			<h4>LAPORAN PINJAMAN BARU</h4>
			<hr>
			<div class="container">
					<div class="row">
							<div class="col-md-6">
                                <form action="{{route('transaction.pinjaman.baru.report')}}" method="get">
                                    <!-- {{ csrf_field() }} -->
                                    <div class="form-group">
                                        <label for="start_date">Tanggal</label>
                                        <input type="date" name="date_trx" id="start_date" class="form-control">
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-primary" id="submit-button">Submit</button>
                                </form>
							</div>
						</div>
					<hr>
				</div>
			</div>
		</div>	
	
@endsection