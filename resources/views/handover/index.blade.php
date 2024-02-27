@extends('layouts.app')
@section('content')
    @include('error.error-notification')

    <div class="box">
        <div class="box-header">

        </div>
        <div class="box-body">

            <form class="form-inline" action="{{ route('handover') }}" method="GET">
                <div class="form-group">
                    <div class="input-group">

                        <input type="text" class="form-control" id="exampleInputAmount" placeholder="Searching.."
                            name="search" autocomplete="off">

                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Search</button>
            </form>


            <table class="table table-responsive-sm table-striped">
                <thead>
                    <tr>
                        <th>{{ trans('general.reg_number') }}</th>
                        <th>{{ trans('general.name') }}</th>
                        <th>{{ trans('general.mobile_phone') }}</th>
                        <th>{{ trans('general.address') }}</th>
                        <th class="text-center" colspan="3">{{ trans('general.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $customer)
                        <tr>
                            <td>{{ $customer->reg_number }}</td>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->mobile_phone }}</td>
                            <td>{{ $customer->address }}</td>
                            {{-- <td style="width:2px;" align="center">
                                <a href="{{ url('customer/handover/create/' . $customer->reg_number) }}"
                                    class="btn btn-success">
                                    <i class="fa fa-plus" title="{{ trans('general.new') }}"></i>
                                </a>
                            </td> --}}
                            <td style="width:2px;" align="center">
                                <a href="{{ url('customer/handover/edit/' . $customer->reg_number) }}"
                                    class="btn btn-info">
                                    <i class="fa fa-edit" title="{{ trans('general.edit') }}"></i>
                                </a>
                            </td>
                            <td>
                                <a href="{{ url('customer/handover/print/' . $customer->reg_number) }}"
                                    class="btn btn-default" target="_blank">
                                    <i class="fa fa-print" title="{{ trans('general.print') }}"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{-- {{ $customer->appends(request()->except('page'))->links() }} --}}
        </div>
    </div>
@endsection
