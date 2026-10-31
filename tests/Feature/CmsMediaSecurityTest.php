<?php

namespace Tests\Feature;

use App\Models\CmsPage;
use App\Models\CmsPageRevision;
use App\Models\Event;
use App\Models\RoleAssignment;
use App\Models\StoredFile;
use App\Models\User;
use App\Services\Cms\PageRevisionWriter;
use App\Services\Cms\PublishedCmsPageReader;
use App\Services\Files\PrivateFiles;
use App\Services\Workspaces;
use Illuminate\Database\ConnectionResolver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\MySqlConnection;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Mockery;
use PDO;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;
use Tests\Unit\PublishedSectionPresenterTest as Fixtures;

class CmsMediaSecurityTest extends TestCase
{
    private Event $event;

    private User $user;

    private CmsPage $page;

    private CmsPageRevision $revision;

    private StoredFile $file;

    private PrivateFiles $storage;

    private const PNG = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+aF1sAAAAASUVORK5CYII=';

    protected function setUp(): void
    {
        parent::setUp();
        config(['app.key' => 'base64:'.base64_encode(random_bytes(32))]);
        DB::shouldReceive('connection')->never();
        Schema::swap(Mockery::mock(\Illuminate\Database\Schema\Builder::class));
        Storage::fake('local');
        Storage::fake('public');
        $this->event = Fixtures::event();
        $this->user = (new User)->forceFill(['id' => (string) Str::uuid7()]);
        $this->storage = new class extends PrivateFiles
        {
            // Real validation and private filesystem; only DB persistence is replaced.
            protected function persist(StoredFile $file): void {}
        };
        $this->app->instance(PrivateFiles::class, $this->storage);
    }

    private function workspace(bool $active = true, bool $eligible = true): void
    {
        $grant = (new RoleAssignment)->forceFill(['id' => 1]);
        $grant->setRelation('event', $this->event);
        $workspaces = Mockery::mock(Workspaces::class)->makePartial();
        $workspaces->shouldReceive('selected')->with($this->user, Mockery::any())->andReturnUsing(function ($user, $key) use ($active, $eligible, $grant) {
            $query = Mockery::mock(Builder::class);
            $allowed = $active && $key === 'assignment:1';
            $query->shouldReceive('exists')->andReturn($allowed);
            $query->shouldReceive('whereNull')->with('institution_id')->andReturnSelf();
            $query->shouldReceive('where')->with('role_key', 'superadmin')->andReturnSelf();
            $query->shouldReceive('with')->with('event')->andReturnSelf();
            $query->shouldReceive('forEvent')->with($this->event)->andReturnSelf();
            $query->shouldReceive('lockForUpdate')->andReturnSelf();
            $query->shouldReceive('first')->andReturn($allowed && $eligible ? $grant : null);

            return $query;
        });
        $this->app->instance(Workspaces::class, $workspaces);
        $this->actingAs($this->user)->withSession(['workspace' => 'assignment:1']);
    }

    private function adminUrl(): string
    {
        return '/workspace/events/'.$this->event->id.'/cms/media';
    }

    public function test_guests_csrf_personal_forged_revoked_ineligible_and_foreign_event_are_denied(): void
    {
        $service = Mockery::mock(PrivateFiles::class);
        $service->shouldNotReceive('storePrivate');
        $this->app->instance(PrivateFiles::class, $service);
        $this->getJson($this->adminUrl())->assertUnauthorized();
        $this->postJson($this->adminUrl())->assertUnauthorized();
        $this->app->instance('env', 'local');
        $this->postJson($this->adminUrl())->assertStatus(419);
        $this->app->instance('env', 'testing');
        foreach ([['personal', true, true], ['assignment:999', true, true], ['assignment:1', false, true], ['assignment:1', true, false]] as [$key, $active, $eligible]) {
            $this->workspace($active, $eligible);
            $this->withSession(['workspace' => $key]);
            $this->getJson($this->adminUrl())->assertForbidden();
            $this->postJson($this->adminUrl(), ['workspace' => 'assignment:1', 'event_id' => $this->event->id])->assertForbidden();
        }
        $this->workspace();
        $this->postJson('/workspace/events/'.Str::uuid7().'/cms/media')->assertForbidden();
        $this->assertSame([], Storage::disk('local')->allFiles());
    }

