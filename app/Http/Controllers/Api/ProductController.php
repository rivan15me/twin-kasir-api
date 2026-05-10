<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('products')->where('is_active', 1);
        if ($request->menu_id)    $query->where('menu_id', $request->menu_id);
        if ($request->best_seller) $query->where('is_best_seller', 1);
        return response()->json(['data' => $query->orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string',
            'price'   => 'required|numeric|min:0',
            'menu_id' => 'required|string',
        ]);

        DB::table('products')->insert([
            'id'               => $request->id ?? ('p' . now()->timestamp),
            'menu_id'          => $request->menu_id,
            'name'             => $request->name,
            'description'      => $request->description ?? '',
            'price'            => $request->price,
            'discount_pct'     => $request->discount_pct ?? 0,
            'operational_cost' => $request->operational_cost ?? 0, // FIX: kolom ini hilang sebelumnya
            'is_best_seller'   => $request->is_best_seller ?? 0,
            'emoji'            => $request->emoji ?? '📦',
            'image_path'       => $request->image_path ?? '',
            'is_active'        => 1,
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        return response()->json(['message' => 'Product created'], 201);
    }

    public function setPromo(Request $request, $id)
    {
        DB::table('products')->where('id', $id)->update([
            'discount_pct'   => $request->discount_pct ?? 0,
            'is_best_seller' => $request->is_best_seller ?? 0,
            'updated_at'     => now(),
        ]);
        return response()->json(['message' => 'Promo updated']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'  => 'required|string',
            'price' => 'required|numeric|min:0',
        ]);

        DB::table('products')->where('id', $id)->update([
            'name'             => $request->name,
            'description'      => $request->description ?? '',
            'price'            => $request->price,
            'menu_id'          => $request->menu_id,
            'discount_pct'     => $request->discount_pct ?? 0,
            'operational_cost' => $request->operational_cost ?? 0, // FIX: update pun perlu kolom ini
            'is_best_seller'   => $request->is_best_seller ?? 0,
            'image_path'       => $request->image_path ?? '',
            'updated_at'       => now(),
        ]);

        return response()->json(['message' => 'Product updated']);
    }

    public function destroy($id)
    {
        DB::table('products')->where('id', $id)->delete();
        return response()->json(['message' => 'Product deleted']);
    }
}
