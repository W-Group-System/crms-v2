<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrrDetail extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'Id';
    protected $table = 'crrdetails';

    CONST CREATED_AT = 'DateCreated';
    CONST UPDATED_AT = 'ModifiedDate';

    public function userByUserId()
    {
        return $this->belongsTo(User::class,'UserId','user_id');
    }

    public function userById()
    {
        return $this->belongsTo(User::class,'UserId','id');
    }
}
