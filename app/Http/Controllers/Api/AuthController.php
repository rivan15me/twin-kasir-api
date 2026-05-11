<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Nama pengguna atau kata sandi salah.'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => [
                'id'         => $user->id,
                'username'   => $user->username,
                'full_name'  => $user->full_name,
                'role'       => $user->role,
                'photo_path' => $user->photo_path ?? '',
            ],
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'username'  => 'required|string|min:3|unique:users',
            'password'  => 'required|string|min:8',
            'full_name' => 'required|string',
            'role'      => 'in:admin,superAdmin',
        ]);

        // BUG FIX: password harus di-hash manual karena $casts 'hashed'
        // kadang tidak aktif di versi Laravel tertentu saat create()
        $user = User::create([
            'name'       => $request->full_name,
            'username'   => $request->username,
            'email'      => $request->username . '@local.twin',
            'password'   => Hash::make($request->password),
            'full_name'  => $request->full_name,
            'role'       => $request->role ?? 'admin',
            'photo_path' => '',
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => [
                'id'         => $user->id,
                'username'   => $user->username,
                'full_name'  => $user->full_name,
                'role'       => $user->role,
                'photo_path' => '',
            ],
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:8',
        ]);

        $user = $request->user();

        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json(['message' => 'Kata sandi lama tidak sesuai.'], 422);
        }

        $user->update(['password' => Hash::make($request->new_password)]);
        $user->tokens()->delete();
        $newToken = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Kata sandi berhasil diperbarui.',
            'token'   => $newToken,
        ]);
    }

    public function resetPassword(Request $request, $userId)
    {
        $caller = $request->user();
        if ($caller->role !== 'superAdmin') {
            return response()->json(['message' => 'Akses ditolak. Hanya Super Admin.'], 403);
        }

        $request->validate(['new_password' => 'required|string|min:6']);

        $target = User::find($userId);
        if (!$target) {
            return response()->json(['message' => 'User tidak ditemukan.'], 404);
        }

        $target->update(['password' => Hash::make($request->new_password)]);
        $target->tokens()->delete();

        return response()->json(['message' => 'Kata sandi berhasil direset.']);
    }
}