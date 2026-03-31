<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'entrepreneur_id',
        'mentor_id',
        'title',
        'description',
        'category',
        'status',
        'submitted_at',
        'decided_at',
        'decision_notes',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'decided_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function entrepreneur()
    {
        return $this->belongsTo(User::class, 'entrepreneur_id');
    }

    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    public function attachments()
    {
        return $this->hasMany(ProjectAttachment::class);
    }

    public function stages()
    {
        return $this->hasMany(Stage::class)->orderBy('stage_order');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }
}
