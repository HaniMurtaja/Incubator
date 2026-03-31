<?php

namespace App\Support\Statuses;

class SubmissionStatus
{
    const SUBMITTED = 'submitted';
    const UNDER_REVIEW = 'under_review';
    const APPROVED = 'approved';
    const CHANGES_REQUESTED = 'changes_requested';

    public static function all()
    {
        return [
            self::SUBMITTED,
            self::UNDER_REVIEW,
            self::APPROVED,
            self::CHANGES_REQUESTED,
        ];
    }
}

