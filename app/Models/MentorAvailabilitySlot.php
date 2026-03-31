<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MentorAvailabilitySlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'mentor_id',
        'starts_at',
        'ends_at',
        'note',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }
}
