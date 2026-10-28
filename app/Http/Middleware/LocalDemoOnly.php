<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LocalDemoOnly
{
    public function handle(Request $request, Closure $next)
    {
        abort_unless(app()->environment('local') && config('iches-auth.demo_mode')
            && in_array($request->getHost(), ['iches-unisya.test', 'localhost', '127.0.0.1'], true)
            && in_array($request->ip(), ['127.0.0.1', '::1'], true), 404);

        return $next($request);
    }
}
