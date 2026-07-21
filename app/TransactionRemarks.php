<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionRemarks extends Model
{
    protected $table = 'trasaction_remarks';
    protected $primaryKey = 'id';
    protected $fillable = [
        'transaction_no',
        'action',
        'action_by',
        'remarks'
    ];
}
