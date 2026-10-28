<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Institution;
use App\Services\Workspaces;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class WorkspaceController
{
    public function index(Request $request, Workspaces $workspaces)
    {
        return response()->json(['workspaces' => $workspaces->choices($request->user()), 'activeWorkspace' => $workspaces->current($request)]);
    }

    public function store(Request $request, Workspaces $workspaces)
    {
        $input = $request->validate(['workspace' => ['required', 'string', 'max:100']]);
        $key = $input['workspace'];
        abort_unless($key === 'personal' || $workspaces->selected($request->user(), $key)->exists(), 403);
        $request->session()->put('workspace', $key);

        return redirect()->route('dashboard');
    }

    public function event(Request $request, Event $event, Workspaces $workspaces)
    {
        Gate::authorize('view', [$event, $workspaces->current($request)]);

        return response()->json(['id' => $event->id, 'name' => $event->name]);
    }

    public function institution(Request $request, Event $event, Institution $institution, Workspaces $workspaces)
    {
        Gate::authorize('view', [$institution, $event, $workspaces->current($request)]);

        return response()->json(['id' => $institution->id, 'name' => $institution->official_name]);
    }
}
