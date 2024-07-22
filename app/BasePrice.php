<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class BasePrice extends Model
{
    use SoftDeletes;
    protected $table = "productmaterialbaseprices";

    protected $primaryKey = "Id";

    protected $fillable = [
        'Id',
        'MaterialId',
        'Price', 
        'EffectiveDate',
        'Status',
        'CreatedBy',
        'ModifiedDate',
        'CreatedDate',
        'CurrencyId',
    ];
    public function productMaterial()
    {
        return $this->belongsTo(RawMaterial::class, 'MaterialId', 'id');
    }

    public function userApproved()
    {
        return $this->belongsTo(User::class, 'ApprovedBy', 'user_id');
    }
    public function userCreated()
    {
        return $this->belongsTo(User::class, 'CreatedBy', 'user_id');
    }
}
