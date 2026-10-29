<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\StoredFile;
use App\Models\User;
use App\Services\Files\PrivateFiles;
use App\Services\Workspaces;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Connection;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Mockery;
use RuntimeException;
use Tests\TestCase;

class PrivateFilesTest extends TestCase
{
    private User $owner;

    private Event $event;

    private bool $grant = true;

    private StoredFile $bound;

    protected function setUp(): void
    {
        parent::setUp();
        config(['app.key' => 'base64:'.base64_encode(random_bytes(32))]);
        DB::shouldReceive('connection')->never();
        Storage::fake('local');
        Storage::fake('public');
        $this->owner = (new User)->forceFill(['id' => (string) Str::uuid7()]);
        $this->event = (new Event)->forceFill(['id' => (string) Str::uuid7()]);
        $workspaces = Mockery::mock(Workspaces::class)->makePartial();
        $workspaces->shouldReceive('selected')->andReturnUsing(function ($user, $key) {
            $query = Mockery::mock(Builder::class);
            $query->shouldReceive('forEvent')->andReturnUsing(function ($event) use ($query): Builder {
                $query->shouldReceive('exists')->andReturn($this->grant && $event->id === $this->event->id);

                return $query;
            });
            $query->shouldReceive('where')->with('event_id', Mockery::any())->andReturnUsing(function ($column, $eventId) use ($query): Builder {
                $query->shouldReceive('exists')->andReturn($this->grant && $eventId === $this->event->id);

                return $query;
            });
            $query->shouldReceive('exists')->byDefault()->andReturn($this->grant && $user->id === $this->owner->id && $key === 'assignment:1');
            if (! $this->grant || $user->id !== $this->owner->id || $key !== 'assignment:1') {
                $query->shouldReceive('forEvent', 'where')->andReturnSelf();
                $query->shouldReceive('exists')->andReturnFalse();
            }

            return $query;
        });
        $this->app->instance(Workspaces::class, $workspaces);
        // Only metadata persistence and binding are replaced. Real policies, middleware and storage run.
        Route::bind('storedFile', fn () => $this->bound);
    }

    private function intake(bool $fail = false): PrivateFiles
    {
        return new class($fail) extends PrivateFiles
        {
            public function __construct(private bool $fail) {}

            protected function persist(StoredFile $file): void
            {
                if ($this->fail) {
                    throw new RuntimeException('Synthetic database failure.');
                }
            }
        };
    }

    private function upload(string $name = 'document.pdf', ?string $content = null): UploadedFile
    {
        return UploadedFile::fake()->createWithContent($name, $content ?? "%PDF-1.4\n1 0 obj\n<< /Type /Catalog >>\nendobj\n%%EOF\n");
    }

    private function store(): StoredFile
    {
        return $this->intake()->storePrivate($this->owner, $this->event, 'assignment:1', 'abstract_pdf', $this->upload());
    }

    private function url(StoredFile $file, int $seconds = 300): string
    {
        return URL::temporarySignedRoute('files.download', now()->addSeconds($seconds), ['storedFile' => $file->id, 'actor' => $this->owner->id]);
    }

    public function test_private_unique_immutable_metadata_and_no_path_leak(): void
    {
        $first = $this->store();
        $second = $this->store();
        $this->assertSame('7', $first->id[14]);
        $this->assertNotSame($first->storage_key, $second->storage_key);
        $this->assertSame($this->owner->id, $first->owner_user_id);
        $this->assertSame($this->owner->id, $first->uploaded_by_user_id);
        // Windows does not implement POSIX chmod visibility; the private root is the boundary there.
        if (PHP_OS_FAMILY !== 'Windows') {
            $this->assertSame('private', Storage::disk('local')->getVisibility($first->storage_key));
        }
        $this->assertFalse(config('filesystems.disks.local.serve'));
        $this->assertNotSame(config('filesystems.disks.local.root'), config('filesystems.disks.public.root'));
        $this->assertSame(hash('sha256', Storage::disk('local')->get($first->storage_key)), $first->sha256);
        $this->assertCount(2, Storage::disk('local')->allFiles());
        $this->assertSame([], Storage::disk('public')->allFiles());
        foreach (['storage_key', 'disk', 'sha256'] as $hidden) {
            $this->assertArrayNotHasKey($hidden, $first->toArray());
        }
        $first->syncOriginal();
        $first->exists = true;
        $first->original_name = 'changed.pdf';
        $this->expectException(\LogicException::class);
        $first->save();
    }

