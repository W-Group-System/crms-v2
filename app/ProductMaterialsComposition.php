<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductMaterialsComposition extends Model
{
    protected $table = "productmaterialcompositions";
    public $timestamps = false;

    public function products()
    {
        return $this->belongsTo(Product::class, 'ProductId');
    }
    public function rawMaterials()
    {
        return $this->belongsTo(RawMaterial::class, 'MaterialId');
    }
}
