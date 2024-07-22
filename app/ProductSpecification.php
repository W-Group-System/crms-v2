<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductSpecification extends Model
{
    protected $table = "productspecifications";
    protected $primaryKey = 'Id';
    
    const CREATED_AT = 'CreatedDate';
    const UPDATED_AT = 'ModifiedDate';
}
