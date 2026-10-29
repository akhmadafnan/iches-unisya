<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public const SUPPORTED = ['en', 'id', 'ar'];

    public function handle(Request $request, Closure $next)
    {
        $locale = $request->session()->get('locale');
        app()->setLocale(in_array($locale, self::SUPPORTED, true) ? $locale : 'en');

        return $next($request);
    }
}
