<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionLogs extends Model
{
    //
    protected $table = "transactionlogs";
    CONST CREATED_AT = "CreatedDate";
    CONST UPDATED_AT = "ModifiedDate";

    public function historyUser()
    {
        return $this->belongsTo(User::class, 'UserId', 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'UserId', 'id');
    }
}
