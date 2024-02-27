@extends('layouts.app')
@section('content')

	<div class="box">
		<div class="box-header">
			Baki
		</div>
		<div class="box-body">
            <form action="{{route('transaction.report.baki')}}" method="get">
                @csrf
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="date">Start Date</label>
                            <input type="date" class="form-control" name="start_date">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="date">End Date</label>
                            <input type="date" class="form-control" name="end_date">
                        </div>
                    </div>
                </div>
            <button type="submit" class="btn btn-success">Submit</button>
            </form>
		</div>
	</div>

@endsection