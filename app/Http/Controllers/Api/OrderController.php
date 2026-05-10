<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;  

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('orders')
            ->leftJoin('customers', 'orders.customer_id', '=', 'customers.id')
            ->select('orders.*', 'customers.name as customer_name');

        if ($request->start_date) $query->where('orders.created_at', '>=', $request->start_date);
        if ($request->end_date)   $query->where('orders.created_at', '<=', $request->end_date);

        return response()->json(['data' => $query->orderByDesc('orders.created_at')->get()]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id'             => 'required|string',
            'total'          => 'required|numeric',
            'payment_method' => 'required|string',
        ]);

        DB::table('orders')->insert([
            'id'              => $request->id,
            'customer_id'     => $request->customer_id,
            'admin_id'        => $request->admin_id,
            'admin_name'      => $request->admin_name ?? '',
            'subtotal'        => $request->subtotal ?? 0,
            'discount'        => $request->discount ?? 0,
            'tax'             => $request->tax ?? 0,
            'total'           => $request->total,
            'paid_amount'     => $request->paid_amount ?? $request->total,
            'remaining'       => $request->remaining ?? 0,
            'payment_type'    => $request->payment_type ?? 'lunas',
            'payment_status'  => $request->payment_status ?? 'lunas',
            'due_date'        => $request->due_date ?: null,
            'notes'           => $request->notes ?? '',
            'payment_method'  => $request->payment_method,
            'payment_reference' => $request->payment_reference ?? '',
            'payment_id'      => $request->payment_id ?? '',
            'service_date'    => $request->service_date ?? '',
            'rental_days'     => $request->rental_days ?? 1,
            'payment_proof'   => $request->payment_proof ?? '',   // ← BARU
            'invoice_path'    => $request->invoice_path ?? '',    // ← BARU
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        // Insert items
        foreach ($request->items ?? [] as $item) {
            DB::table('order_items')->insert([
                'order_id'     => $request->id,
                'product_id'   => $item['product_id'],
                'product_name' => $item['product_name'],
                'menu_name'    => $item['menu_name'] ?? '',
                'quantity'     => $item['quantity'],
                'unit_price'   => $item['unit_price'],
                'subtotal'     => $item['subtotal'],
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }

        // Add points to customer
        if ($request->customer_id && ($request->paid_amount ?? 0) > 0) {
            $pts = intval($request->paid_amount / 10000);
            if ($pts > 0) {
                DB::table('customers')
                    ->where('id', $request->customer_id)
                    ->increment('total_points', $pts);
                DB::table('point_transactions')->insert([
                    'customer_id' => $request->customer_id,
                    'order_id'    => $request->id,
                    'points'      => $pts,
                    'type'        => 'earn',
                    'note'        => 'Earn dari order #' . $request->id,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }
        }

        return response()->json(['message' => 'Order created', 'id' => $request->id], 201);
    }

    public function pending()
    {
        $orders = DB::table('orders')
            ->leftJoin('customers', 'orders.customer_id', '=', 'customers.id')
            ->select('orders.*', 'customers.name as customer_name')
            ->where('orders.payment_status', '!=', 'lunas')
            ->where('orders.remaining', '>', 0)
            ->orderBy('orders.due_date')
            ->get();
        return response()->json(['data' => $orders]);
    }

    public function settle(Request $request, $id)
    {
        $request->validate(['amount' => 'required|numeric|min:0']);
        $order = DB::table('orders')->where('id', $id)->first();
        if (!$order) return response()->json(['message' => 'Order not found'], 404);

        $newPaid      = $order->paid_amount + $request->amount;
        $newRemaining = max(0, $order->total - $newPaid);
        $newStatus    = $newRemaining <= 0 ? 'lunas' : 'dp';

        $updateData = [
            'paid_amount'    => $newPaid,
            'remaining'      => $newRemaining,
            'payment_status' => $newStatus,
            'updated_at'     => now(),
        ];

        // Simpan bukti pembayaran jika ada (FIX: field ini sebelumnya tidak dikirim)
        if ($request->payment_proof) {
            $updateData['payment_proof'] = $request->payment_proof;
        }
        if ($newStatus === 'lunas') {
            $updateData['payment_type'] = 'lunas';
        }

        DB::table('orders')->where('id', $id)->update($updateData);
        return response()->json(['message' => 'Order settled']);
    }

    public function items($id)
    {
        $items = DB::table('order_items')
            ->where('order_id', $id)
            ->get();
        return response()->json(['data' => $items]);
    }

    public function uploadInvoice(Request $request, $id)
    {
        $request->validate(['invoice' => 'required|file|mimes:pdf|max:10240']);

        $file = $request->file('invoice');
        $path = $file->storeAs('public/invoices', "Invoice_{$id}.pdf");
        $url  = url(Storage::url($path));  // ← FIX: Storage sekarang sudah di-import

        DB::table('orders')->where('id', $id)
            ->update(['invoice_path' => $url, 'updated_at' => now()]);

        return response()->json(['url' => $url]);
    }
}