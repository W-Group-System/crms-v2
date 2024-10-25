<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
// use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

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

    public function localSalesApprovers()
    {
        return $this->hasMany(SalesApprovers::class, 'SalesApproverId', 'id')
        ->where('isDeleted', 0)
        ->where('Type', 1);
    }
    public function internationalSalesApprovers()
    {
        return $this->hasMany(SalesApprovers::class, 'SalesApproverId', 'id')
        ->where('isDeleted', 0)
        ->where('Type', 2);
    }
    
    public function getSalesApprover()
    {
        return $this->hasMany(SalesApprovers::class, 'SalesApproverId', 'id');
    }
    public function rndUsers()
    {
        return $this->hasMany(RndUser::class, 'ProductRndUserId', 'user_id')->where('isDeleted', 0);
    }
    public function salesApproverByUserId()
    {
        return $this->hasMany(SalesApprovers::class,'UserId','user_id');
    }
    public function salesApproverById()
    {
        return $this->hasMany(SalesApprovers::class,'UserId','id');
    }
    public function rndApproverByUserId()
    {
        return $this->hasMany(RndApprovers::class,'UserId','user_id');
    }
    public function rndApproverById()
    {
        return $this->hasMany(RndApprovers::class,'UserId','id');
    }
    public function secondarySalesPerson()
    {
        return $this->hasMany(SecondarySalesPerson::class,'PrimarySalesPersonId');
    }
    public function groupSales()
    {
        return $this->hasMany(GroupSales::class);
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