    public function test_invalid_content_extensions_kind_empty_and_oversize_leave_no_files(): void
    {
        config(['filesystems.private_file_limits.abstract_pdf' => 100]);
        foreach ([
            ['abstract_pdf', $this->upload('fake.pdf', '<html>unsafe</html>')],
            ['abstract_pdf', $this->upload('wrong.png')],
            ['abstract_pdf', $this->upload('empty.pdf', '')],
            ['abstract_pdf', $this->upload('large.pdf', '%PDF-'.str_repeat('x', 101))],
            ['cms_image', $this->upload('fake.png', '<svg></svg>')],
            ['cms_image', $this->upload('fake.svg', '<svg></svg>')],
            ['../public', $this->upload()],
            ['full_paper', $this->upload()],
        ] as [$kind, $upload]) {
            try {
                $this->intake()->storePrivate($this->owner, $this->event, 'assignment:1', $kind, $upload);
                $this->fail('Invalid upload accepted.');
            } catch (ValidationException) {
                $this->assertSame([], Storage::disk('local')->allFiles());
            }
        }
    }

    public function test_image_content_is_detected_and_filename_is_sanitized(): void
    {
        $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+aF1sAAAAASUVORK5CYII=');
        $file = $this->intake()->storePrivate($this->owner, $this->event, 'assignment:1', 'cms_image', $this->upload("..\\folder\\bad\r\nname.png", $png));
        $this->assertSame('image/png', $file->detected_mime);
        $this->assertDoesNotMatchRegularExpression('/[\\\\\/\r\n]/', $file->original_name);
        $this->assertStringNotContainsString('folder', $file->storage_key);
    }

    public function test_metadata_failure_cleans_written_bytes(): void
    {
        try {
            $this->intake(true)->storePrivate($this->owner, $this->event, 'assignment:1', 'abstract_pdf', $this->upload());
            $this->fail('Persistence failure ignored.');
        } catch (RuntimeException $exception) {
            $this->assertSame('Unable to store private file.', $exception->getMessage());
            $this->assertSame([], Storage::disk('local')->allFiles());
        }
    }

    public function test_failed_storage_does_not_attempt_metadata_persistence(): void
    {
        $disk = Mockery::mock(FilesystemAdapter::class);
        $disk->shouldReceive('path')->andReturn(storage_path('framework/testing/nonexistent-file'));
        $disk->shouldReceive('put')->once()->andReturnFalse();
        $disk->shouldReceive('delete')->once()->andReturnTrue();
        Storage::shouldReceive('disk')->with('local')->andReturn($disk);
        $service = Mockery::mock(PrivateFiles::class)->makePartial()->shouldAllowMockingProtectedMethods();
        $service->shouldReceive('persist')->never();
        $this->expectException(RuntimeException::class);
        $service->storePrivate($this->owner, $this->event, 'assignment:1', 'abstract_pdf', $this->upload());
    }

    public function test_real_intake_refuses_ambient_transaction_and_removes_written_file(): void
    {
        $connection = Mockery::mock(Connection::class);
        $connection->shouldReceive('transactionLevel')->once()->andReturn(1);
        $manager = Mockery::mock(DatabaseManager::class);
        $manager->shouldReceive('connection')->once()->andReturn($connection);
        $manager->shouldReceive('transaction')->never();
        DB::swap($manager);
        try {
            app(PrivateFiles::class)->storePrivate($this->owner, $this->event, 'assignment:1', 'abstract_pdf', $this->upload());
            $this->fail('Ambient transaction accepted.');
        } catch (RuntimeException $exception) {
            $this->assertStringContainsString('ambient database transaction', $exception->getPrevious()->getMessage());
            $this->assertSame([], Storage::disk('local')->allFiles());
        }
    }

