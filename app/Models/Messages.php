<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    protected $fillable = [
        'user_id',
        'message',
        'task_id',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function messagesFor()
    {
        return $this->belongsToMany(User::class);
    }
    
}
