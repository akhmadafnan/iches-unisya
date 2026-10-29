<?php

namespace App\Http\Controllers;

use App\Models\StoredFile;
use App\Services\Files\PrivateFiles;
use App\Services\Workspaces;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class StoredFileDownloadController
{
    public function __invoke(Request $request, StoredFile $storedFile, PrivateFiles $files, Workspaces $workspaces)
    {
        abort_unless($request->query('actor') === $request->user()->getKey(), 403);
        Gate::authorize('download', [$storedFile, $workspaces->current($request)]);
        $stream = $files->verifiedStream($storedFile);
        Log::info('Private file download authorized.', ['file_id' => $storedFile->id, 'actor_id' => $request->user()->getKey()]);

        return response()->streamDownload(function () use ($stream): void {
            try {
                fpassthru($stream);
            } finally {
                fclose($stream);
            }
        }, $storedFile->original_name, [
            'Content-Type' => 'application/octet-stream',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, no-store',
        ]);
    }
}
