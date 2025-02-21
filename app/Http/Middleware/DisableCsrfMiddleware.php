<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DisableCsrfMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Отключаем CSRF защиту для всех API-запросов
        if ($request->is('users')) {
            // Обход защиты CSRF
            Session::forget('csrf_token');
        }

        return $next($request);
    }
}
