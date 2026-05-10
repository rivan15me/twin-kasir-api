<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class MenuCategoryController extends Controller
{
    public function index()
    {
        $data = DB::table('menu_categories')
                  ->orderBy('sort_order')
                  ->get();
        return response()->json(['data' => $data]);
    }
}