<?php

namespace App\Models;

use App\Traits\HasRolesAndPermissions;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRolesAndPermissions;

//    protected $table = 'users';

    protected $fillable = [
        'login',
        'name',
        'lastname',
        'email',
        'password',
        'phone',
        'department_id',
        'position',
        'img',
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

    public function tasks()
    {
        return $this->belongsToMany(Tasks::class);
    }

    public function messages()
    {
        return $this->belongsToMany(Messages::class);
    }

    public function meetings()
    {
        return $this->belongsToMany(Meetings::class);
    }

    public function setImgAttribute($img) {
        $img = '/storage/'.$img;
        $this->attributes['img'] = $img;
    }
    public function setImagesAttribute($images) {
        if (is_array($images)) {
            $result = [];
            foreach($images as $image) {
                $name = substr( $image, strrpos( $image, "/" ) + 1 );
                $result [] = $pt = 'reviews/'.date("m_Y").'/'.$name;
                $path = storage_path('app/public/').$pt;
                if (!File::exists(dirname($path))) {
                    File::makeDirectory(dirname($path), 0755, true);
                }
                File::move(storage_path('app/public/temp/').$image, $path);
            }
            $this->attributes['images'] = count($result)?json_encode($result):NULL;
        }
    }

    public function getPersonalTasks (){
        return $this->hasManyThrough(Status::class, Tasks::class);
    }
}
