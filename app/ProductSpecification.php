<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ProductSpecification extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = "productspecifications";
    protected $primaryKey = 'Id';
    
    const CREATED_AT = 'CreatedDate';
    const UPDATED_AT = 'ModifiedDate';
}
