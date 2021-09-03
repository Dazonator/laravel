<?php

namespace App\Models;


use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

//    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function position()
    {
        return $this->hasOne(Positions::class, 'id', 'position_id');
    }

    public function department()
    {
        return $this->hasOne(Departments::class, 'id', 'department_id');
    }


//    public function performers()
//    {
//        return $this->belongsToMany(Tasks::class);
//    }

    public function tasks()
    {
        return $this->belongsToMany(Tasks::class)->with(['priority', 'status', 'initiator']);
    }

}
