<?php

namespace App;

use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;

class CcPackaging extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $table = "ccpackaging";
    protected $fillable = [
        'CcId', 'PackPn1', 'PackScNo1', 'PackSoNo1', 'PackQuantity1', 'PackLotNo1', 'PackPn2', 'PackScNo2', 'PackSoNo2', 'PackQuantity2', 'PackLotNo2', 'PackPn3', 'PackScNo3', 'PackSoNo3', 'PackQuantity3', 'PackLotNo3', 'PackPn4', 'PackScNo4', 'PackSoNo4', 'PackQuantity4', 'PackLotNo4'
    ];
}
