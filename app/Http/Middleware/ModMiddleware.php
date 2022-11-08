<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ModMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (backpack_user()->type >= 1) {
            return redirect("admin");
        }
        return $next($request);
    }
}
