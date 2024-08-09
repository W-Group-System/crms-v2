<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
// use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable;
    // use SoftDeletes;

    protected $table = "users";

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', );
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function salespersons()
    {
        return $this->hasMany(SalesUser::class, 'SalesUserId', 'user_id')->where('isDeleted', 0);
    }

    public function localsalespersons()
    {
        return $this->hasMany(SalesUser::class, 'SalesUserId', 'user_id')
        ->where('isDeleted', 0)
        ->where('Type', 1);
    }
    public function rndUsers()
    {
        return $this->hasMany(RndUser::class, 'ProductRndUserId', 'user_id')->where('isDeleted', 0);
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'full_name', 'password', 'role_id', 'company_id', 'department_id', 'email', 'is_active', 'user_id'
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
    
}