    public static function validImages(): iterable
    {
        yield 'PNG' => ['png', 'image/png', base64_decode(self::PNG)];
        yield 'WebP' => ['webp', 'image/webp', base64_decode('UklGRiQAAABXRUJQVlA4IBgAAAAwAQCdASoBAAEAAUAmJaQAA3AA/v02aAA=')];
        yield 'JPEG' => ['jpg', 'image/jpeg', base64_decode('/9j/4AAQSkZJRgABAQEAYABgAAD//gA+Q1JFQVRPUjogZ2QtanBlZyB2MS4wICh1c2luZyBJSkcgSlBFRyB2ODApLCBkZWZhdWx0IHF1YWxpdHkK/9sAQwAIBgYHBgUIBwcHCQkICgwUDQwLCwwZEhMPFB0aHx4dGhwcICQuJyAiLCMcHCg3KSwwMTQ0NB8nOT04MjwuMzQy/9sAQwEJCQkMCwwYDQ0YMiEcITIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIy/8AAEQgAAQABAwEiAAIRAQMRAf/EAB8AAAEFAQEBAQEBAAAAAAAAAAABAgMEBQYHCAkKC//EALUQAAIBAwMCBAMFBQQEAAABfQECAwAEEQUSITFBBhNRYQcicRQygZGhCCNCscEVUtHwJDNicoIJChYXGBkaJSYnKCkqNDU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6g4SFhoeIiYqSk5SVlpeYmZqio6Slpqeoqaqys7S1tre4ubrCw8TFxsfIycrS09TV1tfY2drh4uPk5ebn6Onq8fLz9PX29/j5+v/EAB8BAAMBAQEBAQEBAQEAAAAAAAABAgMEBQYHCAkKC//EALURAAIBAgQEAwQHBQQEAAECdwABAgMRBAUhMQYSQVEHYXETIjKBCBRCkaGxwQkjM1LwFWJy0QoWJDThJfEXGBkaJicoKSo1Njc4OTpDREVGR0hJSlNUVVZXWFlaY2RlZmdoaWpzdHV2d3h5eoKDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uLj5OXm5+jp6vLz9PX29/j5+v/aAAwDAQACEQMRAD8A+f6KKKAP/9k=')];
    }

    #[DataProvider('validImages')]
    public function test_valid_upload_is_private_and_response_has_only_picker_metadata(string $extension, string $mime, string $bytes): void
    {
        $this->workspace();
        $response = $this->post($this->adminUrl(), ['file' => UploadedFile::fake()->createWithContent('sample.'.$extension, $bytes)], ['Accept' => 'application/json']);
        $response->assertCreated()->assertHeader('Cache-Control', 'no-store, private')->assertJsonPath('detected_mime', $mime);
        $this->assertSame(['id', 'original_name', 'detected_mime', 'size_bytes'], array_keys($response->json()));
        $this->assertCount(1, Storage::disk('local')->allFiles());
        $this->assertSame([], Storage::disk('public')->allFiles());
        $this->assertStringNotContainsString('files/', $response->getContent());
    }

    public static function badUploads(): iterable
    {
        yield 'SVG' => ['bad.svg', '<svg xmlns="http://www.w3.org/2000/svg"></svg>'];
        yield 'HTML' => ['bad.png', '<html>unsafe</html>'];
        yield 'spoofed extension' => ['bad.jpg', base64_decode(self::PNG)];
        yield 'wrong kind' => ['bad.pdf', '%PDF-1.4'];
        yield 'malformed PNG' => ['bad.png', "\x89PNG\r\n\x1a\n"];
        yield 'oversized' => ['big.png', base64_decode(self::PNG).str_repeat('x', 5 * 1024 * 1024)];
    }

