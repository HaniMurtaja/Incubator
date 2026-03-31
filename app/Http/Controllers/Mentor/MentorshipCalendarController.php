<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mentor\StoreAvailabilityRequest;
use App\Models\MentorAvailabilitySlot;
use App\Models\MeetingRequest;
use Illuminate\Http\Request;

class MentorshipCalendarController extends Controller
{
    public function index(Request $request)
    {
        $requests = MeetingRequest::with(['project', 'entrepreneur'])
            ->where('mentor_id', $request->user()->id)
            ->orderBy('requested_for')
            ->paginate(10, ['*'], 'meetings_page')
            ->withQueryString();

        $slots = MentorAvailabilitySlot::where('mentor_id', $request->user()->id)
            ->orderByDesc('starts_at')
            ->paginate(10, ['*'], 'slots_page')
            ->withQueryString();

        return view('mentor.calendar.index', compact('requests', 'slots'));
    }

    public function events(Request $request)
    {
        $mentorId = $request->user()->id;
        $events = [];

        foreach (MentorAvailabilitySlot::where('mentor_id', $mentorId)->get() as $slot) {
            $events[] = [
                'id' => 'availability-'.$slot->id,
                'title' => app()->getLocale() === 'ar' ? 'متاح' : 'Available',
                'start' => $slot->starts_at->toIso8601String(),
                'end' => $slot->ends_at->toIso8601String(),
                'backgroundColor' => '#2fb344',
                'borderColor' => '#2fb344',
                'extendedProps' => ['kind' => 'availability', 'slotId' => $slot->id],
            ];
        }

        foreach (MeetingRequest::with('project')->where('mentor_id', $mentorId)->get() as $meeting) {
            if (! $meeting->requested_for) {
                continue;
            }
            $title = optional($meeting->project)->title ?? 'Meeting';
            $events[] = [
                'id' => 'meeting-'.$meeting->id,
                'title' => $title,
                'start' => $meeting->requested_for->toIso8601String(),
                'end' => $meeting->requested_for->copy()->addHour()->toIso8601String(),
                'backgroundColor' => '#206bc4',
                'borderColor' => '#206bc4',
                'extendedProps' => ['kind' => 'meeting', 'status' => $meeting->status],
            ];
        }

        return response()->json($events);
    }

    public function storeAvailability(StoreAvailabilityRequest $request)
    {
        MentorAvailabilitySlot::create([
            'mentor_id' => $request->user()->id,
            'starts_at' => $request->input('starts_at'),
            'ends_at' => $request->input('ends_at'),
            'note' => $request->input('note'),
        ]);

        return redirect()->route('mentor.calendar.index')
            ->with('status', app()->getLocale() === 'ar' ? 'تم حفظ التوفر.' : 'Availability saved.');
    }

    public function destroyAvailability(Request $request, MentorAvailabilitySlot $slot)
    {
        abort_unless($slot->mentor_id === $request->user()->id, 403);
        $slot->delete();

        return redirect()->route('mentor.calendar.index')
            ->with('status', app()->getLocale() === 'ar' ? 'تم حذف الفترة.' : 'Availability removed.');
    }
}
