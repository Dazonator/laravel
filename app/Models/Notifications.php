<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'text'
    ];
    public function notificationsFor()
    {
        return $this->belongsToMany(User::class);
    }
}