    #[DataProvider('badUploads')]
    public function test_invalid_upload_does_not_persist_or_write_private_bytes(string $name, string $bytes): void
    {
        $this->workspace();
        $this->post($this->adminUrl(), ['file' => UploadedFile::fake()->createWithContent($name, $bytes)], ['Accept' => 'application/json'])
            ->assertUnprocessable()->assertJsonValidationErrors('file');
        $this->assertSame([], Storage::disk('local')->allFiles());
    }

    public function test_listing_scopes_event_kind_and_private_disk_without_sensitive_metadata(): void
    {
        $this->workspace();
        $model = Mockery::mock(StoredFile::class)->makePartial();
        $query = Mockery::mock(Builder::class);
        $model->shouldReceive('newQuery')->once()->andReturn($query);
        foreach (['event_id' => $this->event->id, 'kind' => 'cms_image', 'disk' => 'local', 'visibility' => 'private'] as $key => $value) {
            $query->shouldReceive('where')->once()->with($key, $value)->andReturnSelf();
        }
        $query->shouldReceive('orderByDesc')->once()->with('id')->andReturnSelf();
        $file = (new StoredFile)->forceFill(['id' => (string) Str::uuid7(), 'original_name' => 'safe.png', 'detected_mime' => 'image/png', 'size_bytes' => 68,
            'storage_key' => 'SECRET_PATH', 'sha256' => 'SECRET_HASH', 'owner_user_id' => 'SECRET_USER']);
        $query->shouldReceive('paginate')->once()->with(20, ['id', 'original_name', 'detected_mime', 'size_bytes'])
            ->andReturn(new LengthAwarePaginator([$file], 1, 20));
        $this->app->instance(StoredFile::class, $model);
        $this->getJson($this->adminUrl())->assertOk()->assertJsonCount(1, 'data')->assertDontSee('SECRET_');
    }

    public static function mediaSnapshot(string $fileId): array
    {
        $snapshot = Fixtures::snapshot();
        $snapshot['schema_version'] = 2;
        foreach ($snapshot['sections'] as &$section) {
            $section['media_ids'] = [];
            $section['media'] = null;
        }
        unset($section);
        $snapshot['sections'][0]['media_ids'] = [$fileId];
        $snapshot['sections'][0]['media'] = ['file_id' => $fileId, 'focal' => ['x' => 25, 'y' => 75], 'locales' => [
            'en' => ['status' => 'ready', 'content' => ['alt' => 'Synthetic image', 'caption' => '', 'credit' => 'Synthetic credit']],
            'id' => ['status' => 'draft', 'content' => ['alt' => 'SECRET ALT', 'caption' => '', 'credit' => '']],
            'ar' => ['status' => 'missing', 'content' => null],
        ]];

        return $snapshot;
    }

    public static function incompleteDraftAlts(): iterable
    {
        yield 'missing English translation' => [['status' => 'missing', 'content' => null], 2];
        yield 'v3 missing English translation' => [['status' => 'missing', 'content' => null], 3];
        foreach (['', " \t\n ", 'Incomplete alternative text'] as $alt) {
            foreach ([2, 3] as $schema) {
                yield 'v'.$schema.' draft '.json_encode($alt) => [['status' => 'draft', 'content' => ['alt' => $alt, 'caption' => '', 'credit' => '']], $schema];
            }
        }
    }

