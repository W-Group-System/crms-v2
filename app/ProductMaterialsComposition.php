<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ProductMaterialsComposition extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

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
