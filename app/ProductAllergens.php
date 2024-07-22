<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductAllergens extends Model
{
    protected $table = "productdsallergens";
    protected $primaryKey = "Id";

    const CREATED_AT = 'CreatedDate';
    const UPDATED_AT = 'ModifiedDate';
}
