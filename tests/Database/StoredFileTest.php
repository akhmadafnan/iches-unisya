<?php

namespace Tests\Database;

use App\Models\StoredFile;
use App\Services\Files\PrivateFiles;
use Illuminate\Database\Connection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use RuntimeException;
use Tests\Support\FoundationFixtures as Fixtures;
use Tests\TestCase;

class StoredFileTest extends TestCase
{
    private ?Connection $connection = null;

    protected function setUp(): void
    {
        parent::setUp();
        if (getenv('ICHES_DB_TESTS') !== '1' || getenv('ICHES_FILE_DB_TESTS') !== '1') {
            $this->markTestSkipped('Requires separate migration apply GO and explicit file database test opt-in.');
        }
        $mysql = config('database.connections.mysql');
        $this->assertSame('127.0.0.1', $mysql['host']);
        $this->assertSame('3306', (string) $mysql['port']);
        $this->assertSame('iches_unisya', $mysql['database']);
        config(['database.default' => 'mysql', 'app.key' => 'base64:'.base64_encode(random_bytes(32))]);
        $this->connection = DB::connection();
        $selected = $this->connection->selectOne('SELECT DATABASE() AS db, @@foreign_key_checks AS fk');
        $this->assertSame('iches_unisya', $selected->db);
        $this->assertSame(1, (int) $selected->fk);
        if (! Schema::hasTable('stored_files')) {
            $this->markTestSkipped('Draft stored_files migration has not been applied. Tests never apply migrations.');
        }
        $this->connection->beginTransaction();
        Storage::fake('local');
    }

    protected function tearDown(): void
    {
        try {
            while ($this->connection && $this->connection->transactionLevel() > 0) {
                $this->connection->rollBack();
            }
        } finally {
            parent::tearDown();
        }
    }

    public function test_real_metadata_scopes_signed_download_and_role_revocation(): void
    {
        $fixtures = Fixtures::create();
        ['user' => $user, 'otherUser' => $other, 'event' => $event, 'otherEvent' => $otherEvent] = $fixtures;
        $grant = Fixtures::assign($user, $event);
        $otherGrant = Fixtures::assign($user, $otherEvent);
        $key = 'assignment:'.$grant->id;
        $content = "%PDF-1.4\n%%EOF\n";
        $path = 'files/'.$event->id.'/abstract_pdf/'.Str::uuid7().'.pdf';
        Storage::disk('local')->put($path, $content);
        $file = StoredFile::forceCreate([
            'event_id' => $event->id, 'owner_user_id' => $user->id, 'uploaded_by_user_id' => $user->id,
            'kind' => 'abstract_pdf', 'disk' => 'local', 'storage_key' => $path,
            'original_name' => 'synthetic.pdf', 'detected_mime' => 'application/pdf',
            'size_bytes' => strlen($content), 'sha256' => hash('sha256', $content), 'visibility' => 'private',
        ]);
        $this->assertSame('7', $file->id[14]);
        $this->assertTrue(Gate::forUser($user)->allows('download', [$file, $key]));
        foreach (['participant', 'reviewer', 'cohost_pic', 'superadmin'] as $role) {
            $foreign = Fixtures::assign($other, $event, null, $role);
            $this->assertFalse(Gate::forUser($other)->allows('download', [$file, 'assignment:'.$foreign->id]));
        }
        $url = app(PrivateFiles::class)->temporaryDownloadUrl($user, $file, $key);
        $this->actingAs($user)->withSession(['workspace' => $key]);
        $this->assertSame($content, $this->get($url)->assertOk()->streamedContent());
        $this->withSession(['workspace' => 'assignment:'.$otherGrant->id])->get($url)->assertForbidden();
        $this->withSession(['workspace' => $key]);
        $grant->forceFill(['revoked_at' => now()])->save();
        $this->get($url)->assertForbidden();
        $this->actingAs($other)->get($url)->assertForbidden();
        $this->get(URL::temporarySignedRoute('files.download', now()->addMinutes(5), ['storedFile' => Str::uuid7(), 'actor' => $other->id]))->assertNotFound();
    }

    public function test_intake_rejects_outer_transaction_and_cleans_bytes_before_caller_can_rollback(): void
    {
        ['user' => $user, 'event' => $event] = Fixtures::create();
        $grant = Fixtures::assign($user, $event);
        $before = StoredFile::count();
        try {
            app(PrivateFiles::class)->storePrivate($user, $event, 'assignment:'.$grant->id, 'abstract_pdf',
                UploadedFile::fake()->createWithContent('test.pdf', "%PDF-1.4\n%%EOF\n"));
            $this->fail('Outer transaction must be rejected.');
        } catch (RuntimeException) {
            $this->assertSame($before, StoredFile::count());
            $this->assertSame([], Storage::disk('local')->allFiles());
        }
    }

    public function test_draft_schema_has_unique_key_scope_index_and_restrictive_foreign_keys(): void
    {
        $indexes = collect(Schema::getIndexes('stored_files'))->keyBy('name');
        $this->assertTrue($indexes['stored_files_storage_key_unique']['unique']);
        $this->assertSame(['event_id', 'owner_user_id', 'kind', 'created_at'], $indexes['stored_files_owner_scope_index']['columns']);
        $foreignKeys = Schema::getForeignKeys('stored_files');
        $this->assertCount(3, $foreignKeys);
        foreach ($foreignKeys as $key) {
            $this->assertSame('restrict', strtolower($key['on_delete']));
        }
    }
}
