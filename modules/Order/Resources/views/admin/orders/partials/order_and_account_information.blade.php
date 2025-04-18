<div class="order-information-wrapper">
    <div class="order-information-buttons">
        <a href="{{ route('admin.orders.print.show', collect([])) }}" class="btn btn-default" target="_blank"
            data-toggle="tooltip" title="{{ trans('order::orders.print') }}">
            <i class="fa fa-print" aria-hidden="true"></i>
        </a>
    </div>

    <h4 class="section-title">{{ trans('order::orders.order_and_account_information') }}</h4>

    <div class="row">
        <div class="col-md-6">
            <div class="order clearfix">
                <h5>{{ trans('order::orders.order_information') }}</h5>

                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>{{ trans('order::orders.order_id') }}</td>
                                <td>1500</td>
                            </tr>
                            <tr>
                                <td>{{ trans('order::orders.order_date') }}</td>
                                <td>{{ \Carbon\Carbon::parse('2025-04-16T07:24:47.000000Z')->toFormattedDateString() }}</td>
                            </tr>

                            <tr>
                                <td>{{ trans('order::orders.order_status') }}</td>
                                <td>
                                    <div class="row">
                                        <div class="col-lg-9 col-md-10 col-sm-10">
                                            <select id="order-status" class="form-control custom-select-black"
                                                data-id="1500">
                                                @foreach (trans('order::statuses') as $name => $label)
                                                    <option value="{{ $name }}"
                                                        {{ $name === 'pending' ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>{{ trans('order::orders.shipping_method') }}</td>
                                <td>Free Shipping</td>
                            </tr>

                            <tr>
                                <td>{{ trans('order::orders.payment_method') }}</td>
                                <td>
                                    Bank Transfer
                                    <br>
                                    <span style="color: #999; font-size: 13px;">
                                        Bank Name: Lorem Ipsum.
                                         <br>
                                        Beneficiary Name: John Doe.
                                         <br>
                                        Account Number/IBAN: 123456789
                                         <br>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>{{ trans('order::orders.order_note') }}</td>
                                <td>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eius velit possimus eaque voluptatem, autem porro laudantium odit. Nam dolorem, eligendi, soluta mollitia aperiam ab fugit odit impedit doloremque esse quidem?</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="account-information">
                <h5>{{ trans('order::orders.account_information') }}</h5>

                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>{{ trans('order::orders.customer_name') }}</td>
                                <td>Demo Admin</td>
                            </tr>

                            <tr>
                                <td>{{ trans('order::orders.customer_email') }}</td>
                                <td>admin@email.com</td>
                            </tr>

                            <tr>
                                <td>{{ trans('order::orders.customer_phone') }}</td>
                                <td>12345678</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
