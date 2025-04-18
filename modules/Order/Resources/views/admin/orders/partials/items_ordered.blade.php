<div class="items-ordered-wrapper">
    <h4 class="section-title">{{ trans('order::orders.items_ordered') }}</h4>

    <div class="row">
        <div class="col-md-12">
            <div class="items-ordered">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{ trans('order::orders.product') }}</th>
                                <th>{{ trans('order::orders.unit_price') }}</th>
                                <th>{{ trans('order::orders.quantity') }}</th>
                                <th>{{ trans('order::orders.line_total') }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>
                                    <a href="{{ route('admin.products.edit', 136) }}">
                                        Hot Mens Parka Coats Men Winter Warm Hooded
                                    </a>
                                    <br>
                                    {{-- Variation --}}
                                    <span>
                                        Size: <span>L</span>
                                    </span>
                                    <span>
                                        Color: <span>Blue</span>
                                    </span>
                                </td>
                                <td>750.000đ</td>
                                <td>2</td>
                                <td>1.500.000đ</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
