<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class ProductSubcategories extends Model
{
    use SoftDeletes;
    protected $table = "productapplicationsubcategories";
    protected $fillable = [
        'ProductApplicationId', 'Name', 'Description'
    ];

    public function application()
    {
        return $this->belongsTo(ProductApplication::class, 'ProductApplicationId', 'id');
    }
}
