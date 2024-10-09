<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class ProductRawMaterials extends Model
{
    use SoftDeletes; 
    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
