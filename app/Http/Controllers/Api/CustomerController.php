<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('customers');
        if ($request->search) {
            $s = '%' . $request->search . '%';
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', $s)
                  ->orWhere('phone', 'like', $s)
                  ->orWhere('email', 'like', $s);
            });
        }
        return response()->json(['data' => $query->orderBy('name')->get()]);
    }

    public function upsert(Request $request)
    {
        $request->validate(['name' => 'required|string']);

        $existing = null;
        if ($request->phone) {
            $existing = DB::table('customers')->where('phone', $request->phone)->first();
        }

        if ($existing) {
            $updateData = [
                'name'        => $request->name,
                'email'       => $request->email       ?? $existing->email,
                'phone2'      => $request->phone2      ?? $existing->phone2,
                'address'     => $request->address     ?? $existing->address,
                'updated_at'  => now(),
            ];
            // Update foto hanya jika dikirim (tidak overwrite kosong)
            if ($request->foto1_path !== null) $updateData['foto1_path'] = $request->foto1_path;
            if ($request->foto2_path !== null) $updateData['foto2_path'] = $request->foto2_path;

            DB::table('customers')->where('id', $existing->id)->update($updateData);
            return response()->json(['id' => (string) $existing->id]);
        }

        $id = $request->id ?? ('c' . now()->timestamp);
        DB::table('customers')->insert([
            'id'           => $id,
            'name'         => $request->name,
            'email'        => $request->email    ?? '',
            'phone'        => $request->phone    ?? '',
            'phone2'       => $request->phone2   ?? '',
            'address'      => $request->address  ?? '',
            'foto1_path'   => $request->foto1_path ?? '',  // ← BARU
            'foto2_path'   => $request->foto2_path ?? '',  // ← BARU
            'total_points' => 0,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);
        return response()->json(['id' => $id], 201);
    }

    public function orders($id)
    {
        $orders = DB::table('orders')
            ->leftJoin('users', 'orders.admin_id', '=', 'users.id')
            ->leftJoin('customers', 'orders.customer_id', '=', 'customers.id')
            ->where('orders.customer_id', $id)
            ->select(
                'orders.*',
                DB::raw('COALESCE(users.full_name, orders.admin_name) as admin_name'),
                'customers.name as customer_name'
            )
            ->orderByDesc('orders.created_at')
            ->get();

        $result = $orders->map(function ($order) {
            $items = DB::table('order_items')
                ->where('order_id', $order->id)
                ->get();
            $orderArr          = (array) $order;
            $orderArr['items'] = $items->toArray();
            return $orderArr;
        });

        return response()->json(['data' => $result]);
    }
    public function points($id)
{
    $points = DB::table('point_transactions')
                ->where('customer_id', $id)
                ->orderByDesc('created_at')
                ->get();
    return response()->json(['data' => $points]);
}
}