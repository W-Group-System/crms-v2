<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;


class PriceRequestGae extends Model implements Auditable
{
    use SoftDeletes;
    use AuditableTrait;

    protected $table = "pricerequestgaes";
}
