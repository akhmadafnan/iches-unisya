<?php

namespace Tests\Feature;

use App\Http\Middleware\ValidateWorkspace;
use App\Models\CmsPage;
use App\Models\Event;
use App\Models\RoleAssignment;
use App\Models\User;
use App\Policies\CmsPagePolicy;
use App\Services\Cms\PageSnapshotValidator;
use App\Services\Workspaces;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Inertia\Testing\AssertableInertia as Assert;
use Mockery;
use Tests\TestCase;

class CmsBackofficeUiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['app.key' => 'base64:'.base64_encode(random_bytes(32)), 'inertia.testing.page_paths' => [resource_path('js/pages')]]);
        DB::shouldReceive('connection')->never();
        Schema::swap(Mockery::mock(\Illuminate\Database\Schema\Builder::class));
        $this->withoutVite();
    }

    public function test_read_routes_require_auth_and_workspace_without_database_access(): void
    {
        foreach (['cms.pages', 'cms.editor'] as $name) {
            $route = Route::getRoutes()->getByName($name);
            $this->assertContains('auth', $route->gatherMiddleware());
            $this->assertContains(ValidateWorkspace::class, $route->gatherMiddleware());
            $this->get(route($name, ['page' => 'unknown']))->assertRedirect('/login');
        }
    }

    public function test_personal_workspace_and_demo_admin_email_do_not_grant_cms_access(): void
    {
        Schema::shouldReceive('hasTable')->never();
        $user = (new User)->forceFill(['id' => (string) Str::uuid7(), 'email' => 'admin@iches.test']);
        $this->actingAs($user)->withSession(['workspace' => 'personal']);
        $this->get('/backoffice/website/pages?workspace=assignment:1')->assertForbidden();
        $this->get('/backoffice/website/pages/unknown')->assertForbidden();
    }

    private function workspace(bool $selected = true, bool $eligible = true): Event
    {
        $user = (new User)->forceFill(['id' => (string) Str::uuid7(), 'name' => 'Synthetic admin', 'email' => 'synthetic@iches.test', 'password' => 'never-share']);
        $event = (new Event)->forceFill(['id' => (string) Str::uuid7(), 'name' => 'Synthetic event']);
        $grant = (new RoleAssignment)->forceFill(['id' => 1, 'role_key' => 'superadmin', 'institution_id' => null]);
        $grant->setRelation('event', $event);
        $workspaces = Mockery::mock(Workspaces::class)->makePartial();
        $query = Mockery::mock(Builder::class);
        $workspaces->shouldReceive('selected')->with($user, 'assignment:1')->andReturn($query);
        $query->shouldReceive('exists')->andReturn($selected);
        $query->shouldReceive('whereNull')->with('institution_id')->andReturnSelf();
        $query->shouldReceive('where')->with('role_key', 'superadmin')->andReturnSelf();
        $query->shouldReceive('with')->with('event')->andReturnSelf();
        $query->shouldReceive('forEvent')->with($event)->andReturnSelf();
        $query->shouldReceive('lockForUpdate')->andReturnSelf();
        $query->shouldReceive('first')->andReturn($eligible ? $grant : null);
        $workspaces->shouldReceive('choices')->with($user)->andReturn([
            ['key' => 'assignment:1', 'label' => 'Synthetic event', 'role' => 'superadmin', 'event' => $event->name, 'institution' => null],
        ]);
        $this->app->instance(Workspaces::class, $workspaces);
        $this->actingAs($user)->withSession(['workspace' => 'assignment:1']);

        return $event;
    }

    public function test_revoked_or_ineligible_selected_grant_is_denied_before_schema_queries(): void
    {
        Schema::shouldReceive('hasTable')->never();
        $this->workspace(false);
        $this->get('/backoffice/website/pages')->assertForbidden();
        $this->workspace(true, false);
        $this->get('/backoffice/website/pages')->assertForbidden();
    }

    public function test_authorized_missing_schema_returns_safe_inertia_state_in_all_locales(): void
    {
        $event = $this->workspace();
        Schema::shouldReceive('hasTable')->with('cms_pages')->andReturnFalse();
        foreach (['en', 'id', 'ar'] as $locale) {
            $this->withSession(['locale' => $locale]);
            foreach (['/backoffice/website/pages', '/backoffice/website/pages/unknown'] as $url) {
                $this->get($url)->assertOk()->assertHeader('Cache-Control', 'no-store, private')
                    ->assertHeader('X-Robots-Tag', 'noindex, nofollow, noarchive')
                    ->assertInertia(fn (Assert $page) => $page->component('cms/Pages')->where('schemaAvailable', false)
                        ->where('pages', null)->where('cmsEvent', ['id' => $event->id, 'name' => $event->name])
                        ->where('locale', $locale)->where('activeWorkspace', 'assignment:1')
                        ->where('account', ['name' => 'Synthetic admin', 'email' => 'synthetic@iches.test'])
                        ->missing('snapshot')->missing('storage_key')->missing('account.password')->missing('catalog'));
            }
        }
    }

    public function test_search_and_pagination_are_validated_after_authorization(): void
    {
        $this->workspace();
        Schema::shouldReceive('hasTable')->never();
        foreach ([['search' => ['bad']], ['search' => str_repeat('a', 121)], ['page' => 0], ['page' => 'bad']] as $input) {
            $this->getJson('/backoffice/website/pages?'.http_build_query($input))->assertUnprocessable();
        }
    }

    public function test_catalog_exposes_only_existing_validator_metadata_and_is_not_mutable(): void
    {
        $validator = new PageSnapshotValidator;
        $catalog = $validator->catalog();
        $this->assertSame(['editorial-copy', 'hero', 'event-essentials', 'countdown', 'cta'], array_keys($catalog));
        $this->assertSame(['default', 'wide'], $catalog['editorial-copy']['variants']);
        $this->assertSame(['default', 'split'], $catalog['hero']['variants']);
        $this->assertSame(['label' => 'text', 'href' => 'url'], $catalog['cta']['fields']);
        $missing = ['status' => 'missing', 'content' => null];
        foreach ($catalog as $key => $definition) {
            foreach ($definition['variants'] as $variant) {
                $content = array_map(fn ($type) => $type === 'url' ? '/info' : 'Synthetic text', $definition['fields']);
                $snapshot = ['schema_version' => 1, 'locales' => ['en' => $missing, 'id' => $missing, 'ar' => $missing],
                    'sections' => [['id' => (string) Str::uuid7(), 'component_key' => $key, 'variant' => $variant, 'position' => 0, 'visible' => true,
                        'locales' => ['en' => ['status' => 'ready', 'content' => $content], 'id' => $missing, 'ar' => $missing], 'media_ids' => [],
                        'bindings' => $key === 'countdown' ? [['source' => 'event', 'field' => 'starts_at']] : []]]];
                $this->assertSame($snapshot, $validator->validate($snapshot));
            }
        }
        $catalog['cta']['fields']['script'] = 'text';
        $this->assertArrayNotHasKey('script', $validator->catalog()['cta']['fields']);
        $this->assertInstanceOf(CmsPagePolicy::class, Gate::getPolicyFor(CmsPage::class));
    }
}
