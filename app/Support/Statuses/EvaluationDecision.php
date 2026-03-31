<?php

namespace App\Support\Statuses;

class EvaluationDecision
{
    const APPROVED = 'approved';
    const CHANGES_REQUESTED = 'changes_requested';

    public static function all()
    {
        return [
            self::APPROVED,
            self::CHANGES_REQUESTED,
        ];
    }
}

