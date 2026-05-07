<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Stage;
use App\Support\Statuses\StageStatus;
use Illuminate\Http\Request;

class CommandCenterController extends Controller
{
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

    public function index(Request $request)
    {
        $projects = Project::where('mentor_id', $request->user()->id)->orderBy('title')->get();
        $project = $projects->firstWhere('id', $request->input('project_id')) ?: $projects->first();
        $stages = collect();
        $activeStageOrder = (int) $request->input('stage', 1);

        if ($project) {
            $this->ensureNineStages($project->id);
            $stages = Stage::where('project_id', $project->id)
                ->with(['tasks' => function ($q) {
                    $q->orderByDesc('id');
                }])
                ->orderBy('stage_order')
                ->get();
            if (! $stages->pluck('stage_order')->contains($activeStageOrder)) {
                $activeStageOrder = (int) optional($stages->first())->stage_order;
            }
        }

        return view('mentor.command-center.index', compact('projects', 'project', 'stages', 'activeStageOrder'));
    }

    public function updateStage(Request $request, Stage $stage)
    {
        abort_unless((int) $stage->project->mentor_id === (int) $request->user()->id, 403);

        $data = $request->validate([
            'status' => ['required', 'in:not_started,in_progress,completed'],
        ]);

        $stage->update($data);

        return back()->with('status', 'Stage updated.');
    }

    public function storeTask(Request $request, Stage $stage)
    {
        abort_unless((int) $stage->project->mentor_id === (int) $request->user()->id, 403);
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'mentor_comments' => ['nullable', 'string'],
            'due_date' => ['nullable', 'date'],
        ]);

        $stage->tasks()->create([
            'created_by' => $request->user()->id,
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

    public function updateTaskComment(Request $request, Stage $stage, \App\Models\Task $task)
    {
        abort_unless((int) $stage->project->mentor_id === (int) $request->user()->id, 403);
        abort_unless((int) $task->stage_id === (int) $stage->id, 404);

        $data = $request->validate([
            'mentor_comments' => ['nullable', 'string'],
        ]);

        $task->update(['mentor_comments' => $data['mentor_comments'] ?? null]);

        return back()->with('status', app()->getLocale() === 'ar' ? 'تم تحديث التعليق.' : 'Comment updated.');
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
