<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ProductMaterialsComposition extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

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
