<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class CheckUserAccess
{
    public function handle(Request $request, Closure $next)
    {
        $user = session('user_id'); // 👈 You said you store user_id in session

        // 👇 List of allowed routes for user_id = 2
        if ($user == 2) {
            $allowedRoutes = [
                route('box.index'),
                route('box.items'),
                route('box.items.post'),
            ];

            $current = url()->current();

            // If current route not in allowed list, deny access
            if (!in_array($current, $allowedRoutes)) {
                abort(403, 'You are not authorized to access this page.');
            }
        }

        return $next($request);
    }
}
