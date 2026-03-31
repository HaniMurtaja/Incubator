<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Project;
use App\Models\User;

class ActivityLogService
{
    public function log(Project $project, $event, array $payload = [], User $actor = null)
    {
        return ActivityLog::create([
            'project_id' => $project->id,
            'actor_id' => $actor ? $actor->id : null,
            'event' => $event,
            'payload' => $payload,
            'created_at' => now(),
        ]);
    }
}

