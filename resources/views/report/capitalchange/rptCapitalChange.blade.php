@extends('layouts.app')
@section('content')

	<div class="box">
		<div class="box-header">
			{{trans('general.capital_change')}}
		</div>
		<div class="box-body">
			{!! Form::open(['url' => 'report/capital/change/print/', 'method' => 'get', 'class' => 'form-inline text-center']) !!}
                <div class="form-group">
                    <label for="name">Bulan</label>
                    {!! Form::selectMonth('bulan', null, ['class' => 'form-control', 'placeholder' => '-- Bulan --']) !!}
                </div>
                <div class="form-group">
                    <label for="name">Tahun</label>
                    {!! Form::selectRange('tahun', 2018, 2050, null, ['class' => 'form-control', 'placeholder' => '-- Tahun --']) !!}
                    <div class="form-group">
                        <button type="submit" class="btn btn-default btn-md">{{trans('general.search')}}</button>
                    </div>
                </div>
			{!! Form::close() !!}
            <br>
            <h4>Total Data : <strong>{{$total_neraca}}</strong> </h4>
                <table class="table table-striped text-center">
                    <caption class="text-center"> <strong>LAPORAN PERUBAHAN MODAL</strong></caption>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Waktu</th>
                        <th class="text-center">Action</th>
                    </tr>
                    <?php $i = 1 ?>
                    @foreach($daftar_neraca as $data)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ date('F Y', strtotime('1-'.$data->waktu)) }}</td>
                        <td>
                          <!--a href="{{ url('neraca/saldo/detail/'.date('Y-m-d', strtotime('1-'.$data->waktu))) }}" class="btn btn-info">
                            Detail
                          </a-->
                          <a href="{{ url('report/capital/change/print/'.date('Y-m-d', strtotime('1-'.$data->waktu))) }}" class="btn btn-warning" target="_blank">
                            Cetak
                          </a>
						</td>
                    </tr>
                    @endforeach

                </table>
		</div>
	</div>

@endsection