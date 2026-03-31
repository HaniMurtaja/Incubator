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

        if ($project) {
            $this->ensureNineStages($project->id);
            $stages = Stage::where('project_id', $project->id)->orderBy('stage_order')->get();
        }

        return view('mentor.command-center.index', compact('projects', 'project', 'stages'));
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
