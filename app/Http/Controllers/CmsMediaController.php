<?php

namespace App\Http\Controllers;

use App\Models\CmsPage;
use App\Models\Event;
use App\Models\StoredFile;
use App\Services\Files\PrivateFiles;
use App\Services\Workspaces;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CmsMediaController
{
    public function index(Request $request, string $event, Workspaces $workspaces, StoredFile $files)
    {
        $event = $this->event($request, $event, $workspaces);
        $request->validate(['page' => ['nullable', 'integer', 'min:1', 'max:100000']]);
        $images = $files->newQuery()->where('event_id', $event->id)->where('kind', 'cms_image')
            ->where('disk', 'local')->where('visibility', 'private')->orderByDesc('id')
            ->paginate(20, ['id', 'original_name', 'detected_mime', 'size_bytes'])
            ->through(fn ($file) => $file->only(['id', 'original_name', 'detected_mime', 'size_bytes']));

        return response()->json($images)->withHeaders(['Cache-Control' => 'private, no-store']);
    }

    public function store(Request $request, string $event, Workspaces $workspaces, PrivateFiles $files)
    {
        $event = $this->event($request, $event, $workspaces);
        $request->validate(['file' => ['required', 'file', 'max:5120']]);
        $file = $files->storePrivate($request->user(), $event, $request->session()->get('workspace'), 'cms_image', $request->file('file'));

        return response()->json($file->only(['id', 'original_name', 'detected_mime', 'size_bytes']), 201)
            ->withHeaders(['Cache-Control' => 'private, no-store']);
    }

    private function event(Request $request, string $id, Workspaces $workspaces): Event
    {
        $workspace = $request->session()->get('workspace');
        abort_unless(is_string($workspace) && preg_match('/^assignment:[1-9][0-9]*$/D', $workspace), 403);
        $grant = $workspaces->selected($request->user(), $workspace)->whereNull('institution_id')
            ->where('role_key', 'superadmin')->with('event')->first();
        abort_unless($grant && $grant->event->id === $id, 403);
        Gate::authorize('create', [CmsPage::class, $grant->event, $workspace]);

        return $grant->event;
    }
}
