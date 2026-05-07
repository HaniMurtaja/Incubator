<?php

namespace App\Http\Controllers\Entrepreneur;

use App\Http\Controllers\Controller;
use App\Models\IncubatorRound;
use App\Models\MentorAvailabilitySlot;
use App\Models\MeetingRequest;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->input('tab', 'upcoming');
        $meetingsQuery = MeetingRequest::with(['project', 'mentor', 'round'])
            ->where('entrepreneur_id', $request->user()->id);
        if ($tab === 'requests') {
            $meetingsQuery->where('status', 'requested');
        } else {
            $meetingsQuery->where('status', '!=', 'requested');
        }
        $meetings = $meetingsQuery
            ->orderBy('requested_for')
            ->paginate(12, ['*'], 'meetings_page')
            ->withQueryString();

        $projects = Project::where('entrepreneur_id', $request->user()->id)
            ->with('round')
            ->orderBy('title')
            ->get();
        $rounds = IncubatorRound::whereHas('projects', function ($q) use ($request) {
            $q->where('entrepreneur_id', $request->user()->id);
        })->orderByDesc('start_date')->get();
        $mentors = User::whereHas('roles', function ($q) {
            $q->where('name', 'Mentor');
        })->orderBy('name')->get();

        return view('entrepreneur.meetings.index', compact('meetings', 'projects', 'rounds', 'mentors', 'tab'));
    }

    public function availabilityEvents(Request $request)
    {
        $mentorId = $request->query('mentor_id');
        $query = MentorAvailabilitySlot::with('mentor')
            ->whereHas('mentor.roles', function ($q) {
                $q->where('name', 'Mentor');
            });

        if (! empty($mentorId)) {
            $query->where('mentor_id', $mentorId);
        }

        $events = $query->get()->map(function ($slot) {
            return [
                'id' => 'availability-'.$slot->id,
                'title' => (app()->getLocale() === 'ar' ? 'متاح: ' : 'Available: ').optional($slot->mentor)->name,
                'start' => optional($slot->starts_at)->toIso8601String(),
                'end' => optional($slot->ends_at)->toIso8601String(),
                'backgroundColor' => '#2fb344',
                'borderColor' => '#2fb344',
            ];
        })->values();

        return response()->json($events);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'project_id' => ['nullable', 'exists:projects,id'],
            'incubator_round_id' => ['nullable', 'exists:incubator_rounds,id'],
            'mentor_id' => ['required', 'exists:users,id'],
            'requested_for' => ['required', 'date'],
            'meeting_mode' => ['required', 'in:online,offline'],
            'duration_minutes' => ['required', 'in:15,30,45,60,75,90'],
            'agenda' => ['nullable', 'string'],
            'notify_members' => ['nullable', 'boolean'],
        ]);

        if (! empty($data['project_id'])) {
            $project = Project::findOrFail($data['project_id']);
            abort_unless((int) $project->entrepreneur_id === (int) $request->user()->id, 403);
        }

        MeetingRequest::create([
            'project_id' => $data['project_id'] ?? null,
            'incubator_round_id' => $data['incubator_round_id'] ?? null,
            'mentor_id' => $data['mentor_id'],
            'entrepreneur_id' => $request->user()->id,
            'requested_for' => $data['requested_for'],
            'status' => 'requested',
            'agenda' => $data['agenda'] ?? null,
            'meeting_mode' => $data['meeting_mode'],
            'duration_minutes' => (int) $data['duration_minutes'],
            'notify_members' => (bool) ($data['notify_members'] ?? true),
        ]);

        return redirect()->route('entrepreneur.meetings.index', ['tab' => 'requests'])
            ->with('status', app()->getLocale() === 'ar' ? 'تم إرسال طلب الاجتماع للموجه.' : 'Meeting request sent to mentor.');
    }
}