    #[DataProvider('incompleteDraftAlts')]
    public function test_incomplete_media_drafts_can_be_saved_without_publication_readiness(array $english, int $schema): void
    {
        $id = (string) Str::uuid7();
        $snapshot = self::mediaSnapshot($id);
        $snapshot['sections'][0]['media']['locales']['en'] = $english;
        if ($schema === 3) {
            $snapshot['schema_version'] = 3;
            $snapshot['seo'] = PublicSeoLocalizationTest::emptySeo();
        }
        $original = $snapshot;
        $this->workspace();
        $this->user->exists = $this->event->exists = true;
        $page = (new CmsPage)->forceFill([
            'id' => (string) Str::uuid7(), 'event_id' => $this->event->id, 'slug' => 'about',
            'draft_revision_id' => null, 'published_revision_id' => null,
        ]);
        $page->exists = true;
        $connection = Mockery::mock(MySqlConnection::class)->makePartial();
        $connection->__construct(Mockery::mock(PDO::class), 'media_draft_contract', '', ['name' => 'media_draft_contract']);
        $inserted = false;
        $connection->shouldReceive('select')->andReturnUsing(function ($sql, $bindings) use ($id, $page, &$inserted): array {
            if (str_contains($sql, 'from `users`') || str_contains($sql, 'from `events`')) {
                return [(object) ['exists' => true]];
            }
            if (str_contains($sql, 'from `cms_pages`')) {
                $this->assertStringContainsString('for update', $sql);

                return [$page->getAttributes()];
            }
            if (str_contains($sql, 'from `cms_page_revisions`')) {
                if (str_starts_with($sql, 'select exists')) {
                    $this->assertTrue($inserted);
                    $this->assertSame($page->id, $bindings[0]);
                    $this->assertTrue(Str::isUuid($bindings[1]));

                    return [(object) ['exists' => true]];
                }
                $this->assertStringContainsString('for update', $sql);

                return [];
            }
            $this->assertStringContainsString('from `stored_files`', $sql);
            $this->assertStringContainsString('for update', $sql);
            $this->assertSame([$id], $bindings);

            return [[
                'id' => $id, 'event_id' => $this->event->id, 'kind' => 'cms_image', 'disk' => 'local', 'visibility' => 'private',
                'storage_key' => 'files/'.$this->event->id.'/cms_image/'.Str::uuid7().'.png',
                'detected_mime' => 'image/png', 'size_bytes' => 68, 'sha256' => str_repeat('a', 64),
            ]];
        });
        $connection->shouldReceive('insert')->once()->andReturnUsing(function ($sql, $bindings) use ($original, &$inserted): bool {
            $this->assertStringContainsString('insert into `cms_page_revisions`', $sql);
            $this->assertContains(json_encode($original), $bindings);
            $inserted = true;

            return true;
        });
        $connection->shouldReceive('update')->once()->andReturnUsing(function ($sql) {
            $this->assertStringContainsString('update `cms_pages`', $sql);
            $this->assertStringContainsString('draft_revision_id', $sql);
            $this->assertStringNotContainsString('published_revision_id', $sql);

            return 1;
        });
        $connection->shouldNotReceive('delete');
        $connection->shouldReceive('transaction')->andReturnUsing(fn ($callback) => $callback());
        DB::shouldReceive('transaction')->once()->andReturnUsing(fn ($callback) => $callback());
        $resolver = new ConnectionResolver(['media_draft_contract' => $connection]);
        $resolver->setDefaultConnection('media_draft_contract');
        $previous = Model::getConnectionResolver();
        Model::setConnectionResolver($resolver);
        try {
            $revision = app(PageRevisionWriter::class)->saveDraft($this->user, $this->event, $page, $snapshot, 'assignment:1');
            $this->assertSame($original, $revision->snapshot);
            $this->assertSame($schema, $revision->schema_version);
            $this->assertSame($english, $revision->snapshot['sections'][0]['media']['locales']['en']);
            $this->assertSame($original, $snapshot);
        } finally {
            Model::setConnectionResolver($previous);
        }
    }

