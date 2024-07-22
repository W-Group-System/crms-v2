<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductMicrobiologicalAnalysis extends Model
{
    protected $table = "productdsmicrobiologicalanalysi";
    protected $primaryKey = "Id";

    const CREATED_AT = 'CreatedDate';
    const UPDATED_AT = 'ModifiedDate';
}
