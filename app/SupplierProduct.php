<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class SupplierProduct extends Model
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table = "spe";
}
