<?php

namespace App\Services\Files;

use App\Models\Event;
use App\Models\StoredFile;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use RuntimeException;
use Throwable;

class PrivateFiles
{
    private const TYPES = [
        'abstract_pdf' => ['pdf' => 'application/pdf'],
        'payment_proof' => ['pdf' => 'application/pdf', 'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png'],
        'cms_image' => ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'webp' => 'image/webp'],
    ];

    public function storePrivate(User $actor, Event $event, string $workspace, string $kind, UploadedFile $upload): StoredFile
    {
        Gate::forUser($actor)->authorize('create', [StoredFile::class, $event, $workspace]);
        [$extension, $mime, $size] = $this->validate($kind, $upload);
        $file = new StoredFile;
        $file->forceFill([
            'id' => $file->newUniqueId(),
            'event_id' => $event->getKey(),
            'uploaded_by_user_id' => $actor->getKey(),
            'owner_user_id' => $actor->getKey(),
            'kind' => $kind,
            'disk' => 'local',
            'storage_key' => 'files/'.$event->getKey().'/'.$kind.'/'.Str::uuid7().'.'.$extension,
            'original_name' => $this->safeName($upload->getClientOriginalName(), $extension),
            'detected_mime' => $mime,
            'size_bytes' => $size,
            'sha256' => hash_file('sha256', $upload->getRealPath()),
            'visibility' => 'private',
        ]);
        $disk = Storage::disk('local');
        $this->rejectSymlinks($disk->path($file->storage_key));
        $stream = fopen($upload->getRealPath(), 'rb');
        if ($stream === false) {
            throw new RuntimeException('Unable to read uploaded file.');
        }
        try {
            if (! $disk->put($file->storage_key, $stream, ['visibility' => 'private'])) {
                throw new RuntimeException('Private storage write failed.');
            }
            $verified = $this->verifiedStream($file);
            fclose($verified);
            $this->persist($file);
        } catch (Throwable $exception) {
            try {
                if (! $disk->delete($file->storage_key)) {
                    Log::error('Private file cleanup failed.', ['file_id' => $file->id]);
                }
            } catch (Throwable) {
                Log::error('Private file cleanup failed.', ['file_id' => $file->id]);
            }
            throw new RuntimeException('Unable to store private file.', previous: $exception);
        } finally {
            if (is_resource($stream)) {
                fclose($stream);
            }
        }

        return $file;
    }

    protected function persist(StoredFile $file): void
    {
        // An outer transaction could roll back after this service returns and leave an orphan.
        if (DB::connection()->transactionLevel() !== 0) {
            throw new RuntimeException('Private intake must run outside an ambient database transaction.');
        }
        DB::transaction(function () use ($file): void {
            if (! $file->save()) {
                throw new RuntimeException('Private file metadata was not saved.');
            }
        });
    }

    public function temporaryDownloadUrl(User $actor, StoredFile $file, string $workspace): string
    {
        // Never authorize an outdated in-memory owner/event after a resource change.
        $file = $file->fresh() ?? abort(404);
        Gate::forUser($actor)->authorize('download', [$file, $workspace]);

        return URL::temporarySignedRoute('files.download', now()->addSeconds(
            max(1, min(300, (int) config('filesystems.private_download_ttl', 300)))
        ), ['storedFile' => $file->id, 'actor' => $actor->getKey()]);
    }

    public function verifiedStream(StoredFile $file)
    {
        abort_unless($file->disk === 'local' && $file->visibility === 'private'
            && isset(self::TYPES[$file->kind])
            && preg_match('~^files/[0-9a-f-]{36}/[a-z_]+/[0-9a-f-]{36}\.(pdf|jpg|jpeg|png|webp)$~D', $file->storage_key), 404);
        $disk = Storage::disk('local');
        // Reject symlinks, including symlinked parent directories, on the local filesystem.
        $this->rejectSymlinks($disk->path($file->storage_key));
        try {
            $stream = $disk->readStream($file->storage_key);
        } catch (Throwable) {
            abort(404);
        }
        abort_unless(is_resource($stream), 404);
        $hash = hash_init('sha256');
        $size = hash_update_stream($hash, $stream);
        if ($size !== $file->size_bytes || ! hash_equals($file->sha256, hash_final($hash)) || ! rewind($stream)) {
            fclose($stream);
            abort(404);
        }

        return $stream;
    }

    private function validate(string $kind, UploadedFile $upload): array
    {
        if (! $upload->isValid()) {
            throw ValidationException::withMessages(['file' => 'Invalid uploaded file.']);
        }
        $extension = strtolower($upload->getClientOriginalExtension());
        $size = $upload->getSize();
        $limit = (int) config('filesystems.private_file_limits.'.$kind, $kind === 'abstract_pdf' ? 10 * 1024 * 1024 : 5 * 1024 * 1024);
        $mime = $upload->getMimeType();
        $valid = isset(self::TYPES[$kind][$extension])
            && self::TYPES[$kind][$extension] === $mime && $size > 0 && $size <= $limit
            && ! is_link($upload->getPathname());
        if ($valid && $mime === 'application/pdf') {
            $valid = file_get_contents($upload->getRealPath(), false, null, 0, 5) === '%PDF-';
        } elseif ($valid) {
            $image = @getimagesize($upload->getRealPath());
            $valid = $image !== false && $image['mime'] === $mime;
        }
        if (! $valid) {
            throw ValidationException::withMessages(['file' => 'Invalid file type, content or size.']);
        }

        return [$extension, $mime, $size];
    }

    private function rejectSymlinks(string $path): void
    {
        while ($path !== dirname($path)) {
            abort_if(is_link($path), 404);
            $path = dirname($path);
        }
    }

    private function safeName(string $name, string $extension): string
    {
        $base = pathinfo(basename(str_replace('\\', '/', $name)), PATHINFO_FILENAME);
        $base = trim(preg_replace('/[^A-Za-z0-9 _.-]/', '_', $base), ' .');

        return substr($base ?: 'download', 0, 150).'.'.$extension;
    }
}
