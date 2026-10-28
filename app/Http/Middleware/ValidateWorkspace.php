<?php

namespace App\Http\Middleware;

use App\Services\Workspaces;
use Closure;
use Illuminate\Http\Request;

class ValidateWorkspace
{
    public function handle(Request $request, Closure $next)
    {
        app(Workspaces::class)->current($request);

        return $next($request);
    }
}
