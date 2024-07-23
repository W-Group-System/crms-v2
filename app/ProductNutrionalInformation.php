<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductNutrionalInformation extends Model
{
    protected $table = "productdsnutritionalinformation";
    protected $primaryKey = "Id";

    const CREATED_AT = 'CreatedDate';
    const UPDATED_AT = 'ModifiedDate';
}
