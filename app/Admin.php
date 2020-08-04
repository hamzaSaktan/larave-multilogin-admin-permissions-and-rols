<?php

namespace App;

use App\Notifications\AdminResetPasswordNotification;
use App\Notifications\AdminSendEmailVerificationNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;

class Admin extends Authenticatable implements MustVerifyEmail
{
    use LaratrustUserTrait;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function scopeWhereRole($query, $role_name)
    {
        return $query->whereHas('roles',function($q) use ($role_name){
            return $q->whereIn('name',(array)$role_name);
        });
    }

    public function scopeWhereRoleNot($query, $role_name)
    {
        return $query->whereHas('roles',function($q) use ($role_name){
            return $q->whereNotIn('name',(array)$role_name);
        });
    }

    public function sendPasswordResetNotification($token)
{
    $this->notify(new AdminResetPasswordNotification($token));
}

public function SendEmailVerificationNotification(){
    $this->notify(new AdminSendEmailVerificationNotification());
}
}
