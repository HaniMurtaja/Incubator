<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mentor\StoreAvailabilityRequest;
use App\Models\MentorAvailabilitySlot;
use App\Models\MeetingRequest;
use App\Models\Project;
use App\Models\User;
use App\Notifications\MeetingScheduledNotification;
use Illuminate\Http\Request;

class MentorshipCalendarController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->input('tab', 'availability');
        $requests = MeetingRequest::with(['project', 'entrepreneur'])
            ->where('mentor_id', $request->user()->id)
            ->whereIn('status', ['approved', 'done'])
            ->orderBy('requested_for')
            ->paginate(10, ['*'], 'meetings_page')
            ->withQueryString();
        $meetingRequests = MeetingRequest::with(['project', 'entrepreneur'])
            ->where('mentor_id', $request->user()->id)
            ->where('status', 'requested')
            ->orderBy('requested_for')
            ->paginate(10, ['*'], 'requests_page')
            ->withQueryString();

        $slots = MentorAvailabilitySlot::where('mentor_id', $request->user()->id)
            ->orderByDesc('starts_at')
            ->paginate(10, ['*'], 'slots_page')
            ->withQueryString();

        $projects = Project::with('round')
            ->where('mentor_id', $request->user()->id)
            ->orderBy('title')
            ->get();

        return view('mentor.calendar.index', compact('requests', 'meetingRequests', 'slots', 'projects', 'tab'));
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
                'end' => $meeting->requested_for->copy()->addMinutes((int) ($meeting->duration_minutes ?: 60))->toIso8601String(),
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

    public function storeMeeting(Request $request)
    {
        $data = $request->validate([
            'project_id' => ['required', 'exists:projects,id'],
            'requested_for' => ['required', 'date'],
            'meeting_mode' => ['required', 'in:online,offline'],
            'duration_minutes' => ['required', 'in:15,30,45,60,75,90'],
            'agenda' => ['nullable', 'string'],
            'notify_members' => ['nullable', 'boolean'],
        ]);

        $project = Project::findOrFail($data['project_id']);
        abort_unless((int) $project->mentor_id === (int) $request->user()->id, 403);

        $meeting = MeetingRequest::create([
            'project_id' => $data['project_id'] ?? null,
            'mentor_id' => $request->user()->id,
            'entrepreneur_id' => optional($project)->entrepreneur_id,
            'requested_for' => $data['requested_for'],
            'status' => 'approved',
            'agenda' => $data['agenda'] ?? null,
            'meeting_mode' => $data['meeting_mode'],
            'duration_minutes' => (int) $data['duration_minutes'],
            'notify_members' => (bool) ($data['notify_members'] ?? false),
        ]);

        if ($meeting->notify_members) {
            $entrepreneur = User::find($meeting->entrepreneur_id);
            if ($entrepreneur) {
                $entrepreneur->notify(new MeetingScheduledNotification($meeting));
            }
        }

        return redirect()->route('mentor.calendar.index', ['tab' => 'upcoming'])
            ->with('status', app()->getLocale() === 'ar' ? 'تم إنشاء الاجتماع.' : 'Meeting created.');
    }

    public function updateMeetingRequestStatus(Request $request, MeetingRequest $meeting)
    {
        abort_unless((int) $meeting->mentor_id === (int) $request->user()->id, 403);
        $data = $request->validate([
            'status' => ['required', 'in:approved,rejected'],
        ]);

        $meeting->update(['status' => $data['status']]);

        return redirect()->route('mentor.calendar.index', ['tab' => 'requests'])
            ->with('status', app()->getLocale() === 'ar' ? 'تم تحديث حالة الطلب.' : 'Meeting request status updated.');
    }
}
