<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && in_array($user->role, ['admin', 'staff'])) {
            return $next($request);
        }

        abort(403, 'Bạn không có quyền truy cập.');
    }
}
