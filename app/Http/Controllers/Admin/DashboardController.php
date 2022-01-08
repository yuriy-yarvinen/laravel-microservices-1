<?php

namespace App\Http\Controllers\Admin;

use App\Order;
use App\Services\UserService;
use App\Http\Resources\ChartResource;

class DashboardController
{
    public function chart()
    {
        (new UserService())->allows('view', 'orders');

        $orders = Order::query()
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->selectRaw("DATE_FORMAT(orders.created_at, '%Y-%m-%d') as date, sum(order_items.quantity*order_items.product_price) as sum")
            ->groupBy('date')
            ->get();

        return ChartResource::collection($orders);
    }
}
