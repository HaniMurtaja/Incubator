<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProjectDecisionRequest;
use App\Models\Project;
use App\Models\User;
use App\Services\ProjectLifecycleService;
use App\Support\Statuses\ProjectStatus;
use Illuminate\Http\Request;

class ProjectReviewController extends Controller
{
    protected $projectLifecycleService;

    public function __construct(ProjectLifecycleService $projectLifecycleService)
    {
        $this->projectLifecycleService = $projectLifecycleService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['q', 'status', 'mentor_id']);

        $projectsQuery = Project::with(['entrepreneur', 'mentor'])->latest();

        if ($request->filled('q')) {
            $term = trim($request->input('q'));
            $projectsQuery->where(function ($q) use ($term) {
                $q->where('title', 'like', '%'.$term.'%')
                    ->orWhere('description', 'like', '%'.$term.'%');
            });
        }

        if ($request->filled('status')) {
            $projectsQuery->where('status', $request->input('status'));
        }

        if ($request->filled('mentor_id')) {
            $projectsQuery->where('mentor_id', $request->input('mentor_id'));
        }

        $projects = $projectsQuery->paginate(10)->withQueryString();
        $mentors = User::role('Mentor')->orderBy('name')->get();
        $entrepreneurs = User::role('Entrepreneur')->orderBy('name')->get();

        return view('admin.projects.index', compact('projects', 'mentors', 'entrepreneurs', 'filters'));
    }

    public function update(UpdateProjectDecisionRequest $request, Project $project)
    {
        $data = $request->validated();
        $decision = $data['decision'];

        $this->projectLifecycleService->decide(
            $project,
            $decision,
            $request->user(),
            isset($data['decision_notes']) ? $data['decision_notes'] : null
        );

        if (! empty($data['mentor_id']) && $decision === ProjectStatus::ACCEPTED) {
            $mentor = User::role('Mentor')->findOrFail($data['mentor_id']);
            $this->projectLifecycleService->assignMentor($project->fresh(), $mentor, $request->user());
        }

        return redirect()->route('admin.projects.index')->with('status', 'Project review updated successfully.');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category' => ['nullable', 'string', 'max:255'],
            'entrepreneur_id' => ['required', 'exists:users,id'],
            'mentor_id' => ['nullable', 'exists:users,id'],
        ]);

        Project::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'category' => $data['category'] ?? null,
            'entrepreneur_id' => $data['entrepreneur_id'],
            'mentor_id' => $data['mentor_id'] ?? null,
            'status' => $data['mentor_id'] ? ProjectStatus::IN_PROGRESS : ProjectStatus::PENDING,
            'submitted_at' => now(),
            'started_at' => $data['mentor_id'] ? now() : null,
        ]);

        return back()->with('status', 'New project added successfully.');
    }
}