    public function test_download_requires_login_signature_owner_active_event_workspace_and_rechecks_revocation(): void
    {
        $this->bound = $this->store();
        $url = $this->url($this->bound);
        $this->get($url)->assertRedirect('/login');
        $this->actingAs($this->owner)->withSession(['workspace' => 'assignment:1']);
        $response = $this->get($url)->assertOk()->assertHeader('X-Content-Type-Options', 'nosniff');
        $this->assertStringContainsString('attachment;', $response->headers->get('Content-Disposition'));
        $this->assertStringContainsString('no-store', $response->headers->get('Cache-Control'));
        $this->assertSame(Storage::disk('local')->get($this->bound->storage_key), $response->streamedContent());
        $this->get($url.'tampered')->assertForbidden();
        $this->get($this->url($this->bound, -1))->assertForbidden();
        $this->get(route('files.download', $this->bound->id))->assertForbidden();
        $this->grant = false;
        $this->get($url)->assertForbidden();
        $this->grant = true;
        $this->withSession(['workspace' => 'personal'])->get($url)->assertForbidden();
        $this->withSession(['workspace' => 'assignment:1']);
        $this->bound->event_id = (string) Str::uuid7();
        $this->get($url)->assertForbidden();
        $this->bound->event_id = $this->event->id;
        $this->bound->owner_user_id = (string) Str::uuid7();
        $this->get($url)->assertForbidden();
        $this->actingAs((new User)->forceFill(['id' => (string) Str::uuid7()]));
        $this->get($url)->assertForbidden();
    }

    public function test_policy_denies_other_owners_public_drafts_and_unscoped_intake(): void
    {
        $file = $this->store();
        $other = (new User)->forceFill(['id' => (string) Str::uuid7()]);
        $this->assertFalse(Gate::forUser($other)->allows('download', [$file, 'assignment:1']));
        $file->visibility = 'public';
        $this->assertFalse(Gate::forUser($this->owner)->allows('download', [$file, 'assignment:1']));
        $this->expectException(AuthorizationException::class);
        $this->intake()->storePrivate($this->owner, $this->event, 'personal', 'abstract_pdf', $this->upload());
    }

    public function test_url_generation_reloads_metadata_and_authorizes_before_issuing_short_lived_link(): void
    {
        $file = $this->store();
        $stale = Mockery::mock(StoredFile::class)->makePartial();
        $stale->shouldReceive('fresh')->twice()->andReturn($file);
        $url = app(PrivateFiles::class)->temporaryDownloadUrl($this->owner, $stale, 'assignment:1');
        parse_str(parse_url($url, PHP_URL_QUERY), $query);
        $this->assertSame($this->owner->id, $query['actor']);
        $this->assertEqualsWithDelta(now()->addMinutes(5)->timestamp, $query['expires'], 2);
        $this->assertTrue(URL::hasValidSignature(Request::create($url)));
        $this->grant = false;
        $this->expectException(AuthorizationException::class);
        app(PrivateFiles::class)->temporaryDownloadUrl($this->owner, $stale, 'assignment:1');
    }

    public function test_missing_corrupted_and_unsafe_storage_paths_fail_closed(): void
    {
        $this->bound = $this->store();
        $url = $this->url($this->bound);
        $this->actingAs($this->owner)->withSession(['workspace' => 'assignment:1']);
        Storage::disk('local')->put($this->bound->storage_key, str_repeat('x', $this->bound->size_bytes));
        $this->get($url)->assertNotFound();
        Storage::disk('local')->put($this->bound->storage_key, 'corrupted');
        $this->get($url)->assertNotFound();
        Storage::disk('local')->delete($this->bound->storage_key);
        $this->get($url)->assertNotFound();
        foreach (['../private.pdf', '/absolute/path.pdf', 'files/../../secret.pdf'] as $key) {
            $this->bound->storage_key = $key;
            $this->get($url)->assertNotFound();
        }
        $this->bound->disk = 'public';
        $this->get($url)->assertForbidden();
    }
}
