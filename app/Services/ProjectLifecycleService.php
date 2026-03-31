<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Stage;
use App\Models\User;
use App\Notifications\ProjectDecisionNotification;
use App\Support\Statuses\ProjectStatus;
use App\Support\Statuses\StageStatus;

class ProjectLifecycleService
{
    protected $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    public function decide(Project $project, $decision, User $admin, $notes = null)
    {
        $project->status = $decision;
        $project->decided_at = now();
        $project->decision_notes = $notes;
        if ($decision === ProjectStatus::ACCEPTED && ! $project->started_at) {
            $project->started_at = now();
            $project->status = ProjectStatus::IN_PROGRESS;
        }
        $project->save();

        $project->entrepreneur->notify(new ProjectDecisionNotification($project));
        $this->activityLogService->log($project, 'project_decided', [
            'decision' => $decision,
            'notes' => $notes,
        ], $admin);

        return $project;
    }

    public function assignMentor(Project $project, User $mentor, User $admin)
    {
        $project->mentor_id = $mentor->id;
        if ($project->status === ProjectStatus::ACCEPTED) {
            $project->status = ProjectStatus::IN_PROGRESS;
            $project->started_at = $project->started_at ?: now();
        }
        $project->save();

        $this->activityLogService->log($project, 'mentor_assigned', [
            'mentor_id' => $mentor->id,
        ], $admin);

        return $project;
    }

    public function recalculateProjectProgress(Project $project)
    {
        $stages = $project->stages()->get();
        if ($stages->isEmpty()) {
            return 0;
        }

        $completed = $stages->where('status', StageStatus::COMPLETED)->count();
        $progress = (int) floor(($completed / $stages->count()) * 100);

        if ($progress === 100) {
            $project->status = ProjectStatus::COMPLETED;
            $project->completed_at = now();
            $project->save();
        }

        return $progress;
    }

    public function canStartStage(Stage $stage)
    {
        $previous = Stage::where('project_id', $stage->project_id)
            ->where('stage_order', '<', $stage->stage_order)
            ->orderByDesc('stage_order')
            ->first();

        return ! $previous || $previous->status === StageStatus::COMPLETED;
    }
}

