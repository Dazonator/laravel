<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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

    protected static function booted()
    {
        static::created(function ($task) {
            $users = User::where('id', '<>' , Auth::user()->id)->get();
            $users = $users->toArray();
            $forUsers = [];
            foreach ($users as $user){
                $forUsers[] = $user['id'];
            }

        });
        static::updated(function ($task) {

        });
        static::deleted(function ($user) {

        });
    }
}
