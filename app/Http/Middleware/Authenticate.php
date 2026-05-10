<?php
namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    protected function redirectTo(Request $request): ?string
    {
        // Kalau request dari API, return null (jangan redirect, kasih 401 JSON)
        return $request->expectsJson() ? null : null;
    }
}