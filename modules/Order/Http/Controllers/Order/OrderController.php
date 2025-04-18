<?php

namespace Modules\Order\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Order;

class OrderController extends Controller
{
    protected $model = Order::class;

    protected $viewPath = 'order::admin.orders';

    public function index()
    {
        $orders = Order::all();
        return view("{$this->viewPath}.index", compact('orders'));
    }


    public function show()
    {
        return view("{$this->viewPath}.show");
    }
}
