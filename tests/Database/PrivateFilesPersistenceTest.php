<?php

namespace Tests\Database;

use App\Models\Event;
use App\Models\RoleAssignment;
use App\Models\StoredFile;
use App\Models\User;
use App\Services\Files\PrivateFiles;
use Dotenv\Dotenv;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class PrivateFilesPersistenceTest extends TestCase
{
    private function counts(): array
    {
        $counts = [];
        foreach (['users', 'events', 'role_assignments', 'auth_challenges', 'stored_files', 'migrations'] as $table) {
            $counts[$table] = DB::table($table)->count();
        }

        return $counts;
    }

    public function test_real_intake_commits_private_bytes_and_metadata_then_downloads_and_cleans_up(): void
    {
        if (getenv('ICHES_DB_TESTS') !== '1' || getenv('ICHES_FILE_DB_TESTS') !== '1') {
            $this->markTestSkipped('Explicit local MySQL and physical storage opt-in required.');
        }
        // PHPUnit forces APP_ENV=testing; independently require the actual local workspace configuration.
        $this->assertSame('local', Dotenv::parse(file_get_contents(base_path('.env')))['APP_ENV'] ?? null);
        $mysql = config('database.connections.mysql');
        $this->assertSame('127.0.0.1', $mysql['host']);
        $this->assertSame('3306', (string) $mysql['port']);
        $this->assertSame('iches_unisya', $mysql['database']);
        config(['database.default' => 'mysql', 'app.key' => 'base64:'.base64_encode(random_bytes(32))]);
        $connection = DB::connection();
        $selected = $connection->selectOne('SELECT DATABASE() AS db, @@foreign_key_checks AS fk');
        $this->assertSame('iches_unisya', $selected->db);
        $this->assertSame(1, (int) $selected->fk);
        $this->assertTrue(Schema::hasTable('stored_files'));
        $this->assertSame(0, $connection->transactionLevel());
        $this->assertFalse($connection->getPdo()->inTransaction());
        $this->assertSame(4, (int) DB::table('migrations')
            ->where('migration', '2026_10_29_000004_create_stored_files_table')->value('batch'));

        $this->assertSame('local', config('filesystems.disks.local.driver'));
        $this->assertFalse(config('filesystems.disks.local.serve'));
        $disk = Storage::disk('local');
        $this->assertSame(realpath(storage_path('app/private')), realpath($disk->path('')));
        $this->assertNotSame(realpath(storage_path('app/public')), realpath($disk->path('')));

        $actor = User::where('email', 'presenter@iches.test')->firstOrFail();
        $other = User::where('email', 'participant@iches.test')->firstOrFail();
        $event = Event::where('slug', 'iches-demo-local')->firstOrFail();
        $grant = RoleAssignment::active()->where('user_id', $actor->id)
            ->where('event_id', $event->id)->where('role_key', 'presenter')->firstOrFail();
        $this->assertNotSame($actor->id, $other->id);
        $this->assertSame($actor->id, $grant->user_id);
        $this->assertSame($event->id, $grant->event_id);
        $this->assertNull($grant->revoked_at);
        $models = [$actor, $other, $event, $grant];
        $fingerprints = array_map(fn ($model) => hash('sha256', serialize($model->getRawOriginal())), $models);
        $before = $this->counts();
        $beforeFiles = $disk->allFiles();
        sort($beforeFiles);
        $name = 'ich-s0-05-rev01-'.Str::uuid7().'.pdf';
        $this->assertFalse(StoredFile::where('original_name', $name)->exists());
        $content = "%PDF-1.4\n1 0 obj\n<< /Type /Catalog >>\nendobj\n%%EOF\n";
        $workspace = 'assignment:'.$grant->id;
        $file = null;
        $observer = null;

        try {
            $file = app(PrivateFiles::class)->storePrivate($actor, $event, $workspace, 'abstract_pdf',
                UploadedFile::fake()->createWithContent($name, $content));
            $this->assertSame(0, $connection->transactionLevel());
            $this->assertFalse($connection->getPdo()->inTransaction());
            $this->assertSame('7', $file->id[14]);
            $fresh = StoredFile::findOrFail($file->id);
            $this->assertSame($actor->id, $fresh->owner_user_id);
            $this->assertSame($actor->id, $fresh->uploaded_by_user_id);
            $this->assertSame($event->id, $fresh->event_id);
            $this->assertSame('abstract_pdf', $fresh->kind);
            $this->assertSame('local', $fresh->disk);
            $this->assertSame('private', $fresh->visibility);
            $this->assertSame($name, $fresh->original_name);
            $this->assertSame('application/pdf', $fresh->detected_mime);
            $this->assertSame(strlen($content), $fresh->size_bytes);
            $this->assertSame(hash('sha256', $content), $fresh->sha256);
            $this->assertStringStartsWith('files/'.$event->id.'/abstract_pdf/', $fresh->storage_key);
            $this->assertStringNotContainsString($name, $fresh->storage_key);
            $this->assertTrue(is_file($disk->path($fresh->storage_key)));
            $this->assertSame($content, file_get_contents($disk->path($fresh->storage_key)));
            $this->assertSame($fresh->sha256, hash_file('sha256', $disk->path($fresh->storage_key)));
            $this->assertCount(count($beforeFiles) + 1, $disk->allFiles());
            $this->assertSame($before['stored_files'] + 1, StoredFile::count());
            foreach (['disk', 'storage_key', 'sha256'] as $hidden) {
                $this->assertArrayNotHasKey($hidden, $fresh->toArray());
            }
            // An independent PDO connection proves visibility after commit, not just a same-session read.
            $observer = DB::build($mysql);
            $this->assertNotSame($connection->getPdo(), $observer->getPdo());
            $committed = $observer->table('stored_files')->where('id', $file->id)->first();
            $this->assertNotNull($committed);
            $this->assertSame($fresh->sha256, $committed->sha256);
            $this->assertSame($fresh->storage_key, $committed->storage_key);

            $url = app(PrivateFiles::class)->temporaryDownloadUrl($actor, $fresh, $workspace);
            $response = $this->actingAs($actor)->withSession(['workspace' => $workspace])
                ->get($url)->assertOk()->assertHeader('X-Content-Type-Options', 'nosniff');
            $this->assertSame($content, $response->streamedContent());
            $this->actingAs($other)->get($url)->assertForbidden();
        } finally {
            $observer?->disconnect();
            // Recover by this run's UUID marker even if a later assertion interrupted the happy path.
            $created = StoredFile::where('original_name', $name)->get();
            $this->assertLessThanOrEqual(1, $created->count());
            foreach ($created as $record) {
                $this->assertSame($actor->id, $record->owner_user_id);
                $this->assertSame($actor->id, $record->uploaded_by_user_id);
                $this->assertSame($event->id, $record->event_id);
                $this->assertSame('local', $record->disk);
                $this->assertSame('abstract_pdf', $record->kind);
                $this->assertMatchesRegularExpression('~^files/'.preg_quote($event->id, '~').'/abstract_pdf/[0-9a-f-]{36}\.pdf$~D', $record->storage_key);
                if ($file !== null) {
                    $this->assertSame($file->id, $record->id);
                    $this->assertSame($file->storage_key, $record->storage_key);
                }
                $this->assertNotContains($record->storage_key, $beforeFiles);
                $this->assertTrue($disk->delete($record->storage_key));
                $this->assertFalse($disk->exists($record->storage_key));
                $this->assertSame(1, StoredFile::whereKey($record->id)->where('original_name', $name)->delete());
            }
            $this->assertFalse(StoredFile::where('original_name', $name)->exists());
            $this->assertSame($before, $this->counts());
            $afterFiles = $disk->allFiles();
            sort($afterFiles);
            $this->assertSame($beforeFiles, $afterFiles);
            foreach ($models as $index => $model) {
                $this->assertSame($fingerprints[$index], hash('sha256', serialize($model->fresh()->getRawOriginal())));
            }
        }
    }
}
