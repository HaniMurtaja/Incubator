<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskSubmissionFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_submission_id',
        'path',
        'original_name',
        'mime',
        'size',
    ];

    public function submission()
    {
        return $this->belongsTo(TaskSubmission::class, 'task_submission_id');
    }
}
