<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('expenses');
        if ($request->from) $query->where('tanggal', '>=', $request->from);
        if ($request->to)   $query->where('tanggal', '<=', $request->to);
        return response()->json(['data' => $query->orderByDesc('tanggal')->get()]);
    }

    public function store(Request $request)
    {
        DB::table('expenses')->insert([
            'id'          => $request->id ?? ('exp-' . now()->timestamp),
            'tanggal'     => $request->tanggal,
            'kategori'    => $request->kategori ?? 'Lain-lain',
            'keterangan'  => $request->keterangan,
            'jumlah'      => $request->jumlah,
            'image_path'  => $request->image_path ?? '',
            'created_by'  => $request->created_by ?? '',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
        return response()->json(['message' => 'Expense saved'], 201);
    }

    public function update(Request $request, $id)
    {
        DB::table('expenses')->where('id', $id)->update([
            'tanggal'    => $request->tanggal,
            'kategori'   => $request->kategori,
            'keterangan' => $request->keterangan,
            'jumlah'     => $request->jumlah,
            'updated_at' => now(),
        ]);
        return response()->json(['message' => 'Expense updated']);
    }

    public function destroy($id)
    {
        DB::table('expenses')->where('id', $id)->delete();
        return response()->json(['message' => 'Expense deleted']);
    }
}