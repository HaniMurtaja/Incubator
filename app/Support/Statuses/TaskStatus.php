<?php

namespace App\Support\Statuses;

class TaskStatus
{
    const NOT_STARTED = 'not_started';
    const IN_PROGRESS = 'in_progress';
    const SUBMITTED = 'submitted';
    const CHANGES_REQUESTED = 'changes_requested';
    const APPROVED = 'approved';

    public static function all()
    {
        return [
            self::NOT_STARTED,
            self::IN_PROGRESS,
            self::SUBMITTED,
            self::CHANGES_REQUESTED,
            self::APPROVED,
        ];
    }
}

