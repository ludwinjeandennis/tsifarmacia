<?php

namespace App\Http\Services;

use App\Models\Pharmacy;

class RevenueService
{
    public function calcRevenue(Pharmacy $pharmacy){
        $total = \Illuminate\Support\Facades\DB::table('orders')
            ->join('medicines_orders', 'orders.id', '=', 'medicines_orders.order_id')
            ->join('medicines', 'medicines_orders.medicine_id', '=', 'medicines.id')
            ->where('orders.pharmacy_id', $pharmacy->id)
            ->whereIn('orders.status', [5, 6]) // Assuming 5 and 6 are Delivered/Confirmed based on context
            ->sum(\Illuminate\Support\Facades\DB::raw('medicines.price * medicines_orders.quantity'));

        $ordersCount = $pharmacy->orders()->whereIn('status', [5, 6])->count();

        return [
            "Total_Orders" => $ordersCount,
            "Total_Revenue" => $total
        ];
    }

}
