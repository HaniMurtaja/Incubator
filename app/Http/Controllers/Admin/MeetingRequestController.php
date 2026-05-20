<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MeetingRequest;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class MeetingRequestController extends Controller
{
    /** @var array<int, string> */
    public const STATUSES = ['requested', 'approved', 'rejected', 'done', 'postponed', 'in_progress'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tab = $request->input('tab', 'requests');
        $filters = $request->only(['project_id', 'status', 'mentor_id', 'entrepreneur_id']);

        $hasActiveFilter = $request->filled('project_id')
            || $request->filled('status')
            || $request->filled('mentor_id')
            || $request->filled('entrepreneur_id');

        $query = MeetingRequest::with(['project', 'mentor', 'entrepreneur']);

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->input('project_id'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('mentor_id')) {
            $query->where('mentor_id', $request->input('mentor_id'));
        }
        if ($request->filled('entrepreneur_id')) {
            $query->where('entrepreneur_id', $request->input('entrepreneur_id'));
        }

        if ($hasActiveFilter) {
            $requests = $query->latest('requested_for')->paginate(15)->withQueryString();
        } else {
            $requests = new LengthAwarePaginator([], 0, 15, (int) $request->input('page', 1), [
                'path' => $request->url(),
                'query' => $request->query(),
                'pageName' => 'page',
            ]);
        }

        $projects = Project::with(['mentor:id,name', 'entrepreneur:id,name'])->orderBy('title')->get();
        $mentors = User::whereHas('roles', function ($q) {
            $q->where('name', 'Mentor');
        })->orderBy('name')->get();
        $entrepreneurs = User::whereHas('roles', function ($q) {
            $q->where('name', 'Entrepreneur');
        })->orderBy('name')->get();

        $projectStats = Project::query()
            ->withCount([
                'meetingRequests as meetings_total',
                'meetingRequests as meetings_done' => function ($q) {
                    $q->where('status', 'done');
                },
                'meetingRequests as meetings_in_progress' => function ($q) {
                    $q->where('status', 'in_progress');
                },
                'meetingRequests as meetings_postponed' => function ($q) {
                    $q->where('status', 'postponed');
                },
            ])
            ->orderBy('title')
            ->get();

        return view('admin.meetings.index', [
            'requests' => $requests,
            'projects' => $projects,
            'mentors' => $mentors,
            'entrepreneurs' => $entrepreneurs,
            'filters' => $filters,
            'tab' => $tab,
            'projectStats' => $projectStats,
            'statuses' => self::STATUSES,
            'hasActiveFilter' => $hasActiveFilter,
        ]);
    }

    /**
     * JSON events for admin calendar (all mentor & entrepreneur meetings).
     */
    public function calendarEvents(Request $request)
    {
        $query = MeetingRequest::with(['project', 'mentor', 'entrepreneur']);

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->input('project_id'));
        }

        $events = [];
        foreach ($query->orderBy('requested_for')->get() as $meeting) {
            if (! $meeting->requested_for) {
                continue;
            }
            $isAr = app()->getLocale() === 'ar';
            $proj = optional($meeting->project)->title ?? ($isAr ? 'اجتماع' : 'Meeting');
            $mentor = optional($meeting->mentor)->name ?? '';
            $ent = optional($meeting->entrepreneur)->name ?? '';
            $title = $isAr
                ? $proj.' — '.$mentor.' / '.$ent.' ('.$meeting->status.')'
                : $proj.' — '.$mentor.' · '.$ent.' ('.$meeting->status.')';

            $colors = [
                'requested' => ['#f59f00', '#f59f00'],
                'approved' => ['#206bc4', '#206bc4'],
                'rejected' => ['#d63939', '#d63939'],
                'done' => ['#2fb344', '#2fb344'],
                'postponed' => ['#795548', '#795548'],
                'in_progress' => ['#ae3ec9', '#ae3ec9'],
            ];
            $pair = $colors[$meeting->status] ?? ['#206bc4', '#206bc4'];

            $events[] = [
                'id' => 'admin-meeting-'.$meeting->id,
                'title' => $title,
                'start' => $meeting->requested_for->toIso8601String(),
                'end' => $meeting->requested_for->copy()->addMinutes((int) ($meeting->duration_minutes ?: 60))->toIso8601String(),
                'backgroundColor' => $pair[0],
                'borderColor' => $pair[1],
                'extendedProps' => [
                    'status' => $meeting->status,
                    'projectId' => $meeting->project_id,
                ],
            ];
        }

        return response()->json($events);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return redirect()->route('admin.meetings.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'project_id' => ['nullable', 'exists:projects,id'],
            'mentor_id' => ['required', 'exists:users,id'],
            'entrepreneur_id' => ['required', 'exists:users,id'],
            'requested_for' => ['required', 'date'],
            'status' => ['required', 'in:'.implode(',', self::STATUSES)],
            'agenda' => ['nullable', 'string'],
        ]);

        MeetingRequest::create($data);

        return back()->with('status', 'Meeting request added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect()->route('admin.meetings.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return redirect()->route('admin.meetings.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'status' => ['required', 'in:'.implode(',', self::STATUSES)],
            'agenda' => ['nullable', 'string'],
            'requested_for' => ['required', 'date'],
        ]);

        MeetingRequest::findOrFail($id)->update($data);

        return back()->with('status', 'Meeting request updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        MeetingRequest::findOrFail($id)->delete();

        return back()->with('status', 'Meeting request deleted.');
    }
}
