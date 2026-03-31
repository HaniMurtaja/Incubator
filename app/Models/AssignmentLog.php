<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_date',
        'project_id',
        'business_mentor_id',
        'entrepreneur_id',
        'notes',
    ];

    protected $casts = [
        'assignment_date' => 'date',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function mentor()
    {
        return $this->belongsTo(User::class, 'business_mentor_id');
    }

    public function entrepreneur()
    {
        return $this->belongsTo(User::class, 'entrepreneur_id');
    }
}
