<?php

namespace Tests\Feature;

use App\Http\Middleware\ValidateWorkspace;
use App\Models\CmsPage;
use App\Models\CmsPageRevision;
use App\Models\CmsPublicationEvent;
use App\Models\Event;
use App\Models\RoleAssignment;
use App\Models\User;
use App\Policies\CmsPagePolicy;
use App\Services\Cms\CmsPublicationLifecycle;
use App\Services\Workspaces;
use Illuminate\Database\ConnectionResolver;
use Illuminate\Database\DatabaseTransactionsManager;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\MySqlConnection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use LogicException;
use Mockery;
use PDO;
use PHPUnit\Framework\Attributes\DataProvider;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class CmsLifecycleSecurityTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['app.key' => 'base64:'.base64_encode(random_bytes(32))]);
        DB::shouldReceive('connection')->never();
        Cache::spy();
    }

    protected function tearDown(): void
    {
        try {
            Cache::shouldNotHaveReceived('flush');
        } finally {
            parent::tearDown();
        }
    }

    public function test_all_five_routes_require_auth_and_workspace_and_guests_never_reach_database(): void
    {
        foreach (['create', 'draft', 'preview', 'publish', 'restore'] as $action) {
            $route = Route::getRoutes()->getByName('cms.'.$action);
            $this->assertContains('auth', $route->gatherMiddleware());
            $this->assertContains(ValidateWorkspace::class, $route->gatherMiddleware());
            $url = route('cms.'.$action, ['event' => 'unknown', 'page' => 'unknown']);
            if ($action === 'preview') {
                $this->getJson($url)->assertUnauthorized();
            } else {
                $this->postJson($url)->assertUnauthorized();
            }
        }
    }

    public function test_cms_posts_keep_csrf_protection(): void
    {
        $this->app->instance('env', 'local');
        foreach (['create', 'draft', 'publish', 'restore'] as $action) {
            $this->postJson(route('cms.'.$action, ['event' => 'unknown', 'page' => 'unknown']))->assertStatus(419);
        }
    }

    public function test_policy_rejects_null_personal_and_forged_workspace_before_any_grant_query(): void
    {
        $workspaces = Mockery::mock(Workspaces::class);
        $workspaces->shouldNotReceive('selected');
        $this->app->instance(Workspaces::class, $workspaces);
        $user = (new User)->forceFill(['id' => (string) Str::uuid7()]);
        $event = (new Event)->forceFill(['id' => (string) Str::uuid7()]);
        $page = (new CmsPage)->forceFill(['event_id' => $event->id]);
        $this->assertInstanceOf(CmsPagePolicy::class, Gate::getPolicyFor($page));
        foreach ([null, '', 'personal', 'superadmin', 'assignment:0', 'assignment:1:superadmin'] as $workspace) {
            $this->assertFalse(Gate::forUser($user)->allows('create', [CmsPage::class, $event, $workspace]));
            foreach (['updateDraft', 'preview', 'publish', 'restore'] as $ability) {
                $this->assertFalse(Gate::forUser($user)->allows($ability, [$page, $event, $workspace]));
            }
        }
        $page->event_id = (string) Str::uuid7();
        $this->assertFalse(Gate::forUser($user)->allows('publish', [$page, $event, 'assignment:1']));
    }

    public function test_policy_requires_event_wide_superadmin_from_exact_selected_user_grant(): void
    {
        $user = (new User)->forceFill(['id' => (string) Str::uuid7()]);
        $event = (new Event)->forceFill(['id' => (string) Str::uuid7()]);
        $page = (new CmsPage)->forceFill(['event_id' => $event->id]);
        $workspaces = Mockery::mock(Workspaces::class);
        $this->app->instance(Workspaces::class, $workspaces);
        // Exact query expectations prove no unscoped/global Superadmin shortcut is used.
        foreach ([false, true] as $allowed) {
            $query = Mockery::mock(Builder::class);
            $query->shouldReceive('forEvent')->once()->with($event)->andReturnSelf();
            $query->shouldReceive('whereNull')->once()->with('institution_id')->andReturnSelf();
            $query->shouldReceive('where')->once()->with('role_key', 'superadmin')->andReturnSelf();
            $query->shouldReceive('lockForUpdate')->once()->andReturnSelf();
            $query->shouldReceive('first')->once()->andReturn($allowed ? new RoleAssignment : null);
            $workspaces->shouldReceive('selected')->once()->with($user, 'assignment:1')->andReturn($query);
            $this->assertSame($allowed, Gate::forUser($user)->allows('publish', [$page, $event, 'assignment:1']));
        }
    }

    public function test_personal_workspace_cannot_create_even_with_request_workspace_spoofing(): void
    {
        $event = (new Event)->forceFill(['id' => (string) Str::uuid7()]);
        Route::bind('event', fn () => $event);
        $user = (new User)->forceFill(['id' => (string) Str::uuid7()]);
        DB::shouldReceive('transaction')->once()->andReturnUsing(fn ($callback) => $callback());
        $this->actingAs($user)->withSession(['workspace' => 'personal'])
            ->postJson(route('cms.create', $event), ['slug' => 'about', 'workspace' => 'assignment:1', 'event_id' => (string) Str::uuid7()])
            ->assertForbidden();
    }

    public function test_preview_response_is_json_with_no_store_no_index_and_only_explicit_metadata(): void
    {
        $event = (new Event)->forceFill(['id' => (string) Str::uuid7()]);
        Route::bind('event', fn () => $event);
        // Persistence/authorization behavior is tested separately; exercise the real HTTP response boundary here.
        DB::shouldReceive('transaction')->once()->andReturn([
            'page_id' => 'page', 'revision_id' => 'revision', 'version' => 1,
            'snapshot' => ['schema_version' => 1, 'sections' => []],
        ]);
        $user = (new User)->forceFill(['id' => (string) Str::uuid7()]);
        $response = $this->actingAs($user)->withSession(['workspace' => 'personal'])
            ->getJson(route('cms.preview', ['event' => $event->id, 'page' => 'page']))->assertOk()
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow, noarchive')->assertHeader('Content-Type', 'application/json');
        $this->assertStringContainsString('no-store', $response->headers->get('Cache-Control'));
        $this->assertStringContainsString('private', $response->headers->get('Cache-Control'));
        $response->assertExactJson(['page_id' => 'page', 'revision_id' => 'revision', 'version' => 1, 'snapshot' => ['schema_version' => 1, 'sections' => []]]);
        foreach (['storage_key', 'signed_url', 'credential', 'token', 'password'] as $sensitive) {
            $response->assertDontSee($sensitive);
        }
    }

    public function test_audit_instance_mutation_and_deletion_are_blocked_without_database(): void
    {
        $audit = new CmsPublicationEvent;
        $audit->exists = true;
        foreach ([fn () => $audit->save(), fn () => $audit->delete(), fn () => $audit->increment('action'), fn () => $audit->incrementEach(['action' => 1])] as $action) {
            try {
                $action();
                $this->fail('Audit mutation accepted.');
            } catch (LogicException) {
                $this->addToAssertionCount(1);
            }
        }
    }

    public function test_published_cache_identity_is_scoped_to_persisted_event_and_page(): void
    {
        $page = (new CmsPage)->forceFill(['id' => (string) Str::uuid7(), 'event_id' => (string) Str::uuid7()]);
        $this->assertSame('cms:published:'.$page->event_id.':'.$page->id, CmsPublicationLifecycle::cacheKey($page));
        $other = clone $page;
        $other->event_id = (string) Str::uuid7();
        $this->assertNotSame(CmsPublicationLifecycle::cacheKey($page), CmsPublicationLifecycle::cacheKey($other));
    }

    public static function transactionOutcomes(): iterable
    {
        yield 'publish commit then invalidate' => [false, false, 'publish'];
        yield 'publish audit failure rolls back without invalidation' => [true, false, 'publish'];
        yield 'cache failure explicitly reports publication committed' => [false, true, 'publish'];
        yield 'restore commit then invalidate' => [false, false, 'restore'];
        yield 'restore audit failure rolls back without invalidation' => [true, false, 'restore'];
        foreach (['publish', 'restore'] as $action) {
            foreach (['missing', 'draft', 'empty draft alt', 'whitespace draft alt', 'Unicode whitespace alt'] as $case) {
                yield $action.' rejects '.$case.' media alt before mutation' => [false, false, $action, $case, true];
                yield $action.' rejects v3 '.$case.' media alt before mutation' => [false, false, $action, $case, true, 3];
            }
            foreach (['ready', 'ready without translations', 'ready with draft translations', 'hidden', 'no media'] as $case) {
                yield $action.' accepts '.$case.' v2 media' => [false, false, $action, $case, false];
                yield $action.' accepts '.$case.' v3 media' => [false, false, $action, $case, false, 3];
            }
        }
    }

    #[DataProvider('transactionOutcomes')]
    public function test_publication_transaction_and_cache_callback_contract_without_database(bool $auditFails, bool $cacheFails, string $action, ?string $mediaCase = null, bool $reject = false, int $mediaSchema = 2): void
    {
        // Simulated SQL boundary, real Eloquent/service/Gate and Laravel transaction callbacks.
        // This does not prove MySQL atomicity, FKs or concurrent interleaving.
        $user = (new User)->forceFill(['id' => (string) Str::uuid7()]);
        $event = (new Event)->forceFill(['id' => (string) Str::uuid7()]);
        $revisionId = (string) Str::uuid7();
        $page = (new CmsPage)->forceFill(['id' => (string) Str::uuid7(), 'event_id' => $event->id, 'draft_revision_id' => $revisionId, 'published_revision_id' => null]);
        if ($action === 'restore') {
            $page->published_revision_id = (string) Str::uuid7();
            $page->draft_revision_id = (string) Str::uuid7();
        }
        $missing = ['status' => 'missing', 'content' => null];
        $snapshot = ['schema_version' => 1, 'locales' => ['en' => ['status' => 'ready', 'content' => ['title' => 'Conference information']], 'id' => $missing, 'ar' => $missing], 'sections' => []];
        $fileId = (string) Str::uuid7();
        $file = [
            'id' => $fileId, 'event_id' => $event->id, 'kind' => 'cms_image', 'disk' => 'local', 'visibility' => 'private',
            'storage_key' => 'files/'.$event->id.'/cms_image/'.Str::uuid7().'.png',
            'detected_mime' => 'image/png', 'size_bytes' => 68, 'sha256' => str_repeat('a', 64),
        ];
        if ($mediaCase !== null) {
            $snapshot = CmsMediaSecurityTest::mediaSnapshot($fileId);
            if ($mediaSchema === 3) {
                $snapshot['schema_version'] = 3;
                $snapshot['seo'] = PublicSeoLocalizationTest::emptySeo();
            }
            $media = &$snapshot['sections'][0]['media'];
            $media['locales']['en']['content']['caption'] = '';
            $media['locales']['en']['content']['credit'] = '';
            if ($mediaCase === 'ready without translations') {
                foreach (['id', 'ar'] as $locale) {
                    $media['locales'][$locale] = ['status' => 'missing', 'content' => null];
                }
            } elseif ($mediaCase === 'ready with draft translations') {
                foreach (['id', 'ar'] as $locale) {
                    $media['locales'][$locale] = ['status' => 'draft', 'content' => ['alt' => 'Draft alt', 'caption' => '', 'credit' => '']];
                }
            } elseif ($mediaCase === 'missing') {
                $media['locales']['en'] = ['status' => 'missing', 'content' => null];
            } elseif ($mediaCase === 'Unicode whitespace alt') {
                $media['locales']['en']['content']['alt'] = "\u{00a0}\u{2003}\u{0085}";
            } elseif (in_array($mediaCase, ['draft', 'empty draft alt', 'whitespace draft alt'], true)) {
                $media['locales']['en']['status'] = 'draft';
                $media['locales']['en']['content']['alt'] = match ($mediaCase) {
                    'empty draft alt' => '',
                    'whitespace draft alt' => " \t\n ",
                    default => 'Incomplete English alt',
                };
            } elseif ($mediaCase === 'hidden') {
                $snapshot['sections'][0]['visible'] = false;
                $media['locales']['en'] = ['status' => 'missing', 'content' => null];
            } elseif ($mediaCase === 'no media') {
                $media = null;
            }
            unset($media);
        }
        $before = $snapshot;
        $pdo = Mockery::mock(PDO::class);
        $pdo->shouldReceive('beginTransaction')->once()->andReturnTrue();
        $pdo->shouldReceive('exec')->with('SAVEPOINT trans2')->andReturn(0);
        $pdo->shouldReceive('exec')->with('ROLLBACK TO SAVEPOINT trans2')->times($auditFails ? 1 : 0)->andReturn(0);
        $pdo->shouldReceive('inTransaction')->andReturnTrue();
        $committed = false;
        $pdo->shouldReceive('commit')->times($auditFails || $reject ? 0 : 1)->andReturnUsing(function () use (&$committed): bool {
            $committed = true;

            return true;
        });
        $pdo->shouldReceive('rollBack')->times($auditFails || $reject ? 1 : 0)->andReturnTrue();
        $connection = Mockery::mock(MySqlConnection::class)->makePartial();
        $connection->__construct($pdo, 'cms_contract', '', ['name' => 'cms_contract']);
        $connection->setTransactionManager(new DatabaseTransactionsManager);
        $connection->shouldReceive('select')->andReturnUsing(function ($sql) use ($user, $event, $page, $revisionId, $snapshot, $file): array {
            if (str_contains($sql, 'from `users`')) {
                return [$user->getAttributes()];
            }
            if (str_contains($sql, 'from `events`')) {
                return [$event->getAttributes()];
            }
            if (str_contains($sql, 'from `cms_pages`')) {
                $this->assertStringContainsString('for update', $sql);

                return [$page->getAttributes()];
            }
            if (str_contains($sql, 'from `stored_files`')) {
                $this->assertStringContainsString('for update', $sql);

                return [$file];
            }
            $this->assertStringContainsString('from `cms_page_revisions`', $sql);

            return [['id' => $revisionId, 'page_id' => $page->id, 'version' => 1, 'schema_version' => $snapshot['schema_version'], 'snapshot' => json_encode($snapshot)]];
        });
        $connection->shouldReceive('update')->times($reject ? 0 : 1)->andReturnUsing(function ($sql, $bindings) use ($revisionId, $event, $page): int {
            $this->assertStringContainsString('update `cms_pages`', $sql);
            $this->assertStringNotContainsString('draft_revision_id', $sql);
            $this->assertSame($revisionId, $bindings[0]);
            $this->assertContains($page->id, $bindings);
            $this->assertContains($event->id, $bindings);

            return 1;
        });
        $connection->shouldReceive('insert')->times($reject ? 0 : 1)->andReturnUsing(function ($sql, $bindings) use ($auditFails, $user, $revisionId, $action, $page): bool {
            $this->assertStringContainsString('insert into `cms_publication_events`', $sql);
            $this->assertContains($user->id, $bindings);
            $this->assertContains($revisionId, $bindings);
            $this->assertContains($action, $bindings);
            $this->assertContains($page->published_revision_id, $bindings);
            if ($auditFails) {
                throw new RuntimeException('Synthetic audit failure.');
            }

            return true;
        });
        $workspaces = Mockery::mock(Workspaces::class);
        $query = Mockery::mock(Builder::class);
        $workspaces->shouldReceive('selected')->with(Mockery::on(fn ($actor) => $actor->id === $user->id), 'assignment:1')->once()->andReturn($query);
        $query->shouldReceive('forEvent', 'whereNull', 'where', 'lockForUpdate')->andReturnSelf();
        $query->shouldReceive('first')->andReturn(new RoleAssignment);
        $this->app->instance(Workspaces::class, $workspaces);
        DB::shouldReceive('transaction')->once()->andReturnUsing(fn ($callback, $attempts) => $connection->transaction($callback, $attempts));
        DB::shouldReceive('table')->with('cms_pages')->times($reject ? 0 : 1)->andReturnUsing(fn ($table) => $connection->table($table));
        DB::shouldReceive('afterCommit')->times($auditFails || $reject ? 0 : 1)->andReturnUsing(fn ($callback) => $connection->afterCommit($callback));
        if (! $auditFails && ! $reject) {
            Cache::shouldReceive('forget')->once()->with(CmsPublicationLifecycle::cacheKey($page))->andReturnUsing(function () use (&$committed, $cacheFails): bool {
                $this->assertTrue($committed, 'Cache invalidated before outer commit.');
                if ($cacheFails) {
                    throw new RuntimeException('Synthetic cache failure.');
                }

                return true;
            });
        }
        $originalResolver = Model::getConnectionResolver();
        $resolver = new ConnectionResolver(['cms_contract' => $connection]);
        $resolver->setDefaultConnection('cms_contract');
        Model::setConnectionResolver($resolver);
        try {
            if ($reject) {
                // Exercise the real transaction/Gate/validators through Laravel's HTTP validation response.
                Route::post('/_test/publication-media', function () use ($action, $user, $event, $page, $revisionId) {
                    app(CmsPublicationLifecycle::class)->$action($user, $event, $page, 'assignment:1', $revisionId);

                    return response()->json(['unexpected' => 'publication accepted']);
                });
                $this->postJson('/_test/publication-media')->assertUnprocessable()->assertJsonValidationErrors('snapshot')
                    ->assertJsonPath('errors.snapshot.0', 'Publication requires ready, nonempty English alt text for visible media.');
                $this->assertFalse($committed);
                Cache::shouldNotHaveReceived('forget');

                return;
            }
            $revision = app(CmsPublicationLifecycle::class)->$action($user, $event, $page, 'assignment:1', $revisionId);
            $this->assertFalse($auditFails || $cacheFails);
            $this->assertInstanceOf(CmsPageRevision::class, $revision);
            $this->assertSame($revisionId, $revision->id);
            $this->assertSame($before, $revision->snapshot);
            $this->assertSame($before['schema_version'], $revision->schema_version);
        } catch (HttpException $exception) {
            $this->assertTrue($cacheFails);
            $this->assertSame(503, $exception->getStatusCode());
            $this->assertStringContainsString('Publication committed', $exception->getMessage());
            $this->assertTrue($committed);
        } catch (RuntimeException $exception) {
            $this->assertTrue($auditFails);
            $this->assertSame('Synthetic audit failure.', $exception->getMessage());
            $this->assertFalse($committed);
            Cache::shouldNotHaveReceived('forget');
        } finally {
            Model::setConnectionResolver($originalResolver);
        }
    }
}
