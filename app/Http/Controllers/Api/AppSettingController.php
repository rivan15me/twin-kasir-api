<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppSettingController extends Controller
{
    public function show($key)
    {
        $setting = DB::table('app_settings')->where('key', $key)->first();
        return response()->json(['value' => $setting?->value]);
    }

    public function upsert(Request $request)
    {
        DB::table('app_settings')->updateOrInsert(
            ['key' => $request->key],
            ['value' => $request->value, 'updated_at' => now()]
        );
        return response()->json(['message' => 'Setting saved']);
    }
}