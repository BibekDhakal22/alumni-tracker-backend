<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobPost extends Model
{
    protected $fillable = [
        'posted_by',
        'title',
        'description',
        'company',
        'location',
        'type',
        'industry',
        'deadline',
        'contact_email',
    ];

    public function postedBy()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }
}