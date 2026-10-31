<?php

use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\CmsBackofficeController;
use App\Http\Controllers\CmsLifecycleController;
use App\Http\Controllers\CmsMediaController;
use App\Http\Controllers\EventScheduleController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\PublicCmsMediaController;
use App\Http\Controllers\PublicCmsPageController;
use App\Http\Controllers\PublicHomeController;
use App\Http\Controllers\StoredFileDownloadController;
use App\Http\Controllers\WorkspaceController;
use App\Http\Middleware\AuthPrivacy;
use App\Http\Middleware\LocalDemoOnly;
use App\Http\Middleware\ValidateWorkspace;
use Illuminate\Support\Facades\Route;

Route::get('/', PublicHomeController::class)->name('home');

Route::get('/events/{event:slug}/pages/{page:slug}', PublicCmsPageController::class)
    ->where(['event' => '[a-z0-9]+(?:-[a-z0-9]+)*', 'page' => '[a-z0-9]+(?:-[a-z0-9]+)*'])
    ->name('public.cms.page');

Route::get('/events/{event}/pages/{page}/sections/{section}/media/{media}', PublicCmsMediaController::class)
    ->where(['event' => '[a-z0-9]+(?:-[a-z0-9]+)*', 'page' => '[a-z0-9]+(?:-[a-z0-9]+)*'])
    ->whereUuid('section')->whereUuid('media')->name('public.cms.media');

Route::middleware(AuthPrivacy::class)->group(function (): void {
    Route::post('/locale', LocaleController::class)->name('locale');
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
        Route::get('/workspace/events/{event}/cms/media', [CmsMediaController::class, 'index'])->name('cms.media.index');
        Route::post('/workspace/events/{event}/cms/media', [CmsMediaController::class, 'store'])->name('cms.media.store');
        Route::post('/workspace/events/{event}/schedule', EventScheduleController::class)->name('event.schedule');
        Route::get('/backoffice/website/pages', [CmsBackofficeController::class, 'index'])->name('cms.pages');
        Route::get('/backoffice/website/pages/{page}', [CmsBackofficeController::class, 'show'])->name('cms.editor');
        Route::prefix('/workspace/events/{event}/cms/pages')
            ->name('cms.')
            ->group(function (): void {
                Route::post('/', [CmsLifecycleController::class, 'create'])->name('create');
                Route::post('/{page}/draft', [CmsLifecycleController::class, 'draft'])->name('draft');
                Route::get('/{page}/preview', [CmsLifecycleController::class, 'preview'])->name('preview');
                Route::post('/{page}/publish', [CmsLifecycleController::class, 'publish'])->name('publish');
                Route::post('/{page}/restore', [CmsLifecycleController::class, 'restore'])->name('restore');
            });
        Route::get('/files/{storedFile}/download', StoredFileDownloadController::class)->middleware('signed')->name('files.download');
        Route::get('/dashboard', [SessionController::class, 'dashboard'])->name('dashboard');
        Route::post('/logout', [SessionController::class, 'logout']);
        Route::get('/me/workspaces', [WorkspaceController::class, 'index']);
        Route::post('/me/workspace', [WorkspaceController::class, 'store']);
        Route::get('/workspace/events/{event}', [WorkspaceController::class, 'event']);
        Route::get('/workspace/events/{event}/institutions/{institution}', [WorkspaceController::class, 'institution']);
    });
});
