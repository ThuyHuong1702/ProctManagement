<!DOCTYPE html>
<html lang="{{ locale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ trans('order::print.invoice') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['modules/Order/Resources/assets/admin/sass/print.scss'])
</head>

<body class="ltr" dir="ltr">
    <!--[if lt IE 8]>
        <p>You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a>
            to improve your experience.</p>
        <![endif]-->

    <div class="container">
        <div class="invoice-wrapper clearfix">
            <div class="row">
                <div class="invoice-header clearfix">
                    <div class="col-md-3">
                        <div class="store-name">
                            <h1>FleetCart</h1>
                        </div>
                    </div>

                    <div class="col-md-9 clearfix">
                        <div class="invoice-header-right pull-right">
                            <span class="title">{{ trans('order::print.invoice') }}</span>

                            <div class="invoice-info clearfix">
                                <div class="invoice-id">
                                    <label for="invoice-id">{{ trans('order::print.invoice_id') }}:</label>
                                    <span>#1500</span>
                                </div>

                                <div class="invoice-date">
                                    <label for="invoice-date">{{ trans('order::print.date') }}:</label>
                                    <span>{{\Carbon\Carbon::parse('2025-04-16T07:24:47.000000Z')->format('Y / m / d') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="invoice-body clearfix">
                <div class="invoice-details-wrapper">
                    <div class="row">
                        <div class="col-md-6 col-sm-6">
                            <div class="invoice-details">
                                <h5>{{ trans('order::print.order_details') }}</h5>

                                <div class="table-responsive">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td>{{ trans('order::print.email') }}:</td>
                                                <td>admin@email.com</td>
                                            </tr>

                                            <tr>
                                                <td>{{ trans('order::print.phone') }}:</td>
                                                <td>12345678</td>
                                            </tr>

                                            <tr>
                                                <td>{{ trans('order::print.shipping_method') }}:</td>
                                                <td>Free Shipping</td>
                                            </tr>

                                            <tr>
                                                <td>{{ trans('order::print.payment_method') }}:</td>
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
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-sm-6">
                            <div class="invoice-address">
                                <h5>{{ trans('order::print.billing_address') }}</h5>

                                <span>Demo Admin</span>
                                <span>Savar</span>
                                <span>Dhaka, Dhaka 1344</span>
                                <span>BangOccaecat neque ex ex, North Carolina 74211</span>
                                <span>United Statesladesh</span>
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-6">
                            <div class="invoice-address">
                                <h5>{{ trans('order::print.shipping_address') }}</h5>

                                <span>Demo Admin</span>
                                <span>Savar</span>
                                <span>Dhaka, Dhaka 1344</span>
                                <span>BangOccaecat neque ex ex, North Carolina 74211</span>
                                <span>United Statesladesh</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="cart-list">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>{{ trans('order::print.product') }}</th>
                                        <th>{{ trans('order::print.unit_price') }}</th>
                                        <th>{{ trans('order::print.quantity') }}</th>
                                        <th>{{ trans('order::print.line_total') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        <span>Hot Mens Parka Coats Men Winter Warm Hooded</span>
                                        <br>
                                        <div class="option">
                                             <span>
                                                Size: <span>L</span>
                                            </span>
                                            <span>
                                                Color: <span>Blue</span>
                                            </span>
                                        </div>

                                    </td>
                                    <td>750.000đ</td>
                                    <td>2</td>
                                    <td>1.500.000đ</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="total pull-right">
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
    </div>

    <script type="module">
        window.print();
    </script>
</body>

</html>
