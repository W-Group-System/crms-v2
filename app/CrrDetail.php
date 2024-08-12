<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CrrDetail extends Model
{
    protected $primaryKey = 'Id';
    protected $table = 'crrdetails';

    CONST created_at = 'CreatedDate';
    CONST updated_at = 'ModifiedDate';

    public function userByUserId()
    {
        return $this->belongsTo(User::class,'UserId','user_id');
    }

    public function userById()
    {
        return $this->belongsTo(User::class,'UserId','id');
    }
}
