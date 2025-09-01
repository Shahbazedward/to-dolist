<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['title', 'is_completed', 'user_id', 'start_time', 'done_time'];

    protected $casts = [
        'start_time' => 'datetime',
        'done_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
