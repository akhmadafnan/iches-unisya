<?php

use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\StoredFileDownloadController;
use App\Http\Controllers\WorkspaceController;
use App\Http\Middleware\AuthPrivacy;
use App\Http\Middleware\LocalDemoOnly;
use App\Http\Middleware\ValidateWorkspace;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Foundation', ['task' => 'ICH-S0-01'])->name('home');

Route::middleware(AuthPrivacy::class)->group(function (): void {
    Route::middleware('guest')->group(function (): void {
        Route::get('/login', fn () => Inertia\Inertia::render('auth/Login', ['status' => session('status')]))->name('login');
        Route::post('/auth/request', [SessionController::class, 'request'])->middleware('throttle:auth-request');
        Route::post('/auth/verify-code', [SessionController::class, 'verify'])->middleware('throttle:auth-code');
        Route::get('/auth/magic', fn () => Inertia\Inertia::render('auth/Magic'));
        Route::get('/auth/magic/{token}', [SessionController::class, 'capture'])->middleware('throttle:auth-magic');
        Route::post('/auth/magic', [SessionController::class, 'magic'])->middleware('throttle:auth-magic');
        Route::middleware(LocalDemoOnly::class)->group(function (): void {
            Route::inertia('/dev/login', 'auth/Demo');
            Route::post('/dev/login', [SessionController::class, 'demo'])->middleware('throttle:auth-demo');
        });
    });
    Route::middleware(['auth', ValidateWorkspace::class])->group(function (): void {
        Route::get('/files/{storedFile}/download', StoredFileDownloadController::class)
            ->middleware('signed')->name('files.download');
        Route::get('/dashboard', [SessionController::class, 'dashboard'])->name('dashboard');
        Route::post('/logout', [SessionController::class, 'logout']);
        Route::get('/me/workspaces', [WorkspaceController::class, 'index']);
        Route::post('/me/workspace', [WorkspaceController::class, 'store']);
        Route::get('/workspace/events/{event}', [WorkspaceController::class, 'event']);
        Route::get('/workspace/events/{event}/institutions/{institution}', [WorkspaceController::class, 'institution']);
    });
});
