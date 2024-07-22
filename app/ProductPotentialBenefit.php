<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductPotentialBenefit extends Model
{
    protected $table = "productdspotentialbenefits";
    protected $primaryKey = "Id";

    const CREATED_AT = "CreatedDate";
    const UPDATED_AT = "ModifiedDate";
}
