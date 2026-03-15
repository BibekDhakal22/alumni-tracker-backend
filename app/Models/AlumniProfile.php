<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlumniProfile extends Model
{
    protected $fillable = [
        'user_id',
        'batch_year',
        'phone',
        'address',
        'current_job',
        'company',
        'industry',
        'linkedin',
        'photo',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}