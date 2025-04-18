<div class="order-totals-wrapper">
    <div class="row">
        <div class="order-totals pull-right">
            <div class="table-responsive">
                <table class="table">
                    <tbody>
                        <tr>
                            <td>{{ trans('order::orders.subtotal') }}</td>
                            <td class="text-right">1.500.000đ</td>
                        </tr>
                        <tr>
                            <td>{{ trans('order::orders.shipping') }}</td>
                            <td class="text-right">0</td>
                        </tr>
                        <tr>
                            <td>{{ trans('order::orders.coupon') }} (<span class="coupon-code">#FJFDS44</span>)</td>
                            <td class="text-right">&#8211;500.000đ</td>
                        </tr>
                        <tr>
                            <td>{{ trans('order::orders.total') }}</td>
                            <td class="text-right">1.000.000đ</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
