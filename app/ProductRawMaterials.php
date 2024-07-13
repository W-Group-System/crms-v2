<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductRawMaterials extends Model
{
    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
