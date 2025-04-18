<?php

namespace Modules\Order\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Order;

class OrderStatusController extends Controller
{
    public function show()
    {
        return view("{$this->viewPath}.show");
    }
}
