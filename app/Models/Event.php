<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'created_by',
        'title',
        'description',
        'location',
        'event_date',
        'type',
        'max_attendees',
        'contact_email',
    ];

    protected $casts = [
        'event_date' => 'datetime',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}