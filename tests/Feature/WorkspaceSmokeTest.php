<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class WorkspaceSmokeTest extends TestCase
{
    public function test_workspace_and_scoped_endpoints_require_login_without_touching_database(): void
    {
        config(['app.key' => 'base64:'.base64_encode(random_bytes(32))]);
        DB::shouldReceive('connection')->never();
        foreach (['/me/workspaces', '/workspace/events/unknown', '/workspace/events/unknown/institutions/unknown'] as $path) {
            $this->get($path)->assertRedirect('/login');
        }
        $this->post('/me/workspace', ['workspace' => 'personal'])->assertRedirect('/login');
    }

    public function test_workspace_switch_requires_csrf(): void
    {
        config(['app.key' => 'base64:'.base64_encode(random_bytes(32))]);
        DB::shouldReceive('connection')->never();
        $this->app->instance('env', 'local');
        $this->post('/me/workspace', ['workspace' => 'personal'])->assertStatus(419);
    }
}
