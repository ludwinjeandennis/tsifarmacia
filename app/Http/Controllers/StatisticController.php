<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticController extends Controller
{

    /**
    * Display a listing of the resource.
    */
    public function index()
    {
        // Agrupar usuarios por género usando base de datos
        $genderData = User::select('gender', DB::raw('count(*) as total'))
            ->groupBy('gender')
            ->get();
        
        $gender = [];
        foreach($genderData as $item) {
            $gender[$item->gender] = $item->total;
        }

        // Ingresos por mes (Optimizado con DB::raw)
        $monthlyRevenueData = DB::table('orders')
            ->join('medicines_orders', 'orders.id', '=', 'medicines_orders.order_id')
            ->join('medicines', 'medicines_orders.medicine_id', '=', 'medicines.id')
            ->whereIn('orders.status', [5, 6])
            ->whereYear('orders.created_at', date('Y'))
            ->select(
                DB::raw('MONTH(orders.created_at) as month'),
                DB::raw('SUM(medicines.price * medicines_orders.quantity) as total')
            )
            ->groupBy('month')
            ->get();

        $revenue = [];
        $totalRevenue = 0;
        foreach ($monthlyRevenueData as $data) {
            $monthName = Carbon::create()->month($data->month)->locale('es')->monthName;
            $revenue[ucfirst($monthName)] = $data->total;
            $totalRevenue += $data->total;
        }

        // Top usuarios por número de órdenes (Optimizado con Join)
        $topUsers = Order::select('user_id', DB::raw('COUNT(orders.id) as count'), 'users.name as user_name')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->whereIn('status', [5, 6])
            ->groupBy('user_id', 'users.name')
            ->orderByDesc('count')
            ->limit(10)
            ->get();
            
        $totalOrders = Order::whereIn('status', [5, 6])->count();
        $totalClients = User::role('client')->count();

        return view('statistics.index', [
            'gender' => $gender,
            'revenue' => $revenue,
            'topUsers' => $topUsers,
            'totalRevenue' => $totalRevenue,
            'totalOrders' => $totalOrders,
            'totalClients' => $totalClients
        ]);
    }
}
