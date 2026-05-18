<?php

namespace App;

use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;

class CcOthers extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $table = "ccothers";
    protected $fillable = [
        'CcId', 'OthersPn1', 'OthersScNo1', 'OthersSoNo1', 'OthersQuantity1', 'OthersLotNo1', 'OthersPn2', 'OthersScNo2', 'OthersSoNo2', 'OthersQuantity2', 'OthersLotNo2', 'OthersPn3', 'OthersScNo3', 'OthersSoNo3', 'OthersQuantity3', 'OthersLotNo3', 'OthersPn4', 'OthersScNo4', 'OthersSoNo4', 'OthersQuantity4', 'OthersLotNo4'
    ];
}
