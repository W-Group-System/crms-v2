<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use SoftDeletes;
    protected $table = "roles";

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function access()
    {
        return $this->hasMany(UserAccessModule::class);
    }
}
