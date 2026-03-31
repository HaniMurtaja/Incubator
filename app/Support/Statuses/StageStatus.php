<?php

namespace App\Support\Statuses;

class StageStatus
{
    const NOT_STARTED = 'not_started';
    const IN_PROGRESS = 'in_progress';
    const COMPLETED = 'completed';

    public static function all()
    {
        return [
            self::NOT_STARTED,
            self::IN_PROGRESS,
            self::COMPLETED,
        ];
    }
}

