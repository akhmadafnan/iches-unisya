<?php

namespace Tests\Feature;

use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Inertia\Testing\AssertableInertia as Assert;
use PHPUnit\Framework\AssertionFailedError;
use Tests\TestCase;

class FoundationSmokeTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Ephemeral test key; never use local credentials or connect to a database.
        config(['app.key' => 'base64:'.base64_encode(random_bytes(32))]);
        config(['inertia.testing.page_paths' => [resource_path('js/pages')]]);
        config(['iches-public.event_slug' => null]);
        DB::shouldReceive('connection')->never();
    }

    public function test_home_returns_the_public_preparation_page(): void
    {
        $this->withoutVite()->get('/')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('public/Home')
                ->missing('cms')
                ->missing('auth'));
    }

    public function test_inertia_navigation_returns_the_page_payload(): void
    {
        $headers = ['X-Inertia' => 'true', 'X-Requested-With' => 'XMLHttpRequest'];
        $version = app(HandleInertiaRequests::class)->version(Request::create('/'));

        if ($version !== null) {
            $headers['X-Inertia-Version'] = $version;
        }

        $this->get('/', $headers)
            ->assertOk()
            ->assertHeader('X-Inertia', 'true')
            ->assertJsonPath('component', 'public/Home')
            ->assertJsonMissingPath('props.cms');
    }

    public function test_unknown_route_returns_not_found(): void
    {
        $this->get('/foundation-route-that-does-not-exist')->assertNotFound();
    }

    public function test_missing_inertia_component_is_rejected(): void
    {
        Route::inertia('/missing-component', 'MissingFoundationComponent');
        $response = $this->withoutVite()->get('/missing-component')->assertOk();

        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Inertia page component file [MissingFoundationComponent] does not exist.');

        $response->assertInertia(fn (Assert $page) => $page->component('MissingFoundationComponent'));
    }
}