    private function publishedFixture(string $extension = 'png', ?string $bytes = null, int $schema = 2): void
    {
        $this->workspace();
        $this->file = $this->storage->storePrivate($this->user, $this->event, 'assignment:1', 'cms_image', UploadedFile::fake()->createWithContent('sample.'.$extension, $bytes ?? base64_decode(self::PNG)));
        $this->page = (new CmsPage)->forceFill(['id' => (string) Str::uuid7(), 'event_id' => $this->event->id, 'slug' => 'about', 'published_revision_id' => (string) Str::uuid7()]);
        $this->revision = (new CmsPageRevision)->forceFill(['id' => $this->page->published_revision_id, 'page_id' => $this->page->id,
            'schema_version' => 2, 'snapshot' => self::mediaSnapshot($this->file->id)]);
        if ($schema === 3) {
            $snapshot = $this->revision->snapshot;
            $snapshot['schema_version'] = $this->revision->schema_version = 3;
            $snapshot['seo'] = PublicSeoLocalizationTest::emptySeo();
            $this->revision->snapshot = $snapshot;
        }
        Schema::shouldReceive('hasTable')->andReturnTrue();
        foreach ([Event::class => $this->event, CmsPage::class => $this->page, CmsPageRevision::class => $this->revision, StoredFile::class => $this->file] as $class => $record) {
            $model = Mockery::mock($class)->makePartial();
            $query = Mockery::mock(Builder::class);
            $model->shouldReceive('newQuery')->andReturn($query);
            if ($class === Event::class) {
                $query->shouldReceive('where')->with('slug', 'synthetic-event')->andReturnSelf();
            } elseif ($class === CmsPage::class) {
                $query->shouldReceive('where')->with('event_id', $this->event->id)->andReturnSelf();
                $query->shouldReceive('where')->with('slug', 'about')->andReturnSelf();
            } elseif ($class === CmsPageRevision::class) {
                $query->shouldReceive('where')->with('page_id', $this->page->id)->andReturnSelf();
                $query->shouldReceive('whereKey')->andReturnUsing(function ($id) use ($query) {
                    $this->assertSame($this->page->published_revision_id, $id);

                    return $query;
                });
            } else {
                $query->shouldReceive('where')->with('event_id', $this->event->id)->andReturnSelf();
                $query->shouldReceive('where')->with('kind', 'cms_image')->andReturnSelf();
                $query->shouldReceive('whereKey')->with($this->file->id)->andReturnSelf();
                $query->shouldReceive('first')->andReturn($record);
            }
            $query->shouldReceive('firstOrFail')->andReturn($record);
            $this->app->instance($class, $model);
        }
    }

    private function publicUrl(): string
    {
        return '/events/synthetic-event/pages/about/sections/'.$this->revision->snapshot['sections'][0]['id'].'/media/'.$this->file->id;
    }

    public static function publishedImages(): iterable
    {
        foreach ([2, 3] as $schema) {
            foreach (self::validImages() as $name => $image) {
                yield $name.' schema '.$schema => [...$image, $schema];
            }
        }
    }

    #[DataProvider('publishedImages')]
    public function test_published_image_has_fixed_mime_headers_and_verified_bytes(string $extension, string $mime, string $bytes, int $schema): void
    {
        $this->publishedFixture($extension, $bytes, $schema);
        $this->app['auth']->forgetGuards();
        $this->assertGuest();
        $this->get($this->publicUrl())->assertOk()->assertHeader('Content-Type', $mime)
            ->assertHeader('X-Content-Type-Options', 'nosniff')->assertHeader('Cache-Control', 'no-store, private')
            ->assertHeader('Content-Disposition', 'inline')->assertContent($bytes);
        $this->assertCount(1, Storage::disk('local')->allFiles());
    }

    public function test_og_image_uses_verified_published_media_and_trusted_origin_only(): void
    {
        config(['app.url' => 'https://conference.example']);
        $this->publishedFixture(schema: 3);
        $reader = app(PublishedCmsPageReader::class);
        $cms = $reader->read('synthetic-event', 'about', 'en');
        $this->assertSame('https://conference.example'.$this->publicUrl(), $cms['seo']['image']);
        $this->assertStringNotContainsString($this->file->storage_key, json_encode($cms['seo']));
        $this->assertStringNotContainsString($this->file->sha256, json_encode($cms['seo']));
        $this->file->kind = 'payment_proof';
        $this->assertNull($reader->read('synthetic-event', 'about', 'en')['seo']['image']);
        $this->file->kind = 'cms_image';
        Storage::disk('local')->put($this->file->storage_key, 'corrupt');
        $this->assertNull($reader->read('synthetic-event', 'about', 'en')['seo']['image']);
    }

