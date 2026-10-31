<?php

namespace App\Http\Controllers;

use App\Services\Workspaces;
use DateTimeImmutable;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class EventScheduleController
{
    public function __invoke(Request $request, string $event, Workspaces $workspaces)
    {
        $workspace = $request->session()->get('workspace');
        abort_unless(is_string($workspace) && preg_match('/^assignment:[1-9][0-9]*$/D', $workspace), 403);
        $grant = $workspaces->selected($request->user(), $workspace)->whereNull('institution_id')
            ->where('role_key', 'superadmin')->with('event')->first();
        abort_unless($grant && $grant->event->id === $event, 403);
        $event = $grant->event;
        Gate::authorize('manageSchedule', [$event, $workspace]);
        $input = $request->validate([
            'local_start' => ['required', 'string', 'date_format:Y-m-d\TH:i'],
            'timezone' => ['required', 'string', Rule::in(DateTimeZone::listIdentifiers())],
            'confirmed' => ['required', 'accepted'],
        ], [
            'local_start.*' => 'schedule.invalidTime', 'timezone.*' => 'schedule.invalidTimezone',
            'confirmed.*' => 'schedule.confirmRequired',
        ]);
        $start = $this->instant($input['local_start'], $input['timezone']);
        if ($event->ends_at && $start >= $event->ends_at) {
            throw ValidationException::withMessages(['local_start' => 'schedule.beforeEnd']);
        }
        // Only the authoritative instant and its display timezone may change.
        $event->starts_at = $start;
        $event->timezone = $input['timezone'];
        $event->save();

        return response()->json(['starts_at' => $start->format(DATE_ATOM), 'timezone' => $event->timezone])
            ->withHeaders(['Cache-Control' => 'private, no-store']);
    }

    private function instant(string $local, string $timezone): DateTimeImmutable
    {
        $wall = DateTimeImmutable::createFromFormat('!Y-m-d\TH:i', $local, new DateTimeZone('UTC'));
        if (! $wall || $wall->format('Y-m-d\TH:i') !== $local || (int) $wall->format('Y') < 1000) {
            throw ValidationException::withMessages(['local_start' => 'schedule.invalidTime']);
        }
        $zone = new DateTimeZone($timezone);
        $epoch = $wall->getTimestamp();
        $offsets = array_unique(array_column($zone->getTransitions($epoch - 172800, $epoch + 172800), 'offset'));
        $matches = [];
        foreach ($offsets as $offset) {
            $candidate = $wall->setTimestamp($epoch - $offset);
            if ($candidate->setTimezone($zone)->format('Y-m-d\TH:i') === $local) {
                $matches[] = $candidate;
            }
        }
        if (count($matches) !== 1 || (int) $matches[0]->format('Y') < 1000 || (int) $matches[0]->format('Y') > 9999) {
            throw ValidationException::withMessages(['local_start' => 'schedule.invalidTime']);
        }

        return $matches[0];
    }
}
