<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class PriceFixedCost extends Model
{
    use SoftDeletes;
    protected $table = "pricerequestaccountingfixedcost";
    protected $fillable = [
        'EffectiveDate', 'DirectLabor', 'FactoryOverhead', 'DeliveryCost'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'CreatedByUserId', 'user_id', 'username');
    }

    public function userById()
    {
        return $this->belongsTo(User::class,'CreatedByUserId','id');
    }
}
