<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ProductSpecification extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table = "productspecifications";
    protected $primaryKey = 'Id';
    
    const CREATED_AT = 'CreatedDate';
    const UPDATED_AT = 'ModifiedDate';
}
