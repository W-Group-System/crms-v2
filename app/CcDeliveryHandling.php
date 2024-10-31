<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CcDeliveryHandling extends Model
{
    protected $table = "ccdeliveryhandling";
    protected $fillable = [
        'CcId', 'DhPn1', 'DhScNo1', 'DhSoNo1', 'DhQuantity1', 'DhLotNo1', 'DhPn2', 'DhScNo2', 'DhSoNo2', 'DhQuantity2', 'DhLotNo2', 'DhPn3', 'DhScNo3', 'DhSoNo3', 'DhQuantity3', 'DhLotNo3',
    ];
}
