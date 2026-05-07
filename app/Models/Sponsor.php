<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo_path',
    ];

    public function rounds()
    {
        return $this->belongsToMany(IncubatorRound::class, 'incubator_round_sponsor')->withTimestamps();
    }
}

