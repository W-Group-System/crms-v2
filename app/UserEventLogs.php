<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserEventLogs extends Model
{
    protected $primaryKey = 'Id';
    protected $table = "usereventlog";

    public function userByUserId()
    {
        return $this->belongsTo(User::class, 'UserId', 'user_id');
    }
}
