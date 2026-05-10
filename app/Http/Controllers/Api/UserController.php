<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = DB::table('users')
            ->select('id', 'username', 'full_name', 'role', 'created_at')
            ->orderByRaw("CASE WHEN role = 'superAdmin' THEN 0 ELSE 1 END")
            ->orderBy('full_name')
            ->get();
        return response()->json(['data' => $users]);
    }

    public function update(Request $request, $id)
    {
        $data = [];
        if ($request->full_name) $data['full_name'] = $request->full_name;
        if ($request->password)  $data['password']  = Hash::make($request->password);
        if ($request->role)      $data['role']       = $request->role;
        if (!empty($data)) {
            $data['updated_at'] = now();
            DB::table('users')->where('id', $id)->update($data);
        }
        return response()->json(['message' => 'User updated']);
    }

    public function destroy($id)
    {
        DB::table('users')->where('id', $id)->delete();
        return response()->json(['message' => 'User deleted']);
    }
}
