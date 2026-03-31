<?php

namespace App\Services;

use App\Models\Evaluation;
use App\Models\Task;
use App\Models\TaskSubmission;
use App\Models\User;
use App\Notifications\SubmissionReviewedNotification;
use App\Notifications\TaskAssignedNotification;
use App\Support\Statuses\EvaluationDecision;
use App\Support\Statuses\StageStatus;
use App\Support\Statuses\SubmissionStatus;
use App\Support\Statuses\TaskStatus;
use Illuminate\Support\Facades\DB;

class SubmissionEvaluationService
{
    protected $activityLogService;
    protected $projectLifecycleService;

    public function __construct(ActivityLogService $activityLogService, ProjectLifecycleService $projectLifecycleService)
    {
        $this->activityLogService = $activityLogService;
        $this->projectLifecycleService = $projectLifecycleService;
    }

    public function notifyTaskAssigned(Task $task)
    {
        $entrepreneur = $task->stage->project->entrepreneur;
        $entrepreneur->notify(new TaskAssignedNotification($task));
    }

    public function evaluateSubmission(TaskSubmission $submission, User $mentor, $decision, $comments = null)
    {
        return DB::transaction(function () use ($submission, $mentor, $decision, $comments) {
            $evaluation = Evaluation::updateOrCreate(
                ['task_submission_id' => $submission->id],
                [
                    'evaluator_id' => $mentor->id,
                    'decision' => $decision,
                    'comments' => $comments,
                    'evaluated_at' => now(),
                ]
            );

            $submission->status = $decision === EvaluationDecision::APPROVED
                ? SubmissionStatus::APPROVED
                : SubmissionStatus::CHANGES_REQUESTED;
            $submission->save();

            $task = $submission->task;
            $task->status = $decision === EvaluationDecision::APPROVED
                ? TaskStatus::APPROVED
                : TaskStatus::CHANGES_REQUESTED;
            $task->save();

            $this->updateStageStatusFromTasks($task);
            $project = $task->stage->project;
            $this->projectLifecycleService->recalculateProjectProgress($project);

            $submission->submitter->notify(new SubmissionReviewedNotification($submission, $evaluation));
            $this->activityLogService->log($project, 'submission_reviewed', [
                'submission_id' => $submission->id,
                'decision' => $decision,
            ], $mentor);

            return $evaluation;
        });
    }

    protected function updateStageStatusFromTasks(Task $task)
    {
        $stage = $task->stage;
        $tasks = $stage->tasks()->get();
        if ($tasks->isEmpty()) {
            return;
        }

        if ($tasks->every(function ($t) {
            return $t->status === TaskStatus::APPROVED;
        })) {
            $stage->status = StageStatus::COMPLETED;
            $stage->completed_at = now();
            $stage->save();
            return;
        }

        if ($tasks->contains(function ($t) {
            return in_array($t->status, [TaskStatus::IN_PROGRESS, TaskStatus::SUBMITTED, TaskStatus::CHANGES_REQUESTED], true);
        })) {
            $stage->status = StageStatus::IN_PROGRESS;
            $stage->started_at = $stage->started_at ?: now();
            $stage->save();
        }
    }
}

