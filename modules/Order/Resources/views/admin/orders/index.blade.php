@extends('admin::layout')

@component('admin::components.page.header')
    @slot('title', trans('order::orders.orders'))

    <li class="active">{{ trans('order::orders.orders') }}</li>
@endcomponent

@section('content')
    <div class="box box-primary">
        <div class="box-body index-table" id="orders-table">
            @component('admin::components.table')
                @slot('thead')
                    <tr>
                        <th>{{ trans('admin::admin.table.id') }}</th>
                        <th style="width: 24%;">
                            <a href="{{ route('admin.orders.show', 1500) }}">
                                {{ trans('order::orders.table.customer_name') }}
                            </a>
                        </th>
                        <th style="width: 24%;">{{ trans('order::orders.table.customer_email') }}</th>
                        <th>{{ trans('admin::admin.table.status') }}</th>
                        <th>{{ trans('order::orders.table.total') }}</th>
                        <th data-sort>{{ trans('admin::admin.table.created') }}</th>
                    </tr>
                @endslot

                @slot('tbody')
                @foreach ($orders as $order)
                    <tr class="clickable-row">
                        <td class="dt-type-numeric">{{ $order->id }}</td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order->id) }}">{{ $order->customer_full_name }}</a>
                        </td>
                        <td>{{ $order->customer_email }}</td>
                        <td><span class="badge badge-info">{{ ucfirst($order->status) }}</span></td>
                        <td class="dt-type-numeric">{{ number_format($order->total) }}đ</td>
                        <td class="sorting_1">
                            <span data-toggle="tooltip" title="{{ $order->created_at->format('M d, Y') }}">
                                {{ $order->created_at->diffForHumans() }}
                            </span>
                        </td>
                    </tr>
                @endforeach
                @endslot
            @endcomponent
        </div>
    </div>
@endsection

@push('scripts')
    <script type="module">

    </script>
@endpush
