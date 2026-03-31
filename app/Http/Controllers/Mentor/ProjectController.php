<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEvaluationRequest;
use App\Models\Project;
use App\Models\TaskSubmission;
use App\Services\SubmissionEvaluationService;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    protected $submissionEvaluationService;

    public function __construct(SubmissionEvaluationService $submissionEvaluationService)
    {
        $this->submissionEvaluationService = $submissionEvaluationService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['q', 'status']);
        $query = Project::with(['entrepreneur', 'stages.tasks'])
            ->where('mentor_id', auth()->id());

        if ($request->filled('q')) {
            $term = trim($request->input('q'));
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', '%'.$term.'%')
                    ->orWhere('description', 'like', '%'.$term.'%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $projects = $query->latest()->paginate(10)->withQueryString();

        return view('mentor.projects.index', compact('projects', 'filters'));
    }

    public function show(Project $project)
    {
        abort_unless((int) $project->mentor_id === (int) auth()->id(), 403);
        $project->load(['entrepreneur', 'stages.tasks.submissions.evaluation', 'activityLogs.actor']);

        return view('mentor.projects.show', compact('project'));
    }

    public function evaluate(StoreEvaluationRequest $request, TaskSubmission $submission)
    {
        $project = $submission->task->stage->project;
        abort_unless((int) $project->mentor_id === (int) auth()->id(), 403);

        $data = $request->validated();
        $this->submissionEvaluationService->evaluateSubmission(
            $submission,
            $request->user(),
            $data['decision'],
            isset($data['comments']) ? $data['comments'] : null
        );

        return back()->with('status', 'Submission reviewed successfully.');
    }
}
