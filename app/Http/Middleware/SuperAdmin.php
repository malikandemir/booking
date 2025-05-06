<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SuperAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || !$request->user()->isSuperAdmin()) {
            abort(403, 'Access denied.');
        }

        return $next($request);
    }
}
