<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesApprovers extends Model
{
    protected $table = 'salesapprovers';
    protected $primaryKey = 'Id';

    CONST CREATED_AT = 'CreatedDate';
    CONST UPDATED_AT = 'ModifiedDate';

    public function user()
    {
        return $this->belongsTo(User::class, 'UserId');
    }
    public function salesApprover()
    {
        return $this->belongsTo(User::class, 'SalesApproverId');
    }
}
