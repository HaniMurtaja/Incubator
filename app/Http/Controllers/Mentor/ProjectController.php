<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Stage;
use App\Models\Task;
use App\Http\Requests\StoreEvaluationRequest;
use App\Models\Project;
use App\Models\TaskSubmission;
use App\Services\SubmissionEvaluationService;
use App\Support\Statuses\StageStatus;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    protected $submissionEvaluationService;
    protected $defaultPhaseNames = [
        1 => 'Idea Maturation',
        2 => 'Market Analysis',
        3 => 'Audience Study',
        4 => 'Competitor Analysis',
        5 => 'Pricing & Sales',
        6 => 'Team & HR',
        7 => 'Financial Plans',
        8 => 'Operational Plan',
        9 => 'Investment Plan',
    ];

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
        $this->ensureNineStages($project->id);
        $activeStageOrder = (int) request('stage', 1);
        $project->load(['entrepreneur', 'stages.tasks.submissions.evaluation', 'stages.tasks.messages.user', 'activityLogs.actor']);
        if (! $project->stages->pluck('stage_order')->contains($activeStageOrder)) {
            $activeStageOrder = (int) optional($project->stages->sortBy('stage_order')->first())->stage_order;
        }

        return view('mentor.projects.show', compact('project', 'activeStageOrder'));
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

    public function storeTask(Request $request, Project $project, Stage $stage)
    {
        abort_unless((int) $project->mentor_id === (int) auth()->id(), 403);
        abort_unless((int) $stage->project_id === (int) $project->id, 404);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'mentor_comments' => ['nullable', 'string'],
            'due_date' => ['nullable', 'date'],
        ]);

        $stage->tasks()->create([
            'created_by' => auth()->id(),
            'title' => $data['title'],
            'description' => $data['description'],
            'mentor_comments' => $data['mentor_comments'] ?? null,
            'due_date' => $data['due_date'] ?? null,
            'status' => 'not_started',
        ]);

        if ($stage->status === StageStatus::NOT_STARTED) {
            $stage->update(['status' => StageStatus::IN_PROGRESS, 'started_at' => now()]);
        }

        return back()->with('status', app()->getLocale() === 'ar' ? 'تمت إضافة المهمة.' : 'Task added.');
    }

    public function updateTaskComment(Request $request, Project $project, Stage $stage, Task $task)
    {
        abort_unless((int) $project->mentor_id === (int) auth()->id(), 403);
        abort_unless((int) $stage->project_id === (int) $project->id, 404);
        abort_unless((int) $task->stage_id === (int) $stage->id, 404);

        $data = $request->validate([
            'mentor_comments' => ['nullable', 'string'],
        ]);
        $task->update(['mentor_comments' => $data['mentor_comments'] ?? null]);

        return back()->with('status', app()->getLocale() === 'ar' ? 'تم تحديث التعليق.' : 'Comment updated.');
    }

    public function updateTaskStatus(Request $request, Project $project, Stage $stage, Task $task)
    {
        abort_unless((int) $project->mentor_id === (int) auth()->id(), 403);
        abort_unless((int) $stage->project_id === (int) $project->id, 404);
        abort_unless((int) $task->stage_id === (int) $stage->id, 404);

        $data = $request->validate([
            'status' => ['required', 'in:not_started,approved'],
        ]);
        $task->update(['status' => $data['status']]);

        return back()->with('status', app()->getLocale() === 'ar' ? 'تم تحديث حالة المهمة.' : 'Task status updated.');
    }

    public function sendTaskMessage(Request $request, Project $project, Stage $stage, Task $task)
    {
        abort_unless((int) $project->mentor_id === (int) auth()->id(), 403);
        abort_unless((int) $stage->project_id === (int) $project->id, 404);
        abort_unless((int) $task->stage_id === (int) $stage->id, 404);

        $data = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        \App\Models\TaskMessage::create([
            'task_id' => $task->id,
            'user_id' => auth()->id(),
            'message' => $data['message'],
        ]);

        return back()->with('status', app()->getLocale() === 'ar' ? 'تم إرسال الرسالة.' : 'Message sent.');
    }

    protected function ensureNineStages($projectId)
    {
        foreach ($this->defaultPhaseNames as $order => $name) {
            Stage::firstOrCreate(
                ['project_id' => $projectId, 'stage_order' => $order],
                ['name' => $name, 'status' => StageStatus::NOT_STARTED]
            );
        }
    }
}
