<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->period ?? 'harian';
        $now = now();

        switch ($period) {
            case 'mingguan': $start = $now->copy()->subDays(28); break;
            case 'bulanan':  $start = $now->copy()->startOfYear(); break;
            case 'tahunan':  $start = $now->copy()->subYears(4)->startOfYear(); break;
            default:         $start = $now->copy()->subDays(7);
        }

        $totalRevenue = DB::table('orders')
            ->where('created_at', '>=', $start)
            ->sum('paid_amount');

        $totalOrders = DB::table('orders')
            ->where('created_at', '>=', $start)
            ->count();

        $totalCustomers = DB::table('customers')->count();
        $pendingCount   = DB::table('orders')
            ->where('payment_status', '!=', 'lunas')
            ->where('remaining', '>', 0)
            ->count();

        return response()->json([
            'total_revenue'   => $totalRevenue,
            'total_orders'    => $totalOrders,
            'total_customers' => $totalCustomers,
            'pending_count'   => $pendingCount,
        ]);
    }
}
