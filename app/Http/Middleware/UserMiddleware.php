<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login'); // not logged in → login page
        }

        if (auth()->user()->is_admin) {
            return redirect('/admin')->with('error', 'Access denied.'); // admin → filament
        }

        return $next($request);
    }
}
