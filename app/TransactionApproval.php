<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionApproval extends Model
{
    //
    protected $table = 'transactionapprovals';

    public function approverRPE()
    {
        return $this->belongsTo(User::class, 'UserId', 'user_id');
    }

    public function userByUserId()
    {
        return $this->belongsTo(User::class,'UserId','user_id');
    }

    public function userById()
    {
        return $this->belongsTo(User::class,'UserId','id');
    }
}
