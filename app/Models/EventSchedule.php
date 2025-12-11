<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'timezone',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}

