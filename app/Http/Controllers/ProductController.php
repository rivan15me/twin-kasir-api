<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Ambil semua produk
    public function index() {
        return response()->json(Product::all());
    }

    // Tambah produk baru
    public function store(Request $request) {
        $product = Product::create($request->all());
        return response()->json($product, 201);
    }

    // Ambil 1 produk
    public function show($id) {
        return response()->json(Product::findOrFail($id));
    }

    // Update produk
    public function update(Request $request, $id) {
        $product = Product::findOrFail($id);
        $product->update($request->all());
        return response()->json($product);
    }

    // Hapus produk
    public function destroy($id) {
        Product::findOrFail($id)->delete();
        return response()->json(['message' => 'Produk dihapus']);
    }
}