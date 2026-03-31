<?php

namespace App\Support\Statuses;

class ProjectStatus
{
    const PENDING = 'pending';
    const ACCEPTED = 'accepted';
    const REJECTED = 'rejected';
    const IN_PROGRESS = 'in_progress';
    const COMPLETED = 'completed';

    public static function all()
    {
        return [
            self::PENDING,
            self::ACCEPTED,
            self::REJECTED,
            self::IN_PROGRESS,
            self::COMPLETED,
        ];
    }
}

