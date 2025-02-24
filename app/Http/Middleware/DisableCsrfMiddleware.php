<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DisableCsrfMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->is('users')) {
            Session::forget('csrf_token');
        }

        return $next($request);
    }
}
