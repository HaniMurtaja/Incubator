<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_submission_id',
        'evaluator_id',
        'decision',
        'comments',
        'evaluated_at',
    ];

    protected $casts = [
        'evaluated_at' => 'datetime',
    ];

    public function submission()
    {
        return $this->belongsTo(TaskSubmission::class, 'task_submission_id');
    }

    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }
}
