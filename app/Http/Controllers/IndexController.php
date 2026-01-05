<?php

namespace App\Http\Controllers;

use App\Http\Resources\RevenueResource;
use App\Http\Services\RevenueService;
use App\Models\Order;
use App\Models\Pharmacy;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected RevenueService $revenueService)
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        if(auth()->user()->hasRole("admin")){
            $dashboardData=$this->getAdminDashboardData();
        }
        if(auth()->user()->hasRole("pharmacy")){
            $dashboardData=$this->getPharmacyDashboardData();
        }
        return view('index',$dashboardData??[]);
    }


    public function getAdminDashboardData()
    {
        $total_orders = Order::count();
        $new_orders = Order::where("status", 1)->count();
        $clients = User::role('client')->count();

        // Calculate total revenue across all pharmacies in one query
        $sumRevenues = \Illuminate\Support\Facades\DB::table('orders')
            ->join('medicines_orders', 'orders.id', '=', 'medicines_orders.order_id')
            ->join('medicines', 'medicines_orders.medicine_id', '=', 'medicines.id')
            ->whereIn('orders.status', [5, 6])
            ->sum(\Illuminate\Support\Facades\DB::raw('medicines.price * medicines_orders.quantity'));

        // Monthly Revenue (Current Year) - Optimized query
        $monthlyRevenueRaw = \Illuminate\Support\Facades\DB::table('orders')
            ->join('medicines_orders', 'orders.id', '=', 'medicines_orders.order_id')
            ->join('medicines', 'medicines_orders.medicine_id', '=', 'medicines.id')
            ->whereIn('orders.status', [5, 6])
            ->whereYear('orders.created_at', date('Y'))
            ->select(
                \Illuminate\Support\Facades\DB::raw('MONTH(orders.created_at) as month'),
                \Illuminate\Support\Facades\DB::raw('SUM(medicines.price * medicines_orders.quantity) as total')
            )
            ->groupBy('month')
            ->get();
            
        $monthlyRevenue = array_fill(1, 12, 0);
        foreach ($monthlyRevenueRaw as $data) {
            $monthlyRevenue[$data->month] = (float)$data->total;
        }

        // Recent Orders
        $recentOrders = Order::with(['user', 'pharmacy'])->latest()->take(6)->get();

        // Top Medicines
        $topMedicines = \Illuminate\Support\Facades\DB::table('medicines_orders')
            ->join('medicines', 'medicines_orders.medicine_id', '=', 'medicines.id')
            ->select('medicines.name', \Illuminate\Support\Facades\DB::raw('SUM(medicines_orders.quantity) as total_qty'))
            ->groupBy('medicines.id', 'medicines.name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        return [
            "total_orders"=>$total_orders,
            "new_orders"=>$new_orders,
            "clients"=>$clients,
            "sumRevenues"=> $sumRevenues,
            "monthlyRevenue" => array_values($monthlyRevenue),
            "recentOrders" => $recentOrders,
            "topMedicines" => $topMedicines
        ];
    }


    public function getPharmacyDashboardData(){
        $pharmacy = auth()->user()->pharmacy;
        if(!$pharmacy) return [];

        $sumRevenues = $this->revenueService->calcRevenue($pharmacy)["Total_Revenue"];
        $total_orders = $pharmacy->orders()->count();
        $new_orders = $pharmacy->orders()->where("status", 1)->count();
        $clients = Order::where("pharmacy_id", $pharmacy->id)
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->join('model_has_roles', function($join) {
                $join->on('users.id', '=', 'model_has_roles.model_id')
                     ->where('model_has_roles.model_type', '=', User::class);
            })
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('roles.name', 'client')
            ->distinct("user_id")
            ->count('user_id');

        // Monthly Revenue (Pharmacy Specific) - Optimized query
        $monthlyRevenueRaw = \Illuminate\Support\Facades\DB::table('orders')
            ->join('medicines_orders', 'orders.id', '=', 'medicines_orders.order_id')
            ->join('medicines', 'medicines_orders.medicine_id', '=', 'medicines.id')
            ->where('orders.pharmacy_id', $pharmacy->id)
            ->whereIn('orders.status', [5, 6])
            ->whereYear('orders.created_at', date('Y'))
            ->select(
                \Illuminate\Support\Facades\DB::raw('MONTH(orders.created_at) as month'),
                \Illuminate\Support\Facades\DB::raw('SUM(medicines.price * medicines_orders.quantity) as total')
            )
            ->groupBy('month')
            ->get();

        $monthlyRevenue = array_fill(1, 12, 0);
        foreach ($monthlyRevenueRaw as $data) {
            $monthlyRevenue[$data->month] = (float)$data->total;
        }

        // Recent Orders
        $recentOrders = $pharmacy->orders()->with(['user'])->latest()->take(6)->get();

        // Top Medicines (Pharmacy Specific)
        $topMedicines = \Illuminate\Support\Facades\DB::table('medicines_orders')
            ->join('medicines', 'medicines_orders.medicine_id', '=', 'medicines.id')
            ->join('orders', 'medicines_orders.order_id', '=', 'orders.id')
            ->where('orders.pharmacy_id', $pharmacy->id)
            ->select('medicines.name', \Illuminate\Support\Facades\DB::raw('SUM(medicines_orders.quantity) as total_qty'))
            ->groupBy('medicines.id', 'medicines.name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        return [
            "total_orders"=>$total_orders,
            "new_orders"=>$new_orders,
            "clients"=>$clients,
            "sumRevenues"=> $sumRevenues,
            "monthlyRevenue" => array_values($monthlyRevenue),
            "recentOrders" => $recentOrders,
            "topMedicines" => $topMedicines
        ];
    }
}




