<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        foreach (['auth-request' => [10, 'email'], 'auth-code' => [30, 'code'], 'auth-magic' => [30, 'link'], 'auth-demo' => [5, 'email']] as $name => [$attempts, $field]) {
            RateLimiter::for($name, fn (Request $request) => Limit::perMinutes(15, $attempts)->by($request->ip())
                ->response(function (Request $request, array $headers) use ($field) {
                    $message = 'Too many attempts. Please try again in '.($headers['Retry-After'] ?? 900).' seconds.';

                    return $request->header('X-Inertia')
                        ? back()->withErrors([$field => $message])->withHeaders($headers)
                        : response($message, 429, $headers);
                }));
        }
    }
}
