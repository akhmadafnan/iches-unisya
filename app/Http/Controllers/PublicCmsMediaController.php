<?php

namespace App\Http\Controllers;

use App\Models\StoredFile;
use App\Services\Cms\PageRevisionWriter;
use App\Services\Cms\PageSnapshotValidator;
use App\Services\Cms\PublishedCmsPageReader;
use App\Services\Files\PrivateFiles;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

class PublicCmsMediaController
{
    public function __invoke(string $event, string $page, string $section, string $media,
        PublishedCmsPageReader $reader, PageSnapshotValidator $validator, StoredFile $files,
        PageRevisionWriter $writer, PrivateFiles $storage)
    {
        try {
            [$event, , $revision] = $reader->published($event, $page);
            $snapshot = $validator->validate($revision->snapshot);
            abort_unless(in_array($snapshot['schema_version'], [2, 3], true) && $snapshot['locales']['en']['status'] === 'ready', 404);
            $selected = collect($snapshot['sections'])->firstWhere('id', $section);
            abort_unless($selected && $selected['visible'] && $selected['media']
                && $selected['media']['file_id'] === $media && in_array($media, $selected['media_ids'], true)
                && collect($selected['locales'])->contains(fn ($translation) => $translation['status'] === 'ready')
                && collect($selected['media']['locales'])->contains(fn ($translation) => $translation['status'] === 'ready'), 404);
            $file = $files->newQuery()->where('event_id', $event->id)->where('kind', 'cms_image')->whereKey($media)->firstOrFail();
            abort_unless($file->id === $media, 404);
            $bytes = $writer->imageBytes($file, $event, $storage);

            return response($bytes)->withHeaders([
                'Content-Type' => $file->detected_mime, 'X-Content-Type-Options' => 'nosniff',
                'Cache-Control' => 'private, no-store', 'Content-Disposition' => 'inline',
                'Content-Security-Policy' => "default-src 'none'; sandbox",
            ]);
        } catch (QueryException|ValidationException) {
            abort(404);
        }
    }
}
