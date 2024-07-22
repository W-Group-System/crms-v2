<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductHeavyMetal extends Model
{
    protected $table = "productdsheavymetals";
    protected $primaryKey = "Id";

    CONST CREATED_AT = 'CreatedDate';
    CONST UPDATED_AT = 'ModifiedDate';
}
