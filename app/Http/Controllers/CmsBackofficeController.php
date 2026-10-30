<?php

namespace App\Http\Controllers;

use App\Models\CmsPage;
use App\Models\CmsPublicationEvent;
use App\Models\Event;
use App\Services\Cms\PageSnapshotValidator;
use App\Services\Workspaces;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;

class CmsBackofficeController
{
    public function index(Request $request, Workspaces $workspaces)
    {
        $event = $this->event($request, $workspaces);
        $input = $request->validate(['search' => ['nullable', 'string', 'max:120'], 'page' => ['nullable', 'integer', 'min:1']]);
        $available = $this->available();
        $pages = $available ? CmsPage::where('event_id', $event->id)
            ->when($input['search'] ?? '', fn ($query, $search) => $query->where('slug', 'like', '%'.addcslashes($search, '%_\\').'%'))
            ->with(['draftRevision:id,page_id,version', 'publishedRevision:id,page_id,version'])
            ->orderBy('slug')->paginate(20)->withQueryString()->through(fn ($page) => [
                'id' => $page->id, 'slug' => $page->slug,
                'draft_version' => $page->draftRevision?->version, 'published_version' => $page->publishedRevision?->version,
                'updated_at' => $page->updated_at?->toIso8601String(),
            ]) : null;

        return $this->render('cms/Pages', $this->context($request, $workspaces, $event) + [
            'schemaAvailable' => $available, 'pages' => $pages, 'search' => $input['search'] ?? '',
        ]);
    }

    public function show(Request $request, string $page, Workspaces $workspaces, PageSnapshotValidator $validator)
    {
        $event = $this->event($request, $workspaces);
        if (! $this->available()) {
            return $this->render('cms/Pages', $this->context($request, $workspaces, $event) + [
                'schemaAvailable' => false, 'pages' => null, 'search' => '',
            ]);
        }
        $page = CmsPage::where('event_id', $event->id)->whereKey($page)->firstOrFail();
        Gate::authorize('preview', [$page, $event, $request->session()->get('workspace')]);
        $revision = $page->draftRevision;

        return $this->render('cms/Editor', $this->context($request, $workspaces, $event) + [
            'cmsPage' => ['id' => $page->id, 'slug' => $page->slug,
                'draft_revision_id' => $page->draft_revision_id, 'published_revision_id' => $page->published_revision_id],
            'snapshot' => $revision ? $validator->validate($revision->snapshot) : null,
            'catalog' => $validator->catalog(),
            'revisions' => $page->revisions()->orderByDesc('version')->limit(50)
                ->get(['id', 'version', 'created_at'])->map(fn ($item) => [
                    'id' => $item->id, 'version' => $item->version, 'created_at' => $item->created_at?->toIso8601String(),
                ]),
            'audit' => CmsPublicationEvent::where('page_id', $page->id)->orderByDesc('id')->limit(50)
                ->get(['id', 'action', 'from_revision_id', 'to_revision_id', 'occurred_at'])->map(fn ($item) => [
                    'id' => $item->id, 'action' => $item->action, 'from_revision_id' => $item->from_revision_id,
                    'to_revision_id' => $item->to_revision_id, 'occurred_at' => $item->occurred_at?->toIso8601String(),
                ]),
        ]);
    }

    private function event(Request $request, Workspaces $workspaces): Event
    {
        $workspace = $request->session()->get('workspace');
        abort_unless(is_string($workspace) && preg_match('/^assignment:[1-9][0-9]*$/D', $workspace), 403);
        $grant = $workspaces->selected($request->user(), $workspace)->whereNull('institution_id')
            ->where('role_key', 'superadmin')->with('event')->first();
        abort_unless($grant, 403);
        Gate::authorize('create', [CmsPage::class, $grant->event, $workspace]);

        return $grant->event;
    }

    private function available(): bool
    {
        return Schema::hasTable('cms_pages') && Schema::hasTable('cms_page_revisions') && Schema::hasTable('cms_publication_events');
    }

    private function context(Request $request, Workspaces $workspaces, Event $event): array
    {
        return ['account' => $request->user()->only('name', 'email'),
            'workspaces' => $workspaces->choices($request->user()), 'activeWorkspace' => $request->session()->get('workspace'),
            'cmsEvent' => ['id' => $event->id, 'name' => $event->name]];
    }

    private function render(string $component, array $props)
    {
        return Inertia::render($component, $props)->toResponse(request())->withHeaders([
            'Cache-Control' => 'private, no-store', 'X-Robots-Tag' => 'noindex, nofollow, noarchive',
        ]);
    }
}
