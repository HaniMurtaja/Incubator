<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'incubator_round_id',
        'mentor_id',
        'entrepreneur_id',
        'requested_for',
        'status',
        'agenda',
        'meeting_mode',
        'duration_minutes',
        'notify_members',
    ];

    protected $casts = [
        'requested_for' => 'datetime',
        'notify_members' => 'boolean',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function round()
    {
        return $this->belongsTo(IncubatorRound::class, 'incubator_round_id');
    }

    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    public function entrepreneur()
    {
        return $this->belongsTo(User::class, 'entrepreneur_id');
    }
}