    public static function mediaSchemas(): iterable
    {
        yield 'v2' => [2];
        yield 'v3' => [3];
    }

    #[DataProvider('mediaSchemas')]
    public function test_publication_revocation_hidden_draft_removed_and_v1_images_fail_closed(int $schema): void
    {
        $this->publishedFixture(schema: $schema);
        $url = $this->publicUrl();
        $snapshot = $this->revision->snapshot;
        app(PublishedCmsPageReader::class)->read('synthetic-event', 'about', 'en');
        $this->get($url)->assertOk();
        $id = $this->page->published_revision_id;
        $this->page->published_revision_id = null;
        $this->get($url)->assertNotFound();
        $this->page->published_revision_id = $id;
        foreach (['sections.0.visible' => false, 'sections.0.media' => null, 'sections.0.media.locales.en.status' => 'draft'] as $path => $value) {
            $changed = $snapshot;
            data_set($changed, $path, $value);
            $this->revision->snapshot = $changed;
            $this->get($url)->assertNotFound();
        }
        // A restore points to a different immutable revision, with this image removed.
        $this->page->published_revision_id = $this->revision->id = (string) Str::uuid7();
        $restored = $snapshot;
        $restored['sections'][0]['media'] = null;
        $this->revision->snapshot = $restored;
        $this->get($url)->assertNotFound();
        $legacy = $snapshot;
        $legacy['schema_version'] = $this->revision->schema_version = 1;
        unset($legacy['seo']);
        foreach ($legacy['sections'] as &$section) {
            unset($section['media']);
        }
        unset($section);
        $this->revision->snapshot = $legacy;
        $this->get($url)->assertNotFound();
    }

    #[DataProvider('mediaSchemas')]
    public function test_route_ids_and_foreign_publication_records_cannot_authorize_media(int $schema): void
    {
        $this->publishedFixture(schema: $schema);
        $url = $this->publicUrl();
        $this->get(str_replace('/media/'.$this->file->id, '/media/'.Str::uuid7(), $url))->assertNotFound();
        $this->get(str_replace('/sections/'.$this->revision->snapshot['sections'][0]['id'], '/sections/'.Str::uuid7(), $url))->assertNotFound();
        $eventId = $this->page->event_id;
        $this->page->event_id = (string) Str::uuid7();
        $this->get($url)->assertNotFound();
        $this->page->event_id = $eventId;
        $pageId = $this->revision->page_id;
        $this->revision->page_id = (string) Str::uuid7();
        $this->get($url)->assertNotFound();
        $this->revision->page_id = $pageId;
        $snapshot = $this->revision->snapshot;
        $snapshot['sections'][0]['locales']['en']['status'] = 'draft';
        $this->revision->snapshot = $snapshot;
        $this->get($url)->assertNotFound();
    }

    #[DataProvider('mediaSchemas')]
    public function test_foreign_files_wrong_kind_paths_missing_and_corrupt_bytes_are_not_public(int $schema): void
    {
        $this->publishedFixture(schema: $schema);
        $attributes = $this->file->getAttributes();
        $url = $this->publicUrl();
        foreach (['id' => (string) Str::uuid7(), 'event_id' => (string) Str::uuid7(), 'kind' => 'abstract_pdf', 'visibility' => 'public', 'disk' => 'public', 'detected_mime' => 'text/html', 'storage_key' => '../secret'] as $key => $value) {
            $this->file->setRawAttributes(array_replace($attributes, [$key => $value]));
            $this->get($url)->assertNotFound();
        }
        $this->file->setRawAttributes($attributes);
        Storage::disk('local')->put($this->file->storage_key, 'corrupt');
        $this->get($url)->assertNotFound();
        $this->file->size_bytes = 7;
        $this->file->sha256 = hash('sha256', 'corrupt');
        $this->get($url)->assertNotFound();
        Storage::disk('local')->delete($this->file->storage_key);
        $this->get($url)->assertNotFound();
        $this->assertSame([], Storage::disk('public')->allFiles());
    }
}
