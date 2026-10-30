<?php

namespace App\Http\Controllers;

use App\Models\CmsPage;
use App\Models\CmsPageRevision;
use App\Models\Event;
use App\Services\Cms\CmsPublicationLifecycle;
use App\Services\Cms\PageRevisionWriter;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class CmsLifecycleController
{
    public function create(Request $request, Event $event)
    {
        try {
            $page = DB::transaction(function () use ($request, $event): CmsPage {
                Gate::authorize('create', [CmsPage::class, $event, $this->workspace($request)]);
                $input = $request->validate(['slug' => ['required', 'string', 'max:120']]);
                CmsPage::validateSlug($input['slug']);

                return CmsPage::create(['event_id' => $event->id, 'slug' => $input['slug']]);
            }, 3);
        } catch (QueryException $exception) {
            if (($exception->errorInfo[1] ?? null) !== 1062) {
                throw $exception;
            }
            throw ValidationException::withMessages(['slug' => 'This event already has a page with that slug.']);
        }

        return response()->json(['page_id' => $page->id, 'slug' => $page->slug], 201);
    }

    public function draft(Request $request, Event $event, string $page, PageRevisionWriter $writer)
    {
        $page = $this->page($event, $page);
        $revision = $writer->saveDraft($request->user(), $event, $page, $request->input('snapshot'), $this->workspace($request));

        return response()->json($this->metadata($revision), 201);
    }

    public function preview(Request $request, Event $event, string $page, PageRevisionWriter $writer)
    {
        $payload = DB::transaction(function () use ($request, $event, $page, $writer): array {
            $page = CmsPage::where('event_id', $event->id)->whereKey($page)->lockForUpdate()->firstOrFail();
            Gate::authorize('preview', [$page, $event, $this->workspace($request)]);
            abort_unless($page->draft_revision_id, 409, 'No draft available.');
            $revision = $page->revisions()->whereKey($page->draft_revision_id)->firstOrFail();

            return $this->metadata($revision) + ['snapshot' => $writer->validateSnapshot($event, $revision->snapshot)];
        });

        return response()->json($payload)->withHeaders([
            'Cache-Control' => 'private, no-store', 'X-Robots-Tag' => 'noindex, nofollow, noarchive',
        ]);
    }

    public function publish(Request $request, Event $event, string $page, CmsPublicationLifecycle $lifecycle)
    {
        return $this->publication($request, $event, $page, $lifecycle, 'publish');
    }

    public function restore(Request $request, Event $event, string $page, CmsPublicationLifecycle $lifecycle)
    {
        return $this->publication($request, $event, $page, $lifecycle, 'restore');
    }

    private function publication(Request $request, Event $event, string $id, CmsPublicationLifecycle $lifecycle, string $action)
    {
        $page = $this->page($event, $id);
        Gate::authorize($action, [$page, $event, $this->workspace($request)]);
        $input = $request->validate(['revision_id' => ['required', 'uuid']]);
        $revision = $lifecycle->$action($request->user(), $event, $page, $this->workspace($request), $input['revision_id']);

        return response()->json($this->metadata($revision));
    }

    private function workspace(Request $request): ?string
    {
        $workspace = $request->session()->get('workspace');

        return is_string($workspace) ? $workspace : null;
    }

    private function page(Event $event, string $id): CmsPage
    {
        return CmsPage::where('event_id', $event->id)->whereKey($id)->firstOrFail();
    }

    private function metadata(CmsPageRevision $revision): array
    {
        return ['page_id' => $revision->page_id, 'revision_id' => $revision->id, 'version' => $revision->version];
    }
}
