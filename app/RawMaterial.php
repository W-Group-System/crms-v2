<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RawMaterial extends Model
{
    use SoftDeletes;
    protected $table = "productmaterials";

    // public function product_raw_materials()
    // {
    //     return $this->hasOne(ProductRawMaterials::class, 'raw_material_id');
    // }

    public function productMaterialCompositions()
    {
        return $this->hasMany(ProductMaterialsComposition::class, 'MaterialId');
    }
    public function basePrice()
    {
        return $this->hasMany(BasePrice::class,'MaterialId');
    }
    public function productsMaterialComposition()
    {
        return $this->hasMany(ProductMaterialsComposition::class,'MaterialId','id');
    }
}
