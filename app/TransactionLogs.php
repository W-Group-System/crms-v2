<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionLogs extends Model
{
    //
    protected $table = "transactionlogs";

    public function historyUser()
    {
        return $this->belongsTo(User::class, 'UserId', 'user_id');
    }
}
