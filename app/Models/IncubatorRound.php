<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncubatorRound extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'description',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function sponsors()
    {
        return $this->belongsToMany(Sponsor::class, 'incubator_round_sponsor')->withTimestamps();
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'incubator_round_id');
    }
}

